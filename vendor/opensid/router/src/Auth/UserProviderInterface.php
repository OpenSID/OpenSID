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
 * Describes which methods must implement the classes that retrieves
 * authenticated users for the application
 * 
 * @author Anderson Salas <anderson@ingenia.me>
 */
interface UserProviderInterface
{
    /**
     * Gets the related User class name used by this User Provider
     */
    public function getUserClass();

    /**
     * Attemps to load an user from somewhere with the given username and
     * password
     * 
     * @param string       $username
     * @param string|null  $password
     * 
     * @return mixed
     */
    public function loadUserByUsername($username, $password = null);

    /**
     * Hashes the given plain text password with an encryption algorithm
     * 
     * @param string $password
     * 
     * @throws \OpenSID\Auth\Exception\UnverifiedUserException
     */
    public function hashPassword($password);

    /**
     * Verifies that the given (plain text) password matches with
     * the given password hash
     * 
     * @param string $password
     * @param string $hash
     * 
     * @return bool
     */
    public function verifyPassword($password, $hash);

    /**
     * Checks if the current user is active
     * 
     * @param UserInterface $user
     * 
     * @throws \OpenSID\Auth\Exception\InactiveUserException
     * 
     * @return void
     */
    public function checkUserIsActive(UserInterface $user);

    /**
     * Checks if the current user is verified
     *
     * @param UserInterface $user
     * 
     * @throws \OpenSID\Auth\Exception\UnverifiedUserException
     *
     * @return void
     */
    public function checkUserIsVerified(UserInterface $user);
}