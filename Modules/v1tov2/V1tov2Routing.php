<?php

require_once 'Framework/Routing.php';

class V1tov2Routing extends Routing{ 
    
    public function listRouts(){
        
        $this->addRoute("v1tov2", "v1tov2", "v1tov2", "index", array(), array());
        
        $this->addRoute("importgrr", "importgrr", "importgrr", "index", array(), array());
        $this->addRoute("importbiosit", "importbiosit", "importbiosit", "index", array(), array());
        
        
        $this->addRoute("tomultiplebelonging", "tomultiplebelonging", "tomultiplebelonging", "index", array(), array());
        $this->addRoute("importauthorizations", "importauthorizations", "importauthorizations", "index", array(), array());
        $this->addRoute("importspaceauth", "importspaceauth", "importspaceauth", "index", array(), array());
        
        $this->addRoute("activateusers", "activateusers", "activateusers", "index", array(), array());
        $this->addRoute("cleanresa", "cleanresa", "cleanresa", "index", array(), array());
     
        $this->addRoute("importantibodies", "importantibodies", "importantibodies", "index", array(), array());
     
        $this->addRoute("phpinfo", "phpinfo", "phpinfo", "index", array(), array());
    }
}
