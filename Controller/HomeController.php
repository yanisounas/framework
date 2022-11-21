<?php
namespace Framework\Controller;

use Framework\AbstractClass\Controller;
use Framework\ORM\Mapper;
use Framework\Request\Request;
use Framework\Router\Attributes\Route;

class HomeController extends Controller
{
    public function __construct(private readonly Mapper $mapper = new Mapper()) {}

    #[Route("/", method: "GET|POST")]
    public function home(): int
    {
        if (Request::METHOD() == "POST" && !empty(Request::post()))
            $this->mapper->make("User", Request::post());

        $users = $this->mapper->getAll("User");
        var_dump($users[0]->toAssocArray());

        return $this->view('home', ["users" => $users]);
    }
}