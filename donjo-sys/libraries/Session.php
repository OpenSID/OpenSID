<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Session {
	var $sess_encrypt_cookie		= FALSE;
	var $sess_use_database			= FALSE;
	var $sess_table_name			= '';
	var $sess_expiration			= 7200;
	var $sess_expire_on_close		= FALSE;
	var $sess_match_ip				= FALSE;
	var $sess_match_useragent		= TRUE;
	var $sess_cookie_name			= 'ci_session';
	var $cookie_prefix				= '';
	var $cookie_path				= '';
	var $cookie_domain				= '';
	var $cookie_secure				= FALSE;
	var $sess_time_to_update		= 300;
	var $encryption_key				= '';
	var $flashdata_key				= 'flash';
	var $time_reference				= 'time';
	var $gc_probability				= 5;
	var $userdata					= array();
	var $CI;
	var $now;
	public function __construct($params = array())
	{
		log_message('debug', "Session Class Initialized");
		
		$this->CI =& get_instance();
		
		
		foreach (array('sess_encrypt_cookie', 'sess_use_database', 'sess_table_name', 'sess_expiration', 'sess_expire_on_close', 'sess_match_ip', 'sess_match_useragent', 'sess_cookie_name', 'cookie_path', 'cookie_domain', 'cookie_secure', 'sess_time_to_update', 'time_reference', 'cookie_prefix', 'encryption_key') as $key)
		{
			$this->$key = (isset($params[$key])) ? $params[$key] : $this->CI->config->item($key);
		}
		if ($this->encryption_key == '')
		{
			show_error('In order to use the Session class you are required to set an encryption key in your config file.');
		}
		
		$this->CI->load->helper('string');
		
		if ($this->sess_encrypt_cookie == TRUE)
		{
			$this->CI->load->library('encrypt');
		}
		
		if ($this->sess_use_database === TRUE AND $this->sess_table_name != '')
		{
			$this->CI->load->database();
		}
		
		
		$this->now = $this->_get_time();
		
		
		if ($this->sess_expiration == 0)
		{
			$this->sess_expiration = (60*60*24*365*2);
		}
		
		$this->sess_cookie_name = $this->cookie_prefix.$this->sess_cookie_name;
		
		
		if ( ! $this->sess_read())
		{
			$this->sess_create();
		}
		else
		{
			$this->sess_update();
		}
		
		$this->_flashdata_sweep();
		
		$this->_flashdata_mark();
		
		$this->_sess_gc();
		log_message('debug', "Session routines successfully run");
	}
	function sess_read()
	{
		
		$session = $this->CI->input->cookie($this->sess_cookie_name);
		
		if ($session === FALSE)
		{
			log_message('debug', 'A session cookie was not found.');
			return FALSE;
		}
		
		$len = strlen($session) - 40;
		if ($len <= 0)
		{
			log_message('error', 'Session: The session cookie was not signed.');
			return FALSE;
		}
		
		$hmac = substr($session, $len);
		$session = substr($session, 0, $len);
		
		$hmac_check = hash_hmac('sha1', $session, $this->encryption_key);
		$diff = 0;
		for ($i = 0; $i < 40; $i++)
		{
			$xor = ord($hmac[$i]) ^ ord($hmac_check[$i]);
			$diff |= $xor;
		}
		if ($diff !== 0)
		{
			log_message('error', 'Session: HMAC mismatch. The session cookie data did not match what was expected.');
			$this->sess_destroy();
			return FALSE;
		}
		
		if ($this->sess_encrypt_cookie == TRUE)
		{
			$session = $this->CI->encrypt->decode($session);
		}
		
		$session = $this->_unserialize($session);
		
		if ( ! is_array($session) OR ! isset($session['session_id']) OR ! isset($session['ip_address']) OR ! isset($session['user_agent']) OR ! isset($session['last_activity']))
		{
			$this->sess_destroy();
			return FALSE;
		}
		
		if (($session['last_activity'] + $this->sess_expiration) < $this->now)
		{
			$this->sess_destroy();
			return FALSE;
		}
		
		if ($this->sess_match_ip == TRUE AND $session['ip_address'] != $this->CI->input->ip_address())
		{
			$this->sess_destroy();
			return FALSE;
		}
		
		if ($this->sess_match_useragent == TRUE AND trim($session['user_agent']) != trim(substr($this->CI->input->user_agent(), 0, 120)))
		{
			$this->sess_destroy();
			return FALSE;
		}
		
		if ($this->sess_use_database === TRUE)
		{
			$this->CI->db->where('session_id', $session['session_id']);
			if ($this->sess_match_ip == TRUE)
			{
				$this->CI->db->where('ip_address', $session['ip_address']);
			}
			if ($this->sess_match_useragent == TRUE)
			{
				$this->CI->db->where('user_agent', $session['user_agent']);
			}
			$query = $this->CI->db->get($this->sess_table_name);
			
			if ($query->num_rows() == 0)
			{
				$this->sess_destroy();
				return FALSE;
			}
			
			$row = $query->row();
			if (isset($row->user_data) AND $row->user_data != '')
			{
				$custom_data = $this->_unserialize($row->user_data);
				if (is_array($custom_data))
				{
					foreach ($custom_data as $key => $val)
					{
						$session[$key] = $val;
					}
				}
			}
		}
		
		$this->userdata = $session;
		unset($session);
		return TRUE;
	}
	function sess_write()
	{
		
		if ($this->sess_use_database === FALSE)
		{
			$this->_set_cookie();
			return;
		}
		
		$custom_userdata = $this->userdata;
		$cookie_userdata = array();
		
		
		
		foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
		{
			unset($custom_userdata[$val]);
			$cookie_userdata[$val] = $this->userdata[$val];
		}
		
		
		if (count($custom_userdata) === 0)
		{
			$custom_userdata = '';
		}
		else
		{
			
			$custom_userdata = $this->_serialize($custom_userdata);
		}
		
		$this->CI->db->where('session_id', $this->userdata['session_id']);
		$this->CI->db->update($this->sess_table_name, array('last_activity' => $this->userdata['last_activity'], 'user_data' => $custom_userdata));
		
		
		
		$this->_set_cookie($cookie_userdata);
	}
	function sess_create()
	{
		$sessid = '';
		while (strlen($sessid) < 32)
		{
			$sessid .= mt_rand(0, mt_getrandmax());
		}
		
		$sessid .= $this->CI->input->ip_address();
		$this->userdata = array(
							'session_id'	=> md5(uniqid($sessid, TRUE)),
							'ip_address'	=> $this->CI->input->ip_address(),
							'user_agent'	=> substr($this->CI->input->user_agent(), 0, 120),
							'last_activity'	=> $this->now,
							'user_data'		=> ''
							);
		
		if ($this->sess_use_database === TRUE)
		{
			$this->CI->db->query($this->CI->db->insert_string($this->sess_table_name, $this->userdata));
		}
		
		$this->_set_cookie();
	}
	function sess_update()
	{
		
		if ($this->CI->input->is_ajax_request() OR ($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
		{
			return;
		}
		
		
		$old_sessid = $this->userdata['session_id'];
		$new_sessid = '';
		while (strlen($new_sessid) < 32)
		{
			$new_sessid .= mt_rand(0, mt_getrandmax());
		}
		
		$new_sessid .= $this->CI->input->ip_address();
		
		$new_sessid = md5(uniqid($new_sessid, TRUE));
		
		$this->userdata['session_id'] = $new_sessid;
		$this->userdata['last_activity'] = $this->now;
		
		
		$cookie_data = NULL;
		
		if ($this->sess_use_database === TRUE)
		{
			
			$cookie_data = array();
			foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
			{
				$cookie_data[$val] = $this->userdata[$val];
			}
			$this->CI->db->query($this->CI->db->update_string($this->sess_table_name, array('last_activity' => $this->now, 'session_id' => $new_sessid), array('session_id' => $old_sessid)));
		}
		
		$this->_set_cookie($cookie_data);
	}
	function sess_destroy()
	{
		
		if ($this->sess_use_database === TRUE && isset($this->userdata['session_id']))
		{
			$this->CI->db->where('session_id', $this->userdata['session_id']);
			$this->CI->db->delete($this->sess_table_name);
		}
		
		setcookie(
					$this->sess_cookie_name,
					addslashes(serialize(array())),
					($this->now - 31500000),
					$this->cookie_path,
					$this->cookie_domain,
					0
				);
		
		$this->userdata = array();
	}
	function userdata($item)
	{
		return ( ! isset($this->userdata[$item])) ? FALSE : $this->userdata[$item];
	}
	function all_userdata()
	{
		return $this->userdata;
	}
	function set_userdata($newdata = array(), $newval = '')
	{
		if (is_string($newdata))
		{
			$newdata = array($newdata => $newval);
		}
		if (count($newdata) > 0)
		{
			foreach ($newdata as $key => $val)
			{
				$this->userdata[$key] = $val;
			}
		}
		$this->sess_write();
	}
	function unset_userdata($newdata = array())
	{
		if (is_string($newdata))
		{
			$newdata = array($newdata => '');
		}
		if (count($newdata) > 0)
		{
			foreach ($newdata as $key => $val)
			{
				unset($this->userdata[$key]);
			}
		}
		$this->sess_write();
	}
	function set_flashdata($newdata = array(), $newval = '')
	{
		if (is_string($newdata))
		{
			$newdata = array($newdata => $newval);
		}
		if (count($newdata) > 0)
		{
			foreach ($newdata as $key => $val)
			{
				$flashdata_key = $this->flashdata_key.':new:'.$key;
				$this->set_userdata($flashdata_key, $val);
			}
		}
	}
	function keep_flashdata($key)
	{
		
		
		
		
		$old_flashdata_key = $this->flashdata_key.':old:'.$key;
		$value = $this->userdata($old_flashdata_key);
		$new_flashdata_key = $this->flashdata_key.':new:'.$key;
		$this->set_userdata($new_flashdata_key, $value);
	}
	function flashdata($key)
	{
		$flashdata_key = $this->flashdata_key.':old:'.$key;
		return $this->userdata($flashdata_key);
	}
	function _flashdata_mark()
	{
		$userdata = $this->all_userdata();
		foreach ($userdata as $name => $value)
		{
			$parts = explode(':new:', $name);
			if (is_array($parts) && count($parts) === 2)
			{
				$new_name = $this->flashdata_key.':old:'.$parts[1];
				$this->set_userdata($new_name, $value);
				$this->unset_userdata($name);
			}
		}
	}
	function _flashdata_sweep()
	{
		$userdata = $this->all_userdata();
		foreach ($userdata as $key => $value)
		{
			if (strpos($key, ':old:'))
			{
				$this->unset_userdata($key);
			}
		}
	}
	function _get_time()
	{
		if (strtolower($this->time_reference) == 'gmt')
		{
			$now = time();
			$time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));
		}
		else
		{
			$time = time();
		}
		return $time;
	}
	function _set_cookie($cookie_data = NULL)
	{
		if (is_null($cookie_data))
		{
			$cookie_data = $this->userdata;
		}
		
		$cookie_data = $this->_serialize($cookie_data);
		if ($this->sess_encrypt_cookie == TRUE)
		{
			$cookie_data = $this->CI->encrypt->encode($cookie_data);
		}
		$cookie_data .= hash_hmac('sha1', $cookie_data, $this->encryption_key);
		$expire = ($this->sess_expire_on_close === TRUE) ? 0 : $this->sess_expiration + time();
		
		setcookie(
			$this->sess_cookie_name,
			$cookie_data,
			$expire,
			$this->cookie_path,
			$this->cookie_domain,
			$this->cookie_secure
		);
	}
	function _serialize($data)
	{
		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				if (is_string($val))
				{
					$data[$key] = str_replace('\\', '{{slash}}', $val);
				}
			}
		}
		else
		{
			if (is_string($data))
			{
				$data = str_replace('\\', '{{slash}}', $data);
			}
		}
		return serialize($data);
	}
	function _unserialize($data)
	{
		$data = @unserialize(strip_slashes($data));
		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				if (is_string($val))
				{
					$data[$key] = str_replace('{{slash}}', '\\', $val);
				}
			}
			return $data;
		}
		return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
	}
	function _sess_gc()
	{
		if ($this->sess_use_database != TRUE)
		{
			return;
		}
		srand(time());
		if ((rand() % 100) < $this->gc_probability)
		{
			$expire = $this->now - $this->sess_expiration;
			$this->CI->db->where("last_activity < {$expire}");
			$this->CI->db->delete($this->sess_table_name);
			log_message('debug', 'Session garbage collection performed.');
		}
	}
}