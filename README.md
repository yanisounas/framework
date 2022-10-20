# MicroFramework
A Micro Framework in PHP 8.

I just started it, and I'm not an experienced developer,  I am doing this project to improve my skills. So this framework is not intended to be used other than locally.

## Summary

- [First Usage](#first-usage)
    - [File Architecture](#file-architecture)
    - [Make your own Controller](#controllers)
    - [Create your first Web Application](#webapp)
    - [Use your View](#views)
        - [Passing arguments to your View](#passing-arguments-to-your-view)
        - [Passing associative array to your view](#passing-associative-array-to-your-view)
- [404 Error and more response features](#404-error-and-more-response-features)
    - [Intro to 404](#intro-to-404)
    - [Use Controller](#404-page-with-controller)


First Usage
==================

File Architecture
-----------------

First things first you need to create your app folder and your .env file:

    .
    ├── app (Your app)
    ├── microframework (Framework core folder)
    ├── index.php (Main file)
    ├── .htaccess (Rewrite rules)
    ├── .env (Configuration file)
    ├── ... (Composer, .env.exemple, .gitignore, ...)

See the .env.exemple to create your own .env.

(Don't forget to add your application folder in the composer.json file in the "autoloader" section if it's not already the case)


Controllers
------------
Next you need to create a new controller in app/Controller.

I'll call mine "HomeController":

    .
    ├── app                         
        ├── Controller    
            ├── HomeController.php

Put this following code:

```php
namespace App\Controller;

use MicroFramework\Core\Response\Response;
use MicroFramework\Core\Router\Attributes\Route;

class HomeController
{
    #[Route("/")]
    public function home()
    {
        return new Response("Hello World");
    }
}

```

WebApp
------
Finally, you can create a new WebApp in index.php:

```php
require "vendor/autoload.php";


$appManager = new \MicroFramework\Core\ApplicationManager(__DIR__);
$appManager->newApp("ExempleApplication", \App\Controller\HomeController::class);
$appManager->start("ExempleApplication");
```

Go to your favorite browser and try it.


Views
------
To create a view, make sure your .env file contains `VIEW_PATH="app/View"`.
\
Then create a view in app/View:

    .
    ├── app                         
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
namespace App\Controller;

use MicroFramework\Core\AbstractClass\Controller;
use MicroFramework\Core\Router\Attributes\Route;

class HomeController extends Controller
{
    #[Route("/")]
    public function home()
    {
        return $this->view("home.php");
    }
}
```

You have to extend from Controller to access to the view method.

Passing arguments to your View
------------------------------
You can pass arguments to your view in your controller:

```php
namespace App\Controller;

use MicroFramework\Core\AbstractClass\Controller;
use MicroFramework\Core\Router\Attributes\Route;

class HomeController extends Controller
{
    #[Route("/")]
    public function home()
    {
        return $this->view("home.php", ["foo", "bar"]);
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
namespace App\Controller;

use MicroFramework\Core\AbstractClass\Controller;
use MicroFramework\Core\Router\Attributes\Route;

class HomeController extends Controller
{
    #[Route("/")]
    public function home()
    {
        return $this->view("home.php", ["id" => 1, "username" => "Foo"]);
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