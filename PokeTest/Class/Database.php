<?php

namespace Pok;


use PDO;

class Database
{

  /**
   * J'initialise la config bdd
   *
   * @var string
   */
  private $host = 'localhost';
  private $dbname = 'db_pokedex';
  private $user = 'root';
  private $password = 'root';
  private $port = 3306;
  private $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
  ];

  public function getPdo(): PDO
  {
    return new PDO("mysql:host=$this->host:$this->port;dbname=$this->dbname", $this->user, $this->password, $this->options);
  }


}

$conn = new Database();
var_dump($conn->getPdo());