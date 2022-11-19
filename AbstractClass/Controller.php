<?php

namespace Framework\AbstractClass;

use Framework\Response\View;

abstract class Controller
{
    public function view(string $path, ?array $args = null, int $statusCode = null, bool $extract = true): int
    {
        return (new View(dirname(__DIR__) . DIRECTORY_SEPARATOR . $_ENV["VIEW_PATH"] . DIRECTORY_SEPARATOR . ((str_contains($path, ".php")) ? $path : "$path.php"), $statusCode))->renderWithReference($args, $extract);
    }
}