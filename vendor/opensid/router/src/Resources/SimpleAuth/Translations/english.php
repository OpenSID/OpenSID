<?php

/**
 * SimpleAuth English translation file
 *
 * @author Anderson Salas <anderson@ingenia.me>
 * @license MIT
 */

return [

    /*
     * Error messages
     */

    'ERR_LOGIN_INVALID_CREDENTIALS' => 'Invalid login credentials',
    'ERR_LOGIN_INACTIVE_USER'       => 'Your user has been disabled by an administrator',
    'ERR_LOGIN_UNVERIFIED_USER'     => 'You must verify your email address first',
    'ERR_LOGIN_ATTEMPT_BLOCKED'     => 'You can not sign in at this time. Try again later',

    /*
     * Email messages
     *
     * {first_name}, {verification_url} and {password_reset_url} are provided and parsed
     * automatically by OpenSID-CI
     */

    'email_verification_message' => '<p>Hi {first_name}!</p>
        <p>Please verify your email address by clicking the following link:</p>
        <a href="{verification_url}">Verify my email</a>
        <p>If not works, copy and paste in your browser:<br>
        {verification_url}</p>',


    'email_password_reset_message' => '<p>Hi {first_name}!</p>
        <p>To reset your password, follow this link:</p>
        <a href="{password_reset_url}">Reset my password</a>
        <p>If not works, copy and paste in your browser:<br>
        {password_reset_url}</p>',

    /*
     * Login
     */

    'login'                   => 'Log in',
    'remember_me'             => 'Remember me',
    'enter'                   => 'Enter',
    'forgotten_password_link' => 'I forgot my password',
    'register_link'           => 'You do not have an account? Register now!',

    /*
     * Sign up
     */

    'signup'         => 'Sign up',
    'signup_btn'     => 'Register account',
    'signup_success' => 'Sign up success!',
    'signup_success_confirmation_notice' => 'We have sent you an email with the instructions to activate your account. If you can\'t find it, please check your spam folder',
    'signup_success_notice' => 'You can log in to the user area',

    /*
     * Email verification
     */
     'email_verification_failed'          => 'Email verification error',
     'email_verification_failed_message'  => 'The token is not valid or already used',
     'email_verification_success'         => 'Success!',
     'email_verification_success_message' => 'Your account email is now verified. <a href="{login_url}">Login</a>',


    /*
     * Password reset
     */

     'password_reset'               => 'Password reset',
     'password_reset_email_label'   => 'Please enter your email address:',
     'password_reset_email_field'   => 'Email',
     'password_reset_btn'           => 'Submit',
     'password_reset_form_btn'      => 'Change password',
     'password_reset_new_pwd'       => 'New Password',
     'password_reset_repeat_pwd'    => 'Repeat Password',
     'password_reset_result_notice' => 'If the email address exists, instructions will be sent there',
     'password_reset_token_error'   => 'Error',
     'password_reset_token_error_message' => 'The token is not valid or already used',
     'password_reset_validation_password' => 'The new passwords does not match',
     'password_reset_success'         => 'Password changed successfully',
     'password_reset_success_message' => 'Go to <a href="{login_url}">Login</a> page',


    /*
     * Password prompt
     */
     'password_prompt'     => 'Confirm your password',
     'password_prompt_btn' => 'Continue',
     'password_prompt_pwd_field' => 'Current Password',
     'password_prompt_validation_required' => 'Please enter your password',
     'password_prompt_validation_password' => 'The password is incorrect',
];