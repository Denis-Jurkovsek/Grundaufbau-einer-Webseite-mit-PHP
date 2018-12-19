<?php

require __DIR__ . "/autoload.php";

function escape($str) {
  return htmlentities($str, ENT_QUOTES, 'UTF-8');
}

$container = new App\Core\Container();

?>
