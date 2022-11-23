<?php
namespace Framework\Controller;

use Framework\AbstractClass\Controller;
use Framework\ORM\Mapper;
use Framework\Request\Request;
use Framework\Response\Response;
use Framework\Router\Attributes\Route;

class HomeController extends Controller
{
    public function __construct(private readonly Mapper $mapper = new Mapper()) {}

    #[Route("/", method: "GET")]
    public function home(): int
    {
        $users = $this->mapper->getAll("User");

        return $this->view('home', ["users" => $users]);
    }


    #[Route("/login", method: "GET|POST")]
    public function login()
    {
        echo "login";
    }

    #[Route("/register", method: "GET|POST")]
    public function register(): int
    {
        var_dump(Request::post());

        return $this->view('register');
    }
}