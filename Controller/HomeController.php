<?php

namespace Framework\Controller;

use Framework\AbstractClass\Controller;
use Framework\ORM\Mapper;
use Framework\Request\Request;
use Framework\Response\JSONResponse;
use Framework\Response\Response;
use Framework\Response\View;
use Framework\Router\Attributes\Route;
use Framework\Security\Data;
use Framework\Security\Password;
use Framework\User\UserManager;
use ReflectionException;

class HomeController extends Controller
{
    public function __construct(private readonly Mapper $mapper = new Mapper()) {}

    #[Route("/", method: "POST|GET")]
    public function home(): View
    {
        $url = null;
        if (Request::METHOD() == "POST")
        {
            if (Request::post("url"))
            {
                $url = Data::cleanString(Request::post("url"));

                if (!isset($_SESSION["username"]))
                {
                    try
                    {
                        $token = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), -7);
                        $this->mapper->make("Url", user_id: 0, link: $url, token: $token);
                        $url = "http://localhost:8000/$token";
                    }catch (\Exception $e)
                    {
                    }
                }
            }
        }

        return $this->view("home", ["url" => $url]);
    }

    /**
     * @throws ReflectionException
     */
    #[Route("/login", method: "POST|GET")]
    public function login(): View
    {
        $errors = [];

        if (Request::METHOD() == "POST" && Request::post("submit"))
        {
            if (empty(Request::post("username")) || empty(Request::post("password")))
                $errors[] = "Missing credentials";
            else
                if (!($um = new UserManager())->login("User", ["username" => Request::post("username"), "password" => Request::post("password")]))
                    $errors = $um->getErrors();
                else
                    header("Location: /");
        }

        return $this->view("login", ["errors" => $errors]);
    }

    #[Route("/register", method: "POST|GET")]
    public function register(): View
    {
        return $this->view("register");
    }


    #[Route("/{s}:token")]
    public function goToUrl(string $token): JSONResponse|Response
    {
        $token = Data::cleanString($token);
        if (!$url = $this->mapper->getBy("Url", token: $token))
            return new JSONResponse(["success" => false, "error" => "Token $token not found!"]);

        header("location:". $url->link);
        return new Response(statusCode: Response::MOVED_PERMANENTLY);
    }
}