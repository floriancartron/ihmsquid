<?php

namespace UserFrosting;

/*******

/config/*

*******/

// Handles admin-related activities, including site settings, user management, etc
class FilterController extends \UserFrosting\BaseController {

    public function __construct($app){
        $this->_app = $app;
    }


	// EG : Page de l'acces à internet
    public function pageAccess(){
        // On vérifie que le user à le droit d'être ici
        if (!$this->_app->user->checkAccess('uri_access')){
            $this->_app->notFound();
        }

        
		//On fait le rendu de la page, il faudra créer une bdd pour la rémanence des informations. 
        $this->_app->render('access.html', [
            'page' => [
                'author' =>         $this->_app->site->author,
                'title' =>          "Paramètres de l'accès à internet.",
                'description' =>    "",
                'alerts' =>         $this->_app->alerts->getAndClearMessages()
            ]
        ]);    
    }
	
    
}
?>