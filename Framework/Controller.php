<?php

require_once 'Configuration.php';
require_once 'Request.php';
require_once 'View.php';

/**
 * Abstract class defining a controller. 
 * 
 * @author Sylvain Prigent
 */
abstract class Controller {

    /** Action to run */
    protected $action;
    protected $module;

    /** recieved request */
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Define the input request
     * 
     * @param Request $request Recieved request
     */
    public function setRequest(Request $request) {
        $this->request = $request;
    }

    /**
     * 
     * @return type The navigator language
     */
    public function getLanguage() {
        $lang = substr(filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE'), 0, 2);
        if (isset($_SESSION["user_settings"]["language"])) {
            $lang = $_SESSION["user_settings"]["language"];
        }
        return $lang;
    }

    /**
     * Run the action.
     * Call the method with the same name than the action in the curent controller
     * 
     * @throws Exception If the action does not exist in the curent controller
     */
    public function runAction($module, $action, $args = array()) {
        $this->module = strtolower($module);
        $actionName = $action . "Action";
        if (method_exists($this, $actionName)) {
            $this->action = $action;
            //print_r($args);
            call_user_func_array(array($this, $actionName), $args);
            //$this->{$this->action}();
        } else {
            $classController = get_class($this);
            throw new Exception("Action '$action'Action in not defined in the class '$classController'");
        }
    }

    /**
     * Define the default action
     */
    //public abstract function indexAction();

    /**
     * Generate the vue associated to the curent controller
     * 
     * @param array $dataView Data neededbu the view
     * @param string $action Action associated to the view
     */
    protected function render($dataView = array(), $action = null) {
        // Use the curent action by default
        $actionView = $this->action . "Action";
        if ($action != null) {
            $actionView = $action;
        }
        $classController = get_class($this);
        $controllerView = str_replace("Controller", "", $classController);

        // Geneate the view
        //echo "controllerView = " . $controllerView . "<br/>";
        //echo "parent = " . basename(__DIR__) . "<br/>"; 
        $view = new View($actionView, $controllerView, $this->module);
        $view->generate($dataView);
    }

    /**
     * Redirect to a controller and a specific action
     * 
     * @param string $path Path to the controller adn action
     * @param type $args Get arguments
     */
    protected function redirect($path, $args = array()) {
        $rootWeb = Configuration::get("rootWeb", "/");
        foreach ($args as $key => $val) {
            $path .= "?" . $key . "=" . $val;
        }
        header_remove();
        header("Location:" . $rootWeb . $path);
    }
    
    protected function redirectNoRemoveHeader($path, $args = array()){
        $rootWeb = Configuration::get("rootWeb", "/");
        foreach ($args as $key => $val) {
            $path .= "?" . $key . "=" . $val;
        }
        header("Location:" . $rootWeb . $path);
    }

}
