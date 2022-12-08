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

require 'template/header.php';

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
  echo "  <script>
  PlaySound = function () {
              var audio = new Audio('pokesong.mp3');
              audio.loop = false;
              audio.play(); 
          }
          PlaySound();
    </script>";

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

  <div class="p-5 mb-5 rounded add">
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
      <h1>Modifier le Pokemon</h1>
      <section class="container df-main add">

        <div class="df-modal-content ">
          
          <p>En remplissant ce formulaire</p>



          <form method="POST" class="df-form" action="addMessage.php" enctype="multipart/form-data">

            <div class="form-group mt-3">

              <div class="df-input-box">
                <label for="username">Votre pseudo</label>
                <input type="text" value="<?= isset($_SESSION['pseudo']) ? htmlspecialchars($_SESSION['pseudo']) : '' ?>" name="pseudo" class="form-control" id="username" placeholder="Votre pseudo">

              </div>

              <div class="df-input-box">
                <label for="message">Votre message</label>
                <textarea type="text" value="<?= isset($_SESSION['message']) ? htmlspecialchars($_SESSION['message']) : '' ?>" name="message" class="form-control" id="message" placeholder="Votre message"></textarea>
              </div>

              <div class="df-input-box">
                <label for="username">Le nom</label>
                <input type="text" value="<?= isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : '' ?>" name="nom" class="form-control" id="nom" placeholder="Le nom">
              </div>


              <label for="types">Le type</label>
              <div class="df-select-box">
                <select name="typesP" class="df-select-box" id="typesP">

                  <option selected>Voir les types Physique</option>
                  <option value="Acier">Acier</option>
                  <option value="Combat">Combat</option>
                  <option value="Insecte">Insecte</option>
                  <option value="Normal">Normal</option>
                  <option value="Poison">Poison</option>
                  <option value="Roche">Roche</option>
                  <option value="Sol">Sol</option>
                  <option value="Spectre">Spectre</option>
                  <option value="Vol">Vol</option>
                  <?= isset($_SESSION['typesP']) ? htmlspecialchars($_SESSION['typesP']) : '' ?>
                </select>

              </div>



              <label for="types">Le type Special</label>
              <div class="df-select-box">
                <select name="typesS" class="df-select-box" id="typesS">

                  <option selected>Voir les types spéciaux</option>
                  <option value="Acier">Dragon</option>
                  <option value="Combat">Eau</option>
                  <option value="Insecte">Electrik</option>
                  <option value="Normal">Feu</option>
                  <option value="Poison">Glace</option>
                  <option value="Roche">Plante</option>
                  <option value="Sol">Psy</option>
                  <option value="Spectre">Ténèbres</option>
                  <?= isset($_SESSION['typesS']) ? htmlspecialchars($_SESSION['typesS']) : '' ?>
                </select>

              </div>



              <div class="df-input-box">
                <label for="num">Le numéro national</label>
                <input type="number" name="num" class="form-control" id="num" rows="3" placeholder="Numéro national"><?= isset($_SESSION['num']) ? (is_numeric($_SESSION['num'])) : '' ?></input>
              </div>

              <div class="df-input-box">
                <label for="taille">La taille</label>
                <input type="number" name="taille" class="form-control" id="taille" rows="3" placeholder="Taille"><?= isset($_SESSION['taille']) ? (is_float($_SESSION['taille'])) : '' ?></input>
              </div>

              <div class="df-input-box">

                <label for="poids">Le poids</label>

                <input type="number" name="poids" class="form-control" id="poids" rows="3" placeholder="Poids"><?= isset($_SESSION['poids']) ? (is_numeric($_SESSION['poids'])) : '' ?></input>
              </div>


              <label for="types">Le talent</label>
              <div class="df-select-box">
                <select name="talent" class="df-select-box" id="typesS">

                  <option selected>Voir les talents</option>
                  <option value="Acier">Dragon</option>
                  <option value="Combat">Eau</option>
                  <option value="Insecte">Electrik</option>
                  <option value="Normal">Feu</option>
                  <option value="Poison">Glace</option>
                  <option value="Roche">Plante</option>
                  <option value="Sol">Psy</option>
                  <option value="Spectre">Ténèbres</option>
                  <?= isset($_SESSION['talent']) ? (is_numeric($_SESSION['talent'])) : '' ?>
                </select>

              </div>



              <label for="couleur">La Couleur</label>
              <div class="df-select-box">
                <select name="couleur" class="df-select-box" id="couleur">

                  <option selected>Voir les couleurs</option>
                  <option value="Rouge">Rouge</option>
                  <option value="Bleu">Bleu</option>
                  <option value="Vert">Vert</option>
                  <option value="Rose">Rose</option>
                  <option value="Violet">Violet</option>
                  <option value="Brun">Brun</option>
                  <option value="Gris">Gris</option>
                  <option value="Blanc">Blanc</option>
                  <option value="Noir">Noir</option>
                  <?= isset($_SESSION['couleur']) ? (is_numeric($_SESSION['couleur'])) : '' ?>
                </select>

              </div>

              <div class="df-input-box">
                <label for="couleur">Choisir L'image</label>
                <input required type="file" name="image" id="image"><br>
              </div>

            </div>
            <button type="submit" class="btn btn-secondary mt-3">Valider</button>
          </form>
        </div>

      </section>

    <?php
    } else {
    ?>
      <section class="container df-main add">

        <div class="df-modal-content ">
          <h2>Ajouter votre Pokemon</h2>
          <p>En remplissant ce formulaire</p>



          <form method="POST" class="df-form" action="addMessage.php" enctype="multipart/form-data">

            <div class="form-group mt-3">

              <div class="df-input-box">
                <label for="username">Votre pseudo</label>
                <input type="text" value="<?= isset($_SESSION['pseudo']) ? htmlspecialchars($_SESSION['pseudo']) : '' ?>" name="pseudo" class="form-control" id="username" placeholder="Votre pseudo">

              </div>

              <div class="df-input-box">
                <label for="message">Votre message</label>
                <textarea type="text" value="<?= isset($_SESSION['message']) ? htmlspecialchars($_SESSION['message']) : '' ?>" name="message" class="form-control" id="message" placeholder="Votre message"></textarea>
              </div>

              <div class="df-input-box">
                <label for="username">Le nom</label>
                <input type="text" value="<?= isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : '' ?>" name="nom" class="form-control" id="nom" placeholder="Le nom">
              </div>


              <label for="types">Le type</label>
              <div class="df-select-box">
                <select name="typesP" class="df-select-box" id="typesP">

                  <option selected>Voir les types Physique</option>
                  <option value="Acier">Acier</option>
                  <option value="Combat">Combat</option>
                  <option value="Insecte">Insecte</option>
                  <option value="Normal">Normal</option>
                  <option value="Poison">Poison</option>
                  <option value="Roche">Roche</option>
                  <option value="Sol">Sol</option>
                  <option value="Spectre">Spectre</option>
                  <option value="Vol">Vol</option>
                  <?= isset($_SESSION['typesP']) ? htmlspecialchars($_SESSION['typesP']) : '' ?>
                </select>

              </div>



              <label for="types">Le type Special</label>
              <div class="df-select-box">
                <select name="typesS" class="df-select-box" id="typesS">

                  <option selected>Voir les types spéciaux</option>
                  <option value="Acier">Dragon</option>
                  <option value="Combat">Eau</option>
                  <option value="Insecte">Electrik</option>
                  <option value="Normal">Feu</option>
                  <option value="Poison">Glace</option>
                  <option value="Roche">Plante</option>
                  <option value="Sol">Psy</option>
                  <option value="Spectre">Ténèbres</option>
                  <?= isset($_SESSION['typesS']) ? htmlspecialchars($_SESSION['typesS']) : '' ?>
                </select>

              </div>



              <div class="df-input-box">
                <label for="num">Le numéro national</label>
                <input type="number" name="num" class="form-control" id="num" rows="3" placeholder="Numéro national"><?= isset($_SESSION['num']) ? (is_numeric($_SESSION['num'])) : '' ?></input>
              </div>

              <div class="df-input-box">
                <label for="taille">La taille</label>
                <input type="number" name="taille" class="form-control" id="taille" rows="3" placeholder="Taille"><?= isset($_SESSION['taille']) ? (is_float($_SESSION['taille'])) : '' ?></input>
              </div>

              <div class="df-input-box">

                <label for="poids">Le poids</label>

                <input type="number" name="poids" class="form-control" id="poids" rows="3" placeholder="Poids"><?= isset($_SESSION['poids']) ? (is_numeric($_SESSION['poids'])) : '' ?></input>
              </div>


              <label for="types">Le talent</label>
              <div class="df-select-box">
                <select name="talent" class="df-select-box" id="typesS">

                  <option selected>Voir les talents</option>
                  <option value="Acier">Dragon</option>
                  <option value="Combat">Eau</option>
                  <option value="Insecte">Electrik</option>
                  <option value="Normal">Feu</option>
                  <option value="Poison">Glace</option>
                  <option value="Roche">Plante</option>
                  <option value="Sol">Psy</option>
                  <option value="Spectre">Ténèbres</option>
                  <?= isset($_SESSION['talent']) ? (is_numeric($_SESSION['talent'])) : '' ?>
                </select>

              </div>

              <label for="couleur">La Couleur</label>
              <div class="df-select-box">
                <select name="couleur" class="df-select-box" id="couleur">

                  <option selected>Voir les couleurs</option>
                  <option value="Rouge">Rouge</option>
                  <option value="Bleu">Bleu</option>
                  <option value="Vert">Vert</option>
                  <option value="Rose">Rose</option>
                  <option value="Violet">Violet</option>
                  <option value="Brun">Brun</option>
                  <option value="Gris">Gris</option>
                  <option value="Blanc">Blanc</option>
                  <option value="Noir">Noir</option>
                  <?= isset($_SESSION['couleur']) ? (is_numeric($_SESSION['couleur'])) : '' ?>
                </select>

              </div>

              <div class="df-input-box">
                <label for="couleur">Choisir L'image</label>
                <input required type="file" name="image" id="image"><br>
              </div>

            </div>
            <button type="submit" class="btn btn-secondary mt-3">Valider</button>
          </form>
        </div>

      </section>

    <?php
    }
    ?>
  </div>
  <!-- Je récupère le nombre de message -->
  <h2>Les Pokemon existants (<?= $resultPagination->nbr ?>) :</h2>
  <div class="pag-pok">
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
    </div>
  
  <div class="card-pok">

    <?php
    if ($resultPagination->nbr === 0) {
    ?>
      <div class="card mb-4">
        <div class="card-body ">
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
            <h5 class="card-title"><?= htmlspecialchars($data->getNom()) ?> Nom </h5>
            <h5 class="card-title"><?= htmlspecialchars($data->getTypesP()) ?> Types Physique</h5>
            <h5 class="card-title"><?= htmlspecialchars($data->getTypesS()) ?> Types Special</h5>
            <h5 class="card-title"><?= htmlspecialchars($data->getNum()) ?> Numéro</h5>
            <h5 class="card-title"><?= htmlspecialchars($data->getTaille()) ?> Taille </h5>
            <h5 class="card-title"><?= htmlspecialchars($data->getPoids()) ?> Poids</h5>
            <h5 class="card-title"><?= htmlspecialchars($data->getTalent()) ?> Talent</h5>
            <h5 class="card-title"><?= htmlspecialchars($data->getCouleur()) ?> Couleur</h5>
            <h6 class="card-subtitle mb-2 text-muted">Le <?= $data->getDate()->format('d/m/Y à H:i:s') ?></h6>
            <p class="card-text"> <?= htmlspecialchars($data->getContenu()) ?></p>
            <img src="image/<?= $data->getImage() ?>" alt="image" width="200px">





            <a href="deleteMessage.php?id=<?= $data->getId() ?>" style="color:red" class="card-link">Supprimer</a>
            <a href="?action=update&id=<?= $data->getId() ?>" class="card-link">Modifier</a>
          </div>
        </div>
    <?php
      }
    }
    ?>
    

  </div>
</main>

<?php
require 'template/footer.php';
?>