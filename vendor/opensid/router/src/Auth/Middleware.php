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

use OpenSID\Auth;
use OpenSID\Middleware as OpenSIDMiddleware;
use OpenSID\MiddlewareInterface;
use OpenSID\Auth\Exception\UserNotFoundException;
use OpenSID\Auth\Exception\InactiveUserException;
use OpenSID\Auth\Exception\UnverifiedUserException;
use OpenSID\Route;
use OpenSID\Debug;

/**
 * Controller-based authentication middleware
 * 
 * This is a special middleware used internally by OpenSID CI. Handles
 * the Controller-based authentication and dispatches special authentication
 * events during the process.
 * 
 * @author AndersonRafael
 */
abstract class Middleware implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     * 
     * @see \OpenSID\MiddlewareInterface::run()
     */
    final public function run($userProvider)
    {
        // Debug::log(
        //     '>>> USING CONTROLLER-BASED AUTH ['
        //     . get_class(ci()) . ', '
        //     . get_class($userProvider) . ', '
        //     . get_class( is_object(ci()->getMiddleware()) ? ci()->getMiddleware() : OpenSIDMiddleware::load(ci()->getMiddleware()) ) . ' ]',
        //     'info', 'auth');

        $authLoginRoute = config_item('auth_login_route') !== null
            ? config_item('auth_login_route')
            : 'login';

        $authLoginRouteRedirect = config_item('auth_login_route_redirect') !== null
            ? config_item('auth_login_route_redirect')
            : null;

        $authLogoutRoute = config_item('auth_logout_route') !== null
            ? config_item('auth_logout_route')
            : 'logout';

        $authLogoutRouteRedirect = config_item('auth_logout_route_redirect') !== null
            ? config_item('auth_logout_route_redirect')
            : null;

        $authRouteAutoRedirect = is_array( config_item('auth_route_auto_redirect')  )
            ? config_item('auth_route_auto_redirect')
            : [];

        if( !Auth::isGuest() && ( ci()->route->getName() == $authLoginRoute || in_array(ci()->route->getName(), $authRouteAutoRedirect)) )
        {
            return redirect(
                $authLoginRouteRedirect !== null && route_exists($authLoginRouteRedirect)
                    ? route($authLoginRouteRedirect)
                    : base_url()
            );
        }

        $this->preLogin(ci()->route);

        if(ci()->route->getName() == $authLoginRoute && ci()->route->requestMethod == 'POST')
        {
            $usernameField = config_item('auth_form_username_field') !== null
                ? config_item('auth_form_username_field')
                : 'username';

            $passwordField = config_item('auth_form_password_field') !== null
                ? config_item('auth_form_password_field')
                : 'password';

            $username = ci()->input->post($usernameField);
            $password = ci()->input->post($passwordField);

            // Debug::logFlash('>>> LOGIN ATTEMPT INTERCEPTED', 'info', 'auth');
            // Debug::logFlash('Username: ' . $username, 'info', 'auth');
            // Debug::logFlash('Password: ' . $password . ' [hash: ' . $userProvider->hashPassword($password) . ']', 'info', 'auth');

            try
            {
                $user = $userProvider->loadUserByUsername($username, $password);
                        $userProvider->checkUserIsActive($user);
                        $userProvider->checkUserIsVerified($user);
            }
            catch(UserNotFoundException $e)
            {
                // Debug::logFlash('FAILED: ' . UserNotFoundException::class, 'error', 'auth');
                ci()->session->set_flashdata('_auth_messages', [ 'danger' => 'ERR_LOGIN_INVALID_CREDENTIALS' ]);
                $this->onLoginFailed($username);

                return redirect(route($authLoginRoute));
            }
            catch(InactiveUserException $e)
            {
                // Debug::logFlash('FAILED: ' . InactiveUserException::class, 'error', 'auth');
                ci()->session->set_flashdata('_auth_messages', [ 'danger' => 'ERR_LOGIN_INACTIVE_USER' ]);
                $this->onLoginInactiveUser($user);

                return redirect(route($authLoginRoute));
            }
            catch(UnverifiedUserException $e)
            {
                // Debug::logFlash('FAILED: ' . UnverifiedUserException::class, 'error', 'auth');
                ci()->session->set_flashdata('_auth_messages', [ 'danger' => 'ERR_LOGIN_UNVERIFIED_USER' ]);
                $this->onLoginUnverifiedUser($user);

                return redirect(route($authLoginRoute));
            }

            Auth::store($user);
            $this->onLoginSuccess($user);

            return redirect( $authLoginRouteRedirect !== null ? route($authLoginRouteRedirect) : base_url() );
        }

        if(ci()->route->getName() == $authLogoutRoute)
        {
            Auth::destroy();
            $this->onLogout();

            return redirect(
                $authLogoutRouteRedirect !== null && route_exists($authLogoutRouteRedirect)
                    ? route($authLogoutRouteRedirect)
                    : base_url()
            );
        }
    }

    /**
     * Event triggered when the user visits the login path, regardless of whether 
     * logs in or not
     * 
     * @param Route $route Current route
     * 
     * @return void
     */
    abstract public function preLogin(Route $route);

    /**
     * Event triggered immediately after a successful login session, and before the 
     * redirect that follows
     * 
     * @param UserInterface $user Current user
     * 
     * @return void
     */
    abstract public function onLoginSuccess(UserInterface $user);

    /**
     * Event triggered after a failed session attempt, and before the redirect that
     *  follows
     *  
     * @param string $username Attempted username
     * 
     * @return void
     */
    abstract public function onLoginFailed($username);

    /**
     * Event triggered if an InactiveUserException exception is thrown within the 
     * User Provider, corresponding to an inactive user login error
     * 
     * @param UserInterface $user
     * 
     * @return void
     */
    abstract public function onLoginInactiveUser(UserInterface $user);

    /**
     * Event triggered if an `UnverifiedUserException` exception is thrown inside the 
     * User Provider, corresponding to an error by login of an unverified user.
     * 
     * @param UserInterface $user
     * 
     * @return void
     */
    abstract public function onLoginUnverifiedUser(UserInterface $user);

    /**
     * Event triggered immediately after the user log out.
     * 
     * @return void
     */
    abstract public function onLogout();
}