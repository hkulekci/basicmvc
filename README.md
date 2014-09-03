basicmvc
========

Basic MVC for Slim Framework Usage

An example directory structure is following. You can change your structure what you want.
You will specify your directories in your configurations. 

```
    app/
        public/
            controller/
                ...
            model/
                ...
            view/
                ...
    .htaccess
    index.php
```

`index.php` file content is following. Define your directory constants and firstly, initialize Slim after that initalize BasicMVC. Thats all. 

```php
<?php 
/************** Some Configurations ****************/
define("APP_DIR", __DIR__ . "/app/");
define("APP_THEME", "default");
define("APP_TEMPLATE", APP_DIR . "public/view/theme/".APP_THEME."/");
/********** End of Some Configurations *************/


require_once __DIR__ . '/../vendor/autoload.php';

use BasicMVC\BasicMVC;

$twigView = new \Slim\Views\Twig();

$app = new \Slim\Slim(
    array(
        'mode'              => 'development', // development, test, and production
        'debug'             => true,
        'view'              => $twigView,
        'templates.path'    => APP_TEMPLATE
        )
    );

$basicmvc = new BasicMVC($app, array(
    "controllers_path"   => APP_DIR . "public/controller/",
    "models_path"        => APP_DIR . "public/model/",
    "library_path"       => APP_DIR . "public/system/library/",
    "template_constants" => array(
        "APP_THEME"     => APP_THEME
    )
));

$app->map('/(:directory(/:controller(/:method(/:args+))))', 
            function (
                $directory = "home", 
                $controller = "home", 
                $method = "index", 
                $args = array()
            ) use($app, $basicmvc) {

    $route = array(
        "directory"       => $directory,
        "controller"      => $controller,
        "method"          => $method,
        );

    echo $basicmvc->run($route, $args);

})->via('GET', 'POST', 'PUT', 'DELETE');

$app->run();
```
