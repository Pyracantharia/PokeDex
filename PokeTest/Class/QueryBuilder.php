<?php

namespace Pokedex;

use PDO;

class QueryBuilder
{

    private $pdo;

    private $fields;
    private $conditions;
    private $from;

    // cette fonction permet de récupérer la connexion à la bdd ,
    // de la stocker dans la propriété $pdo et de retourner l'objet courant 

    public function getPdo(): PDO
    {
        return new PDO("mysql:host=$this->host:$this->port;dbname=$this->dbname", $this->user, $this->password, $this->options);
    }

    
}
