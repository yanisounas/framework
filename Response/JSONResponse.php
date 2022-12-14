<?php

namespace Framework\Response;

class JSONResponse extends Response
{
    public function __construct(
        array $content,
        ?int $statusCode = null,
        ?string $contentType = "application/json")
    {
        parent::__construct(json_encode($content), $statusCode, $contentType);
    }
}