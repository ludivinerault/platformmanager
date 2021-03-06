<?php

require_once 'Framework/Model.php';
require_once 'Modules/core/Model/CoreAdminMenu.php';

/**
 * Class defining methods to install and initialize the core database
 *
 * @author Sylvain Prigent
 */
class DevInstall extends Model {

    /**
     * Create the core database
     *
     * @return boolean True if the base is created successfully
     */
    public function createDatabase() {        

        $modelCoreMenu = new CoreAdminMenu();
        $modelCoreMenu->setAdminMenu("Dev", "dev", "glyphicon glyphicon-plus", 1);
    }
}
