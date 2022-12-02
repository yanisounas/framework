<?php

namespace Framework\Controller;

use Framework\AbstractClass\Controller;
use Framework\ORM\Mapper;
use Framework\Request\Request;
use Framework\Response\JSONResponse;
use Framework\Response\Response;
use Framework\Router\Attributes\Route;
use Framework\Security\Data;
use Framework\Security\Password;

class HomeController extends Controller
{
    public function __construct(private readonly Mapper $mapper = new Mapper()) {}

    /**
     * @throws \ReflectionException
     */
    #[Route("/", method: "POST|GET")]
    public function home(): void
    {
        $url = null;
        $errors = [];
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
                        $this->mapper->make("Url", user_id: 0, url: $url, token: $token);
                        $url = "http://localhost:8000/$token";
                    }catch (\Exception $e)
                    {
                    }
                }
            }

            if (Request::post("action") == "register")
            {
                $username = Data::cleanString(Request::post("username_register"));
                $password = Request::post("password_register");
                $confirm = Request::post("confirm_register");

                if (empty($username) || empty($password) || empty($confirm))
                    $errors[] = "Missing field";

                if ($password != $confirm)
                    $errors[] = "Passwords not match";

                if (!Password::isValid($password))
                    $errors = array_merge($errors, Password::getLastErrors());

                if ($this->mapper->getBy("User", ["username" => $username]))
                    $errors[] = "Username already found";


                if (empty($errors))
                {
                    $password = Password::hashPassword($password);
                    $this->mapper->make("User", ["username" => $username, "password" => $password]);
                }
            }
        }

        $this->view("home", ["url" => $url]);
    }

    #[Route("/{s}:token")]
    public function goToUrl(string $token): JSONResponse|Response
    {
        $token = Data::cleanString($token);
        if (!$url = $this->mapper->getBy("Url", token: $token))
            return new JSONResponse(["success" => false, "error" => "Token $token not found!"]);

        header("location:". $url->url);
        return new Response(statusCode: Response::MOVED_PERMANENTLY);
    }
}