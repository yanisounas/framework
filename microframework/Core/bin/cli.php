<?php

namespace MicroFramework\Core\bin;

use MicroFramework\Core\bin\Parser\Parser;

class CLI
{
    public function __construct(private readonly Parser $parser) {}

    public function getParsedValues(): Parser
    {
        return $this->parser;
    }

}



