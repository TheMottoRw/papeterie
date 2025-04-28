<?php
include "../php/clients.php";
$client = new Clients();
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        switch ($_POST['cate']) {
            case 'register':
                echo $client->insert($_POST);
                break;
            case 'update':
                echo $client->update($_POST['id'],$_POST);
                break;
        }
        break;
    case'GET':
        switch ($_GET['cate']) {
            case 'load':
                echo $client->load();
                break;
            case 'loadbyid':
                echo $client->loadById($_GET['id']);
                break;

            default:
                echo "Invalid";
        }
        break;
}

?>