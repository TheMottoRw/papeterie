<?php
include 'db.php';
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        header('Content-Type: application/json');
        switch ($_GET['action']) {
            case "getItems":
                echo getItems();
                break;
            case "getItemById":
                echo getItemById($_GET['id']);
                break;
                default:
                    echo "Action not found";
        }
}
function getItems(){
    global $pdo;
    $query = "SELECT * FROM items";
    $stmt = $pdo->query($query);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return json_encode($items);
}
function getItemById($id){
    global $pdo;
    $query = "SELECT * FROM items WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':id' => $id
    ]);
    $items = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($items);
}
?>