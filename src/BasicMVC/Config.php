<?php
namespace BasicMVC;

/**
 * Config Class of BasicMVC
 *
 * @package BasicMVC
 * @author Haydar KULEKCI <haydarkulekci@gmail.com>
 **/
final class Config
{
    protected $data = array();

    public function set($key, $value)
    {
        return $this->data[$key] = $value;
    }

    public function get($key)
    {
        return ( isset($this->data[$key]) ? $this->data[$key] : null );
    }

    public function has($key) {
        return isset($this->data[$key]);
    }

} // END Config class 