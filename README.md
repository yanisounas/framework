# FastFramework
A single line for 20 instructions


# Warning

I just started it, and I'm not an experienced developer, I'm doing this project to improve my skills. So this framework is not intended to be used other than locally.

## Summary

- [First Usage](#first-usage)
- [File Architecture](#file-architecture)
- [Make your own Controller](#controllers)
- [Create your first Web Application](#webapp)
- [Use your View](#views)
    - [Passing arguments to your View](#passing-arguments-to-your-view)
    - [Passing associative array to your view](#passing-associative-array-to-your-view)
    - [Customize your views](#customize-your-views)
    - [Upgrade your views with FastEngine]() - Coming soon
- [Make your API](#make-your-api)
- [Work with database]() - Coming soon
- [Create a user]() - Coming soon

First Usage
==================

File Architecture
-----------------

First things first you need to create your .env file:

    .
    ├── Controller
    ├── View
    ├── Entity
    ├── .env
    ├── ... (Composer, .env.exemple, .gitignore, ...)

See the .env.exemple to create your own.


Controllers
------------
Next you need to create a new controller in the Controller directory.

I'll call mine "HomeController":

    .
    ├── Controller    
        ├── HomeController.php

Put this following code:

```php
namespace Framework\Controller;

use Framework\Response\Response;
use Framework\Router\Attributes\Route;

class HomeController
{
    #[Route("/")]
    public function home(): Response
    {
        return new Response("Hello World");
    }
}

```
Launch the command `php startServer.php`.

Go to your favorite browser and try it.

You must restart the server if you add a controller.


Views
------
To create a view, make sure your .env file contains `VIEW_PATH="View"`.
\
Then create a view in View/:

    .                      
    ├── Controller    
        ├── HomeController.php
    ├── View
        ├── home.php

home.php:
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <h1>Hello world!</h1>
</body>
</html>
```

Then go to your HomeController and change some code:

```php
namespace Framework\Controller;

use Framework\AbstractClass\Controller;
use Framework\Router\Attributes\Route;

class HomeController extends Controller
{
    #[Route("/")]
    public function home(): int
    {
        return $this->view('home');
    }
}
```

You have to extend from Controller for the view method.

Passing arguments to your View
------------------------------
You can pass arguments to your view in your controller:

```php
namespace Framework\Controller;

use Framework\AbstractClass\Controller;
use Framework\Router\Attributes\Route;

class HomeController extends Controller
{
    #[Route("/")]
    public function home(): int
    {
        return $this->view('home', ["foo", "bar"]);
    }
}
```

Then go to your home.php:

```php
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Home</title>
    </head>
    <body>
        <h1>Hello World</h1>
        <ul>
            <?php foreach ($args as $arg): ?>
                <li><?= $arg ?></li>
            <?php endforeach; ?>
        </ul>
    </body>
</html>
```

Passing associative array to your view
-------------------------------------
You can also use an associative array:

```php
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
```

home.php:

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Home</title>
    </head>
    <body>
        <h1>Hello World</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
            </tr>
            <tr>
                <td><?= $args["id"] ?></td>
                <td><?= $args["username"] ?></td>
            </tr>
        </table>
    </body>
</html>
```

Use your keys as variable names:

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Home</title>
    </head>
    <body>
        <h1>Hello World</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
            </tr>
            <tr>
                <td><?= $id ?></td>
                <td><?= $username ?></td>
            </tr>
        </table>
    </body>
</html>
```

Customize your views
--------------------

You can use css to customize your views, first create the public/assets/css folder and your css file.

    .                      
    ├── public
        ├── assets
            ├── css
                ├── style.css


Then add the link tag to your head 

```html     
<link rel="stylesheet" href="./assets/style.css">
```


Make your API
=============
FastFramework provides a simple way to make an api. 

First, create a new controller

    .                      
    ├── Controller
        ├── ApiController.php


We will pretend that our API manages users.
```php
<?php

namespace Framework\Controller;

use Framework\AbstractClass\ApiBase;
use Framework\API\Attributes\API;

#[API(appName: "Users")]
class ApiController extends ApiBase
{

}
```
The #[API] attribute will tell the RouterInitializer that our Controller is an API so that the ApiManager can use them afterwards.

Let's add some data and two Endpoints.

```php
<?php

namespace Framework\Controller;

use Framework\AbstractClass\ApiBase;
use Framework\API\Attributes\API;

#[API(appName: "Users")]
class ApiController extends ApiBase
{
    private array $users;

    public function __construct()
    {
        for ($i = 1; $i <= 5; $i++)
            $this->users["user$i"] = ["username" => "user$i","email" => "user$i@example.com", "password" => "randompassword$i"];
    }

    #[Route("/api")]
    #[Endpoint("getAllUsers", description: "A list of all users")]
    public function showUsers(): JSONResponse
    {
        return $this->json($this->users);
    }

    #[Route("/api/{s}:username")]
    #[Endpoint("getUserByUsername", description: "Get a specific user by username", params: ["username" => ["type" => "string", "description" => "Username"]])]
    public function getUserByUsername(string $username): JSONResponse
    {
        return $this->json($this->users[$username]);
    }
}
```
You have to put the #[Route] attribute as well as the #[Endpoint] attribute (they will be joined soon).

The #[Endpoint] attribute will give the APIManager all the information that will allow you to create a documentation very easily or use the generated one at /api-documentation (Endpoint name, description, method (optional) and parameters (optional)).

Now, reboot the server and try

- [localhost:8000/api](http://localhost:8000/api) 
- [localhost:8000/api/user1](http://localhost:8000/api/user1)
- [localhost:8000/api-documentation](http://localhost:8000/api-documentation)