<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Security {
	protected $_xss_hash			= '';
	protected $_csrf_hash			= '';
	protected $_csrf_expire			= 7200;
	protected $_csrf_token_name		= 'ci_csrf_token';
	protected $_csrf_cookie_name	= 'ci_csrf_token';
	protected $_never_allowed_str = array(
		'document.cookie'	=> '[removed]',
		'document.write'	=> '[removed]',
		'.parentNode'		=> '[removed]',
		'.innerHTML'		=> '[removed]',
		'-moz-binding'		=> '[removed]',
		'<!--'				=> '&lt;!--',
		'-->'				=> '--&gt;',
		'<![CDATA['			=> '&lt;![CDATA[',
		'<comment>'			=> '&lt;comment&gt;'
	);
	protected $_never_allowed_regex = array(
		'javascript\s*:',
		'(document|(document\.)?window)\.(location|on\w*)',
		'expression\s*(\(|&\#40;)', 
		'vbscript\s*:', 
		'wscript\s*:', 
		'jscript\s*:', 
		'vbs\s*:', 
		'Redirect\s+30\d:',
		"([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
	);
	public function __construct()
	{
		
		if (config_item('csrf_protection') === TRUE)
		{
			
			foreach (array('csrf_expire', 'csrf_token_name', 'csrf_cookie_name') as $key)
			{
				if (FALSE !== ($val = config_item($key)))
				{
					$this->{'_'.$key} = $val;
				}
			}
			
			if (config_item('cookie_prefix'))
			{
				$this->_csrf_cookie_name = config_item('cookie_prefix').$this->_csrf_cookie_name;
			}
			
			$this->_csrf_set_hash();
		}
		log_message('debug', "Security Class Initialized");
	}
	public function csrf_verify()
	{
		
		if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST')
		{
			return $this->csrf_set_cookie();
		}
		
		if ( ! isset($_POST[$this->_csrf_token_name], $_COOKIE[$this->_csrf_cookie_name]))
		{
			$this->csrf_show_error();
		}
		
		if ($_POST[$this->_csrf_token_name] != $_COOKIE[$this->_csrf_cookie_name])
		{
			$this->csrf_show_error();
		}
		
		
		unset($_POST[$this->_csrf_token_name]);
		
		unset($_COOKIE[$this->_csrf_cookie_name]);
		$this->_csrf_set_hash();
		$this->csrf_set_cookie();
		log_message('debug', 'CSRF token verified');
		return $this;
	}
	public function csrf_set_cookie()
	{
		$expire = time() + $this->_csrf_expire;
		$secure_cookie = (config_item('cookie_secure') === TRUE) ? 1 : 0;
		if ($secure_cookie && (empty($_SERVER['HTTPS']) OR strtolower($_SERVER['HTTPS']) === 'off'))
		{
			return FALSE;
		}
		setcookie($this->_csrf_cookie_name, $this->_csrf_hash, $expire, config_item('cookie_path'), config_item('cookie_domain'), $secure_cookie);
		log_message('debug', "CRSF cookie Set");
		return $this;
	}
	public function csrf_show_error()
	{
		show_error('The action you have requested is not allowed.');
	}
	public function get_csrf_hash()
	{
		return $this->_csrf_hash;
	}
	public function get_csrf_token_name()
	{
		return $this->_csrf_token_name;
	}
	public function xss_clean($str, $is_image = FALSE)
	{
		
		if (is_array($str))
		{
			while (list($key) = each($str))
			{
				$str[$key] = $this->xss_clean($str[$key]);
			}
			return $str;
		}
		
		$str = remove_invisible_characters($str);
		
		do
		{
			$str = rawurldecode($str);
		}
		while (preg_match('/%[0-9a-f]{2,}/i', $str));
		
		$str = preg_replace_callback("/[^a-z0-9>]+[a-z0-9]+=([\'\"]).*?\\1/si", array($this, '_convert_attribute'), $str);
		$str = preg_replace_callback('/<\w+.
si', array($this, '_decode_entity'), $str);
		
		$str = remove_invisible_characters($str);
		
		$str = str_replace("\t", ' ', $str);
		
		$converted_string = $str;
		
		$str = $this->_do_never_allowed($str);
		
		if ($is_image === TRUE)
		{
			
			
			
			$str = preg_replace('/<\?(php)/i', '&lt;?\\1', $str);
		}
		else
		{
			$str = str_replace(array('<?', '?'.'>'), array('&lt;?', '?&gt;'), $str);
		}
		
		$words = array(
			'javascript', 'expression', 'vbscript', 'jscript', 'wscript',
			'vbs', 'script', 'base64', 'applet', 'alert', 'document',
			'write', 'cookie', 'window', 'confirm', 'prompt', 'eval'
		);
		foreach ($words as $word)
		{
			$word = implode('\s*', str_split($word)).'\s*';
			
			
			$str = preg_replace_callback('#('.substr($word, 0, -3).')(\W)#is', array($this, '_compact_exploded_words'), $str);
		}
		
		do
		{
			$original = $str;

			if (preg_match('/<a/i', $str))
			{
				$str = preg_replace_callback('#<a[^a-z0-9>]+([^>]*?)(?:>|$)#si', array($this, '_js_link_removal'), $str);
			}

			if (preg_match('/<img/i', $str))
			{
				$str = preg_replace_callback('#<img[^a-z0-9]+([^>]*?)(?:\s?/?>|$)#si', array($this, '_js_img_removal'), $str);
			}

			if (preg_match('/script|xss/i', $str))
			{
				$str = preg_replace('#</*(?:script|xss).*?>#si', '[removed]', $str);
			}
		}
		while($original !== $str);
		unset($original);
		$pattern = '#'
			.'<((?<slash>/*\s*)(?<tagName>[a-z0-9]+)(?=[^a-z0-9]|$)' // tag start and name, followed by a non-tag character
			.'[^\s\042\047a-z0-9>/=]*' // a valid attribute character immediately after the tag would count as a separator
			// optional attributes
			.'(?<attributes>(?:[\s\042\047/=]*' // non-attribute characters, excluding > (tag close) for obvious reasons
			.'[^\s\042\047>/=]+' // attribute characters
			// optional attribute-value
				.'(?:\s*=' // attribute-value separator
					.'(?:[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*))' // single, double or non-quoted value
				.')?' // end optional attribute-value group
			.')*)' // end optional attributes group
			.'[^>]*)(?<closeTag>\>)?#isS';

		do
		{
			$old_str = $str;
			$str = preg_replace_callback($pattern, array($this, '_sanitize_naughty_html'), $str);
		}
		while ($old_str !== $str);
		unset($old_str);

		/*
		 * Sanitize naughty scripting elements
		 *
		 * Similar to above, only instead of looking for
		 * tags it looks for PHP and JavaScript commands
		 * that are disallowed.  Rather than removing the
		 * code, it simply converts the parenthesis to entities
		 * rendering the code un-executable.
		 *
		 * For example:	eval('some code')
		 * Becomes:		eval&#40;'some code'&#41;
		 */
		$str = preg_replace(
			'#(alert|prompt|confirm|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si',
			'\\1\\2&#40;\\3&#41;',
			$str
		);
		$str = $this->_do_never_allowed($str);
		
		if ($is_image === TRUE)
		{
			return ($str === $converted_string);
		}
		log_message('debug', "XSS Filtering completed");
		return $str;
	}
	public function xss_hash()
	{
		if ($this->_xss_hash == '')
		{
			mt_srand();
			$this->_xss_hash = md5(time() + mt_rand(0, 1999999999));
		}
		return $this->_xss_hash;
	}
	public function entity_decode($str, $charset='UTF-8')
	{
		if (strpos($str, '&') === FALSE)
		{
			return $str;
		}
		static $_entities;
		isset($charset) OR $charset = strtoupper(config_item('charset'));
		$flag = is_php('5.4')
			? ENT_COMPAT | ENT_HTML5
			: ENT_COMPAT;
		do
		{
			$str_compare = $str;
			
			if (preg_match_all('/\&[a-z]{2,}(?![a-z;])/i', $str, $matches))
			{
				if ( ! isset($_entities))
				{
					$_entities = array_map(
						'strtolower',
						is_php('5.3.4')
							? get_html_translation_table(HTML_ENTITIES, $flag, $charset)
							: get_html_translation_table(HTML_ENTITIES, $flag)
					);
					
					
					if ($flag === ENT_COMPAT)
					{
						$_entities[':'] = '&colon;';
						$_entities['('] = '&lpar;';
						$_entities[')'] = '&rpar;';
						$_entities["\n"] = '&newline;';
						$_entities["\t"] = '&tab;';
					}
				}
				$replace = array();
				$matches = array_unique(array_map('strtolower', $matches[0]));
				foreach ($matches as &$match)
				{
					if (($char = array_search($match.';', $_entities, TRUE)) !== FALSE)
					{
						$replace[$match] = $char;
					}
				}
				$str = str_ireplace(array_keys($replace), array_values($replace), $str);
			}
			
			$str = html_entity_decode(
				preg_replace('/(&#(?:x0*[0-9a-f]{2,5}(?![0-9a-f;])|(?:0*\d{2,4}(?![0-9;]))))/iS', '$1;', $str),
				$flag,
				$charset
			);
		}
		while ($str_compare !== $str);
		return $str;
	}
	public function sanitize_filename($str, $relative_path = FALSE)
	{
		$bad = array(
			'../', '<!--', '-->', '<', '>',
			"'", '"', '&', '$', '#',
			'{', '}', '[', ']', '=',
			';', '?', '%20', '%22',
			'%3c',		
			'%253c',	
			'%3e',		
			'%0e',		
			'%28',		
			'%29',		
			'%2528',	
			'%26',		
			'%24',		
			'%3f',		
			'%3b',		
			'%3d'		
		);
		if ( ! $relative_path)
		{
			$bad[] = './';
			$bad[] = '/';
		}
		$str = remove_invisible_characters($str, FALSE);
		do
		{
			$old = $str;
			$str = str_replace($bad, '', $str);
		}
		while ($old !== $str);
		return stripslashes($str);
	}
	protected function _compact_exploded_words($matches)
	{
		return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
	}
	protected function _sanitize_naughty_html($matches)
	{
		static $naughty_tags    = array(
			'alert', 'prompt', 'confirm', 'applet', 'audio', 'basefont', 'base', 'behavior', 'bgsound',
			'blink', 'body', 'embed', 'expression', 'form', 'frameset', 'frame', 'head', 'html', 'ilayer',
			'iframe', 'input', 'button', 'select', 'isindex', 'layer', 'link', 'meta', 'keygen', 'object',
			'plaintext', 'style', 'script', 'textarea', 'title', 'math', 'video', 'svg', 'xml', 'xss'
		);
		static $evil_attributes = array(
			'on\w+', 'style', 'xmlns', 'formaction', 'form', 'xlink:href', 'FSCommand', 'seekSegmentTime'
		);
		
		if (empty($matches['closeTag']))
		{
			return '&lt;'.$matches[1];
		}
		
		elseif (in_array(strtolower($matches['tagName']), $naughty_tags, TRUE))
		{
			return '&lt;'.$matches[1].'&gt;';
		}
		
		elseif (isset($matches['attributes']))
		{
			
			$attributes = array();
			
			$attributes_pattern = '#'
				.'(?<name>[^\s\042\047>/=]+)' 
				
				.'(?:\s*=(?<value>[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*)))' 
				.'#i';
			
			$is_evil_pattern = '#^('.implode('|', $evil_attributes).')$#i';
			
			do
			{
				
				
				
				$matches['attributes'] = preg_replace('#^[^a-z]+#i', '', $matches['attributes']);
				if ( ! preg_match($attributes_pattern, $matches['attributes'], $attribute, PREG_OFFSET_CAPTURE))
				{
					
					break;
				}
				if (
					
					preg_match($is_evil_pattern, $attribute['name'][0])
					
					OR (trim($attribute['value'][0]) === '')
				)
				{
					$attributes[] = 'xss=removed';
				}
				else
				{
					$attributes[] = $attribute[0][0];
				}
				$matches['attributes'] = substr($matches['attributes'], $attribute[0][1] + strlen($attribute[0][0]));
			}
			while ($matches['attributes'] !== '');
			$attributes = empty($attributes)
				? ''
				: ' '.implode(' ', $attributes);
			return '<'.$matches['slash'].$matches['tagName'].$attributes.'>';
		}
		return $matches[0];
	}
	protected function _js_link_removal($match)
	{
		return str_replace(
			$match[1],
			preg_replace(
				'#href=.*?(?:(?:alert|prompt|confirm)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|data\s*:)#si',
				'',
				$this->_filter_attributes($match[1])
			),
			$match[0]
		);
	}
	protected function _js_img_removal($match)
	{
		return str_replace(
			$match[1],
			preg_replace(
				'#src=.*?(?:(?:alert|prompt|confirm|eval)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si',
				'',
				$this->_filter_attributes($match[1])
			),
			$match[0]
		);
	}
	protected function _convert_attribute($match)
	{
		return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
	}
	protected function _filter_attributes($str)
	{
		$out = '';
		if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches))
		{
			foreach ($matches[0] as $match)
			{
				$out .= preg_replace("#/\*.*?\
#s", '', $match);
			}
		}
		return $out;
	}
	protected function _decode_entity($match)
	{
		
		
		$match = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-/]+)|i', $this->xss_hash().'\\1=\\2', $match[0]);
		
		return str_replace(
			$this->xss_hash(),
			'&',
			$this->entity_decode($match, strtoupper(config_item('charset')))
		);
	}
	protected function _do_never_allowed($str)
	{
		$str = str_replace(array_keys($this->_never_allowed_str), $this->_never_allowed_str, $str);
		foreach ($this->_never_allowed_regex as $regex)
		{
			$str = preg_replace('#'.$regex.'#is', '[removed]', $str);
		}
		return $str;
	}
	protected function _csrf_set_hash()
	{
		if ($this->_csrf_hash == '')
		{
			
			
			
			
			if (isset($_COOKIE[$this->_csrf_cookie_name]) &&
				preg_match('#^[0-9a-f]{32}$#iS', $_COOKIE[$this->_csrf_cookie_name]) === 1)
			{
				return $this->_csrf_hash = $_COOKIE[$this->_csrf_cookie_name];
			}
			return $this->_csrf_hash = md5(uniqid(rand(), TRUE));
		}
		return $this->_csrf_hash;
	}
}