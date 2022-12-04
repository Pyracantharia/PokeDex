<?php

use Pokedex\Utils\Database;

session_start();

require 'vendor/autoload.php';
require 'Class/Utils/Database.php';

$db = new Database();

/**
 * Je vérifié si les POST existent
 */
if(isset($_GET['id'])) {
    $error = false;
    $id = (int)$_GET['id'];
    /**
     * Prépare -> j'execute -> je récupère
     */
    $query = $db->getPdo()->prepare('SELECT id FROM livredor WHERE id = :id');
    $query->execute([
        "id" => $id
    ]);
    $checkId = $query->fetch();

    /**
     * Je vérifie si l'id existe bien en base de données (Celui envoyer en paramètre)
     */
    if(!$checkId) {
        $error = true;
    }

    if(empty($id)) {
        $error = true;
    } 

    if($error) { 
        header('Location: index.php?error=1');
        exit();
    } else {
        $query = $db->getPdo()->prepare("DELETE FROM livredor WHERE id = :id");
        $query->execute([
           'id' => $id
        ]);
        header("Location: index.php?delete=1&id=$id");
        exit();
    }
} else {
    header("Location: index.php?error=1");
    exit();
}