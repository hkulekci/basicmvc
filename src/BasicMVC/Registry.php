<?php
namespace BasicMVC;

/**
 * Registry Class of BasicMVC
 *
 * @package BasicMVC
 * @author Haydar KULEKCI <haydarkulekci@gmail.com>
 **/
final class Registry
{
    private $data = array();

    public function get($key) {
        return (isset($this->data[$key]) ? $this->data[$key] : NULL);
    }

    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    public function has($key) {
        return isset($this->data[$key]);
    }

} // END Registry class 