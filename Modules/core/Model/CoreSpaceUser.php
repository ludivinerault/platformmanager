<?php

require_once 'Framework/Model.php';
require_once 'Modules/core/Model/CoreTranslator.php';

/**
 * Class defining the Status model
 *
 * @author Sylvain Prigent
 */
class CoreSpaceUser extends Model {

    /**
     * Create the status table
     * 
     * @return PDOStatement
     */
    public function __construct() {

        $this->tableName = "core_j_spaces_user";
        $this->setColumnsInfo("id", "int(11)", 0);
        $this->setColumnsInfo("id_user", "varchar(100)", "");
        $this->setColumnsInfo("id_space", "varchar(100)", "");
        $this->setColumnsInfo("status", "varchar(100)", "");
        $this->setColumnsInfo("date_convention", "date", "0000-00-00");
        $this->setColumnsInfo("convention_url", "varchar(255)", "");
        $this->setColumnsInfo("date_contract_end", "date", "0000-00-00");
        $this->primaryKey = "id";

    }
    
    public function setRole($id_user, $id_space, $role){
        if ( !$this->exists($id_user, $id_space) ){
            $sql = "INSERT INTO core_j_spaces_user (id_user, id_space, status) VALUES (?,?,?)";
            $this->runRequest($sql, array($id_user, $id_space, $role));
        }
        else{
            $sql = "UPDATE core_j_spaces_user SET status=? WHERE id_user=? AND id_space=?";
            $this->runRequest($sql, array($role, $id_user, $id_space));    
        }
        
        if ( $role > 0 ){
            $sql = "UPDATE core_users SET is_active=? where id=?";
            $this->runRequest($sql, array(1, $id_user));
        }
    }
    
    public function exists($id_user, $id_space){
        $sql = "SELECT id FROM core_j_spaces_user WHERE id_user=? AND id_space=?";
        $req = $this->runRequest($sql, array($id_user, $id_space));
        if ($req->rowCount() > 0){
            return true;
        }
        return false;
    }
    
    public function setDateEndContract($id_user, $id_space, $date_contract_end){
        $sql = "UPDATE core_j_spaces_user SET date_contract_end=? WHERE id_user=? AND id_space=?";
        $this->runRequest($sql, array($date_contract_end, $id_user, $id_space));
    }
    
    public function setDateConvention($id_user, $id_space, $date_convention){
        $sql = "UPDATE core_j_spaces_user SET date_convention=? WHERE id_user=? AND id_space=?";
        $this->runRequest($sql, array($date_convention, $id_user, $id_space));        
    }
    
    public function setConventionUrl($id_user, $id_space, $convention_url){
        $sql = "UPDATE core_j_spaces_user SET convention_url=? WHERE id_user=? AND id_space=?";
        $this->runRequest($sql, array($convention_url, $id_user, $id_space)); 
    }
    
    public function getUserSpaceInfo($id_user){
        $sql = "SELECT * FROM core_j_spaces_user WHERE id_user=?";
        return $this->runRequest($sql, array($id_user))->fetch();
    }
   
    public function getUserSpaceInfo2($id_space, $id_user){
        $sql = "SELECT * FROM core_j_spaces_user WHERE id_space=? AND id_user=?";
        return $this->runRequest($sql, array($id_space, $id_user))->fetch();
    }
}
