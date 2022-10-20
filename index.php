<?php

require "vendor/autoload.php";


$appManager = new \MicroFramework\Core\ApplicationManager(__DIR__);
$appManager->newApp("BaseApplication", \App\Controller\TestController::class);

$appManager->start("BaseApplication");