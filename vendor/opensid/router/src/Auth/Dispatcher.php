<?php

/*
 * OpenSID CI
 *
 * (c) 2018 Ingenia Software C.A
 *
 * This file is part of OpenSID CI, a plugin for CodeIgniter 3. See the LICENSE
 * file for copyright information and license details
 */

namespace OpenSID\Auth;

use OpenSID\Auth\Middleware as AuthMiddlewareInterface;
use OpenSID\Auth;
use OpenSID\Middleware;
use OpenSID\MiddlewareInterface;

/**
 * Internal middleware that dispatches the Controller-based authentication
 * when the ControllerInterface is detected in the framework singleton base object
 * 
 * @author Anderson Salas <anderson@ingenia.me>
 */
class Dispatcher implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     * 
     * @see \OpenSID\MiddlewareInterface::run()
     */
    public function run($args)
    {
        if(!ci() instanceof ControllerInterface)
        {
            return;
        }

        $authMiddleware = ci()->getMiddleware();

        if(is_string($authMiddleware))
        {
            $authMiddleware = Middleware::load($authMiddleware);
        }

        if(!$authMiddleware instanceof AuthMiddlewareInterface)
        {
            show_error('The auth middleware must inherit the OpenSID\Auth\Middleware class');
        }

        ci()->middleware->run($authMiddleware,  Auth::loadUserProvider(ci()->getUserProvider()));
    }
}