<?php
include dirname(__DIR__)."/php/items.php";
$item = new Items();
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        switch ($_POST['cate']) {
            case 'register':
                echo $item->insert($_POST);
                break;
            case 'update':
                echo $item->update($_POST['id'],$_POST);
                break;
        }
        break;
    case'GET':
        switch ($_GET['cate']) {
            case 'load':
                echo $item->load();
                break;
            case 'loadbyid':
                echo $item->loadById($_GET['id']);
                break;

            default:
                echo "Invalid";
        }
        break;
}

?>