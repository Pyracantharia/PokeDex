<?php
/**
 * Utilisation du namespace App
 */
namespace Pokedex;

use DateTime;

class LivreDor
{

  private $username;
  private $contenu;
  private $date;
  private $nom;
  private $id;

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
