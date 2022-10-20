<?php

namespace MicroFramework\Core\Api\Documentation\Controller;

use MicroFramework\Core\Api\Endpoint\Attributes\Endpoint;
use MicroFramework\Core\Response\View;

class ApiDocumentationController
{
    #[Endpoint("/api-documentation", name:"api-documentation")]
    public function home($endpoints)
    {
        return (new View($_ENV["APIDOC_PATH"] . "index.php"))->render($endpoints, false);
    }
}