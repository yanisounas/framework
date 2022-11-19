<?php

namespace Framework\Router\Exceptions;

class RouterExceptions extends \Exception
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}