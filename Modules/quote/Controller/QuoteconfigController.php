<?php

require_once 'Framework/Controller.php';
require_once 'Framework/Form.php';
require_once 'Modules/core/Controller/CoresecureController.php';
require_once 'Modules/core/Model/CoreStatus.php';
require_once 'Modules/quote/Model/QuoteInstall.php';
require_once 'Modules/quote/Model/QuoteTranslator.php';

/**
 * 
 * @author sprigent
 * Controller for the home page
 */
class QuoteconfigController extends CoresecureController {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        if (!$this->isUserAuthorized(CoreStatus::$USER)) {
            throw new Exception("Error 503: Permission denied");
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see Controller::indexAction()
     */
    public function indexAction($id_space) {

        $this->checkSpaceAdmin($id_space, $_SESSION["id_user"]);
        $lang = $this->getLanguage();

        $modelSpace = new CoreSpace();
        
        // maintenance form
        $formMenusactivation = $this->menusactivationForm($lang, $id_space);
        if ($formMenusactivation->check()) {

            $modelSpace->setSpaceMenu($id_space, "quote", "quote", "glyphicon-user", 
                    $this->request->getParameter("quotemenustatus"),
                    $this->request->getParameter("quotemenusdisplay"),
                    1,
                    $this->request->getParameter("quotemenuscolor")
                    );
            
            $this->redirect("quoteconfig/".$id_space);
            return;
        }

        // view
        $forms = array($formMenusactivation->getHtml($lang));
        
        $this->render(array("id_space" => $id_space, "forms" => $forms, "lang" => $lang));
    }

    protected function menusactivationForm($lang, $id_space) {

        $modelSpace = new CoreSpace();
        $statusUserMenu = $modelSpace->getSpaceMenusRole($id_space, "quote");
        $quotemenusdisplay = $modelSpace->getSpaceMenusDisplay($id_space, "quote");
        $quotemenuscolor = $modelSpace->getSpaceMenusColor($id_space, "quote");
        
        
        
        $form = new Form($this->request, "menusactivationForm");
        $form->addSeparator(CoreTranslator::Activate_desactivate_menus($lang));

        $roles = $modelSpace->roles($lang);

        $form->addSelect("quotemenustatus", CoreTranslator::Users($lang), $roles["names"], $roles["ids"], $statusUserMenu);
        $form->addNumber("quotemenusdisplay", CoreTranslator::Display_order($lang), false, $quotemenusdisplay);
        $form->addColor("quotemenuscolor", CoreTranslator::color($lang), false, $quotemenuscolor);
        
        $form->setValidationButton(CoreTranslator::Save($lang), "quoteconfig/".$id_space);
        $form->setButtonsWidth(2, 9);

        return $form;
    }

}