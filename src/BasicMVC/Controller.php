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
        return $this->load->controller(
            array(
                "directory" => $directory,
                "controller" => $controller,
                "method" => $method
                ), 
            $args
            );
    }

    public function render($template_file, $data = array(), $status = null)
    {
        if (!is_null($status)) {
            $this->app->response()->status($status);
        }
        $this->app->view()->appendData($data);
        return $this->app->view()->fetch($template_file);
    }

} // END Controller class 