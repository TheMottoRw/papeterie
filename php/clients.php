<?php
include_once dirname(__DIR__)."/php/DatabaseConnection.php";

class Clients
{
    public $db;

    function __construct()
    {
        $this->db = DatabaseConnection::getInstance();
    }

    function insert($datas)
    {
        $res = $this->db->insert("INSERT INTO clients SET name=?,phone=?",[$datas['name'],$datas['phone']]);
        return $res;
    }
    function load()
    {
        $data = $this->db->select("SELECT * FROM clients");
        return json_encode($data);
    }
    function loadById($id)
    {
        $data = $this->db->selectOne("SELECT * FROM clients WHERE id = ?",[$id]);
        return json_encode($data);

    }

    function update($id,$datas)
    {
        $res = $this->db->update("UPDATE clients SET name=?,phone=? WHERE id=?",[$datas['name'],$datas['phone'],$id]);
        return $res?'ok':'fail';
    }
}

?>