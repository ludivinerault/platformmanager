<?php

require_once 'Framework/Controller.php';
require_once 'Framework/TableView.php';
require_once 'Framework/Form.php';

require_once 'Modules/core/Controller/CoresecureController.php';
require_once 'Modules/antibodies/Model/AcApplication.php';

class ApplicationController extends CoresecureController {

    /**
     * User model object
     */
    private $acapplicationModel;

    public function __construct(Request $request) {
        parent::__construct($request);
        $this->acapplicationModel = new AcApplication();
        $_SESSION["openedNav"] = "antibodies";
    }

    // affiche la liste des Prelevements
    public function indexAction($id_space) {

        // get the user list
        $acapplicationsArray = $this->acapplicationModel->getBySpace($id_space);

        $table = new TableView();
        $table->setTitle("Application", 3);
        $table->addLineEditButton("applicationedit/".$id_space."/");
        $table->addDeleteButton("applicationdelete/".$id_space."/", "id", "name");
        
        $headers = array("id" => "ID", "name" => "Nom");
        $tableHtml = $table->view($acapplicationsArray, $headers);
        
        $this->render(array(
            'lang' => $this->getLanguage(),
            'id_space' => $id_space,
            'tableHtml' => $tableHtml
        ));
    }

    public function editAction($id_space, $id) {

        // get isotype info
        $lang = $this->getLanguage();
        $acapplication = $this->acapplicationModel->get($id);
        
        $form = new Form($this->request, "acapplicationeditform");
        $form->setTitle("Modifier application");
        $form->addText("nom", "nom", true, $acapplication["name"]);
        $form->setValidationButton(CoreTranslator::Save($lang), "applicationedit/".$id_space.'/'.$id);
        
        if($form->check()){
            $name = $this->request->getParameter("nom");
            if ($id == 0){
                $this->acapplicationModel->add($name, $id_space);
            }
            else{
                $this->acapplicationModel->edit($id, $name, $id_space);
            }
            
            $this->redirect("application/".$id_space);
        }

        $this->render(array(
            'lang' => $this->getLanguage(),
            'id_space' => $id_space,
            'formHtml' => $form->getHtml($lang)
        ));
    }

    public function deleteAction($id_space, $id) {

        // get source info
        $this->acapplicationModel->delete($id);

        $this->redirect("application/" . $id_space);
    }

}
