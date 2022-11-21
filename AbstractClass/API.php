<?php

namespace Framework\AbstractClass;

use Framework\Response\JSONResponse;

abstract class API
{
    public function json(array $content, ?int $statusCode = null): JSONResponse
    {
        return new JSONResponse($content, $statusCode);
    }
}