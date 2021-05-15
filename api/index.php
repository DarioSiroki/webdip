<?php
namespace Znamenitosti;

require __DIR__ . '/vendor/autoload.php';
require_once("config/router.php");

$router = new Router();
$router->start();

?>
