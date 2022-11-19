<?php
namespace Framework\Controller;

use Framework\AbstractClass\Controller;
use Framework\Router\Attributes\Route;

class HomeController extends Controller
{
    #[Route("/")]
    public function home(): int
    {
        return $this->view('home', ["id" => 1, "username" => "Foo"]);
    }
}