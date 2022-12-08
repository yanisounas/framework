<?php

namespace Framework\Controller;

use Framework\AbstractClass\Controller;
use Framework\ORM\Mapper;
use Framework\Request\Request;
use Framework\Response\JSONResponse;
use Framework\Response\RedirectResponse;
use Framework\Response\Response;
use Framework\Response\View;
use Framework\Router\Attributes\Route;
use Framework\Router\Exceptions\RouteNotFound;
use Framework\Router\Router;
use Framework\Security\Data;
use Framework\User\UserManager;
use ReflectionException;

class HomeController extends Controller
{
    public function __construct(private readonly Mapper $mapper = new Mapper()) {}

    /**
     * @throws ReflectionException
     */
    #[Route("/", method: "POST|GET", routeName: "home.index")]
    public function home(): View
    {
        Request::useSession();
        $url = null;

        if (Request::METHOD() == "POST")
        {
            if (Request::post("url"))
            {
                $url = Data::cleanString(Request::post("url"));

                $token = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), -7);
                $this->mapper->make("Url", user_id: (Request::session("id") ?  Request::session("id") : 0), link: $url, token: $token);
                $url = "http://localhost:8000/$token";
            }
        }

        return $this->view("home", ["url" => $url]);
    }

    /**
     * @throws ReflectionException
     * @throws RouteNotFound
     */
    #[Route("/login", method: "POST|GET", routeName: "home.login")]
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
     * @throws RouteNotFound
     */
    #[Route("/register", method: "POST|GET", routeName: "home.register")]
    public function register(): View|RedirectResponse
    {
        if (Request::session("username"))
            return new RedirectResponse(Router::getPathFrom("home.index"));
        return $this->view("register");
    }

    /**
     * @throws ReflectionException
     * @throws RouteNotFound
     */
    #[Route("/disconnect", routeName: "home.disconnect")]
    public function disconnect(): RedirectResponse
    {
        Request::removeSession();
        return new RedirectResponse(Router::getPathFrom("home.index"));
    }

    /**
     * @throws ReflectionException
     */
    #[Route("/{s}:token")]
    public function goToUrl(string $token): JSONResponse|RedirectResponse
    {
        $token = Data::cleanString($token);
        if (!$url = $this->mapper->getBy("Url", token: $token))
            return new JSONResponse(["success" => false, "error" => "Token $token not found!"]);

        return new RedirectResponse($url->link);
    }

}