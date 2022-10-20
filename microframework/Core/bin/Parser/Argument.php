<?php

namespace MicroFramework\Core\bin\Parser;

class Argument
{
    private array $aliases;

    public function __construct(private string $argument, string ...$aliases )
    {
        $this->aliases = $aliases;
    }
}