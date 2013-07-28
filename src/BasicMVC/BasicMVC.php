<?php
namespace BasicMVC;

/**
 * BasicMVC Main Class to initialize some settings and objects
 *
 * @package BasicMVC
 * @author Haydar KULEKCI <haydarkulekci@gmail.com>
 **/
class BasicMVC
{
    protected $registry;
    protected $route;
    protected $args;

    public function __construct($app, $app_config = array())
    {
        $this->registry = new Registry();
        $this->registry->set("app", $app);

        $configs = new Config();

        if (!isset($app_config['controllers_path']) ||!isset($app_config['models_path']))
        {
            trigger_error("Directory information is required for controllers and models");
            return;
        }

        if (!file_exists($app_config['controllers_path']) ||!file_exists($app_config['models_path']))
        {
            trigger_error("Directory information is required for controllers and models");
            return;
        }

        if (isset($app_config['template_constants']) && $app_config['template_constants'])
        {
            $configs->set("template_constants", $app_config['template_constants']);
        }
        else
        {
            $configs->set("template_constants", array());
        }

        $configs->set("controllers_path", $app_config['controllers_path']);
        $configs->set("models_path", $app_config['models_path']);
        $this->registry->set("config", $configs);

        $loader = new Loader($this->registry);
        $this->registry->set("load", $loader);

    }
    
    public function __get($key)
    {
        return $this->registry->get($key);
    }

    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }

    public function run($route, $args = array())
    {
        $this->route = $route;
        $this->args = $args;
        return $this->load->controller($this->route, $this->args);
    }

} // END BasicMVC Class