<?php

namespace Framework\AbstractClass;

use Framework\Response\JSONResponse;
use Framework\Response\Response;

abstract class ApiBase
{
    public function json(array $content, ?int $statusCode = null): JSONResponse
    {
        return new JSONResponse($content, $statusCode);
    }

    public function raw(string $content, ?int $statusCode = null): Response
    {
        return new Response($content, $statusCode);
    }
}