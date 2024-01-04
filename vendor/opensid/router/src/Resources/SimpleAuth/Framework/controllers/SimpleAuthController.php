<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Welcome to your SimpleAuth controller!
 *
 * This class inherits the OpenSID-CI base SimpleAuth controller, which contains
 * all the authentication logic out-the-box.
 *
 * (And yes, is still a controller, so you can add your own methods)
 */
class SimpleAuthController extends OpenSID\Auth\SimpleAuth\Controller
{

    /**
     * Sign up form fields
     *
     * This method must return an array with the Sign up form fields and their respective
     * validation.
     *
     * Feel free to modify this array, it's YOUR sign up form ;)
     *
     * The syntax is the following:
     *
     * [
     *      [field name] => [
     *          [field type],
     *          [field label],
     *          [HTML5 attributes] (In key => value array format),
     *          [CI Validation] (array of validation rules),
     *          [CI Validation Messages] (array, optional)
     *      ]
     * ]
     *
     * @return mixed
     *
     * @access public
     */
    public function getSignupFields()
    {
        return [
            'first_name' => [
                'text',
                'First Name',
                ['required' => true],
                ['required', 'max_length[45]'],
                null
            ],
            'last_name' => [
                'text',
                'Last Name',
                ['required' => 'required'],
                ['required', 'max_length[45]'],
            ],
            'username' => [
                'text',
                'Username',
                ['required' => true, 'max_length' => 22],
                ['required', 'min_length[3]', 'max_length[22]', 'is_unique[' . config_item('simpleauth_users_table') . '.username' . ']'],
            ],
            'email' => [
                'email',
                'Email',
                ['required' => true],
                ['required', 'valid_email', 'max_length[255]', 'is_unique[' . config_item('simpleauth_users_table') . '.' . config_item('simpleauth_username_col') .']'],
            ],
            'gender' => [
                'radio' => [ 'm' => 'Male', 'f' => 'Female'],
                'Gender',
                ['required' => true],
                ['required', 'max_length[45]'],
            ],
            'password' => [
                'password',
                'Password',
                ['required' => true],
                ['required', 'min_length[8]', 'matches[password_repeat]'],
                ['matches' => 'The passwords does not match.'],
            ],
            'password_repeat' => [
                'password',
                'Repeat Password',
                ['required' => true],
                ['required']
            ],
            'tos_agreement' => [
                'checkbox' => [ 'accept' => 'I accept the Terms and Conditions of Service'],
                'Conditions agreement',
                ['required' => true],
                ['required'],
                ['required' => 'You must accept the Terms and Conditions of Service']
            ],
        ];
    }

    /**
     * Fillable database user fields
     *
     * This method must return an array with the database fillable user fields.
     *
     * (In other words, this is the equivalent of the $fillable property of Eloquent ORM
     *  models, but only for auth proposes)
     *
     * @return array
     *
     * @access public
     */
    public function getUserFields()
    {
        return [
            'first_name',
            'last_name',
            'username',
            'gender',
            'email',
            'password',
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }
}