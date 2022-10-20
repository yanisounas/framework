<?php

namespace App\Controller;

use MicroFramework\Core\AbstractClass\Controller;
use MicroFramework\Core\Response\JsonResponse;
use MicroFramework\Core\Router\Attributes\Endpoint;

class ApiController extends Controller
{
    #[Endpoint("api/")]
    public function home()
    {
        return new JsonResponse(["test"=>"ok"]);
    }
}