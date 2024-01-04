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

/**
 * Describes the methods that must have a controller that authenticates users in 
 * CodeIgniter applications
 * 
 * @author Anderson Salas <anderson@ingenia.me>
 */
interface ControllerInterface
{
    /**
     * Gets the User provider used by the Controller-based authentication
     * 
     * @return UserProviderInterface|string
     */
    public function getUserProvider();

    /**
     * Gets the middleware used by the Controller-based authentication
     * 
     * @return \OpenSID\Auth\Middleware
     */
    public function getMiddleware();

    /**
     * User login screen
     * 
     * @return mixed
     */
    public function login();

    /**
     * User logout screen
     * 
     * @return mixed
     */
    public function logout();

    /**
     * User sign up screen
     * 
     * @return mixed
     */
    public function signup();

    /**
     * User email verification screen 
     * 
     * @param string $token Verification token
     * 
     * @return mixed
     */
    public function emailVerification($token);

    /**
     * User password reset screen
     * 
     * @return mixed
     */
    public function passwordReset();

    /**
     * User password reset form screem
     * 
     * @param string $token Password reset token
     * 
     * @return mixed
     */
    public function passwordResetForm($token);
}
