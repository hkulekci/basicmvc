<?php
namespace BasicMVC;

/**
 * Controller Class of BasicMVC
 *
 * @package BasicMVC
 * @author Haydar KULEKCI <haydarkulekci@gmail.com>
 **/
abstract class Controller
{
    protected $registry;
    protected $data = array();

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->data = array_merge((array)$this->config->get("template_constants"), (array)$this->data);
        $hook_response = $this->hooks->execute_hooks("before_controller", "controller");
        $this->data = array_merge((array)$hook_response, (array)$this->data);
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }

    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }

    public function checkRequestMethod($required_method)
    {
        $result = false;
        if ($required_method == "GET")
            $result = $this->getRequestMethod() == \Slim\Http\Request::METHOD_GET;
        else if ($required_method == "POST")
            $result = $this->getRequestMethod() == \Slim\Http\Request::METHOD_POST;
        else if ($required_method == "PUT")
            $result = $this->getRequestMethod() == \Slim\Http\Request::METHOD_PUT;
        else if ($required_method == "DELETE")
            $result = $this->getRequestMethod() == \Slim\Http\Request::METHOD_DELETE;
        return $result;
    }

    public function getRequestMethod()
    {
        return $this->app->request()->getMethod();
    }

    public function getChild($directory, $controller, $method = "index", $args = array())
    {

        $path = $this->config->get("controllers_path");
        $file = $path . ( $directory ? $directory . "/" : "" ) . $controller . ".php" ;
        $class = $controller;
        $method = $method;

        if (is_file($file)) {
            $class = '\\Controller\\' . preg_replace('/[^a-zA-Z0-9]/', '', $class);
        }

        if (file_exists($file)) {
            include_once($file);

            $controller = new $class($this->registry);

            if (is_callable(array($controller, $method))) {
                return call_user_func_array(array($controller, $method), $args);
            } else {
                trigger_error('Error: Method does not exist for child [' . $controller . '][' . $method . ']!');
                return false;
            }
        } else {
            trigger_error('Error: File does not exist for child [' . $file . ']!');
            return false;
        }

    }

    public function redirect($path, $status = 302)
    {
        $this->app->redirect($path, $status);
    }

    public function render($template_file, $data = array(), $status = null)
    {
        if (!is_null($status)) {
            $this->app->response()->status($status);
        }

        $this->data = array_merge((array)$data, (array)$this->data);

        $this->app->view()->appendData($this->data);
        return $this->app->view()->fetch($template_file);
    }

} // END Controller class