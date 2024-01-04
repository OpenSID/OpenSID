<?php

/*
 * OpenSID CI
 *
 * (c) 2018 Ingenia Software C.A
 *
 * This file is part of OpenSID CI, a plugin for CodeIgniter 3. See the LICENSE
 * file for copyright information and license details
 */

namespace OpenSID;

use OpenSID\Auth\UserInterface;
use OpenSID\Auth\UserProviderInterface;

/**
 * OpenSID CI authentication class
 * 
 * @author Anderson Salas <anderson@ingenia.me>
 */
class Auth
{
    private static $providers = [];

    /**
     * Returns the current authentication session name
     *
     * @return string
     */
    private static function getSessionName()
    {
        return config_item('auth_session_var') !== null
            ? config_item('auth_session_var')
            : 'auth';
    }

    /**
     * Loads an User class and his related User Provider class
     *
     * @param  string $userClass User class name
     *
     * @return \OpenSID\Auth\UserProviderInterface
     */
    public static function loadUserProvider($userProviderClass)
    {
        if(isset(self::$providers[$userProviderClass]))
        {
            return self::$providers[$userProviderClass];
        }

        if( substr($userProviderClass,-8) != 'Provider')
        {
            $userProviderClass .= 'Provider';
        }

        if(file_exists( APPPATH . '/security/providers/' . $userProviderClass . '.php') )
        {
            require_once APPPATH . '/security/providers/' . $userProviderClass . '.php';

            if(!class_exists($userProviderClass))
            {
                show_error('User provider class "' .$userProviderClass . '" not found');
            }
        }
        else
        {
            show_error('Unable to find "' . $userProviderClass . '" User Provider class file');
        }

        $userProviderInstance = new $userProviderClass();
        $userClass = $userProviderInstance->getUserClass();

        if(!file_exists( APPPATH . '/security/providers/' . $userClass . '.php') )
        {
            show_error('Unable to find "' . $userClass . '" attached User class file');
        }

        require_once APPPATH . '/security/providers/' . $userClass . '.php';

        if(!class_exists($userClass))
        {
            show_error('User attached class "' . $userClass . '" not found');
        }

        self::$providers[$userClass] = $userProviderInstance;
        return $userProviderInstance;
    }

    /**
     * Checks if the current user is guest (not authenticated)
     *
     * @return bool
     */
    public static function isGuest()
    {
        return self::user() === null;
    }

    /**
     * Checks if current user has the provided role name
     *
     * @param  string $role Role name
     *
     * @return bool
     */
    public static function isRole($user, $role = null)
    {
        if(self::isGuest())
        {
            return false;
        }

        if($role === null)
        {
            $role = $user;
            $user = self::user();
        }

        $roles = $user->getRoles();

        if(!is_array($roles))
        {
            show_error('The getRoles()  method  of ' . get_class($user) . ' class method must return an array', 500, 'Auth error');
        }

        return in_array($role, $user->getRoles());
    }

    /**
     * Checks if current user has a the provided permission name
     *
     * @param  string  $permission
     *
     * @return bool
     */
    public static function isGranted($user, $permission = null)
    {
        if(self::isGuest())
        {
            return false;
        }

        if($permission === null)
        {
            $permission = $user;
            $user = self::user();
        }

        $permissions = $user->getPermissions();

        if(!is_array($permissions))
        {
            show_error('The getPermissions()  method  of ' . get_class($user) . ' class must return an array', 500, 'Auth error');
        }

        if(substr($permission,-2) != '.*')
        {
            return in_array($permission, $permissions);
        }
        else
        {
            foreach($permissions as $_permission)
            {
                if(preg_match('/^' . substr($permission, 0, -2) . '/', $_permission))
                {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Stores an user in the authentication session
     *
     * @param  UserInterface $user Authenticated user
     * @param  array         $data User data
     *
     * @return void
     */
    final public static function store(UserInterface $user, $data = [])
    {
        $storedSessionUser = [
            'user' =>
                [
                    'class'       => get_class($user),
                    'entity'      => $user->getEntity(),
                    'username'    => $user->getUsername(),
                    'roles'       => $user->getRoles(),
                    'permissions' => $user->getPermissions(),
                ],
            'validated' => false,
            'fully_authenticated' => true,
        ];


        foreach($data as $name => $value)
        {
            if($name == 'user')
            {
                continue;
            }

            $storedSessionUser[$name] = $value;
        }

        ci()->session->set_userdata( self::getSessionName() ,$storedSessionUser);
    }

    /**
     * Initializes the authentication session
     *
     * @return void
     */
    public static function init()
    {
        if(ci()->session->userdata( self::getSessionName() ) === null)
        {
            ci()->session->set_userdata( self::getSessionName() , [
                'user'        => null,
                'validated'   => false,
                'fully_authenticated' => false
            ]);
        }
    }

    /**
     * Gets (or sets) an authentication session variable
     *  
     * @param  string  $name 
     * @param  mixed   $value
     *
     * @return mixed
     */
    public static function session($name = null, $value = null)
    {
        $authSession  = ci()->session->userdata( self::getSessionName() );

        if($name === null)
        {
            return $authSession;
        }
        else
        {
            if($value === null)
            {
                return isset($authSession[$name]) ? $authSession[$name] : null;
            }
            else
            {
                $authSession[$name] = $value;
                ci()->session->set_userdata( self::getSessionName() , $authSession);
            }
        }
    }

    /**
     * Gets the current authenticated user
     *
     * @param  bool $refresh Force user refresh
     *
     * @return mixed
     */
    public static function user($refresh = false)
    {
        $sessionUser = self::session('user');

        if($sessionUser === NULL)
        {
            return null;
        }

        $userInstance = null;
        $userProvider = self::loadUserProvider($sessionUser['class']);
        $userClass    = $sessionUser['class'];

        if(self::session('validated') === false || $refresh === true)
        {
            log_message('notice', 'There is a stored user in session. Attempting to validate...');
            // Debug::log('There is a stored user in session. Attempting to validate...', 'info','auth');

            try
            {
                $userInstance = self::bypass($sessionUser['username'], $userProvider);
            }
            catch(\Exception $e)
            {
                $userInstance = null;
                log_message('notice', 'ERROR! User auth validation failed. Role set to "guest"');
                // Debug::log('ERROR! User auth validation failed. Role set to "guest"', 'error','auth');
            }

            log_message('notice', 'SUCCESS! User validated.');
            // Debug::log('SUCCESS! User validated.', 'info','auth');
            self::session('validated', true);
        }
        else
        {
            $userInstance = new $userClass((object) $sessionUser['entity'], $sessionUser['roles'], $sessionUser['permissions']);
        }

        return $userInstance;
    }

    /**
     * Deletes the current authenticated user
     *
     * @return void
     */
    public static function destroy()
    {
        ci()->session->unset_userdata( self::getSessionName() );
        self::init();
    }

    /**
     * Attempts login using an username, password and a User Provider class
     * 
     * (You must catch any exception produced here manually)
     * 
     * @param string                 $username      Username
     * @param string                 $password     
     * @param UserProviderInterface  $userProvider
     * 
     * @throws \Exception
     * 
     * @return UserInterface
     */
    final public static function attempt($username, $password, $userProvider)
    {
        if(!is_string($userProvider) && !$userProvider instanceof UserProviderInterface)
        {
            throw new \Exception("Invalid user provider. Must be a string or an instance of UserProviderInterface");
        }

        if(is_string($userProvider))
        {
            $userProvider = self::loadUserProvider($userProvider);
        }

        $user = $userProvider->loadUserByUsername($username, $password);
                $userProvider->checkUserIsActive($user);
                $userProvider->checkUserIsVerified($user);

        return $user;
    }
    
    /**
     * Forces a user login 
     *
     * (Even if you can bypass the authentication process with this method, is still 
     * required that the target user exists)
     * 
     * @param string                       $username
     * @param string|UserProviderInterface $userProvider
     * 
     * @throws \Exception
     * 
     * @return UserInterface
     */
    final public static function bypass($username, $userProvider)
    {
        if(!is_string($userProvider) && !$userProvider instanceof UserProviderInterface)
        {
            throw new \Exception("Invalid user provider. Must be a string or an instance of UserProviderInterface");
        }

        if(is_string($userProvider))
        {
            $userProvider = self::loadUserProvider($userProvider);
        }

        $user = $userProvider->loadUserByUsername($username, null);

        if(!$user instanceof UserInterface)
        {
            show_error('Returned user MUST be an instance of UserInterface');
        }

        $userProvider->checkUserIsActive($user);
        $userProvider->checkUserIsVerified($user);

        return $user;
    }

    /**
     * Gets the current authentication messages (useful for validations, etc)
     *
     * @return array
     */
    public static function messages()
    {
        $messages = ci()->session->flashdata('_auth_messages');

        return !empty($messages)
            ? $messages
            : [];
    }
}