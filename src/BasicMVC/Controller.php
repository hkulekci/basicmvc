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
    protected $template;
    protected $data = array();
    protected $status = 200;

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