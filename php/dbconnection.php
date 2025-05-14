<?php
function connect()
{
    $connection = null;
    try {
        $connection = new PDO("mysql:host=localhost;dbname=papeterie", "root", "root");
    } catch (PDOException $ex) {
        echo "Could not connect to DatabaseConnection";
    }
    return $connection;
}

$conn = connect();
?>
