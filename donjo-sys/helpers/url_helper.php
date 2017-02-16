<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('site_url'))
{
	function site_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->site_url($uri);
	}
}
if ( ! function_exists('demode'))
{
	function demode()
	{
		$demode = "1";
		
		return $demode;
	}
}
if ( ! function_exists('base_url'))
{
	function base_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->base_url($uri);
	}
}
if ( ! function_exists('current_url'))
{
	function current_url()
	{
		$CI =& get_instance();
		return $CI->config->site_url($CI->uri->uri_string());
	}
}
if ( ! function_exists('uri_string'))
{
	function uri_string()
	{
		$CI =& get_instance();
		return $CI->uri->uri_string();
	}
}
if ( ! function_exists('index_page'))
{
	function index_page()
	{
		$CI =& get_instance();
		return $CI->config->item('index_page');
	}
}
if ( ! function_exists('anchor'))
{
	function anchor($uri = '', $title = '', $attributes = '')
	{
		$title = (string) $title;
		if ( ! is_array($uri))
		{
			$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? site_url($uri) : $uri;
		}
		else
		{
			$site_url = site_url($uri);
		}
		if ($title == '')
		{
			$title = $site_url;
		}
		if ($attributes != '')
		{
			$attributes = _parse_attributes($attributes);
		}
		return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
	}
}
if ( ! function_exists('anchor_popup'))
{
	function anchor_popup($uri = '', $title = '', $attributes = FALSE)
	{
		$title = (string) $title;
		$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? site_url($uri) : $uri;
		if ($title == '')
		{
			$title = $site_url;
		}
		if ($attributes === FALSE)
		{
			return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank');\">".$title."</a>";
		}
		if ( ! is_array($attributes))
		{
			$attributes = array();
		}
		foreach (array('width' => '800', 'height' => '600', 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0', ) as $key => $val)
		{
			$atts[$key] = ( ! isset($attributes[$key])) ? $val : $attributes[$key];
			unset($attributes[$key]);
		}
		if ($attributes != '')
		{
			$attributes = _parse_attributes($attributes);
		}
		return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank', '"._parse_attributes($atts, TRUE)."');\"$attributes>".$title."</a>";
	}
}
if ( ! function_exists('mailto'))
{
	function mailto($email, $title = '', $attributes = '')
	{
		$title = (string) $title;
		if ($title == "")
		{
			$title = $email;
		}
		$attributes = _parse_attributes($attributes);
		return '<a href="mailto:'.$email.'"'.$attributes.'>'.$title.'</a>';
	}
}
if ( ! function_exists('safe_mailto'))
{
	function safe_mailto($email, $title = '', $attributes = '')
	{
		$title = (string) $title;
		if ($title == "")
		{
			$title = $email;
		}
		for ($i = 0; $i < 16; $i++)
		{
			$x[] = substr('<a href="mailto:', $i, 1);
		}
		for ($i = 0; $i < strlen($email); $i++)
		{
			$x[] = "|".ord(substr($email, $i, 1));
		}
		$x[] = '"';
		if ($attributes != '')
		{
			if (is_array($attributes))
			{
				foreach ($attributes as $key => $val)
				{
					$x[] =  ' '.$key.'="';
					for ($i = 0; $i < strlen($val); $i++)
					{
						$x[] = "|".ord(substr($val, $i, 1));
					}
					$x[] = '"';
				}
			}
			else
			{
				for ($i = 0; $i < strlen($attributes); $i++)
				{
					$x[] = substr($attributes, $i, 1);
				}
			}
		}
		$x[] = '>';
		$temp = array();
		for ($i = 0; $i < strlen($title); $i++)
		{
			$ordinal = ord($title[$i]);
			if ($ordinal < 128)
			{
				$x[] = "|".$ordinal;
			}
			else
			{
				if (count($temp) == 0)
				{
					$count = ($ordinal < 224) ? 2 : 3;
				}
				$temp[] = $ordinal;
				if (count($temp) == $count)
				{
					$number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);
					$x[] = "|".$number;
					$count = 1;
					$temp = array();
				}
			}
		}
		$x[] = '<'; $x[] = '/'; $x[] = 'a'; $x[] = '>';
		$x = array_reverse($x);
		ob_start();
	?><script type="text/javascript">
	var l=new Array();
	<?php
	$i = 0;
	foreach ($x as $val){ ?>l[<?php echo $i++; ?>]='<?php echo $val; ?>';<?php } ?>
	for (var i = l.length-1; i >= 0; i=i-1){
	if (l[i].substring(0, 1) == '|') document.write("&#"+unescape(l[i].substring(1))+";");
	else document.write(unescape(l[i]));}
	</script><?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}
if ( ! function_exists('auto_link'))
{
	function auto_link($str, $type = 'both', $popup = FALSE)
	{
		if ($type != 'email')
		{
			if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches))
			{
				$pop = ($popup == TRUE) ? " target=\"_blank\" " : "";
				for ($i = 0; $i < count($matches['0']); $i++)
				{
					$period = '';
					if (preg_match("|\.$|", $matches['6'][$i]))
					{
						$period = '.';
						$matches['6'][$i] = substr($matches['6'][$i], 0, -1);
					}
					$str = str_replace($matches['0'][$i],
										$matches['1'][$i].'<a href="http'.
										$matches['4'][$i].'://'.
										$matches['5'][$i].
										$matches['6'][$i].'"'.$pop.'>http'.
										$matches['4'][$i].'://'.
										$matches['5'][$i].
										$matches['6'][$i].'</a>'.
										$period, $str);
				}
			}
		}
		if ($type != 'url')
		{
			if (preg_match_all("/([a-zA-Z0-9_\.\-\+]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches))
			{
				for ($i = 0; $i < count($matches['0']); $i++)
				{
					$period = '';
					if (preg_match("|\.$|", $matches['3'][$i]))
					{
						$period = '.';
						$matches['3'][$i] = substr($matches['3'][$i], 0, -1);
					}
					$str = str_replace($matches['0'][$i], safe_mailto($matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i]).$period, $str);
				}
			}
		}
		return $str;
	}
}
if ( ! function_exists('prep_url'))
{
	function prep_url($str = '')
	{
		if ($str == 'http://' OR $str == '')
		{
			return '';
		}
		$url = parse_url($str);
		if ( ! $url OR ! isset($url['scheme']))
		{
			$str = 'http://'.$str;
		}
		return $str;
	}
}
if ( ! function_exists('url_title'))
{
	function url_title($str, $separator = 'dash', $lowercase = FALSE)
	{
		if ($separator == 'dash')
		{
			$search		= '_';
			$replace	= '-';
		}
		else
		{
			$search		= '-';
			$replace	= '_';
		}
		$trans = array(
						'&\#\d+?;'				=> '',
						'&\S+?;'				=> '',
						'\s+'					=> $replace,
						'[^a-z0-9\-\._]'		=> '',
						$replace.'+'			=> $replace,
						$replace.'$'			=> $replace,
						'^'.$replace			=> $replace,
						'\.+$'					=> ''
					);
		$str = strip_tags($str);
		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}
		if ($lowercase === TRUE)
		{
			$str = strtolower($str);
		}
		return trim(stripslashes($str));
	}
}
if ( ! function_exists('redirect'))
{
	function redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = site_url($uri);
		}
		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}
}
if ( ! function_exists('_parse_attributes'))
{
	function _parse_attributes($attributes, $javascript = FALSE)
	{
		if (is_string($attributes))
		{
			return ($attributes != '') ? ' '.$attributes : '';
		}
		$att = '';
		foreach ($attributes as $key => $val)
		{
			if ($javascript == TRUE)
			{
				$att .= $key . '=' . $val . ',';
			}
			else
			{
				$att .= ' ' . $key . '="' . $val . '"';
			}
		}
		if ($javascript == TRUE AND $att != '')
		{
			$att = substr($att, 0, -1);
		}
		return $att;
	}
}