<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Hooks extends CI_Hooks
{
    /**
     * Prepends hook callback to the named callback list.
     */
    function prepend($name, $callback) {
        $this->add_name($name);
        array_unshift($this->hooks[$name], $callback);
    }

    /**
     * Appends hook callback to the named callback list.
     */
    function append($name, $callback) {
        $this->add_name($name);
        $this->hooks[$name][] = $callback;
    }

    protected function add_name($name) {
        if (!isset($this->hooks[$name])) {
            $this->hooks[$name] = array();
        }
    }

}
