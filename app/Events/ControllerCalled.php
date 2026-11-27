<?php

namespace App\Events;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class ControllerCalled
{
    public Route $route;
    public \CI_Controller $controller;
    public string $method;
    public array $parameters;
    public Request $request;
    public mixed $response;

    public function __construct(
        Route $route,
        \CI_Controller $controller,
        string $method,
        array $parameters,
        Request $request,
        mixed $response
    ) {
        $this->route = $route;
        $this->controller = $controller;
        $this->method = $method;
        $this->parameters = $parameters;
        $this->request = $request;
        $this->response = $response;
    }

    public function getControllerClass(): string
    {
        return $this->controller::class;
    }
}
