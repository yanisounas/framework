<?php

require "vendor/autoload.php";


$appManager = new \MicroFramework\Core\ApplicationManager(__DIR__);
//$appManager->newApp("Shortener", \App\Controller\Shortener\HomeController::class);
$appManager->newApp("Api", \App\Controller\Shortener\HomeController::class);
$appManager->startAll();