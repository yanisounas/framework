<?php

namespace Framework\ORM\Exceptions;

class ORMException extends \Exception
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}