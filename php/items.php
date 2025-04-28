<?php
include_once dirname(__DIR__)."/php/DatabaseConnection.php";

class Items
{
    public $db;

    function __construct()
    {
        $this->db = DatabaseConnection::getInstance();
    }

    function insert($datas)
    {
        $res = $this->db->insert("INSERT INTO items (name, buying_price, selling_price, quantity) 
              VALUES (?,?,?,?)",[$datas['name'],$datas['buying_price'],$datas['selling_price'],$datas['quantity']]);
        return $res;
    }
    function load()
    {
        $data = $this->db->select("SELECT * FROM items");
        return json_encode($data);
    }
    function loadById($id)
    {
        $data = $this->db->selectOne("SELECT * FROM items WHERE id = ?",[$id]);
        return json_encode($data);

    }

    function update($id,$datas)
    {
        $res = $this->db->update("UPDATE clients SET name=?,buying_price=?,selling_price=?,quantity=? WHERE id=?",[$datas['name'],$datas['buying_price'],$datas['selling_price'],$datas['quantity'],$id]);
        return $res?'ok':'fail';
    }
}

?>