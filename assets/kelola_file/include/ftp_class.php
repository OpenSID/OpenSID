<?php

Class FTPClient
{
	// *** Class variables
	private $connectionId;
	private $loginOk = false;
	private $messageArray = array();

	public function __construct() { }

	private function logMessage($message) 
	{
		$this->messageArray[] = $message;
	}

	public function getMessages()
	{
		return $this->messageArray;
	}

	public function connect ($server, $ftpUser, $ftpPassword, $isPassive = false)
	{

		// *** Set up basic connection
		$this->connectionId = ftp_connect($server);

		// *** Login with username and password
		$loginResult = ftp_login($this->connectionId, $ftpUser, $ftpPassword);

		// *** Sets passive mode on/off (default off)
		ftp_pasv($this->connectionId, $isPassive);

		// *** Check connection
		if ((!$this->connectionId) || (!$loginResult)) {
			$this->logMessage('FTP connection has failed!');
			$this->logMessage('Attempted to connect to ' . $server . ' for user ' . $ftpUser, true);
			return false;
		} else {
			$this->logMessage('Connected to ' . $server . ', for user ' . $ftpUser);
			$this->loginOk = true;
			return true;
		}
	}
	public function makeDir($directory)
	{
		// *** If creating a directory is successful...
		if (ftp_mkdir($this->connectionId, $directory)) {

			$this->logMessage('Directory "' . $directory . '" created successfully');
			return true;

		} else {

			// *** ...Else, FAIL.
			$this->logMessage('Failed creating directory "' . $directory . '"');
			return false;
		}
	}

	public function changeDir($directory)
	{
	    if (ftp_chdir($this->connectionId, $directory)) {
	        $this->logMessage('Current directory is now: ' . ftp_pwd($this->connectionId));
	        return true;
	    } else { 
	        $this->logMessage('Couldn\'t change directory');
	        return false;
	    }
	}

	public function getDirListing($directory = '.', $parameters = '-la')
	{
		echo shell_exec('whoami')." is who i am </br>";
		echo "Current directory is now: " . ftp_pwd($this->connectionId) . "</br>";

	    // get contents of the current directory
	    $contentsArray = ftp_rawlist($this->connectionId, $parameters . '  ' . $directory);
	 	echo error_get_last();
	    return $contentsArray;
	}
}