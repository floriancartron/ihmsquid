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
        $ip_squid = MySqlConfgenLoader::fetch("ip_squid", "libelle");
        $ssh_user = MySqlConfgenLoader::fetch("ssh_user", "libelle");
        $squid_conf_path = MySqlConfgenLoader::fetch("squid_conf_path", "libelle");
        $squidguard_conf_path = MySqlConfgenLoader::fetch("squidguard_conf_path", "libelle");
        $delay_pool_max_size = MySqlConfgenLoader::fetch("delay_pool_max_size", "libelle");
        $delay_pool_restore_rate = MySqlConfgenLoader::fetch("delay_pool_restore_rate", "libelle");
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/confgen.json");
        $validators = new \Fortress\ClientSideValidator($schema, $this->_app->translator);
        $this->_app->render('confgen.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "Configuration générale",
                'description' => "",
                'alerts' => $this->_app->alerts->getAndClearMessages(),
            ],
            "form_action" => $this->_app->site->uri['public'] . "/confgen",
            'ip_squid' => $ip_squid,
            'ssh_user' => $ssh_user,
            'squidguard_conf_path' => $squidguard_conf_path,
            'squid_conf_path' => $squid_conf_path,
            'delay_pool_max_size' => $delay_pool_max_size,
            'delay_pool_restore_rate' => $delay_pool_restore_rate,
            "validators" => $validators->formValidationRulesJson()
        ]);
    }

    public function updateSquidSettings() {
        $post = $this->_app->request->post();
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/confgen.json");
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
//        var_dump($data);

        $newip = MySqlConfgenLoader::fetch("ip_squid", "libelle");
        $newip->value = $data['ip_squid'];
        $newip->store();

        $newsshuser = MySqlConfgenLoader::fetch("ssh_user", "libelle");
        $newsshuser->value = $data['ssh_user'];
        $newsshuser->store();

        $newpathsquidguard = MySqlConfgenLoader::fetch("squidguard_conf_path", "libelle");
        $newpathsquidguard->value = $data['squidguard_conf_path'];
        $newpathsquidguard->store();

        $newpathsquid = MySqlConfgenLoader::fetch("squid_conf_path", "libelle");
        $newpathsquid->value = $data['squid_conf_path'];
        $newpathsquid->store();

        $newdelay_pool_max_size = MySqlConfgenLoader::fetch("delay_pool_max_size", "libelle");
        $newdelay_pool_max_size->value = $data['delay_pool_max_size'];
        $newdelay_pool_max_size->store();

        $newdelay_pool_restore_rate = MySqlConfgenLoader::fetch("delay_pool_restore_rate", "libelle");
        $newdelay_pool_restore_rate->value = $data['delay_pool_restore_rate'];
        $newdelay_pool_restore_rate->store();

        $ms->addMessageTranslated("success", "Mise à jour de la configuration réussie");

        $this->pageSquidSettings();
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
        if (MySqlWorkingHoursLoader::update(1, $data["timepicker1"], $data["timepicker2"], $data["timepicker3"], $data["timepicker4"])) {
            $ms->addMessageTranslated("success", "Mise à jour des horaires réussie");
        } else {
            $ms->addMessageTranslated("error", "La mise à jour des horaires a échoué");
        }
        $this->pageHoraires();

        $controller = new ProxyController($this->_app);
        $controller->genSquidguardConf();
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

    //Fonction d'affichage de la page de gestion des horaires
    public function pageCategories() {
        // On vérifie que le user à le droit d'être ici
        if (!$this->_app->user->checkAccess('app_admin')) {
            $this->_app->notFound();
        }

        $categories = MySqlBlacklistCategoriesLoader::fetchAll();
        //On fait le rendu de la page, il faudra créer une bdd pour la rémanence des informations. 
        $this->_app->render('categories.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "Liste noire",
                'description' => "",
                "form_action" => $this->_app->site->uri['public'] . "/blacklist",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ],
            "categories" => $categories
        ]);
    }

    public function changeCategories() {
        $post = $this->_app->request->post();
        $ms = $this->_app->alerts;
        unset($post['csrf_token']);
        MySqlBlacklistCategoriesLoader::reset();
        foreach ($post as $categorie => $p) {
            MySqlBlacklistCategoriesLoader::setAllowed($categorie);
        }

        $ms->addMessageTranslated("success", "Mise à jour des catégories bloquées réussie");

        $this->pageCategories();

        $controller = new ProxyController($this->_app);
        $controller->genSquidguardConf();
    }

}

?>