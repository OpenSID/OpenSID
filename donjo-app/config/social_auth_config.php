<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Facebook API Configuration
| -------------------------------------------------------------------
|
| To get an facebook app details you have to create a Facebook app
| at Facebook developers panel (https://developers.facebook.com)
|
|  facebook_app_id               string   Your Facebook App ID.
|  facebook_app_secret           string   Your Facebook App Secret.
|  facebook_login_type           string   Set login type. (web, js, canvas)
|  facebook_login_redirect_url   string   URL to redirect back to after login. (do not include base URL)
|  facebook_logout_redirect_url  string   URL to redirect back to after logout. (do not include base URL)
|  facebook_permissions          array    Your required permissions.
|  facebook_graph_version        string   Specify Facebook Graph version. Eg v2.6
|  facebook_auth_on_load         boolean  Set to TRUE to check for valid access token on every page load.
*/
$config['facebook_app_id']              = '1304570809649783';
$config['facebook_app_secret']          = '2be58a38fc9deb0c84cb30d29591699b';
$config['facebook_login_type']          = 'web';
$config['facebook_login_redirect_url']  = 'users/social_login/facebook_login';
$config['facebook_logout_redirect_url'] = 'user_authentication/logout';
$config['facebook_permissions']         = array('email');
$config['facebook_graph_version']       = 'v2.6';
$config['facebook_auth_on_load']        = TRUE;



//Twitter API Configuration
$config['client_id'] = 'Ps0HSs9mBruaZHrj5T7FgNG25';
$config['secret_id'] = 'lH4GT1LxYyLtAeXL5rb8q1tblLDpTjYhROGB3cRmg3SmXNaVBy';
$config['call_back'] = base_url().'users/social_login/twitter_login/';


// Google Project API Credentials
$config['google_client_id'] = '554815054343-mrip66a0c4a9r4tmse3ohidmfk5d7pt8.apps.googleusercontent.com';
$config['google_secret_id'] = 'm-9HZQDEuNhphErNkNXveh3J';
$config['google_call_back'] = base_url() . 'users/social_login/google_login/';


// Instagram API Credentials
$config['insta_client_id'] = 'cc0c6e3ffad5457eb811cf3bd99f0524';
$config['insta_secret_id'] = '8abce035a6f741edb739dbdff8a4fe84';
$config['grant_type']      = 'authorization_code';
$config['insta_call_back'] = base_url() . 'users/Social_login/instagram_login';


//Linkedin Credentials
$config['linkedin_client_id'] = "81uy3lt801lccs";
$config['linkedin_client_secret'] = "v3XvcLVh2200Lr27";
$config['linkedin_redirect_uri'] = base_url()."users/social_login/linkedin_data";
$config['linkedin_csrf_token'] = rand(1111111,9999999);
$config['linkedin_scopes'] = "r_basicprofile%20r_emailaddress";



