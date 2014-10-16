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

        if (!isset($app_config['controllers_path']) ||!isset($app_config['models_path']) ||!isset($app_config['library_path']))
        {
            trigger_error("Directory information is required for controllers, models and library");
            return;
        }

        if (!file_exists($app_config['controllers_path']) ||!file_exists($app_config['models_path']) ||!file_exists($app_config['library_path']))
        {
            trigger_error("Directory information is required for controllers, models and library");
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
        $configs->set("library_path", $app_config['library_path']);
        $configs->set("models_path", $app_config['models_path']);
        $this->registry->set("config", $configs);

        $this->registry->set("hooks", new Hooks((array)$this->config->get("hooks")));

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

    public function setTemplateConstant($key, $value)
    {
        $template_constants = $this->config->get('template_constants');
        $template_constants[$key] = $value;
        $configs->set("template_constants", $template_constants);
        unset($template_constants);
    }

    public function run($route, $args = array())
    {
        $this->route = $route;
        $this->args = $args;
        return $this->load->controller($this->route, $this->args);
    }

} // END BasicMVC Class