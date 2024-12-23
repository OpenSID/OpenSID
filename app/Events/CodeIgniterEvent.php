<?php

namespace App\Events;

class CodeIgniterEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(public \CI_Controller $ci)
    {
        //
    }
}
