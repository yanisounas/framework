<?php

namespace App\Controller;

use MicroFramework\Core\AbstractClass\Controller;
use MicroFramework\Core\Request\Request;
use MicroFramework\Core\Router\Attributes\Route;

class HomeController extends Controller
{
    #[Route("/")]
    public function home()
    {
        return $this->view("home.php");
    }

    #[Route("/", method: "POST")]
    public function shorten()
    {
        $result = null;
        $req = (new Request)->post();
        if (isset($req["submit_url"]))
        {
            if (!filter_var($req["url"], FILTER_VALIDATE_URL))
                $result = ["shorten" => "Error: Enter a valid url"];

        }
        return $this->view("home.php", $result);
    }
}