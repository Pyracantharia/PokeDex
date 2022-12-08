<?php
/**
 * Utilisation du namespace App
 */
namespace Pokedex;

use DateTime;
use Image;

class PokeLivre
{

  private $username;
  private $contenu;
  private $date;
  private $nom;
  private $id;
  private $types;

  

  public function setUsername(string $username)
  {
    $this->username = $username;
  }

  public function getUsername(): string {
    return $this->username;
  }

  public function setContenu(string $contenu)
  {
    $this->contenu = $contenu;
  }

  public function getContenu(): string {
    return $this->contenu;
  }

  public function setNom(string $contenu)
  {
    $this->nom = $nom;
  }

  public function getNom(): string {
    return $this->nom;
  }

  public function setTypesP(string $types)
  {
    $this->typesP = $typesP;
  }

  public function getTypesP(): string {
    return $this->typesP;
  }

  public function setTypesS(string $types)
  {
    $this->typesS = $typesS;
  }
  
  public function getTypesS(): string {
    return $this->typesS;
  }

  public function setNum(int $num)
  {
    $this->num = $num;
  }

  public function getNum(): int {
    return $this->num;
  }

  public function setTaille(int $taille)
  {
    $this->num = $taille;
  }

  public function getTaille(): int {
    return $this->taille;
  }

  public function setPoids(int $poids)
  {
    $this->poids = $poids;
  }

  public function getPoids(): int {
    return $this->poids;
  }

  public function setTalent(string $talent)
  {
    $this->talent = $talent;
  }
  public function getTalent(): string {
    return $this->talent;
  }

  public function setCouleur(string $couleur)
  {
    $this->couleur = $couleur;
  }
  public function getCouleur(): string {
    return $this->couleur;
  }

  public function setImage(string $image)
  {
    $this->image = $image;
  }
  public function getImage(): string {
    return $this->image;
  }




  public function setDate(string $date)
  {
    $this->date = $date;
  }

  /**
   * A chaque fois que j'appel le getDate() ce sera toujours au format DateTime
   *
   * @return DateTime
   */
  public function getDate(): DateTime {
    return new DateTime($this->date);
  }

  public function setId(int $id)
  {
    $this->id = $id;
  }

  public function getId(): int {
    return $this->id;
  }
}
