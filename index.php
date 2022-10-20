<?php

require "vendor/autoload.php";


$appManager = new \MicroFramework\Core\ApplicationManager(__DIR__);
$appManager->newApp("BaseApplication", \App\Controller\HomeController::class);
$appManager->newApp("Api", \App\Controller\ApiController::class);

$appManager->startAll();
