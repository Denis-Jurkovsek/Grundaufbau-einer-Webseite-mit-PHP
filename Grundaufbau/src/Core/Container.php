<?php

namespace App\Core;

use PDO;
use PDOException;
use App\Post\PostsRepository;
use App\Post\PostsController;
use App\Post\CommentsRepository;
use App\Login\LoginRepository;
use App\Login\LoginController;
use App\Login\LoginService;
use App\Post\AdminController;

class Container
{
    private $receipts = [];
    private $instances = [];

    public function __construct()
    {
      $this->receipts = [
        'postsController' => function() {
          return new PostsController(
            $this->make('postsRepository'),
            $this->make('commentsRepository')
          );
        }, 'adminController' => function() {
          return new AdminController(
            $this->make('postsRepository'),
            $this->make('loginService')
          );
        }, 'loginService' => function() {
          return new LoginService(
            $this->make("loginRepository")
          );
        }, 'loginController' => function() {
          return new LoginController(
            $this->make("loginService")
          );
        }, 'postsRepository' => function() {
          return new PostsRepository(
            $this->make("pdo")
          );
        }, 'commentsRepository' => function() {
          return new CommentsRepository(
            $this->make("pdo")
          );
        }, 'loginRepository' => function() {
          return new LoginRepository(
            $this->make("pdo")
          );
        }, 'pdo' => function() {
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
