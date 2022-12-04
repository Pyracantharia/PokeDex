<?php

/**
 * Ajout des namespace dans les class ci-dessous et je les appelles avec le USE
 */


use Pokedex\PokeLivre;
use Pokedex\Utils\Database;

require 'Class/Utils/Database.php';
require 'vendor/autoload.php';
require 'Class/PokeLivre.php';

session_start();
$title = "Accueil";

require 'templates/header.php';

/**
 * Initialisation des variables d'error, de succes...
 */
$error = null;
$update = null;
$success = null;
$delete = null;
$currentPage = 1;
$parPage = 5;


/**
 * Ces condition permettent de récupérer les élèments dans le GET
 */

if (isset($_GET['success']) && $_GET['success'] === '1') {
  $success = "Votre message a bien été enregistré";
}
if (isset($_GET['update']) && $_GET['update'] === '1') {
  $update = "Votre message a bien été modifié";
}
if (isset($_GET['error']) && $_GET['error'] === '1') {
  $error = "Erreur formulaire";
}
if (isset($_GET['delete']) && $_GET['delete'] === '1') {
  $delete = "Message # " . (int)$_GET['id'] . " supprimé";
}
if (isset($_GET['page']) && !empty($_GET['page'])) {
  $currentPage = (int)$_GET['page'];
} else {
  $currentPage = 1;
}

/**
 * Initialisation de la base de donné
 */
$db = new Database();

// On prépare la requête
/**
 * La pagination
 * Je prépare la requête et je compte le nombre d'element que j'ai dans la table pokedex
 * Ensuite je le renvoie sous le format nbr
 */
$query = $db->getPdo()->prepare('SELECT COUNT(*) AS nbr FROM pokedex');
$query->execute();
$resultPagination = $query->fetch();

/**
 * NbrMessage = Nombre d'element dans ma base de données sous format INT
 */
$nbrMessage = (int)$resultPagination->nbr;
$pages = ceil($nbrMessage / $parPage); // 8 message / 5 mesage par page
$premier = ($currentPage * $parPage) - $parPage; // Page 1 * 5 message par page - 5 message par page

/**
 * Je récupère mes élèments dans ma table PokeLivre
 * Je lui défini un ordre decroissant = Du plus récent au plus ancien
 * Je lui défini dans les parametre le :premier qui est la page à afficher
 * Je lui défini dans les parametres le :parpage qui est le nombre d'element que je souhaite par page initialiser en haut du fichier
 * Je bindValue poour lui dire que je veux que des int
 * Ensuite j'execute ma requête préparé
 * A la fin je fetchAll ça veut dire que je souhaite tout récupérer et j'appel le tout dans ma class PokeLivre
 * le PokeLivre::class c'est pour appeler la class grâce au namespace, si je ne fais pas ça ça me retourne une erreur
 */
$query = $db->getPdo()->prepare('SELECT * FROM pokedex ORDER BY id DESC LIMIT :premier, :parpage');
$query->bindValue(':premier', $premier, PDO::PARAM_INT);
$query->bindValue(':parpage', $parPage, PDO::PARAM_INT);
$query->execute();
$result = $query->fetchAll(PDO::FETCH_CLASS, PokeLivre::class);


?>

<main class="container">
  <?php
  if (!empty($success)) {
  ?>
    <div class="alert alert-success">
      <?= $success; ?>
    </div>
  <?php
  }
  ?>
  <?php
  if (!empty($update)) {
  ?>
    <div class="alert alert-success">
      <?= $update; ?>
    </div>
  <?php
  }
  ?>
  <?php
  if (!empty($delete)) {
  ?>
    <div class="alert alert-success">
      <?= $delete; ?>
    </div>
  <?php
  }
  ?>
  <?php
  if (!empty($error)) {
  ?>
    <div class="alert alert-danger">
      <?= $error; ?>
    </div>
  <?php
  }
  ?>

  <div class="bg-light p-5 mb-5 rounded">
    <?php
    /**
     * Je récupère l'action d'update depuis l'url puis je fais une condition
     * Si je suis en train de modifier je modifie sinon j'insère
     */
    if (isset($_GET['action']) && $_GET['action'] === 'update') {
      /**
       * Je cast l'id que j'ai récupéré en INT
       */
      $id = (int)$_GET['id'];
      $query = $db->getPdo()->prepare('SELECT * FROM pokedex WHERE id = :id');
      $query->execute([
        "id" => $id
      ]);
      $getData = $query->fetch();

    ?>
      <h1>Modifier mon message</h1>
      <form method="POST" action="updateMessage.php?id=<?= $getData->id ?>">
        <div class="form-group">
          <label for="username">Votre pseudo</label>
          <input type="text" value="<?= htmlspecialchars($getData->username) ?>" name="pseudo" class="form-control" id="username" placeholder="Votre pseudo">
        </div>
        <div class="form-group mt-3">
          <label for="message">Votre message</label>
          <textarea name="message" class="form-control" id="message" rows="3"><?= htmlspecialchars($getData->contenu) ?></textarea>
          <label for="nom">Le nom</label>
          <textarea name="nom" class="form-control" id="message" rows="3"><?= isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : '' ?></textarea>
          <label for="types">Le type</label>
          <textarea name="types" class="form-control" id="types" rows="3"><?= isset($_SESSION['types']) ? htmlspecialchars($_SESSION['types']) : '' ?></textarea>
          <label for="num">Le numéro national</label>
          <textarea name="num" class="form-control" id="num" rows="3"><?= isset($_SESSION['num']) ? (is_numeric($_SESSION['num'])) : '' ?></textarea>
          <label for="taille">La taille</label>
          <textarea name="taille" class="form-control" id="taille" rows="3"><?= isset($_SESSION['taille']) ? (is_float($_SESSION['taille'])) : '' ?></textarea>
          <label for="poids">Le poids</label>
          <textarea name="poids" class="form-control" id="poids" rows="3"><?= isset($_SESSION['poids']) ? (is_numeric($_SESSION['poids'])) : '' ?></textarea>
          <label for="talent">Le talent</label>
          <textarea name="talent" class="form-control" id="talent" rows="3"><?= isset($_SESSION['talent']) ? (is_numeric($_SESSION['talent'])) : '' ?></textarea>
          <label for="couleur">La couleur</label>
          <textarea name="couleur" class="form-control" id="couleur" rows="3"><?= isset($_SESSION['couleur']) ? (is_numeric($_SESSION['couleur'])) : '' ?></textarea>
          Image: <input required type="file" name="image" id="image"><br>
        </div>

        
        <button type="submit" class="btn btn-primary mt-3">Modifier</button>

      </form>
    <?php
    } else {
    ?>

      <h1>Laissez moi un message</h1>
      <form method="POST" action="addMessage.php">
        <div class="form-group">
          <label for="username">Votre pseudo</label>
          <input type="text" value="<?= isset($_SESSION['pseudo']) ? htmlspecialchars($_SESSION['pseudo']) : '' ?>" name="pseudo" class="form-control" id="username" placeholder="Votre pseudo">

        </div>
        <div class="form-group mt-3">
          <label for="message">Votre message</label>
          <textarea name="message" class="form-control" id="message" rows="3"><?= isset($_SESSION['message']) ? htmlspecialchars($_SESSION['message']) : '' ?></textarea>
          <label for="message">Le nom</label>
          <textarea name="nom" class="form-control" id="message" rows="3"><?= isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : '' ?></textarea>
          <label for="types">Le type</label>
          <textarea name="types" class="form-control" id="types" rows="3"><?= isset($_SESSION['types']) ? htmlspecialchars($_SESSION['types']) : '' ?></textarea>
          <label for="num">Le numéro national</label>
          <textarea name="num" class="form-control" id="num" rows="3"><?= isset($_SESSION['num']) ? (is_numeric($_SESSION['num'])) : '' ?></textarea>
          <label for="taille">La taille</label>
          <textarea name="taille" class="form-control" id="taille" rows="3"><?= isset($_SESSION['taille']) ? (is_float($_SESSION['taille'])) : '' ?></textarea>
          <label for="poids">Le poids</label>
          <textarea name="poids" class="form-control" id="poids" rows="3"><?= isset($_SESSION['poids']) ? (is_numeric($_SESSION['poids'])) : '' ?></textarea>
          <label for="talent">Le talent</label>
          <textarea name="talent" class="form-control" id="talent" rows="3"><?= isset($_SESSION['talent']) ? (is_numeric($_SESSION['talent'])) : '' ?></textarea>
          <label for="couleur">La couleur</label>
          <textarea name="couleur" class="form-control" id="couleur" rows="3"><?= isset($_SESSION['couleur']) ? (is_numeric($_SESSION['couleur'])) : '' ?></textarea>
          Image: <input required type="file" name="image" id="image"><br>

        </div>
        <button type="submit" class="btn btn-primary mt-3">Valider</button>
      </form>
    <?php
    }
    ?>
  </div>
  <!-- Je récupère le nombre de message -->
  <h2>Mes messages (<?= $resultPagination->nbr ?>) :</h2>
  <?php
  if ($resultPagination->nbr === 0) {
  ?>
    <div class="card mb-4">
      <div class="card-body">
        N'hésitez pas à nous laisser un message
      </div>
    </div>
    <?php
  } else {
    /**
     * Je fais une boucle qui récupère tout
     */
    foreach ($result as $data) {
    ?>
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($data->getUsername()) ?></h5>
          <h5 class="card-title"><?= htmlspecialchars($data->getNom()) ?>   Nom </h5>
          <h5 class="card-title"><?= htmlspecialchars($data->getTypes()) ?>   Types</h5>
          <h5 class="card-title"><?= htmlspecialchars($data->getNum()) ?>    Numéro</h5>
          <h5 class="card-title"><?= htmlspecialchars($data->getTaille()) ?>   Taille </h5>
          <h5 class="card-title"><?= htmlspecialchars($data->getPoids()) ?>  Poids</h5>
          <h5 class="card-title"><?= htmlspecialchars($data->getTalent()) ?>   Talent</h5>
          <h5 class="card-title"><?= htmlspecialchars($data->getCouleur()) ?>   Couleur</h5>
          <h6 class="card-subtitle mb-2 text-muted">Le <?= $data->getDate()->format('d/m/Y à H:i:s') ?></h6>
          <p class="card-text"> <?= htmlspecialchars($data->getContenu()) ?></p>
          

    
     

          <a href="deleteMessage.php?id=<?= $data->getId() ?>" style="color:red" class="card-link">Supprimer</a>
          <a href="?action=update&id=<?= $data->getId() ?>" class="card-link">Modifier</a>
        </div>
      </div>
  <?php
    }
  }
  ?>
  Pagination
  <nav>
    <ul class="pagination">
      <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
      <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
        <a href="./?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
      </li>
      <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
      <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
        <a href="./?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
      </li>
    </ul>
  </nav>
</main>

<?php
require 'templates/footer.php';
?>