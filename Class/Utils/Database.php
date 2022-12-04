<?php

namespace Pokedex\Utils;

use PDO;

class Database
{

  /**
   * J'initialise la config bdd
   *
   * @var string
   */
  private $host = 'localhost';
  private $dbname = 'pokedex';
  private $user = 'root';
  private $password = 'root';
  private $port = 3306;
  private $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
  ];

  /**
   * J'appelle PDO et je retourne PDO
   *
   * @return PDO
   */
  public function getPdo(): PDO
  {
    return new PDO("mysql:host=$this->host:$this->port;dbname=$this->dbname", $this->user, $this->password, $this->options);
  }
}
