# Cybe-Router, PHP Standalone Router Library

## Requirement
1. PHP 7.4 or higher
2. composer

## Quick Start

**Installation**
`composer require cybe/cybe-router:1.1.0`

**Basic Usage**
Assuming ./public/ is public-accessible directory with index.php file:
```php
<?php

// public/index.php

require_once __DIR__ . "/../vendor/autoload.php";

use Cybe\Router;

$router = new Router;

// GET method
$router->get("/", function() {
    echo "Hello World!";
});

// POST method
$router->post("/", function() {
    echo "This is POST";
})

$router->serve();
```

Then run the following command in the terminal to start the localhost web server.
`php -S localhost:9000 -t public`

## Guides / Example

**Use an existing function as a callback**
```php
<?php

// public/index.php

require_once __DIR__ . "/../vendor/autoload.php";

use Cybe\Router;

// existing function
function hello_world()
{
    echo "Hello World!";
}

$router = new Router;

$router->get("/", "hello_world");

$router->serve();
```

**Using class**
```php
<?php

// public/index.php

require_once __DIR__ . "/../vendor/autoload.php";

use Cybe\Router;

// class
class Example
{
    public function hello_world()
    {
        echo "Hello World!";
    }
}

$router = new Router;

$router->get("/", [[new Example, "hello_world"]]);

$router->serve();
```

**Dynamic routing**
```php
<?php

// public/index.php

require_once __DIR__ . "/../vendor/autoload.php";

use Cybe\Router;

$router = new Router;

$router->get("/:name", function ($param) {
    echo "Hello " . $param['name'] . "!";
});

$router->serve();
```

**Middleware**
```php
<?php

// public/index.php

require_once __DIR__ . "/../vendor/autoload.php";

use Cybe\Router;

// middleware
function middleware($param, $position, $next)
{
    if ($param["name"] != "akbar") {
        echo "You're not Akbar";
    } else {
        //execute next function
        $next($param, $position);
    }
}

//controller
function controller($param)
{
    echo "Hello " . $param['name'] . "!";
}

$router = new Router;

$router->get("/:name", ["middleware", "controller"]);

$router->serve();
```

**Custom 404 page**
```php
<?php

// public/index.php

require_once __DIR__ . "/../vendor/autoload.php";

use Cybe\Router;

$router = new Router;

// setting custom 404 page
$router->set_404(function () {
    echo "Page not found";
});

$router->get("/", function () {
    echo "Hello World!";
});

$router->serve();
```