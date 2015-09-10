<?php

namespace UserFrosting;

/* * *****

  /groups/*

 * ***** */

// Handles group-related activities
class SalleController extends \UserFrosting\BaseController {

    public function __construct($app) {
        $this->_app = $app;
    }

    public function pageSalles() {
        // Access-controlled page
        if (!$this->_app->user->checkAccess('app_admin')) {
            $this->_app->notFound();
        }

        $salles = MySqlSalleLoader::fetchAll();

        $this->_app->render('salles.html', [
            'page' => [
                'author' => $this->_app->site->author,
                'title' => "Salles",
                'description' => "",
                'alerts' => $this->_app->alerts->getAndClearMessages()
            ],
            "salles" => $salles
        ]);
    }

    // Display the form for creating a new group
    public function formSalleCreate() {
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
        $data['name'] = "";
        $data['description'] = "";
        $data['network'] = "";
        $data['mask_cidr'] = "";

        // Create a dummy Salle to prepopulate fields
        $salle = new MySqlSalle($data);


        $template = "components/salle-info-modal.html";

        $masks = array();
        for ($x = 8; $x <= 30; $x++) {
            $masks[] = $x;
        }

//        // Determine authorized fields
//        $show_fields = ['name', 'new_user_title', 'landing_page', 'theme', 'is_default', 'icon'];
        $disabled_fields = [];

// Load validator rules
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/salle-create.json");
        $validators = new \Fortress\ClientSideValidator($schema, $this->_app->translator);

        $this->_app->render($template, [
            "box_id" => $get['box_id'],
            "box_title" => "Nouvelle salle",
            "submit_button" => "Créer la salle",
            "form_action" => $this->_app->site->uri['public'] . "/salles",
            "salle" => $salle,
            "masks" => $masks,
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

    // Create new group
    public function createSalle() {
        $post = $this->_app->request->post();

        // DEBUG: view posted data
        error_log(print_r($post, true));
        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/salle-create.json");

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Access-controlled resource
        if (!$this->_app->user->checkAccess('app_admin')) {
            $ms->addMessageTranslated("danger", "ACCESS_DENIED");
            $this->_app->halt(403);
        }

        // Set up Fortress to process the request
        $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);

        // Validate, and halt on validation errors.
        $error = !$rf->validate(true);

        // Get the filtered data
        $data = $rf->data();

        // Remove csrf_token from object data
        $rf->removeFields(['csrf_token']);


        // Check if group name already exists
        if (MySqlSalleLoader::exists($data['name'], 'name')) {
            $ms->addMessageTranslated("danger", "Nom de salle déjà utilisé", $post);
            $error = true;
        }
        // Check if group name already exists
        if (MySqlSalleLoader::exists($data['network'], 'network')) {
            $ms->addMessageTranslated("danger", "Réseau déjà utilisé pour une autre salle", $post);
            $error = true;
        }
        // Halt on any validation errors
        if ($error) {
            $this->_app->halt(400);
        }


        $salle = new MySqlSalle($data);
        $salle->store();

        // Success message
        $ms->addMessageTranslated("success", "Salle '{{name}}' créée", $data);
    }

    public function deleteSalle($salle_id) {
        $post = $this->_app->request->post();


        $salle = MySqlSalleLoader::fetch($salle_id);

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Check authorization
        if (!$this->_app->user->checkAccess('app_admin', ['salle' => $salle])) {
            $ms->addMessageTranslated("danger", "ACCESS_DENIED");
            $this->_app->halt(403);
        }



        $ms->addMessageTranslated("success", "Salle '{{name}}' supprimée", ["name" => $salle->name]);
        $salle->delete();       // TODO: implement Group function
        unset($salle);
    }

    // Display the form for editing an existing salle
    public function formSalleEdit($salle_id) {
        // Access-controlled resource
        if (!$this->_app->user->checkAccess('app_admin')) {
            $this->_app->notFound();
        }

        $get = $this->_app->request->get();

        if (isset($get['render']))
            $render = $get['render'];
        else
            $render = "modal";

        // Get the salle to edit
        $salle = MySqlSalleLoader::fetch($salle_id);

        $template = "components/salle-info-modal.html";
        
        $masks = array();
        for ($x = 8; $x <= 30; $x++) {
            $masks[] = $x;
        }

        // Determine authorized fields
        $disabled_fields = [];
        $hidden_fields = [];

        // Load validator rules
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/salle-create.json");
        $validators = new \Fortress\ClientSideValidator($schema, $this->_app->translator);

        $this->_app->render($template, [
            "box_id" => $get['box_id'],
            "box_title" => "Modifier une salle",
            "submit_button" => "Modifier la salle",
            "form_action" => $this->_app->site->uri['public'] . "/salles/s/$salle_id",
            "salle" => $salle,
            "masks" => $masks,
            "fields" => [
                "disabled" => $disabled_fields,
                "hidden" => $hidden_fields
            ],
            "buttons" => [
                "hidden" => [
                    "edit", "delete"
                ]
            ],
            "validators" => $validators->formValidationRulesJson()
        ]);
    }

}
