<?php

require_once 'Framework/Model.php';

class EsSaleItem extends Model {

    public function __construct() {
        $this->tableName = "es_sale_items";
        $this->setColumnsInfo("id", "int(11)", 0);
        $this->setColumnsInfo("id_sale", "int(11)", 0);
        $this->setColumnsInfo("id_batch", "int(11)", 0);
        $this->setColumnsInfo("quantity", "int(11)", 0);
        $this->setColumnsInfo("price", "float", -1);
        $this->primaryKey = "id";
    }

    public function getitems($id_sale) {
        $sql = "SELECT * FROM es_sale_items WHERE id_sale=?";
        return $this->runRequest($sql, array($id_sale))->fetchAll();
    }
    
    public function set($id, $id_sale, $id_batch, $quantity, $price = -1) {
        if ($id == 0) {
            $sql = 'INSERT INTO es_sale_items (id_sale, id_batch, quantity, price) VALUES (?,?,?,?)';
            $this->runRequest($sql, array($id_sale, $id_batch, $quantity, $price));
            return $this->getDatabase()->lastInsertId();
        } else {
            $sql = 'UPDATE es_sale_items SET id_sale=?, id_batch=?, quantity=?, price=? WHERE id=?';
            $this->runRequest($sql, array($id_sale, $id_batch, $quantity, $price, $id));
        }
    }
    
    public function delete($id) {
        $sql = "DELETE FROM es_sale_items WHERE id=?";
        $this->runRequest($sql, array($id));
    }

}
