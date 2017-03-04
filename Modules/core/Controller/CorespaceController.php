<?php

require_once 'Framework/Controller.php';
require_once 'Framework/Form.php';
require_once 'Framework/TableView.php';
require_once 'Modules/core/Controller/CoresecureController.php';
require_once 'Modules/core/Model/CoreUser.php';
require_once 'Modules/core/Model/CoreStatus.php';
require_once 'Modules/core/Model/CoreSpace.php';
require_once 'Modules/core/Model/CoreInstalledModules.php';

/**
 * 
 * @author sprigent
 * Controller for the home page
 */
class CorespaceController extends CoresecureController {

    private $spaceModel;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        if (!$this->isUserAuthorized(CoreStatus::$USER)) {
            throw new Exception("Error 503: Permission denied");
        }
        $this->spaceModel = new CoreSpace ();
    }

    /**
     * (non-PHPdoc)
     * @see Controller::indexAction()
     */
    public function indexAction() {

        //$lang = $this->getLanguage();
        //$this->render(array("lang" => $lang, "id_space" => $id_space));
    }

    /**
     * 
     * @param type $id_space
     */
    public function viewAction($id_space) {

        $space = $this->spaceModel->getSpace($id_space);

        $lang = $this->getLanguage();
        $showAdmMenu = false;
        if ($_SESSION['user_status'] > CoreStatus::$USER) {
            $spaceMenuItems = $this->spaceModel->getSpaceMenus($space["id"], CoreSpace::$ADMIN);
            $showAdmMenu = true;
        } else {
            $role = $this->spaceModel->getUserSpaceRole($space["id"], $_SESSION['id_user']);
            if ($role > CoreSpace::$MANAGER) {
                $showAdmMenu = true;
            }
            $spaceMenuItems = $this->spaceModel->getSpaceMenus($space["id"], $role);
        }
        $configModel = new CoreConfig();
        for ($i = 0; $i < count($spaceMenuItems); $i++) {
            $item = $spaceMenuItems[$i];
            $classTranslator = ucfirst($item["module"]) . "Translator";
            $TranslatorFile = "Modules/" . $item["module"] . "/Model/" . $classTranslator . ".php";
            require_once $TranslatorFile;
            $translator = new $classTranslator();
            $url = $item["url"];
            $donfigTitle = $configModel->getParamSpace($url . "menuname", $id_space);
            if ($donfigTitle != "") {
                $name = $donfigTitle;
            } else {
                $name = $translator->$url($lang);
            }
            $spaceMenuItems[$i]['name'] = $name;

            $menuColor = $item["color"];

            if ($menuColor == "") {
                $menuColor = '#428bca';
            }
            $spaceMenuItems[$i]['color'] = $menuColor;
        }

        $this->render(array("lang" => $lang, "id_space" => $id_space, "space" => $space, "spaceMenuItems" => $spaceMenuItems, "showAdmMenu" => $showAdmMenu));
    }

    /**
     * 
     * @param type $id_space
     */
    public function configAction($id_space) {

        $_SESSION["openedNav"] = "config";
        $this->checkSpaceAdmin($id_space, $_SESSION["id_user"]);
        $lang = $this->getLanguage();
        $space = $this->spaceModel->getSpace($id_space);
        $modulesTable = $this->configModulesTable($lang, $id_space);
        $this->render(array("lang" => $lang, "id_space" => $id_space, "space" => $space, "modulesTable" => $modulesTable));
    }

    /**
     * 
     * @param type $id_space
     */
    public function configusersAction($id_space) {

        $_SESSION["openedNav"] = "configusers";
        $this->checkSpaceAdmin($id_space, $_SESSION["id_user"]);
        $lang = $this->getLanguage();

        // space info
        $space = $this->spaceModel->getSpace($id_space);

        // user form
        $userForm = $this->configUsersForm($lang, $id_space);
        if ($userForm->check()) {
            $id_space = $this->request->getParameter("id_space");
            $id_user = $this->request->getParameter("id_user");
            $id_role = $this->request->getParameter("id_role");
            $this->spaceModel->setUser($id_user, $id_space, $id_role);
            $this->redirect("spaceconfiguser/" . $id_space);
        }


        $userTable = $this->configUsersTable($lang, $id_space);
        $this->render(array("lang" => $lang, "id_space" => $id_space, "space" => $space, "userForm" => $userForm->getHtml($lang), "userTable" => $userTable));
    }

    /**
     * 
     * @param type $lang
     * @param type $id_space
     * @return type
     */
    protected function configModulesTable($lang, $id_space) {

        $modelInstalledMod = new CoreInstalledModules();
        $modules = $modelInstalledMod->getModules();

        $mods = array();
        $count = -1;
        for ($i = 0; $i < count($modules); ++$i) {

            $moduleName = ucfirst(strtolower($modules[$i]["name"]));
            $abstractMethod = $moduleName . "ConfigAbstract";
            $configFile = "Modules/" . $modules[$i]["name"] . "/Controller/" . $moduleName . "configController.php";
            if (file_exists($configFile)) {
                $count++;
                // name
                $mods[$count]['name'] = $modules[$i]["name"];
                if ($modules[$i]["name"] != "core") {
                    require_once "Modules/" . $modules[$i]["name"] . "/Model/" . $moduleName . "Translator.php";
                }
                // get abstract html text
                $mods[$count]['abstract'] = forward_static_call(array($moduleName . "Translator", $abstractMethod), $lang);
                // construct action
                $action = $modules[$i]["name"] . "config";
                $mods[$count]['action'] = $action;
                $mods[$count]['id'] = $i;
            }
        }

        $headers = array("name" => CoreTranslator::Name($lang),
            "abstract" => CoreTranslator::Description($lang));

        $tableView = new TableView("tableModules");
        $tableView->setTitle(CoreTranslator::Modules_configuration($lang), 3);
        $tableView->addLineEditButton("spaceconfigmodule/" . $id_space, "name");
        return $tableView->view($mods, $headers);
    }

    /**
     * 
     * @param type $lang
     * @param type $id_space
     * @return type
     */
    protected function configUsersTable($lang, $id_space) {

        $data = $this->spaceModel->getUsers($id_space);
        //print_r($data);
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]["role"] == 1) {
                $data[$i]["role"] = CoreTranslator::Visitor($lang);
            }
            if ($data[$i]["role"] == 2) {
                $data[$i]["role"] = CoreTranslator::User($lang);
            }
            if ($data[$i]["role"] == 3) {
                $data[$i]["role"] = CoreTranslator::Manager($lang);
            }
            if ($data[$i]["role"] == 4) {
                $data[$i]["role"] = CoreTranslator::Admin($lang);
            }
        }

        $tableUsers = new TableView();
        $tableUsers->addDeleteButton("spaceconfigdeleteuser/" . $id_space);
        return $tableUsers->view($data, array("name" => CoreTranslator::Name($lang),
                    "firstname" => CoreTranslator::Firstname($lang),
                    "role" => CoreTranslator::Role($lang)));
    }

    /**
     * 
     * @param type $id_space
     * @param type $id_user
     */
    public function configdeleteuserAction($id_space, $id_user) {
        $this->spaceModel->deleteUser($id_space, $id_user);
        $this->redirect("spaceconfiguser/" . $id_space);
    }

    /**
     * 
     * @param type $lang
     * @param type $id_space
     * @return \Form
     */
    protected function configUsersForm($lang, $id_space) {
        $_SESSION["openedNav"] = "configusers";
        $modeluser = new CoreUser();
        $users = $modeluser->getActiveUsers("name");
        $usersNames = array();
        $usersId = array();
        foreach ($users as $user) {
            $usersNames[] = $user["name"] . " " . $user["firstname"];
            $usersId[] = $user["id"];
        }

        $roles = CoreSpace::roles($lang);

        $formUser = new Form($this->request, "adduser");
        $formUser->setTitle(CoreTranslator::Access($lang));
        $formUser->setColumnsWidth(3, 6);
        $formUser->setButtonsWidth(2, 8);
        $formUser->addHidden("id_space", $id_space);
        $formUser->addSelect("id_user", CoreTranslator::User($lang), $usersNames, $usersId);
        $formUser->addSelect("id_role", CoreTranslator::Role($lang), $roles["names"], $roles["ids"]);
        $formUser->setValidationButton(CoreTranslator::Ok($lang), "spaceconfiguser/" . $id_space);
        return $formUser;
    }

    /**
     * 
     * @param type $id_space
     * @return type
     */
    public function spaceName($id_space) {
        $space = $this->spaceModel->getSpace($id_space);
        return $space["name"];
    }

    /**
     * 
     * @param type $id_space
     * @return string
     */
    public function navbar($id_space) {

        $space = $this->spaceModel->getSpace($id_space);
        
        
        $spaceColor = "#428bca";
        if($space["color"] != ""){
            $spaceColor = $space["color"];
        }

        $lang = $this->getLanguage();
        $showAdmMenu = false;
        if ($_SESSION['user_status'] > CoreStatus::$USER) {
            $spaceMenuItems = $this->spaceModel->getSpaceMenus($space["id"], CoreSpace::$ADMIN);
            $showAdmMenu = true;
        } else {
            $role = $this->spaceModel->getUserSpaceRole($space["id"], $_SESSION['id_user']);
            if ($role > CoreSpace::$MANAGER) {
                $showAdmMenu = true;
            }
            $spaceMenuItems = $this->spaceModel->getSpaceMenus($space["id"], $role);
        }

        $html = file_get_contents('Modules/core/View/Corespace/navbar.php');

        // replace admin
        $adminMenu = "";
        if ($showAdmMenu) {
            $colorConfig = "";
            $colorConfigUser = "";
            if (isset($_SESSION["openedNav"]) && $_SESSION["openedNav"] == "config") {
                $colorConfig = 'style="background-color:'.$spaceColor.'; color: #fff;"';
            }
            if (isset($_SESSION["openedNav"]) && $_SESSION["openedNav"] == "configusers") {
                $colorConfigUser = 'style="background-color:'.$spaceColor.'; color: #fff;"';
            }
            $adminMenu .= '<li>';
            $adminMenu .= '<a  '.$colorConfig.' href="spaceconfig/' . $space["id"] . '">' . CoreTranslator::Configuration($lang) . '<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-cog"></span></a>';
            $adminMenu .= "</li>";
            $adminMenu .= '<li >';
            $adminMenu .= '<a '.$colorConfigUser.' href="spaceconfiguser/' . $space["id"] . '">' . CoreTranslator::Access($lang) . ' <span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-cog"></span></a>';
            $adminMenu .= "</li>";
        }
        $html = str_replace("{{adminitems}}", $adminMenu, $html);

        $configModel = new CoreConfig();
        $mainMenu = "";
        foreach ($spaceMenuItems as $item) {

            //print_r($item);

            $classTranslator = ucfirst($item["module"]) . "Translator";
            $TranslatorFile = "Modules/" . $item["module"] . "/Model/" . $classTranslator . ".php";
            require_once $TranslatorFile;
            $translator = new $classTranslator();
            $url = $item["url"];
            $donfigTitle = $configModel->getParamSpace($url . "menuname", $id_space);
            if ($donfigTitle != "") {
                $name = $donfigTitle;
            } else {
                $name = $translator->$url($lang);
            }
            $open = "";
            $colorMenu = "";
            if (isset($_SESSION["openedNav"]) && $_SESSION["openedNav"] == $item["url"]) {
                $open = "open";
                if(isset($item["color"]) && $item["color"] != ""){
                    $colorMenu = 'style="background-color:'.$item["color"].'; color: #fff;"';
                }
                else{
                    $colorMenu = 'style="background-color:#428bca; color: #fff;"';
                }
            }

            if ($item['has_sub_menu'] == 0) {
                
                $mainMenu .= '<li>';
                $mainMenu .= '<a '.$colorMenu.' href="' . $item["url"] . '/' . $space["id"] . '">' . $name . ' <span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon ' . $item["icon"] . '"></span></a>';
                $mainMenu .= '</li>';
            } else {

                $mainMenu .= '<li class="dropdown ' . $open . '">';
                $mainMenu .= '<a ' . $colorMenu . ' href="' . $item["url"] . '/' . $space["id"] . '" class="dropdown-toggle" data-toggle="dropdown">' . $name . ' <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon ' . $item["icon"] . '"></span></a>';
                $mainMenu .= '<ul class="dropdown-menu forAnimate" role="menu">';

                $menuController = ucfirst($item["module"]) . 'Controller';
                $fileMenuController = 'Modules/' . ucfirst($item["module"]) . '/Controller/' . $menuController . '.php';
                if (file_exists($fileMenuController)) {
                    require_once $fileMenuController;
                    $controller = new $menuController();
                    $mainMenu .= $controller->navbar($id_space);
                }
                $mainMenu .= '</ul>';
                $mainMenu .= '</li>';
            }
        }
        $html = str_replace("{{menuitems}}", $mainMenu, $html);
        $html = str_replace("{{space.name}}", $space["name"], $html);
        $html = str_replace("{{space.color}}", $spaceColor, $html);
        return $html;
    }

    /**
     * 
     * @param type $id_space
     * @param type $name_module
     */
    public function configmoduleAction($id_space, $name_module) {

        $path = $name_module . "config/";
        $this->redirect($path . $id_space);
    }

}
