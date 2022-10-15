<?php
try {
    $engine = 'mysql';
    $host = 'database';
    $database = 'data';
    $user = 'root';
    $pass = 'password';

    $db = new PDO("$engine:host=$host;dbname=$database", $user, $pass);
} catch(Exception $e){
    die('Erreur : '.$e->getMessage());
}