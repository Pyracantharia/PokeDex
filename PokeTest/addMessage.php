<?php

use Pokedex\Utils\Database;
require 'Class/Utils/Database.php';

session_start();

/**
 * J'appeler l'autoloader
 */
require 'vendor/autoload.php';

$db = new Database();

/**
 * Je vérifié si les POST existent
 */

if(isset($_POST['pseudo'], $_POST['message'], $_POST['nom'], $_POST['types'], $_POST['num'], $_POST['taille'], $_POST['poids'], $_POST['talent'], $_POST['couleur'], $_POST['image'])) {
    $error = false;
    /**
     * Je trim le pseudo et le message c'est à dire que j'enlève les espace avant et après
     */
    $username = trim($_POST['pseudo']);
    $contenu = trim($_POST['message']);
    $nom = trim($_POST['nom']);
    $types = trim($_POST['types']);
    $num = trim($_POST['num']);
    $taille = trim($_POST['taille']);
    $poids = trim($_POST['poids']);
    $talent = trim($_POST['talent']);
    $couleur = trim($_POST['couleur']);
    date_default_timezone_set('Europe/Paris'); 
    $date = date('Y-m-d H:i:s');
    $image = $_POST['image'];
    

    


    /**
     * Je vérifie si les variable ne sont pas vides
     * Si un utilisateur à rentrer qqchose sinon ERROR
     */
    if(empty($username) && empty($contenu) && empty($nom) && empty($types) && empty($num) && empty($taille) && empty($poids) && empty($talent) && empty($couleur) && empty($image)) {
        $error = true;
    } 

    /**
     * Je vérifie la taille de mon pseudo avec la fonction mb_strlen qui vérifie même les caractères unicode
     * Si la taille du pseudo est supérieur à 29 caractères erreur
     */
    if(mb_strlen($username) > 29) {
        $error = true;
    }

     /**
     * Je vérifie la taille de mon message avec la fonction mb_strlen qui vérifie même les caractères unicode
     * Si la taille du message est supérieur à 300 caractères erreur
     */
    if(mb_strlen($contenu) > 300) {
        $error = true;
    }
    if(mb_strlen($nom) > 29) {
        $error = true;
    }
    if(mb_strlen($types) > 29) {
        $error = true;
    }
    if($num > 151) {
        $error = true;
    }
    if($num < 1) {
        $error = true;
    }
    if(is_numeric($num) == false) {
        $error = true;
    }
    if(is_int($num) == true) {
        $error = true;
    }
    if($taille < 0.1) {
        $error = true;
    }
    if($poids < 0.1) {
        $error = true;
    }
    if(mb_strlen($talent) > 29) {
        $error = true;
    }
    if(mb_strlen($couleur) > 29) {
        $error = true;
    }    
    /**
     *  Si y a au moins une erreur ça rentre dans la fonction
     */
    if($error) { 
        /**
         * Je déclare une session pour sauvegarder ce que l'utilisateur à rentrer
         * Ensuite je redirige l'utilisateur vers la page d'accueil
         * et j'exit
         */
        $_SESSION['pseudo'] = $username;
        $_SESSION['message'] = $contenu;
        $_SESSION['nom'] = $nom;
        $_SESSION['types'] = $types;
        $_SESSION['num'] = $num;
        $_SESSION['taille'] = $taille;
        $_SESSION['poids'] = $poids;
        $_SESSION['talent'] = $talent;
        $_SESSION['couleur'] = $couleur;
        $_SESSION['image'] = $image;

        header('Location: index.php?error=1');
        exit();
    } else {
        /**
         * Je prepare la requête ensuite je l'execute
         * Après je unset les session, ce qui permettra de les vider 
         * Puis je rediriger l'utilisateur vers la page d'accueil
         * et j'exit
         */
        $query = $db->getPdo()->prepare("INSERT INTO livredor (username, contenu, date, nom, types, num, taille, poids, talent, couleur,image) VALUES (:username, :contenu, :date, :nom, :types, :num , :taille, :poids, :talent, :couleur, :image)");
        $query->execute([
            'username' => $username,
            'contenu' => $contenu,
            'date' => $date,
            'nom' => $nom,
            'types' => $types,
            'num' => $num,
            'taille' => $taille,
            'poids' => $poids,
            'talent' => $talent,
            'couleur' => $couleur,
            'image' => $image
        ]);
        unset($_SESSION['pseudo']);
        unset($_SESSION['message']);
        unset($_SESSION['nom']);
        unset($_SESSION['types']);
        unset($_SESSION['num']);
        unset($_SESSION['taille']);
        unset($_SESSION['poids']);
        unset($_SESSION['talent']);
        unset($_SESSION['couleur']);
        unset($_SESSION['image']);

        header('Location: index.php?success=1');
        exit();
    }
}