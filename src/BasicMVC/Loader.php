<?php
namespace BasicMVC;

/**
 * Loader Class of BasicMVC
 *
 * @package BasicMVC
 * @author Haydar KULEKCI <haydarkulekci@gmail.com>
 **/
final class Loader
{
    protected $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }

    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }

    /**
     * Load Library to registry
     * @param  string|object                $level
     * @throws \InvalidFileException        If invalid file
     */
    public function library($library, $name = "")
    {
        if (is_object($library)) {

            if (!$name)
                $name = strtolower(get_class($library));

        } else {

            $class = preg_replace('/[^a-zA-Z0-9]/', '', $library);
            $file = $this->config->get("library_path") . $library . ".php";
            if (file_exists($file) && realpath($file) == $file) {
                include_once($file);
                $library = new $class($this->registry);
                if (!$name)
                    $name = str_replace('/', '_', $library);
            } else {
                throw new \InvalidFileException('Error: Could not load library.' . $file . '!');
                exit();
            }

        }
        $this->registry->set($name, $library);

        return true;
    }

    public function model($model)
    {
        $model_paths = explode('/', $model);
        if (count($model_paths) < 2){

            trigger_error('Error: Model directory and file name required!');
            return false;
        }else{

            $class = '\\Model\\Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
            $file = $this->config->get("models_path") . $model . ".php";
            if (file_exists($file) && realpath($file) == $file) {
                include_once($file);
                $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
            } else {
                trigger_error('Error: Could not load model ' . $file . '!');
                exit();
            }
            return true;

        }

    }

    public function controller($route, $args){
        $path = $this->config->get("controllers_path");
        $file = $path . ( $route['directory'] ? $route['directory'] . "/" : "" ) . $route['controller'] . ".php" ;
        $class = $route['controller'];
        $method = $route['method'];

        if (is_file($file)) {
            $class = '\\Controller\\' . preg_replace('/[^a-zA-Z0-9]/', '', $class);
        }

        if (file_exists($file) && realpath($file) == $file) {
            include_once($file);

            $controller = new $class($this->registry);

            if (is_callable(array($controller, $method))) {
                return call_user_func_array(array($controller, $method), $args);
            } else {
                $this->app->response()->status(404);
                $this->app->response()->write('You made a bad request');
                return false;
            }
        } else {
            //trigger_error('Error: Could not load controller ' . $file . '!');
            $this->app->response()->status(404);
            $this->app->response()->write('You made a bad request');
            return false;
        }
    }

} // END Loader class