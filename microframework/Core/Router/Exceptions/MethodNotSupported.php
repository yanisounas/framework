<?php

namespace MicroFramework\Core\Router\Exceptions;

class MethodNotSupported extends RouterExceptions
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}