basicmvc
========

Basic MVC for Slim Framework

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

`index.php` file content


    <?php 
    /************** Some Configurations ****************/
    define("APP_DIR", __DIR__ . "/app/");
    define("APP_THEME", "default");
    define("APP_TEMPLATE", APP_DIR . "public/view/theme/".APP_THEME."/");
    /********** End of Some Configurations *************/


    require_once __DIR__ . '/../vendor/autoload.php';

    use BasicMVC\BasicMVC;

    $twigView = new \Slim\Extras\Views\Twig();

    $app = new \Slim\Slim(
        array(
            'mode'              => 'development', // development, test, and production
            'debug'             => true,
            'view'              => $twigView,
            'templates.path'    => APP_TEMPLATE
            )
        );

    $request = new \Slim\Http\Request($app);

    $basicmvc = new BasicMVC($app, array(
        "controllers_path"   => APP_DIR . "public/controller/",
        "models_path"        => APP_DIR . "public/model/",
    ));

    $app->map('/(:directory(/:controller(/:method(/:args+))))', function ($directory = "home", $controller = "home", $method = "index", $args = array()) use($app, $basicmvc) {

        $route = array(
            "directory"       => $directory,
            "controller"      => $controller,
            "method"          => $method,
            );

        echo $basicmvc->run($route, $args);

        print_r($basicmvc->app_registry());

    })->via('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD');

    $app->run();
