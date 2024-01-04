<?php

/*
 * OpenSID CI
 *
 * (c) 2018 Ingenia Software C.A
 *
 * This file is part of OpenSID CI, a plugin for CodeIgniter 3. See the LICENSE
 * file for copyright information and license details
 */

namespace OpenSID\Auth\Exception;

/**
 * Exception thrown by an User provider when an inactive user attempts to log in
 * 
 * @author Anderson Salas <anderson@ingenia.me>
 */
class InactiveUserException extends \Exception
{
};