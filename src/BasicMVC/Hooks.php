<?php
namespace BasicMVC;

/**
 * Controller Class of BasicMVC
 *
 * @package BasicMVC
 * @author Haydar KULEKCI <haydarkulekci@gmail.com>
 **/
class Hooks {
    protected $hooks = array();

    public function __construct($config = array())
    {
        $this->hooks = array_merge($config, (array)$this->hooks);
    }

    public function add_hook($tag, $function)
    {
        $tag = trim($tag);
        $this->hooks[$tag][] = $function;
    }

    public function hook_exist($tag)
    {
        $tag = trim($tag);
        return (isset($this->hooks[$tag]) && $this->hooks[$tag]) ? true : false;
    }

    public function delete_hook($tag)
    {
        $tag = trim($tag);
        unset($this->hooks[$tag]);
    }

    public function execute_hooks($tag, $args = "")
    {
        $response = array();
        $tag = trim($tag);
        if ($this->hook_exist($tag)) {
            foreach ($this->hooks as $hooks) {
                foreach ($hooks as $hook) {
                    $response = array_merge((array)call_user_func($hook, $args), $response);
                }
            }
        }
        return $response;
    }
}
