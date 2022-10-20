<?php

namespace MicroFramework\Core\AbstractClass;

use MicroFramework\Core\Response\View;

abstract class Controller
{
    public function view(string $path, ?array $args = null, int $statusCode = null, bool $extract = true): int
    {
        return (new View($_ENV["VIEW_PATH"] . $path, $statusCode))->renderWithReference($args, $extract);
    }

}