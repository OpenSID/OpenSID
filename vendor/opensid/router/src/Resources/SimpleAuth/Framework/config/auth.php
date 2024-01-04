<?php

/**
 * --------------------------------------------------------------------------------------
 *                                General Auth configuration
 * --------------------------------------------------------------------------------------
 */

//
// Basic routing
//

$config['auth_login_route']  = 'login';

$config['auth_logout_route'] = 'logout';

$config['auth_login_route_redirect'] = 'dashboard';

$config['auth_logout_route_redirect'] = 'homepage';

$config['auth_route_auto_redirect'] = [

    # The following routes will redirect to the 'auth_login_route_redirect' if
    # the user is logged in:

    'login',
    'signup',
    'password_reset',
    'password_reset_form'
];

//
// Main login form
//

$config['auth_form_username_field'] = 'email';

$config['auth_form_password_field'] = 'password';

//
// Session & Cookies configuration
//

$config['auth_session_var'] = 'auth';


/**
 * --------------------------------------------------------------------------------------
 *                                SimpleAuth configuration
 * --------------------------------------------------------------------------------------
 */

//
// Enable/disable features
//

$config['simpleauth_enable_signup'] = TRUE;

$config['simpleauth_enable_password_reset'] = TRUE;

$config['simpleauth_enable_remember_me'] = TRUE;

$config['simpleauth_enable_email_verification'] = TRUE;

$config['simpleauth_enforce_email_verification'] = FALSE;

$config['simpleauth_enable_brute_force_protection'] = TRUE;

$config['simpleauth_enable_acl'] = TRUE ;

//
// Views configuration
//

$config['simpleauth_skin'] = 'default';

$config['simpleauth_assets_dir'] = 'assets/auth';

//
// ACL Configuration
//

$config['simpleauth_acl_map'] = [

    // If you are worried about performance, you can fill this array with $key => $value
    // pairs of known permissions/permissions groups ids, reducing drastically the
    // amount of executed database queries
    //
    // Example
    //    [ permission full name ]       [ permission id ]
    //    'general.blog.read'        =>         1
    //    'general.blog.edit'        =>         2
    //    'general.blog.delete'      =>         3
];

//
// Email configuration
//

$config['simpleauth_email_configuration'] = null;

$config['simpleauth_email_address'] = 'noreply@example.com';

$config['simpleauth_email_name'] = 'Example';

$config['simpleauth_email_verification_message'] = NULL;

$config['simpleauth_password_reset_message'] = NULL;

//
// Remember me configuration
//

$config['simpleauth_remember_me_field'] = 'remember_me';

$config['simpleauth_remember_me_cookie'] = 'remember_me';

//
// Database configuration
//

$config['simpleauth_user_provider'] = 'User';

$config['simpleauth_users_table']  = 'users';

$config['simpleauth_users_email_verification_table']  = 'email_verifications';

$config['simpleauth_password_resets_table']  = 'password_resets';

$config['simpleauth_login_attempts_table']  = 'login_attempts';

$config['simpleauth_users_acl_table']  = 'user_permissions';

$config['simpleauth_users_acl_categories_table']  = 'user_permissions_categories';

$config['simpleauth_id_col'] = 'id';

$config['simpleauth_username_col'] = 'email';

$config['simpleauth_email_col']  = 'email';

$config['simpleauth_email_first_name_col'] = 'first_name';

$config['simpleauth_password_col'] = 'password';

$config['simpleauth_role_col'] = 'role';

$config['simpleauth_active_col'] = 'active';

$config['simpleauth_verified_col'] = 'verified';

$config['simpleauth_remember_me_col'] = 'remember_token';
