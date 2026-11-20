<?php

namespace App\Events;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class CallingController
{
    public Route $route;
    public \CI_Controller $controller;
    public string $method;
    public array $parameters;
    public Request $request;

    public function __construct(
        Route $route,
        \CI_Controller $controller,
        string $method,
        array $parameters,
        Request $request
    ) {
        $this->route = $route;
        $this->controller = $controller;
        $this->method = $method;
        $this->parameters = $parameters;
        $this->request = $request;
    }

    public function getControllerClass(): string
    {
        return $this->controller::class;
    }
}
