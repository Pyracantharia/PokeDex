<?php

use Pokedex\Utils\Database;
require 'Class/Utils/Database.php';
//recuperation des images


$db = new Database();
$query = $db->getPdo()->prepare('SELECT * FROM pokedex');
$query->execute();
$pokemons = $query->fetchAll();
var_dump($pokemons);


echo 'Voir les images';
foreach ($pokemons as $pokemon) {
    echo '<img src="uploads/'.$pokemon['image'].'">';
}


?>

