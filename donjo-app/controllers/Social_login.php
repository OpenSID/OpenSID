<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
Auther : Salman Iqbal
Date   : 24/5/2017
IDE    : Sublime
Email  : salmaniqbal937@gmail.com
*/
class Social_login extends Admin_Controller
{
    function __construct() 
    {
		parent::__construct();

		// Load facebook library
		$this->load->library('facebook');

		$this->load->model('social_login_model');

		$this->load->config('social_auth_config');
		
		
		//Include the twitter oauth php libraries
		include_once APPPATH."libraries/twitter-oauth-php-codexworld/twitteroauth.php";

		// Include the google api php libraries
		include_once APPPATH."libraries/google-api-php-client/Google_Client.php";
		include_once APPPATH."libraries/google-api-php-client/contrib/Google_Oauth2Service.php";
    }
    public function twitter_login()
    {
		$userData = array();
		
		//Twitter API Configuration
		$consumerKey    = $this->config->item('client_id');
		$consumerSecret = $this->config->item('secret_id');
		$oauthCallback  = $this->config->item('call_back');
		
		//Get existing token and token secret from session
		$sessToken = $this->session->userdata('token');
		$sessTokenSecret = $this->session->userdata('token_secret');
		
		//Get status and user info from session
		$sessStatus = $this->session->userdata('status');
		$sessUserData = $this->session->userdata('userData');
		
		if(isset($sessStatus) && $sessStatus == 'verified')
		{
			//Connect and get latest tweets
			$connection = new TwitterOAuth($consumerKey, $consumerSecret, $sessUserData['accessToken']['oauth_token'], $sessUserData['accessToken']['oauth_token_secret']); 
			$data['tweets'] = $connection->get('statuses/user_timeline', array('screen_name' => $sessUserData['username'], 'count' => 5));

			//User info from session
			$userData = $sessUserData;
		}
		elseif(isset($_REQUEST['oauth_token']) && $sessToken == $_REQUEST['oauth_token'])
		{
			//Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
			$connection = new TwitterOAuth($consumerKey, $consumerSecret, $sessToken, $sessTokenSecret); //print_r($connection);die;
			$accessToken = $connection->getAccessToken($_REQUEST['oauth_verifier']);
			if($connection->http_code == '200'){
				//Get user profile info
				$userInfo = $connection->get('account/verify_credentials');

				//Preparing data for database insertion
				$name = explode(" ",$userInfo->name);
				$first_name = isset($name[0])?$name[0]:'';
				$last_name = isset($name[1])?$name[1]:'';
				$userData = array(
					'oauth_provider' => 'twitter',
					// 'oauth_uid' => $userInfo->id,
					'username' 		 => $userInfo->screen_name,
					'first_name' 	 => $first_name,
					'last_name' 	 => $last_name,
					'date'			 => date('y-m-d'),
					// 'locale' => $userInfo->lang,
					// 'profile_url' => 'https://twitter.com/'.$userInfo->screen_name,
					// 'picture_url' => $userInfo->profile_image_url
				);

				$username = $userData['username'];
				$email    = $userData['username']."@twitter.com";

				// pr($userData);die();

				//Insert or update user data
				$userID = $this->social_login_model->socail_login($userData,$username,$email,'users');

				//Store status and user profile info into session
				$userData['accessToken'] = $accessToken;
				$this->session->set_userdata('status','verified');
				
				//Get latest tweets
				$data['tweets'] = $connection->get('statuses/user_timeline', array('screen_name' => $userInfo->screen_name, 'count' => 5));

				if($userID)
				{
					//if the login is successful
					//redirect them back to the home page
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					redirect('users/Auth','refresh');
				}
				else
				{
					// if the login was un-successful
					// redirect them back to the login page
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect('users/auth/login', 'refresh'); // use redirects instead of loading views for compatibility with Admin_Controller libraries
				}

			}else{
				$data['error_msg'] = 'Some problem occurred, please try again later!';
			}
		}else{
			//unset token and token secret from session
			$this->session->unset_userdata('token');
			$this->session->unset_userdata('token_secret');
			
			//Fresh authentication
			$connection = new TwitterOAuth($consumerKey, $consumerSecret);
			$requestToken = $connection->getRequestToken($oauthCallback);
			
			//Received token info from twitter
			$this->session->set_userdata('token',$requestToken['oauth_token']);
			$this->session->set_userdata('token_secret',$requestToken['oauth_token_secret']);
			
			//Any value other than 200 is failure, so continue only if http code is 200
			if($connection->http_code == '200'){
				//redirect user to twitter
				$twitterUrl = $connection->getAuthorizeURL($requestToken['oauth_token']);
				$data['oauthURL'] = $twitterUrl;
			}else{
				$data['oauthURL'] = base_url().'user_authentication';
				$data['error_msg'] = 'Error connecting to twitter! try again later!';
			}
        }
    }

    public function facebook_login()
    {
		$userData = array();
		
		// Check if user is logged in
		if($this->facebook->is_authenticated()){
			// Get user facebook profile details
			$userProfile = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,gender,locale,picture');

            // Preparing data for database insertion
			$userData = array('oauth_provider' => 'facebook',
							  // 'oauth_uid'      => $userProfile['id'],
							  'first_name' 	   => $userProfile['first_name'],
							  'last_name' 	   => $userProfile['last_name'],
							  'email' 		   => $userProfile['email'],
							  // 'gender' 		   => $userProfile['gender'],
							  // 'locale' 		   => $userProfile['locale'],
							  // 'profile_url'    => 'https://www.facebook.com/'.$userProfile['id'],
							  // 'picture_url'    => $userProfile['picture']['data']['url'] 
							  );


			$username  = $userData['first_name']." ".$userData['last_name'];

			$useremail = $userData['email'];
			
			if ($useremail) 
			{
				$email = $userData['email'];
			}
			else
			{
				$email = $userData['first_name']."".$userData['last_name']."@facebook.com";
			}

			//Insert or update user data
			$userID = $this->social_login_model->socail_login($userData,$username,$email,'users');

			// pr($userID);die();

			if($userID)
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('users/Auth','refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('users/Auth', 'refresh'); // use redirects instead of loading views for compatibility with Admin_Controller libraries
			}
		} else {
            $fbuser = '';
			
			// Get login URL
            $data['authUrl'] =  $this->facebook->login_url();
        }
    }

    public function google_login()
    {
		// Google Project API Credentials
		$clientId     = $this->config->item('google_client_id');
        $clientSecret = $this->config->item('google_secret_id');
        $redirectUrl  = $this->config->item('google_call_back');
		
		// Google Client Configuration
        $gClient = new Google_Client();
        $gClient->setApplicationName('Login System');
        $gClient->setClientId($clientId);
        $gClient->setClientSecret($clientSecret);
        $gClient->setRedirectUri($redirectUrl);
        $google_oauthV2 = new Google_Oauth2Service($gClient);

        if (isset($_REQUEST['code'])) 
        {
            $gClient->authenticate();
            $this->session->set_userdata('token', $gClient->getAccessToken());
            redirect($redirectUrl);
        }

        $token = $this->session->userdata('token');

        if (!empty($token)) 
        {
            $gClient->setAccessToken($token);
        }

        if ($gClient->getAccessToken()) 
        {
            $userProfile = $google_oauthV2->userinfo->get();

            // Preparing data for database insertion
            $userData = array('oauth_provider' => 'google', 
            				  // 'oauth_uid'      => $userProfile['id'], 
            				  'first_name'     => $userProfile['given_name'], 
            				  'last_name'      => $userProfile['family_name'], 
            				  'email'          => $userProfile['email'],
            				  'date'   		   => date('y-m-d'),	 
            				  // 'locale'         => $userProfile['locale'], 
            				  // 'picture_url'    => $userProfile['picture']
            				 );

            $username = $userData['first_name']." ".$userData['last_name'];
            $email    = $userData['email'];


            
			// Insert or update user data
            $userID = $this->social_login_model->socail_login($userData,$username,$email,'users');
            
			// pr($userID);die();
            
			if($userID)
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('users/Auth','refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('users/auth/login', 'refresh'); // use redirects instead of loading views for compatibility with Admin_Controller libraries
			}
        } else {
            $data['authUrl'] = $gClient->createAuthUrl();
        }
		// $this->load->view('social_login',$data);
    }
    public function instagram_login()
	{
		if (isset($_GET['code'])) 
		{
			$instagram_parameter = array(
				'client_id'     => $this->config->item('insta_client_id'),
				'client_secret' => $this->config->item('insta_secret_id'),
				'grant_type'    => $this->config->item('grant_type'),
				'redirect_uri'  => $this->config->item('insta_call_back'),
				'code'          => $_GET['code']
				);
			// echo '<pre>';
			$curl = curl_init('https://api.instagram.com/oauth/access_token');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($curl, CURLOPT_POSTFIELDS,$instagram_parameter);
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($curl);
			curl_close($curl);

			$mydata = json_decode($result);

			 // Preparing data for database insertion
            $userData = array('oauth_provider'  => 'Instagram', 
            				  // 'oauth_uid'    => $mydata->user->id, 
            				  'username'        => $mydata->user->username, 
            				  // 'fullname'        => $mydata->user->full_name, 
            				  // 'profile_picture' => $user->https://ig-s-c-a.akamaihd.net/hphotos-ak-xpa1/t51.2885-19/s150x150/13116577_482403895289198_325395608_a.jpg,
            				  'date'   		    => date('y-m-d'),	 
            				  // 'bio'          => $mydata->$user->bio, 
            				  // 'website'      => $mydata->$user->website
            				 );

            $fullname = $mydata->user->full_name;

            //breaking full name in first name and last name
            list($firstName, $lastName) = explode(' ',$fullname);

			$userData['first_name'] = $firstName;
			$userData['last_name']  = $lastName;

			$username = $userData['username'];
            $email    = $userData['username']."@instagram.com";

			// Insert or update user data
            $userID = $this->social_login_model->socail_login($userData,$username,$email,'users');
            
            // pr($userID);die();

			if($userID)
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('users/Auth','refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('users/auth/login', 'refresh'); // use redirects instead of loading views for compatibility with Admin_Controller libraries
			}
		}
		else
		{
			$this->load->view('welcome_message');
		}	
	}

	public function linkedin_login()
    {
        $client_id     = $this->config->item('linkedin_client_id');
        $client_secret = $this->config->item('linkedin_client_secret');
        $redirect_uri  = $this->config->item('linkedin_redirect_uri');
        $csrf_token    = $this->config->item('linkedin_csrf_token');
        $scopes        = $this->config->item('linkedin_scopes');

        if (isset($_REQUEST['code'])) 
        {
            $code = $_REQUEST['code'];
            $url = 'https://www.linkedin.com/oauth/v2/accessToken';
            $params = [
                    'client_id'     => $client_id,
                    'client_secret' => $client_secret,
                    'redirect_uri'  => $redirect_uri,
                    'code'          => $code,
                    'grant_type' 	=> 'authorization_code',
            ];

            $accessToken = $this->curl($url,http_build_query($params));
            
            $accessToken = json_decode($accessToken)->access_token;

            $url = "https://api.linkedin.com/v1/people/~:(id,firstName,lastName,pictureUrls::(original),headline,publicProfileUrl,location,industry,positions,email-address)?format=json&oauth2_access_token=" . $accessToken;

            $user = file_get_contents($url, false);

            return (json_decode($user));
        }
    }

    //this curl is for linkedin
    public function curl($url,$parameters)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        $headers = [];

        $headers[] = "Content-Type: application/x-www-form-urlencoded";

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        return $result;
    }

    //getting linkedin data
    public function linkedin_data()
    {
    	/*
    		If your want to show more data of
    		user then print_r
    	*/
        $user = $this->linkedin_login();

        // Preparing data for database insertion
        $userData = array('oauth_provider' => 'Likedin', 
        				  // 'oauth_uid'      => $user->id, 
        				  'first_name'     => $user->firstName, 
        				  'last_name'      => $user->lastName, 
        				  'email'          => $user->emailAddress,
        				  'date'   		   => date('y-m-d'),	 
        				  // 'industry'         => $user->industry, 
        				 );

        $username = $userData['first_name']." ".$userData['last_name'];
        $email    = $userData['email'];
        
		// Insert or update user data
        $userID = $this->social_login_model->socail_login($userData,$username,$email,'users');

		if($userID)
		{
			//if the login is successful
			//redirect them back to the home page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect('users/Auth','refresh');
		}
		else
		{
			// if the login was un-successful
			// redirect them back to the login page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect('users/auth/login', 'refresh'); // use redirects instead of loading views for compatibility with Admin_Controller libraries
		}
    }
	
	public function google_logout() {
		$this->session->unset_userdata('token');
		$this->session->unset_userdata('userData');
        $this->session->sess_destroy();
		redirect('Auth');
    }
    public function twitter_logout() {
		$this->session->unset_userdata('token');
		$this->session->unset_userdata('token_secret');
		$this->session->unset_userdata('status');
		$this->session->unset_userdata('userData');
        $this->session->sess_destroy();
		redirect('Auth');
    }
    public function fb_logout() {
		// Remove local Facebook session
		$this->facebook->destroy_session();
		// Remove user data from session
		$this->session->unset_userdata('userData');
		// Redirect to login page
        redirect('Auth');
    }
}
