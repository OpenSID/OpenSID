<?php

/*
 * OpenSID CI
 *
 * (c) 2018 Ingenia Software C.A
 *
 * This file is part of OpenSID CI, a plugin for CodeIgniter 3. See the LICENSE
 * file for copyright information and license details
 */

namespace OpenSID\Auth\SimpleAuth;

use OpenSID\Auth;
use OpenSID\Auth\Exception\UserNotFoundException;
use OpenSID\Auth\Exception\PermissionNotFoundException;

/**
 * SimpleAuth useful methods in a CodeIgniter-compatible library format
 * 
 * @author Anderson Salas <anderson@ingenia.me>
 */
class Library
{
    /**
     * @var string[]
     */
    private static $fetchedPermissions = [];

    /**
     * @var string[]
     */
    private static $foundedPermissions = [];

    final public function __call($name, $args)
    {
        if(method_exists(Auth::class, $name))
        {
            return call_user_func_array([Auth::class, $name], $args);
        }
        
        show_error('Unknown "' . $name . '" method', 500, 'SimpleAuth error');
    }
    
    /**
     * Resolves the ACL permission name, walking up from the given permission ID
     * 
     * @param string $id             
     * @param string $permissionName
     * 
     * @throws PermissionNotFoundException
     * 
     * @return void
     */
    final public static function walkUpPermission($id, &$permissionName = '')
    {
        // FASTEST: Defined permission ID in ACL Map
        $aclMap = config_item('simpleauth_acl_map');

        if(is_array($aclMap) && !empty($aclMap))
        {
            $foundedPermission = array_search($id,$aclMap);
        }
        else
        {
            $foundedPermission = FALSE;
        }

        if(is_array($aclMap) && !empty($aclMap) && $foundedPermission !== FALSE)
        {
            $permissionName = $foundedPermission;
            return;
        }
        // FAST: Cached permission
        else if(isset(self::$fetchedPermissions[$id]))
        {
            $permission = self::$fetchedPermissions[$id];
        }
        // SLOW: Application guessing of permission name iterating over the ACL categories
        //       table
        else
        {
            $permission = ci()->db->get_where(
                config_item('simpleauth_users_acl_categories_table'),
                [
                    'id' => $id,
                ]
            )->result();

            if(empty($permission))
            {
                throw new PermissionNotFoundException($permissionName);
            }

            $permission =  $permission[0];
            self::$fetchedPermissions[$permission->id] = $permission;
        }

        if(!empty($permissionName))
        {
            $permissionName = explode('.', $permissionName);
            array_unshift($permissionName, $permission->name);
            $permissionName = implode('.', $permissionName);
        }
        else
        {
            $permissionName = $permission->name;
        }

        if($permission->parent_id !== null)
        {
            self::walkUpPermission( $permission->parent_id , $permissionName);
        }
    }

    /**
     * Gets the actual ID of the given ACL permission name, walking to the more
     * nested permission
     * 
     * @param string $permission Permission name
     * @param int    $parentID   Parent permission id
     * 
     * @throws PermissionNotFoundException
     * 
     * @return int
     */
    final public static function walkDownPermission($permission , $parentID = null)
    {
        $aclMap = config_item('simpleauth_acl_map');

        // FASTEST: Defined permission ID in ACL Map
        if(is_array($aclMap) && !empty($aclMap) && isset($aclMap[$permission]))
        {
            return $aclMap[$permission];
        }

        // SLOW: Application guessing of permission name iterating over the ACL categories
        //       table
        $_permission    = explode('.', $permission);
        $permissionName = array_shift($_permission);

        $getCategory = function($permissionName, $parentID)
        {
            $category = ci()->db->get_where(
                config_item('simpleauth_users_acl_categories_table'),
                [
                    'name'      => $permissionName,
                    'parent_id' => $parentID,
                ]
            )->result();

            if(empty($category))
            {
                throw new PermissionNotFoundException($permissionName);
            }

            $category = $category[0];

            self::$fetchedPermissions[$category->id] = $category;

            return $category;
        };

        if($parentID === null)
        {
            $cachedPermission = array_search($permission, self::$foundedPermissions);

            if($cachedPermission !== FALSE)
            {
                return $cachedPermission;
            }
            else
            {
                $category = $getCategory($permissionName, $parentID);
            }
        }
        else
        {
            $category = $getCategory($permissionName, $parentID);
        }

        if(count($_permission) > 0)
        {
            return self::walkDownPermission( implode('.' , $_permission), $category->id);
        }

        return $category->id;
    }

    /**
     * Checks if the user is logged in and was not remembered by a cookie
     * 
     * @return bool
     */
    public function isFullyAuthenticated()
    {
        if(Auth::isGuest())
        {
            return false;
        }

        return Auth::session('fully_authenticated') === TRUE;
    }

    /**
     * Checks if the user has been authenticated through the 'Remember me' 
     * cookie and asks for the password to continue with the request in that 
     * case
     * 
     * @param string $route
     * 
     * @return bool
     */
    public function promptPassword($route = 'confirm_password')
    {
        if( Auth::isGuest() || !route_exists($route) )
        {
            return;
        }

        if( !$this->isFullyAuthenticated() )
        {
            $currentUrl = route();

            redirect( route($route) . '?redirect_to=' . $currentUrl );
            exit;
        }
    }

    /**
     * Searches the given user in the database and returns an object 
     * with their data in case of match, or NULL if no user matches the 
     * criteria
     *  
     * @param mixed $search
     * 
     * @return object|NULL
     */
    public function searchUser($search)
    {
        if(is_int($search))
        {
            ci()->load->database();

            $user = ci()->db->get_where( config_item('simpleauth_users_table'), [ config_item('simpleauth_id_col') => $search ])
                ->result();

            return !empty($user) ? $user[0] : null;
        }
        else if( is_string($search) )
        {
            ci()->load->database();

            $user = ci()->db->get_where( config_item('simpleauth_users_table'), [ config_item('simpleauth_username_col') => $search ])
                ->result();

            return !empty($user) ? $user[0] : null;
        }
        else if( is_array($search) )
        {
            ci()->load->database();

            $user = ci()->db->get_where( config_item('simpleauth_users_table'), $search )
                ->result();

            return !empty($user) ? $user[0] : null;
        }
        else
        {
            show_error('Unknown user search criteria', 500, 'SimpleAuth error');
        }
    }

    /**
     * Updates the user that matches the criteria with the provided data
     *  
     * @param string|int $user Search criteria
     * @param array      $values new data
     */
    public function updateUser($user, $values = null)
    {
        if($values === null)
        {
            $values = $user;
            $user   = Auth::user();
        }

        if(!is_int($search) && !is_string($search))
        {
            show_error('The $user argument must be an integer or a string', 500, 'SimpleAuth error');
        }

        if(!is_array($values))
        {
            show_error('The new values must be provided as an associative array', 500, 'SimpleAuth error');
        }

        ci()->load->database();

        ci()->db->update(
            config_item('simpleauth_users_table'),
            $values,
            [
                config_item('simpleauth_id_col') => $user->{config_item( is_int($user) ? 'simpleauth_id_col' : 'simpleauth_username_col')}
            ]
        );
    }

    /**
     * Stores a new user in the database with the given data
     * 
     * @param array $user The new user
     */
    public function createUser($user)
    {
        // Automatic password hash
        if(isset($user[config_item('simpleauth_password_col')]))
        {
            $user[config_item('simpleauth_password_col')] = Auth::loadUserProvider( config_item('simpleauth_user_provider') )
                ->hashPassword($user[config_item('simpleauth_password_col')]);
        }

        ci()->db->insert(config_item('simpleauth_users_table'), $user);
    }
    
    /**
     * Checks if the given permission exists in the ACL categories table
     * 
     * @param string $permission
     * 
     * @return bool
     */
    final public function permissionExists($permission)
    {
        if(config_item('simpleauth_enable_acl') !== true)
        {
            return false;
        }

        try
        {
            $id = self::walkDownPermission($permission);
        }
        catch(PermissionNotFoundException $e)
        {

            return false;
        }

        self::$foundedPermissions[$id] = $permission;

        return true;
    }

    /**
     * Grants the given permission to an user (searched by its username)
     * 
     * @param string $username
     * @param string $permission Permission name
     * 
     * @return bool
     */
    final public function grantPermission($username, $permission = null)
    {
        if(config_item('simpleauth_enable_acl') !== true)
        {
            return false;
        }

        if($permission === null)
        {
            $permission = $username;
            $user = Auth::user(true);
        }
        else
        {
            $userProvider = Auth::loadUserProvider(config_item('simpleauth_user_provider'));

            try
            {
                $user = $userProvider->loadUserByUsername($username);
            }
            catch(UserNotFoundException $e)
            {
                return false;
            }
        }

        if($this->permissionExists($permission) && !Auth::isGranted($user, $permission))
        {
            $id = self::walkDownPermission($permission);

            ci()->load->database();
            ci()->db->insert(
                config_item('simpleauth_users_acl_table'),
                [
                    'user_id'     => $user->{config_item('simpleauth_id_col')},
                    'category_id' => $id,
                ],
                [
                    config_item('simpleauth_username_col') => $user->getUsername()
                ]
            );

            Auth::session('validated',false);
            return true;
        }

        return false;
    }

    /**
     * Revokes the given permission to an user (searched by its username)
     * 
     * @param string $username
     * @param string $permission
     * 
     * @return bool
     */
    final public function revokePermission($username, $permission = null)
    {
        if(config_item('simpleauth_enable_acl') !== true)
        {
            return false;
        }

        if($permission === null)
        {
            $permission = $username;
            $user = Auth::user(true);
        }
        else
        {
            $userProvider = Auth::loadUserProvider(config_item('simpleauth_user_provider'));

            try
            {
                $user = $userProvider->loadUserByUsername($username);
            }
            catch(UserNotFoundException $e)
            {
                return false;
            }
        }

        if($this->permissionExists($permission) && Auth::isGranted($user, $permission))
        {
            $id = self::walkDownPermission($permission);

            ci()->load->database();
            ci()->db->delete(
                config_item('simpleauth_users_acl_table'),
                [
                    'user_id'     => $user->{config_item('simpleauth_id_col')},
                    'category_id' => $id,
                ]
            );
            Auth::session('validated',false);
            return true;
        }
    }
}
