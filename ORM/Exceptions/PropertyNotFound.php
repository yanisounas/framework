<?php

namespace Framework\ORM\Exceptions;

class PropertyNotFound extends ORMException
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}