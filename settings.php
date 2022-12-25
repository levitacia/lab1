<?php
   require_once 'pdoconfig.php';
    try {
        $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    } catch (PDOException $e) {
        exit("Error: " . $e->getMessage());
    }
?>