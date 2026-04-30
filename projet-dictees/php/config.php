<?php

// Mettre en commentaire les infos de connexion à ignorer 

//Serveur local xampp
$host = 'localhost';
$dbname = 'gr4m1idl'; 
$username = 'root';
$password = '';

// Serveur I3L
// $host = 'localhost';
// $dbname = 'gr4m1idl'; 
// $username = 'm2dilipem';
// $password = 'm2dilipem';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>