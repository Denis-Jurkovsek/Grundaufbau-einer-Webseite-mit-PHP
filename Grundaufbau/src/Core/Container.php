<?php

namespace App\Core;

use PDO;
use PDOException;

class Container
{
    private $receipts = [];
    private $instances = [];

    public function __construct()
    {
      $this->receipts = [
        'pdo' => function() {
          try {
            $pdo = new PDO(
              'mysql:host=HOST;dbname=DBNAME;charset=utf8',
              'USERNAME',
              'PASSWORD'
            );
          } catch (PDOException $e) {
            echo("Die Verbindung zur Datenbank ist fehlgeschlagen.");
            die();
          }

          $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
          return $pdo;
        }
      ];
    }

    public function make($name)
    {
      if (!empty($this->instances[$name])) {
          return $this->instances[$name];
      }

      if (isset($this->receipts[$name])) {
        $this->instances[$name] = $this->receipts[$name]();
      }

      return $this->instances[$name];
    }
}

?>
