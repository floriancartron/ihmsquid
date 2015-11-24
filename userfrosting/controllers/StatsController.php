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

    public function pageStatsGet(){
        $di = new \DateInterval('P6D');
        $di->invert = 1;
        $this->pageStats(date_add(new \DateTime(),$di),new \DateTime(),"all");
    }

    public function pageStatsPost() {
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
        
        $this->stats($data["startday"],$data["endday"],$data["salle"]);
    }

    private function pageStats($startday,$endday,$salle) {
        
        $di=new \DateInterval('P1D');
        $dates=array();
        while($startday != $endday){
            $dates[]=$startday->format("Y-m-d");
            date_add($startday,$di);
        }
        $dates[]=$startday->format("Y-m-d");
//        var_dump($dates);
        $nbhits=array();
        foreach($dates as $date){
            $nbhits[]=array("date"=>$date,"hits"=>MySqlLoglineLoader::hits4day($date));
        }
        var_dump($nbhits);
        exit(0);
        
    }

}

?>