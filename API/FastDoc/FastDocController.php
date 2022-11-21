<?php

namespace Framework\API\FastDoc;

use Framework\AbstractClass\Controller;
use Framework\API\ApiManager;
use Framework\Router\Attributes\Route;

class FastDocController extends Controller
{
    #[Route("/api-documentation")]
    public function home()
    {
        return $this->view("FastDoc/home", ["apis" => ApiManager::getInstance()->api]);
    }
}