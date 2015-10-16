<?php

namespace UserFrosting;

/*******

/config/*

*******/

// Handles admin-related activities, including site settings, user management, etc
class StatsController extends \UserFrosting\BaseController {

    public function __construct($app){
        $this->_app = $app;
    }


    //Accès à la page des logs
    public function pageLogs(){
        // On vérifie que le user à le droit d'être ici
        if (!$this->_app->user->checkAccess('app_admin')){
            $this->_app->notFound();
        }
        $logs = MySqlLoglineLoader::fetchAll();

        
        $this->_app->render('logs.html', [
            'page' => [
                'author' =>         $this->_app->site->author,
                'title' =>          "Visualisation des logs d'accès.",
                'description' =>    "",
                'alerts' =>         $this->_app->alerts->getAndClearMessages()
            ],
            'loglines' => $logs
        ]);    
    }
	
    
}
?>