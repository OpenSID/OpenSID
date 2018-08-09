<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Security extends CI_Security
{
	/** @inheritdoc */
	public function csrf_show_error()
	{
		$message = 'Bad Request';

		if (ENVIRONMENT === 'development')
		{
			$message .= '<br>CSRF Verification Failed';
		}

		set_status_header(400);
		exit($message);
	}
}
