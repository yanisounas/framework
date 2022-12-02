<?php

namespace Framework\AbstractClass;

use Framework\Response\View;

abstract class Controller
{
    public function view(string $path, ?array $args = null, int $statusCode = null, bool $extract = true): View
    {
        return new View(view: dirname(__DIR__) . DIRECTORY_SEPARATOR . $_ENV["VIEW_PATH"] . DIRECTORY_SEPARATOR . ((str_contains($path, ".php")) ? $path : "$path.php"),
            statusCode: $statusCode,
            args: $args,
            extract: $extract);
    }
}