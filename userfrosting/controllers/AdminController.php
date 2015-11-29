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
        $squidguard_db = MySqlConfgenLoader::fetch("squidguard_db", "libelle");
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
            'squidguard_db' => $squidguard_db,
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

        $newpathsquidguarddb = MySqlConfgenLoader::fetch("squidguard_db", "libelle");
        $newpathsquidguarddb->value = $data['squidguard_db'];
        $newpathsquidguarddb->store();

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

        $controller = new ProxyController($this->_app);
        $controller->update_delay_pools();
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
        if (!$this->_app->user->checkAccess('app_admin')) {
            $this->_app->notFound();
        }
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

    public function pageExtSites() {
        // Access-controlled page
        if (!$this->_app->user->checkAccess('app_admin')) {
            $this->_app->notFound();
        }

        $extsites = MySqlExtSitesLoader::fetchAll();

        $this->_app->render('extsites.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "Autres sites",
                'description' => "",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ],
            "extsites" => $extsites
        ]);
    }

    public function formExtSitesCreate() {

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('app_admin')) {
            $this->_app->notFound();
        }

        $get = $this->_app->request->get();

        if (isset($get['render']))
            $render = $get['render'];
        else
            $render = "modal";


        // Set default values
        $data['url'] = "";
        $data['description'] = "";

        // Create a dummy Salle to prepopulate fields
        $es = new MySqlExtSites($data);


        $template = "components/extsite-info-modal.html";


//        // Determine authorized fields
//        $show_fields = ['name', 'new_user_title', 'landing_page', 'theme', 'is_default', 'icon'];
        $disabled_fields = [];

// Load validator rules
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/extsite-create.json");
        $validators = new \Fortress\ClientSideValidator($schema, $this->_app->translator);

        $this->_app->render($template, [
            "box_id" => $get['box_id'],
            "box_title" => "Ajout d'un site interdit",
            "submit_button" => "Ajouter le site",
            "form_action" => $this->_app->site->uri['public'] . "/ext_sites",
            "es" => $es,
            "fields" => [
                "disabled" => $disabled_fields,
                "hidden" => []
            ],
            "buttons" => [
                "hidden" => [
                    "edit", "delete"
                ]
            ],
            "validators" => $validators->formValidationRulesJson()
        ]);
    }

    public function createExtSites() {
        $post = $this->_app->request->post();

        // DEBUG: view posted data
//        error_log(print_r($post, true));
        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/extsite-create.json");

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('app_admin')) {
            $ms->addMessageTranslated("danger", "ACCESS_DENIED");
            $this->_app->halt(403);
        }

        // Set up Fortress to process the request
        $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);

        $rf->sanitize();
        // Validate, and halt on validation errors.
        $error = !$rf->validate(true);

        // Get the filtered data
        $data = $rf->data();

//        
        // Remove csrf_token from object data
        $rf->removeFields(['csrf_token']);

        // Check if url already exists
        if (MySqlExtSitesLoader::exists($data['url'], 'url')) {
            $ms->addMessageTranslated("danger", "L'url entrée existe déjà", $post);
            $error = true;
        }

        if (MySqlExtSitesLoader::exists($data['description'], 'description')) {
            $ms->addMessageTranslated("danger", "Le site entré existe déjà", $post);
            $error = true;
        }

        // Halt on any validation errors
        if ($error) {
            $this->_app->halt(400);
        }


        $es = new MySqlExtSites($data);
        $es->store();

        // Success message
        $ms->addMessageTranslated("success", "Site '{{description}}' ajouté", $data);
        
        $this->updateExtSitesConf();

    }
    
        public function deleteExtSites($es_id) {

        $es = MySqlExtSitesLoader::fetch($es_id);

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Check authorization
        if (!$this->_app->user->checkAccess('app_admin')) {
            $ms->addMessageTranslated("danger", "ACCESS_DENIED");
            $this->_app->halt(403);
        }



        $ms->addMessageTranslated("success", "Site '{{description}}' supprimé", ["description" => $es->description]);
        $es->delete();       // TODO: implement Group function
        unset($es);
        
        $this->updateExtSitesConf();

    }
    
    private function updateExtSitesConf(){
        $extsites=  MySqlExtSitesLoader::fetchAll();
        $extsitesconf="";
        foreach ($extsites as $extsite){
            $extsitesconf.="<li><a href=\"".$extsite->url."\"  target=\"_blank\">".$extsite->description."</a></li>";
        }
        MySqlExtSitesLoader::updateConf($extsitesconf);
    }

}

?>