<?php

require_once 'Framework/Controller.php';
require_once 'Framework/Form.php';
require_once 'Modules/core/Controller/CoresecureController.php';
require_once 'Modules/template/Model/TemplateTranslator.php';

/**
 * 
 * @author sprigent
 * Controller for the home page
 */
class TemplateController extends CoresecureController {

     /**
     * Constructor
     */
    public function __construct(Request $request) {
        parent::__construct($request);
    }

    public function navbar($id_space){
        
        $lang = $this->getLanguage();
        
        $html = file_get_contents('Modules/template/View/Template/navbar.php');
        
        $html = str_replace('{{id_space}}', $id_space, $html);
        $html = str_replace('{{Providers}}', TemplateTranslator::Providers($lang), $html);
        $html = str_replace('{{NewProvider}}', TemplateTranslator::NewProvider($lang), $html);
        
        return $html;
    }
}
