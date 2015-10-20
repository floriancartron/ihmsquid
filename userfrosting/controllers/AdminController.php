<?php

namespace UserFrosting;

/* * *****

  /config/*

 * ***** */

// Handles admin-related activities, including site settings, user management, etc
class AdminController extends \UserFrosting\BaseController {

    public function __construct($app) {
        $this->_app = $app;
    }

    // EG : Page de la configuration générale de Squid 3
    public function pageSquidSettings() {
        // On vérifie que le user à le droit d'être ici
        if (!$this->_app->user->checkAccess('app_admin')) {
            $this->_app->notFound();
        }

        //On fait le rendu de la page, il faudra créer une bdd pour la rémanence des informations. 
        $this->_app->render('confgen.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "Paramètres de Squid",
                'description' => "",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ]
        ]);
    }

    //Fonction d'affichage de la page de gestion des horaires
    public function pageHoraires() {
        // On vérifie que le user à le droit d'être ici
        if (!$this->_app->user->checkAccess('app_admin')) {
            $this->_app->notFound();
        }

        $horaires = MySqlWorkingHoursLoader::fetch(1);
        //On fait le rendu de la page, il faudra créer une bdd pour la rémanence des informations. 
        $this->_app->render('horaires.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "Horaires de cours",
                'description' => "",
                "form_action" => $this->_app->site->uri['public'] . "/horaires",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ],
            "horaires" => $horaires
        ]);
    }

    //mise à jour des horaires
    public function changeHoraires() {
        $post = $this->_app->request->post();
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/change-horaires.json");
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
        if (MySqlWorkingHoursLoader::update(1, $data["timepicker1"], $data["timepicker2"],$data["timepicker3"],$data["timepicker4"])){
            $ms->addMessageTranslated("success", "Mise à jour des horaires réussie");
        }else{
            $ms->addMessageTranslated("error", "La mise à jour des horaires a échoué");
        }
        $this->pageHoraires();
    }

    public function pageSiteSettings() {
        // Access-controlled page
        if (!$this->_app->user->checkAccess('uri_site_settings')) {
            $this->_app->notFound();
        }

        // Hook for core and plugins to register their settings
        $this->_app->applyHook("settings.register");

        $this->_app->render('site-settings.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "Paramètres du site",
                'description' => "",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ],
            'settings' => $this->_app->site->getRegisteredSettings(),
            'info' => $this->_app->site->getSystemInfo(),
            'error_log' => $this->_app->site->getLog(50)
        ]);
    }

    public function siteSettings() {
        // Get the alert message stream
        $ms = $this->_app->alerts;

        $post = $this->_app->request->post();

        // Remove CSRF token
        if (isset($post['csrf_token']))
            unset($post['csrf_token']);

        // Access-controlled page
        if (!$this->_app->user->checkAccess('update_site_settings')) {
            $ms->addMessageTranslated("danger", "ACCESS_DENIED");
            $this->_app->halt(403);
        }

        // Hook for core and plugins to register their settings
        $this->_app->applyHook("settings.register");

        // Get registered settings
        $registered_settings = $this->_app->site->getRegisteredSettings();

        // Ok, check that all posted settings are registered
        foreach ($post as $plugin => $settings) {
            if (!isset($registered_settings[$plugin])) {
                $ms->addMessageTranslated("danger", "CONFIG_PLUGIN_INVALID", ["plugin" => $plugin]);
                $this->_app->halt(400);
            }
            foreach ($settings as $name => $value) {
                if (!isset($registered_settings[$plugin][$name])) {
                    $ms->addMessageTranslated("danger", "CONFIG_SETTING_INVALID", ["plugin" => $plugin, "name" => $name]);
                    $this->_app->halt(400);
                }
            }
        }

        // TODO: validate setting syntax
        // If validation passed, then update
        foreach ($post as $plugin => $settings) {
            foreach ($settings as $name => $value) {
                $this->_app->site->set($plugin, $name, $value);
            }
        }
        $this->_app->site->store();
    }

}

?>