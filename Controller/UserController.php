<?php

namespace Framework\Controller;

use Framework\AbstractClass\Controller;
use Framework\Request\Request;
use Framework\Response\RedirectResponse;
use Framework\Response\View;
use Framework\Router\Attributes\Route;
use Framework\Router\Router;
use Framework\User\UserManager;
use ReflectionException;

class UserController extends Controller
{

    public function __construct() {}

    /**
     * @throws ReflectionException
     */
    #[Route("/login", method: "POST|GET", routeName: "account.login")]
    public function login(): View|RedirectResponse
    {
        if (Request::session("username"))
            return new RedirectResponse(Router::getPathFrom("home.index"));

        $errors = [];

        if (Request::METHOD() == "POST" && Request::post("submit"))
        {
            if (empty(Request::post("username")) || empty(Request::post("password")))
                $errors[] = "Missing credentials";
            else
                if (!($um = new UserManager())->login("User", ["username" => Request::post("username"), "password" => Request::post("password")]))
                    $errors = $um->getErrors();
                else
                    return new RedirectResponse(Router::getPathFrom("home.index"));
        }

        return $this->view("login", ["errors" => $errors]);
    }

    /**
     * @throws ReflectionException
     */
    #[Route("/register", method: "POST|GET", routeName: "account.register")]
    public function register(): View|RedirectResponse
    {
        if (Request::session("username"))
            return new RedirectResponse(Router::getPathFrom("home.index"));
        return $this->view("register");
    }

    /**
     * @throws ReflectionException
     */
    #[Route("/disconnect", routeName: "account.disconnect")]
    public function disconnect(): RedirectResponse
    {
        Request::removeSession();
        return new RedirectResponse(Router::getPathFrom("home.index"));
    }
}