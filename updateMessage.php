<?php

use Pokedex\Utils\Database;

session_start();
require 'Class/Utils/Database.php';
require 'vendor/autoload.php';

$db = new Database();

if (isset($_GET['id'])) {
    $errorId = false;
    $id = (int)$_GET['id'];
    /**
     * Prépare -> j'execute -> je récupère
     */
    $query = $db->getPdo()->prepare('SELECT id FROM pokedex WHERE id = :id');
    $query->execute([
        "id" => $id
    ]);
    $checkId = $query->fetch();

    /**
     * Je vérifie si l'id existe bien en base de données (Celui envoyer en paramètre)
     */
    if (!$checkId) {
        $errorId = true;
    }

    if (empty($id)) {
        $errorId = true;
    }

    if ($errorId) {
        header('Location: index.php?error=1');
        exit();
    } else {
        /**
         * Memes condition que le addMessage
         */
        if (isset($_POST['pseudo'], $_POST['message'], $_POST['nom'], $_POST['typesP'], $_POST['num'], $_POST['taille'], $_POST['poids'], $_POST['talent'], $_POST['couleur'])) {
            $errorForm = false;
            $username = trim($_POST['pseudo']);
            $contenu = trim($_POST['message']);
            $nom = trim($_POST['nom']);
            $typesP = trim($_POST['typesP']);
            $num = trim($_POST['num']);
            $taille = trim($_POST['taille']);
            $poids = trim($_POST['poids']);
            $talent = trim($_POST['talent']);
            $couleur = trim($_POST['couleur']);
            date_default_timezone_set('Europe/Paris'); // On défini la timezone
            $date = date('Y-m-d H:i:s');







            if (empty($username) && empty($contenu) && empty($nom) && empty($typesP) && empty($num) && empty($taille) && empty($poids) && empty($talent) && empty($couleur)) {
                $errorForm = true;
            }

            if (mb_strlen($username) > 29) {
                $errorForm = true;
            }

            if (mb_strlen($contenu) > 300) {
                $errorForm = true;
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



            



                        
    
            if ($errorForm) {
                /**
                 * Je renvoie l'id en paramètre pour garder la modification afin d'eviter que ça ramene dans l'insertion
                 */
                header("Location: index.php?action=update&id=$id&error=1");
                exit();
            } else {
                $query = $db->getPdo()->prepare("UPDATE pokedex SET username = :username, contenu = :contenu, date = :date, nom = :nom, typesP = :typesP, num = :num, taille = :taille, poids = :poids, talent = :talent, couleur = :couleur WHERE id = :id");
                $query->execute([
                    'id' => $id,
                    'username' => $username,
                    'contenu' => $contenu,
                    'date' => $date,
                    'nom' => $nom,
                    'date' => $date,
                    'nom' => $nom,
                    'typesP' => $typesP,
                    'num' => $num,
                    'taille' => $taille,
                    'poids' => $poids,
                    'talent' => $talent,
                    'couleur' => $couleur
                    
                    

                    
                ]);
                header("Location: index.php?update=1");
                exit();
            }
        }
    }
} else {
    header("Location: index.php?error=1");
    exit();
}
