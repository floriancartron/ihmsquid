<?php

namespace UserFrosting;

/* * *****

  /config/*

 * ***** */

// Handles filtering configuration activities
class FilterController extends \UserFrosting\BaseController {

    public function __construct($app) {
        $this->_app = $app;
    }

    // EG : Page de l'acces à internet
    public function pageAccess($id = 0) {
        // On vérifie que le user à le droit d'être ici
        if (!$this->_app->user->checkAccess('uri_access')) {
            $this->_app->notFound();
        }

        $salles = MySqlSalleLoader::fetchAll();
        $customconfs = MySqlCustomConfLoader::fetchAll();

        $get = $this->_app->request->get();
        $disableapply = false;
        if (isset($get["salle"])) {
            $idsalle = $get["salle"];
        } else {
            if (isset(array_values($salles)[0])) {
                $idsalle = array_values($salles)[0]->id;
            } else {
                $disableapply = true;
            }
        }
        if ($id != 0) {
            $idsalle = $id;
        }

        $selectedsalle = MySqlSalleLoader::fetch($idsalle);

        $this->_app->render('access.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "Gestion de la salle",
                'description' => "",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ],
            'form_action' => $this->_app->site->uri['public'] . "/access",
            'salles' => $salles,
            'filters' => $customconfs,
            'selectedsalle' => $selectedsalle,
            'disableapply' => $disableapply
        ]);
    }

    public function changeAccess() {
        $post = $this->_app->request->post();
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/change-access.json");
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
        var_dump($data);
        $salle = MySqlSalleLoader::fetch($data['salle']);
        $salle->id_customconf = $data['filter'];
        if ($salle->store()) {
            $ms->addMessageTranslated("success", "Mise à jour de l'accès de la salle réussie");
        } else {
            $ms->addMessageTranslated("error", "La mise à jour de l'accès a échoué");
        }
        $this->pageAccess($salle->id);

        $controller = new ProxyController($this->_app);
        $controller->genSquidguardConf();
    }

    public function pageCustomBlacklist() {
        // Access-controlled page
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
            $this->_app->notFound();
        }

        $blacklist = MySqlCustomBlacklistLoader::fetchWithUsernames();

        $this->_app->render('addblacklist.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "Sites interdits",
                'description' => "",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ],
            "blacklist" => $blacklist
        ]);
    }

    public function deleteBlacklist($bl_id) {

        $bl = MySqlCustomBlacklistLoader::fetch($bl_id);

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Check authorization
        if (!$this->_app->user->checkAccess('uri_filterconf', ['blacklist' => $bl])) {
            $ms->addMessageTranslated("danger", "ACCESS_DENIED");
            $this->_app->halt(403);
        }



        $ms->addMessageTranslated("success", "Site '{{url}}' supprimé", ["url" => $bl->url]);
        $bl->delete();       // TODO: implement Group function
        unset($bl);
        
        $controller = new ProxyController($this->_app);
        $controller->update_black_or_white_list('black');
    }

    // Display the form for creating a new blacklist
    public function formBlacklistCreate() {

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
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
        $bl = new MySqlCustomBlacklist($data);


        $template = "components/blacklist-info-modal.html";


//        // Determine authorized fields
//        $show_fields = ['name', 'new_user_title', 'landing_page', 'theme', 'is_default', 'icon'];
        $disabled_fields = [];

// Load validator rules
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/blacklist-create.json");
        $validators = new \Fortress\ClientSideValidator($schema, $this->_app->translator);

        $this->_app->render($template, [
            "box_id" => $get['box_id'],
            "box_title" => "Ajout d'un site interdit",
            "submit_button" => "Ajouter le site",
            "form_action" => $this->_app->site->uri['public'] . "/addblacklist",
            "bl" => $bl,
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

    // Create new Blacklist item
    public function createBlacklist($userid) {
        $post = $this->_app->request->post();

        // DEBUG: view posted data
//        error_log(print_r($post, true));
        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/blacklist-create.json");

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
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
        $data["id_user"] = $userid;
//        $e=serialize($data);
//            throw new \Exception("$e");
//        
        // Remove csrf_token from object data
        $rf->removeFields(['csrf_token']);

        // Check if url already exists
        if (MySqlCustomBlacklistLoader::exists($data['url'], 'url')) {
            $ms->addMessageTranslated("danger", "L'url entrée existe déjà", $post);
            $error = true;
        }

        // Halt on any validation errors
        if ($error) {
            $this->_app->halt(400);
        }


        $bl = new MySqlCustomBlacklist($data);
        $bl->store();

        // Success message
        $ms->addMessageTranslated("success", "Site '{{url}}' ajouté", $data);
        
        $controller = new ProxyController($this->_app);
        $controller->update_black_or_white_list('black');
    }

    public function pageCustomWhitelist() {
        // Access-controlled page
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
            $this->_app->notFound();
        }

        $whitelist = MySqlCustomWhitelistLoader::fetchWithUsernames();

        $this->_app->render('addwhitelist.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "SItes interdits",
                'description' => "",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ],
            "whitelist" => $whitelist
        ]);
    }

    public function deleteWhitelist($bl_id) {

        $bl = MySqlCustomWhitelistLoader::fetch($bl_id);

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Check authorization
        if (!$this->_app->user->checkAccess('uri_filterconf', ['whitelist' => $bl])) {
            $ms->addMessageTranslated("danger", "ACCESS_DENIED");
            $this->_app->halt(403);
        }



        $ms->addMessageTranslated("success", "Site '{{url}}' supprimé", ["url" => $bl->url]);
        $bl->delete();       // TODO: implement Group function
        unset($bl);
        
        $controller = new ProxyController($this->_app);
        $controller->update_black_or_white_list('white');
    }

    // Display the form for creating a new whitelist
    public function formWhitelistCreate() {

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
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
        $bl = new MySqlCustomWhitelist($data);


        $template = "components/whitelist-info-modal.html";


//        // Determine authorized fields
//        $show_fields = ['name', 'new_user_title', 'landing_page', 'theme', 'is_default', 'icon'];
        $disabled_fields = [];

// Load validator rules
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/whitelist-create.json");
        $validators = new \Fortress\ClientSideValidator($schema, $this->_app->translator);

        $this->_app->render($template, [
            "box_id" => $get['box_id'],
            "box_title" => "Ajout d'un site autorisé",
            "submit_button" => "Ajouter le site",
            "form_action" => $this->_app->site->uri['public'] . "/addwhitelist",
            "bl" => $bl,
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

    // Create new Whitelist item
    public function createWhitelist($userid) {
        $post = $this->_app->request->post();

        // DEBUG: view posted data
//        error_log(print_r($post, true));
        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/whitelist-create.json");

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
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
        $data["id_user"] = $userid;
//        $e=serialize($data);
//            throw new \Exception("$e");
//        
        // Remove csrf_token from object data
        $rf->removeFields(['csrf_token']);

        // Check if url already exists
        if (MySqlCustomWhitelistLoader::exists($data['url'], 'url')) {
            $ms->addMessageTranslated("danger", "L'url entrée existe déjà", $post);
            $error = true;
        }

        // Halt on any validation errors
        if ($error) {
            $this->_app->halt(400);
        }


        $bl = new MySqlCustomWhitelist($data);
        $bl->store();

        // Success message
        $ms->addMessageTranslated("success", "Site '{{url}}' ajouté", $data);
        
        $controller = new ProxyController($this->_app);
        $controller->update_black_or_white_list('white');
    }

    public function pageCustomBypasslist() {
        // Access-controlled page
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
            $this->_app->notFound();
        }

        $bypasslist = MySqlCustomBypasslistLoader::fetchWithUsernames();

        $this->_app->render('addbypasslist.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "SItes interdits",
                'description' => "",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ],
            "bypasslist" => $bypasslist
        ]);
    }

    public function deleteBypasslist($bl_id) {

        $bl = MySqlCustomBypasslistLoader::fetch($bl_id);

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Check authorization
        if (!$this->_app->user->checkAccess('uri_filterconf', ['bypasslist' => $bl])) {
            $ms->addMessageTranslated("danger", "ACCESS_DENIED");
            $this->_app->halt(403);
        }



        $ms->addMessageTranslated("success", "Site '{{url}}' supprimé", ["url" => $bl->url]);
        $bl->delete();       // TODO: implement Group function
        unset($bl);

        $controller = new ProxyController($this->_app);
        $controller->gen_bypasslist();
    }

    // Display the form for creating a new bypasslist
    public function formBypasslistCreate() {

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
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
        $bl = new MySqlCustomBypasslist($data);


        $template = "components/bypasslist-info-modal.html";


//        // Determine authorized fields
//        $show_fields = ['name', 'new_user_title', 'landing_page', 'theme', 'is_default', 'icon'];
        $disabled_fields = [];

// Load validator rules
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/bypasslist-create.json");
        $validators = new \Fortress\ClientSideValidator($schema, $this->_app->translator);

        $this->_app->render($template, [
            "box_id" => $get['box_id'],
            "box_title" => "Ajout d'un site autorisé",
            "submit_button" => "Ajouter le site",
            "form_action" => $this->_app->site->uri['public'] . "/addbypasslist",
            "bl" => $bl,
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

    // Create new Bypasslist item
    public function createBypasslist($userid) {
        $post = $this->_app->request->post();

        // DEBUG: view posted data
//        error_log(print_r($post, true));
        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/bypasslist-create.json");

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
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
        $data["id_user"] = $userid;
//        $e=serialize($data);
//            throw new \Exception("$e");
//        
        // Remove csrf_token from object data
        $rf->removeFields(['csrf_token']);

        // Check if url already exists
        if (MySqlCustomBypasslistLoader::exists($data['url'], 'url')) {
            $ms->addMessageTranslated("danger", "L'url entrée existe déjà", $post);
            $error = true;
        }

        // Halt on any validation errors
        if ($error) {
            $this->_app->halt(400);
        }


        $bl = new MySqlCustomBypasslist($data);
        $bl->store();

        // Success message
        $ms->addMessageTranslated("success", "Site '{{url}}' ajouté", $data);

        $controller = new ProxyController($this->_app);
        $controller->gen_bypasslist();
    }

    public function pageCustomFilter() {
        // Access-controlled page
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
            $this->_app->notFound();
        }
        $showitems = true;
        $lists = MySqlCustomConfLoader::fetchAllExceptStandard();
        $get = $this->_app->request->get();
        if (isset($get["listselect"])) {
            $idlist = $get["listselect"];
        } else {
            if (isset(array_values($lists)[0])) {
                $idlist = array_values($lists)[0]->id;
            } else {
                $idlist = 0;
                $showitems = false;
            }
        }


        $sites = MySqlCustomConfItemLoader::fetchAll($idlist, "id_custom_conf");
        $selectedlist = MySqlCustomConfLoader::fetch($idlist);
        $this->_app->render('customfilter.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "Filtrage personnalisé",
                'description' => "",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ],
            "lists" => $lists,
            "selectedlist" => $selectedlist,
            "sites" => $sites,
            "showitems" => $showitems
        ]);
    }

    // Display the form for creating a new list
    public function formCustomFilterListCreate() {

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
            $this->_app->notFound();
        }

        $get = $this->_app->request->get();

        if (isset($get['render']))
            $render = $get['render'];
        else
            $render = "modal";


        // Set default values
        $data['name'] = "";
        $data['description'] = "";

        // Create a dummy Salle to prepopulate fields
        $l = new MySqlCustomConf($data);


        $template = "components/customlist-info-modal.html";


//        // Determine authorized fields
//        $show_fields = ['name', 'new_user_title', 'landing_page', 'theme', 'is_default', 'icon'];
        $disabled_fields = [];

// Load validator rules
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/customlist-create.json");
        $validators = new \Fortress\ClientSideValidator($schema, $this->_app->translator);

        $this->_app->render($template, [
            "box_id" => $get['box_id'],
            "box_title" => "Ajout d'une liste personnalisée",
            "submit_button" => "Ajouter la liste",
            "form_action" => $this->_app->site->uri['public'] . "/customfilter",
            "l" => $l,
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

    public function createCustomFilterList() {
        $post = $this->_app->request->post();

        // DEBUG: view posted data
//        error_log(print_r($post, true));
        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/customlist-create.json");

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
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

        // Check if name already exists
        if (MySqlCustomConfLoader::exists($data['name'], 'name')) {
            $ms->addMessageTranslated("danger", "Le nom existe déjà", $post);
            $error = true;
        }

        // Halt on any validation errors
        if ($error) {
            $this->_app->halt(400);
        }


        $l = new MySqlCustomConf($data);
        $l->store();

        // Success message
        $ms->addMessageTranslated("success", "Liste '{{name}}' ajoutée", $data);
    }

    public function deleteCustomFilterList($list_id) {

        $l = MySqlCustomConfLoader::fetch($list_id);

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Check authorization
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
            $ms->addMessageTranslated("danger", "ACCESS_DENIED");
            $this->_app->halt(403);
        }



        $ms->addMessageTranslated("success", "Liste '{{name}}' supprimée", ["name" => $l->name]);
        $l->delete();
        unset($l);
    }

    public function deleteCustomFilterItem($list_id) {

        $l = MySqlCustomConfItemLoader::fetch($list_id);
        $id_custom_conf=$l->id_custom_conf;
        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Check authorization
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
            $ms->addMessageTranslated("danger", "ACCESS_DENIED");
            $this->_app->halt(403);
        }



        $ms->addMessageTranslated("success", "Site '{{url}}' supprimé", ["url" => $l->url]);
        $l->delete();
        unset($l);

        $controller = new ProxyController($this->_app);
        $controller->gen_customfilter($id_custom_conf);
    }

    // Display the form for creating a new list item
    public function formCustomFilterListItemCreate() {

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
            $this->_app->notFound();
        }

        $get = $this->_app->request->get();

        if (isset($get['render']))
            $render = $get['render'];
        else
            $render = "modal";


        // Set default values
        $data['url'] = "";

        // Create a dummy Salle to prepopulate fields
        $l = new MySqlCustomConfItem($data);


        $template = "components/customlistitem-info-modal.html";


//        // Determine authorized fields
//        $show_fields = ['name', 'new_user_title', 'landing_page', 'theme', 'is_default', 'icon'];
        $disabled_fields = [];

// Load validator rules
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/customlistitem-create.json");
        $validators = new \Fortress\ClientSideValidator($schema, $this->_app->translator);

        $this->_app->render($template, [
            "box_id" => $get['box_id'],
            "box_title" => "Ajout d'un site à une liste personnalisée",
            "submit_button" => "Ajouter le site",
            "form_action" => $this->_app->site->uri['public'] . "/customlistitem",
            "l" => $l,
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

    public function createCustomFilterListItem() {
        $post = $this->_app->request->post();

        // DEBUG: view posted data
//        error_log(print_r($post, true));
        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/customlistitem-create.json");

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('uri_filterconf')) {
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

        // Check if name already exists
//        if (MySqlCustomConfLoader::exists($data['name'], 'name')) {
//            $ms->addMessageTranslated("danger", "Le nom existe déjà", $post);
//            $error = true;
//        }
        // Halt on any validation errors
        if ($error) {
            $this->_app->halt(400);
        }


        $l = new MySqlCustomConfItem($data);
        $l->store();

        // Success message
        $ms->addMessageTranslated("success", "Site '{{url}}' ajouté à la liste", $data);

        $controller = new ProxyController($this->_app);
        $controller->gen_customfilter($data["id_custom_conf"]);
    }

}

?>