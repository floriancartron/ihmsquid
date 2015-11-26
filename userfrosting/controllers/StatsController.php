<?php

namespace UserFrosting;

/* * *****

  /config/*

 * ***** */

// Handles admin-related activities, including site settings, user management, etc
class StatsController extends \UserFrosting\BaseController {

    public function __construct($app) {
        $this->_app = $app;
    }

    //Accès à la page des logs
    public function pageLogs() {
        // On vérifie que le user à le droit d'être ici
        if (!$this->_app->user->checkAccess('app_admin')) {
            $this->_app->notFound();
        }
        $logs = MySqlLoglineLoader::fetchAll();


        $this->_app->render('logs.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "Visualisation des logs d'accès.",
                'description' => "",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ],
            'loglines' => $logs
        ]);
    }

    public function pageStatsGet() {
        $di = new \DateInterval('P6D');
        $di->invert = 1;
        $salle = new MySqlSalle(array("name" => "", "desctiption" => "", "network" => "", "mask_cidr" => "", "id_customconf" => "", "ip_formateur" => ""));
        $this->pageStats(date_add(new \DateTime(), $di), new \DateTime(), $salle);
    }

    public function pageStatsPost() {
        var_dump($this->_app->request->post());
        exit(0);
        $post = $this->_app->request->post();
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/stats.json");
        $ms = $this->_app->alerts;
        unset($post['csrf_token']);
        $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);
        // Sanitize
        $rf->sanitize();
        // Validate, and halt on validation errors.
        if (!$rf->validate()) {
            $this->_app->halt(400);
        }
        $data = $rf->data();

        $this->stats($data["startday"], $data["endday"], MySqlSalleLoader::fetch($data["salle"]));
    }

    private function pageStats($startday, $endday, $salle) {
        if (!$this->_app->user->checkAccess('uri_stats')) {
            $this->_app->notFound();
        }
        $di = new \DateInterval('P1D');
        $dates = array();
        
        while ($startday != $endday) {
            $dates[] = $startday->format("Y-m-d");
            date_add($startday, $di);
        }
        $dates[] = $startday->format("Y-m-d");
        $nbhits = array();
        foreach ($dates as $date) {
            $nbhits[$date] = MySqlLoglineLoader::hits4day($date, $salle->network, $salle->mask_cidr);
        }

        $nbaccess = array();
        foreach ($dates as $date) {
            $nbaccess[] = array("date" => $date, "hits" => $nbhits[$date],"blocks" => MySqlLoglineLoader::blocks4day($date, $salle->id));
        }

        $top10hits = MySqlLoglineLoader::top10hits(reset($dates), end($dates), $salle->network, $salle->mask_cidr);
        $top10blocks = MySqlLoglineLoader::top10blocks(reset($dates), end($dates), $salle->id);

        $totalhits = 0;
        $totalblocks = 0;
        foreach ($nbaccess as $n) {
            $totalhits+=$n["hits"];
            $totalblocks+=$n["blocks"];
        }


        $percentblock = round($totalblocks / $totalhits * 100, 2);
        $percentnonblock = 100 - $percentblock;

        $blocksPerCategory = MySqlLoglineLoader::blocksPerCategory(reset($dates), end($dates), $salle->id);
        $totalblocks = 0;
        foreach ($blocksPerCategory as $b) {
            $totalblocks+=$b["nbblocks"];
        }
        $percentBlockPerCategory = array();
        foreach ($blocksPerCategory as $b) {
            $percentBlockPerCategory[] = array("category"=>$b["category"], "percent" => round($b["nbblocks"] / $totalblocks * 100, 2));
        }
        
        
        $salles=  MySqlSalleLoader::fetchAll();
        $this->_app->render('stats.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "Statistiques",
                'description' => "",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ],
            'nbaccess' => $nbaccess,
            'percentblock' => $percentblock,
            'percentnonblock' => $percentnonblock,
            'percentblockpercategory' => $percentBlockPerCategory,
            'top10hits' => $top10hits,
            'top10blocks' => $top10blocks,
            'startday' => reset($dates),
            'endday' => end($dates),
            "salles" => $salles,
            "selectedsalle" => $salle->id
        ]);
    }

}

?>