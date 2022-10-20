Warning: this is the old README, do not use this framework like this.
====================================================================

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

First things first you need to create your app folder:

    .
    ├── app (Your app)
    ├── microframework (Framework core folder)
    ├── index.php (Main file)
    ├── .htaccess (Rewrite rules)
    ├── .env (Configuration file)
    ├── ... (Composer, .env.exemple, .gitignore, ...)

See the .env.exemple to create your own .env.

Controllers
------------
Next you need to create a new controller in app/Controller.

I'll call mine "HomeController":

    .
    ├── app                         
        ├── Controller    
            ├── HomeController.php
    ├── microframework
    ├── index.php
    ├── .htaccess
    ├── .env
    ├── ...

Put this following code:

```php
namespace App\Controller;

use MicroFramework\Core\Response\Response;
use MicroFramework\Core\WebApp\Router\Attributes\Route;

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

$web = $appManager->newWebApp(\App\Controller\HomeController::class);
$web->start();
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
    ├── microframework
    ├── index.php
    ├── .htaccess
    ├── .env
    ├── .env.exemple
    ├── ...

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

use MicroFramework\Core\AbstractClass\Controller;use MicroFramework\Core\WebApp\Router\Attributes\Route;

class HomeController extends Controller
{
    #[Route("/")]
    public function home()
    {
        return $this->view("home.php");
    }
}
```

Passing arguments to your View
------------------------------
You can pass arguments to your view in your controller:

```php
namespace App\Controller;

use MicroFramework\Core\AbstractClass\Controller;use MicroFramework\Core\WebApp\Router\Attributes\Route;

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

use MicroFramework\Core\AbstractClass\Controller;use MicroFramework\Core\WebApp\Router\Attributes\Route;

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

404 Error and more response features
====================================
Intro to 404
-------------
I'll show you how to make your first 404 in JSON 


The easiest way to do this is the RouteNotFound exception.

Go back to your index.php:

```php
require "vendor/autoload.php";


$app = new \MicroFramework\WebApp(__DIR__, \App\Controller\HomeController::class);

try 
{
    $app->start();
}
catch (\MicroFramework\Core\Router\Exceptions\RouteNotFound $e)
{
    echo "404";
}
```

Now use JsonReponse to return our 404 error:

```php
require "vendor/autoload.php";


$app = new \MicroFramework\WebApp(__DIR__, \App\Controller\HomeController::class);

try 
{
    $app->start();
}
catch (\MicroFramework\Core\Router\Exceptions\RouteNotFound $e)
{
    return new \MicroFramework\Core\Response\JsonResponse(["error" => "404 Not Found"]);
}
```

Finally, change the status code:

```php
require "vendor/autoload.php";


$app = new \MicroFramework\WebApp(__DIR__, \App\Controller\HomeController::class);

try 
{
    $app->start();
}
catch (\MicroFramework\Core\Router\Exceptions\RouteNotFound $e)
{
    return new \MicroFramework\Core\Response\JsonResponse(["error" => "404 Not Found"], 404);
}
```

404 Page with controller
------------------------
A better way would be to use controller to manage your 404

For now, I'll use the HomeController:

```php
namespace App\Controller;

use MicroFramework\Core\AbstractClass\Controller;use MicroFramework\Core\WebApp\Router\Attributes\Route;

class HomeController extends Controller
{
    #[Route("/")]
    public function home()
    {
        return $this->view("home.php");
    }

    #[Route("/404")]
    public function notFound()
    {
        return $this->view("404.php", 404);
    }
}
```

Create the 404.php in your view Directory:
```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>404 not found</title>
    </head>
    <body>
        <h1>404 Not Found</h1>
    </body>
</html>
```

Finally, make sure your .env file contains `ERROR_404="./404"`


To be continued...
==================
