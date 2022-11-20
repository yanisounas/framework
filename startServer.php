<?php
chdir(__DIR__. "/public/");

require "../vendor/autoload.php";

if (PHP_VERSION < "8.0.0")
    exit(throw new \Exception("PHP version must be at least 8.0.0; Your version : " . PHP_VERSION));

Framework\Router\RouterInitializer::getInstance(autoStart: true);

exec("php -S localhost:8000 -t ./ ./router.php");