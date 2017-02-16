<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Loader {
	protected $_ci_ob_level;
	protected $_ci_view_paths		= array();
	protected $_ci_library_paths	= array();
	protected $_ci_model_paths		= array();
	protected $_ci_helper_paths		= array();
	protected $_base_classes		= array(); 
	protected $_ci_cached_vars		= array();
	protected $_ci_classes			= array();
	protected $_ci_loaded_files		= array();
	protected $_ci_models			= array();
	protected $_ci_helpers			= array();
	protected $_ci_varmap			= array('unit_test' => 'unit',
											'user_agent' => 'agent');
	public function __construct()
	{
		$this->_ci_ob_level  = ob_get_level();
		$this->_ci_library_paths = array(APPPATH, BASEPATH);
		$this->_ci_helper_paths = array(APPPATH, BASEPATH);
		$this->_ci_model_paths = array(APPPATH);
		$this->_ci_view_paths = array(APPPATH.'views/'	=> TRUE);
		log_message('debug', "Loader Class Initialized");
	}
	public function initialize()
	{
		$this->_ci_classes = array();
		$this->_ci_loaded_files = array();
		$this->_ci_models = array();
		$this->_base_classes =& is_loaded();
		$this->_ci_autoloader();
		return $this;
	}
	public function is_loaded($class)
	{
		if (isset($this->_ci_classes[$class]))
		{
			return $this->_ci_classes[$class];
		}
		return FALSE;
	}
	public function library($library = '', $params = NULL, $object_name = NULL)
	{
		if (is_array($library))
		{
			foreach ($library as $class)
			{
				$this->library($class, $params);
			}
			return;
		}
		if ($library == '' OR isset($this->_base_classes[$library]))
		{
			return FALSE;
		}
		if ( ! is_null($params) && ! is_array($params))
		{
			$params = NULL;
		}
		$this->_ci_load_class($library, $params, $object_name);
	}
	public function model($model, $name = '', $db_conn = FALSE)
	{
		if (is_array($model))
		{
			foreach ($model as $babe)
			{
				$this->model($babe);
			}
			return;
		}
		if ($model == '')
		{
			return;
		}
		$path = '';
		
		if (($last_slash = strrpos($model, '/')) !== FALSE)
		{
			
			$path = substr($model, 0, $last_slash + 1);
			
			$model = substr($model, $last_slash + 1);
		}
		if ($name == '')
		{
			$name = $model;
		}
		if (in_array($name, $this->_ci_models, TRUE))
		{
			return;
		}
		$CI =& get_instance();
		if (isset($CI->$name))
		{
			show_error('The model name you are loading is the name of a resource that is already being used: '.$name);
		}
		$model = strtolower($model);
		foreach ($this->_ci_model_paths as $mod_path)
		{
			if ( ! file_exists($mod_path.'models/'.$path.$model.'.php'))
			{
				continue;
			}
			if ($db_conn !== FALSE AND ! class_exists('CI_DB'))
			{
				if ($db_conn === TRUE)
				{
					$db_conn = '';
				}
				$CI->load->database($db_conn, FALSE, TRUE);
			}
			if ( ! class_exists('CI_Model'))
			{
				load_class('Model', 'core');
			}
			require_once($mod_path.'models/'.$path.$model.'.php');
			$model = ucfirst($model);
			$CI->$name = new $model();
			$this->_ci_models[] = $name;
			return;
		}
		
		show_error('Unable to locate the model you have specified: '.$model);
	}
	public function database($params = '', $return = FALSE, $active_record = NULL)
	{
		
		$CI =& get_instance();
		
		if (class_exists('CI_DB') AND $return == FALSE AND $active_record == NULL AND isset($CI->db) AND is_object($CI->db))
		{
			return FALSE;
		}
		require_once(BASEPATH.'database/DB.php');
		if ($return === TRUE)
		{
			return DB($params, $active_record);
		}
		
		
		$CI->db = '';
		
		$CI->db =& DB($params, $active_record);
	}
	public function dbutil()
	{
		if ( ! class_exists('CI_DB'))
		{
			$this->database();
		}
		$CI =& get_instance();
		
		
		$CI->load->dbforge();
		require_once(BASEPATH.'database/DB_utility.php');
		require_once(BASEPATH.'database/drivers/'.$CI->db->dbdriver.'/'.$CI->db->dbdriver.'_utility.php');
		$class = 'CI_DB_'.$CI->db->dbdriver.'_utility';
		$CI->dbutil = new $class();
	}
	public function dbforge()
	{
		if ( ! class_exists('CI_DB'))
		{
			$this->database();
		}
		$CI =& get_instance();
		require_once(BASEPATH.'database/DB_forge.php');
		require_once(BASEPATH.'database/drivers/'.$CI->db->dbdriver.'/'.$CI->db->dbdriver.'_forge.php');
		$class = 'CI_DB_'.$CI->db->dbdriver.'_forge';
		$CI->dbforge = new $class();
	}
	public function view($view, $vars = array(), $return = FALSE)
	{
		return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
	}
	public function file($path, $return = FALSE)
	{
		return $this->_ci_load(array('_ci_path' => $path, '_ci_return' => $return));
	}
	public function vars($vars = array(), $val = '')
	{
		if ($val != '' AND is_string($vars))
		{
			$vars = array($vars => $val);
		}
		$vars = $this->_ci_object_to_array($vars);
		if (is_array($vars) AND count($vars) > 0)
		{
			foreach ($vars as $key => $val)
			{
				$this->_ci_cached_vars[$key] = $val;
			}
		}
	}
	public function get_var($key)
	{
		return isset($this->_ci_cached_vars[$key]) ? $this->_ci_cached_vars[$key] : NULL;
	}
	public function helper($helpers = array())
	{
		foreach ($this->_ci_prep_filename($helpers, '_helper') as $helper)
		{
			if (isset($this->_ci_helpers[$helper]))
			{
				continue;
			}
			$ext_helper = APPPATH.'helpers/'.config_item('subclass_prefix').$helper.'.php';
			
			if (file_exists($ext_helper))
			{
				$base_helper = BASEPATH.'helpers/'.$helper.'.php';
				if ( ! file_exists($base_helper))
				{
					show_error('Unable to load the requested file: helpers/'.$helper.'.php');
				}
				include_once($ext_helper);
				include_once($base_helper);
				$this->_ci_helpers[$helper] = TRUE;
				log_message('debug', 'Helper loaded: '.$helper);
				continue;
			}
			
			foreach ($this->_ci_helper_paths as $path)
			{
				if (file_exists($path.'helpers/'.$helper.'.php'))
				{
					include_once($path.'helpers/'.$helper.'.php');
					$this->_ci_helpers[$helper] = TRUE;
					log_message('debug', 'Helper loaded: '.$helper);
					break;
				}
			}
			
			if ( ! isset($this->_ci_helpers[$helper]))
			{
				show_error('Unable to load the requested file: helpers/'.$helper.'.php');
			}
		}
	}
	public function helpers($helpers = array())
	{
		$this->helper($helpers);
	}
	public function language($file = array(), $lang = '')
	{
		$CI =& get_instance();
		if ( ! is_array($file))
		{
			$file = array($file);
		}
		foreach ($file as $langfile)
		{
			$CI->lang->load($langfile, $lang);
		}
	}
	public function config($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		$CI =& get_instance();
		$CI->config->load($file, $use_sections, $fail_gracefully);
	}
	public function driver($library = '', $params = NULL, $object_name = NULL)
	{
		if ( ! class_exists('CI_Driver_Library'))
		{
			
			require BASEPATH.'libraries/Driver.php';
		}
		if ($library == '')
		{
			return FALSE;
		}
		
		
		if ( ! strpos($library, '/'))
		{
			$library = ucfirst($library).'/'.$library;
		}
		return $this->library($library, $params, $object_name);
	}
	public function add_package_path($path, $view_cascade=TRUE)
	{
		$path = rtrim($path, '/').'/';
		array_unshift($this->_ci_library_paths, $path);
		array_unshift($this->_ci_model_paths, $path);
		array_unshift($this->_ci_helper_paths, $path);
		$this->_ci_view_paths = array($path.'views/' => $view_cascade) + $this->_ci_view_paths;
		
		$config =& $this->_ci_get_component('config');
		array_unshift($config->_config_paths, $path);
	}
	public function get_package_paths($include_base = FALSE)
	{
		return $include_base === TRUE ? $this->_ci_library_paths : $this->_ci_model_paths;
	}
	public function remove_package_path($path = '', $remove_config_path = TRUE)
	{
		$config =& $this->_ci_get_component('config');
		if ($path == '')
		{
			$void = array_shift($this->_ci_library_paths);
			$void = array_shift($this->_ci_model_paths);
			$void = array_shift($this->_ci_helper_paths);
			$void = array_shift($this->_ci_view_paths);
			$void = array_shift($config->_config_paths);
		}
		else
		{
			$path = rtrim($path, '/').'/';
			foreach (array('_ci_library_paths', '_ci_model_paths', '_ci_helper_paths') as $var)
			{
				if (($key = array_search($path, $this->{$var})) !== FALSE)
				{
					unset($this->{$var}[$key]);
				}
			}
			if (isset($this->_ci_view_paths[$path.'views/']))
			{
				unset($this->_ci_view_paths[$path.'views/']);
			}
			if (($key = array_search($path, $config->_config_paths)) !== FALSE)
			{
				unset($config->_config_paths[$key]);
			}
		}
		
		$this->_ci_library_paths = array_unique(array_merge($this->_ci_library_paths, array(APPPATH, BASEPATH)));
		$this->_ci_helper_paths = array_unique(array_merge($this->_ci_helper_paths, array(APPPATH, BASEPATH)));
		$this->_ci_model_paths = array_unique(array_merge($this->_ci_model_paths, array(APPPATH)));
		$this->_ci_view_paths = array_merge($this->_ci_view_paths, array(APPPATH.'views/' => TRUE));
		$config->_config_paths = array_unique(array_merge($config->_config_paths, array(APPPATH)));
	}
	protected function _ci_load($_ci_data)
	{
		
		foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val)
		{
			$$_ci_val = ( ! isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
		}
		$file_exists = FALSE;
		
		if ($_ci_path != '')
		{
			$_ci_x = explode('/', $_ci_path);
			$_ci_file = end($_ci_x);
		}
		else
		{
			$_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
			$_ci_file = ($_ci_ext == '') ? $_ci_view.'.php' : $_ci_view;
			foreach ($this->_ci_view_paths as $view_file => $cascade)
			{
				if (file_exists($view_file.$_ci_file))
				{
					$_ci_path = $view_file.$_ci_file;
					$file_exists = TRUE;
					break;
				}
				if ( ! $cascade)
				{
					break;
				}
			}
		}
		if ( ! $file_exists && ! file_exists($_ci_path))
		{
			show_error('Unable to load the requested file: '.$_ci_file);
		}
		
		
		$_ci_CI =& get_instance();
		foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var)
		{
			if ( ! isset($this->$_ci_key))
			{
				$this->$_ci_key =& $_ci_CI->$_ci_key;
			}
		}
		
		if (is_array($_ci_vars))
		{
			$this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
		}
		extract($this->_ci_cached_vars);
		
		ob_start();
		
		
		
		if ((bool) @ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE)
		{
			echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
		}
		else
		{
			include($_ci_path); 
		}
		log_message('debug', 'File loaded: '.$_ci_path);
		
		if ($_ci_return === TRUE)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}
		
		if (ob_get_level() > $this->_ci_ob_level + 1)
		{
			ob_end_flush();
		}
		else
		{
			$_ci_CI->output->append_output(ob_get_contents());
			@ob_end_clean();
		}
	}
	protected function _ci_load_class($class, $params = NULL, $object_name = NULL)
	{
		
		
		
		$class = str_replace('.php', '', trim($class, '/'));
		
		
		$subdir = '';
		if (($last_slash = strrpos($class, '/')) !== FALSE)
		{
			
			$subdir = substr($class, 0, $last_slash + 1);
			
			$class = substr($class, $last_slash + 1);
		}
		
		foreach (array(ucfirst($class), strtolower($class)) as $class)
		{
			$subclass = APPPATH.'libraries/'.$subdir.config_item('subclass_prefix').$class.'.php';
			
			if (file_exists($subclass))
			{
				$baseclass = BASEPATH.'libraries/'.ucfirst($class).'.php';
				if ( ! file_exists($baseclass))
				{
					log_message('error', "Unable to load the requested class: ".$class);
					show_error("Unable to load the requested class: ".$class);
				}
				
				if (in_array($subclass, $this->_ci_loaded_files))
				{
					
					
					
					if ( ! is_null($object_name))
					{
						$CI =& get_instance();
						if ( ! isset($CI->$object_name))
						{
							return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
						}
					}
					$is_duplicate = TRUE;
					log_message('debug', $class." class already loaded. Second attempt ignored.");
					return;
				}
				include_once($baseclass);
				include_once($subclass);
				$this->_ci_loaded_files[] = $subclass;
				return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
			}
			
			$is_duplicate = FALSE;
			foreach ($this->_ci_library_paths as $path)
			{
				$filepath = $path.'libraries/'.$subdir.$class.'.php';
				
				if ( ! file_exists($filepath))
				{
					continue;
				}
				
				if (in_array($filepath, $this->_ci_loaded_files))
				{
					
					
					
					if ( ! is_null($object_name))
					{
						$CI =& get_instance();
						if ( ! isset($CI->$object_name))
						{
							return $this->_ci_init_class($class, '', $params, $object_name);
						}
					}
					$is_duplicate = TRUE;
					log_message('debug', $class." class already loaded. Second attempt ignored.");
					return;
				}
				include_once($filepath);
				$this->_ci_loaded_files[] = $filepath;
				return $this->_ci_init_class($class, '', $params, $object_name);
			}
		} 
		
		if ($subdir == '')
		{
			$path = strtolower($class).'/'.$class;
			return $this->_ci_load_class($path, $params);
		}
		
		
		if ($is_duplicate == FALSE)
		{
			log_message('error', "Unable to load the requested class: ".$class);
			show_error("Unable to load the requested class: ".$class);
		}
	}
	protected function _ci_init_class($class, $prefix = '', $config = FALSE, $object_name = NULL)
	{
		
		if ($config === NULL)
		{
			
			$config_component = $this->_ci_get_component('config');
			if (is_array($config_component->_config_paths))
			{
				
				
				foreach ($config_component->_config_paths as $path)
				{
					
					
					
					if (defined('ENVIRONMENT') AND file_exists($path .'config/'.ENVIRONMENT.'/'.strtolower($class).'.php'))
					{
						include($path .'config/'.ENVIRONMENT.'/'.strtolower($class).'.php');
						break;
					}
					elseif (defined('ENVIRONMENT') AND file_exists($path .'config/'.ENVIRONMENT.'/'.ucfirst(strtolower($class)).'.php'))
					{
						include($path .'config/'.ENVIRONMENT.'/'.ucfirst(strtolower($class)).'.php');
						break;
					}
					elseif (file_exists($path .'config/'.strtolower($class).'.php'))
					{
						include($path .'config/'.strtolower($class).'.php');
						break;
					}
					elseif (file_exists($path .'config/'.ucfirst(strtolower($class)).'.php'))
					{
						include($path .'config/'.ucfirst(strtolower($class)).'.php');
						break;
					}
				}
			}
		}
		if ($prefix == '')
		{
			if (class_exists('CI_'.$class))
			{
				$name = 'CI_'.$class;
			}
			elseif (class_exists(config_item('subclass_prefix').$class))
			{
				$name = config_item('subclass_prefix').$class;
			}
			else
			{
				$name = $class;
			}
		}
		else
		{
			$name = $prefix.$class;
		}
		
		if ( ! class_exists($name))
		{
			log_message('error', "Non-existent class: ".$name);
			show_error("Non-existent class: ".$class);
		}
		
		
		$class = strtolower($class);
		if (is_null($object_name))
		{
			$classvar = ( ! isset($this->_ci_varmap[$class])) ? $class : $this->_ci_varmap[$class];
		}
		else
		{
			$classvar = $object_name;
		}
		
		$this->_ci_classes[$class] = $classvar;
		
		$CI =& get_instance();
		if ($config !== NULL)
		{
			$CI->$classvar = new $name($config);
		}
		else
		{
			$CI->$classvar = new $name;
		}
	}
	private function _ci_autoloader()
	{
		if (defined('ENVIRONMENT') AND file_exists(APPPATH.'config/'.ENVIRONMENT.'/autoload.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/autoload.php');
		}
		else
		{
			include(APPPATH.'config/autoload.php');
		}
		if ( ! isset($autoload))
		{
			return FALSE;
		}
		
		if (isset($autoload['packages']))
		{
			foreach ($autoload['packages'] as $package_path)
			{
				$this->add_package_path($package_path);
			}
		}
		
		if (count($autoload['config']) > 0)
		{
			$CI =& get_instance();
			foreach ($autoload['config'] as $key => $val)
			{
				$CI->config->load($val);
			}
		}
		
		foreach (array('helper', 'language') as $type)
		{
			if (isset($autoload[$type]) AND count($autoload[$type]) > 0)
			{
				$this->$type($autoload[$type]);
			}
		}
		
		
		if ( ! isset($autoload['libraries']) AND isset($autoload['core']))
		{
			$autoload['libraries'] = $autoload['core'];
		}
		
		if (isset($autoload['libraries']) AND count($autoload['libraries']) > 0)
		{
			
			if (in_array('database', $autoload['libraries']))
			{
				$this->database();
				$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
			}
			
			foreach ($autoload['libraries'] as $item)
			{
				$this->library($item);
			}
		}
		
		if (isset($autoload['model']))
		{
			$this->model($autoload['model']);
		}
	}
	protected function _ci_object_to_array($object)
	{
		return (is_object($object)) ? get_object_vars($object) : $object;
	}
	protected function &_ci_get_component($component)
	{
		$CI =& get_instance();
		return $CI->$component;
	}
	protected function _ci_prep_filename($filename, $extension)
	{
		if ( ! is_array($filename))
		{
			return array(strtolower(str_replace('.php', '', str_replace($extension, '', $filename)).$extension));
		}
		else
		{
			foreach ($filename as $key => $val)
			{
				$filename[$key] = strtolower(str_replace('.php', '', str_replace($extension, '', $val)).$extension);
			}
			return $filename;
		}
	}
}