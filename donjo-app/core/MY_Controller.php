<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	/**
     * Common data
     */
    public $user;
    public $settings;
    public $includes;
    public $current_uri;
    public $theme;
    public $template;
    public $error;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
				/* set default theme if not exist */
        if (empty($this->setting->web_theme)) {
        	$this->theme = 'default';
        	$this->theme_folder = 'themes';
        } else {
	        $this->theme = preg_replace("/desa\//","",strtolower($this->setting->web_theme)) ;
	        $this->theme_folder = preg_match("/desa\//", strtolower($this->setting->web_theme)) ? "desa/themes" : "themes";
        }
        // declare main template
        $this->template = "../../{$this->theme_folder}/{$this->theme}/template.php";
		}

		// --------------------------------------------------------------------

		function set_title( $page_title )
		{
			$this->includes[ 'page_title' ] = $page_title;

			/* check wether page_header has been set or has a value
			* if not, then set page_title as page_header
			*/
			$this->includes[ 'page_header' ] = isset( $this->includes[ 'page_header' ] ) ? $this->includes[ 'page_header' ] : $page_title;
			return $this;
		}

		/* Set Page Header
		 * sometime, we want to have page header different from page title
		 * so, use this function
		 * --------------------------------------
		 * @author	Arif Rahman Hakim
		 * @since	Version 3.0.5
		 * @access	public
		 * @param	string
		 * @return	chained object
		 */

		function set_page_header( $page_header )
		{
			$this->includes[ 'page_header' ] = $page_header;
			return $this;
		}

		/* Set Template
		 * sometime, we want to use different template for different page
		 * for example, 404 template, login template, full-width template, sidebar template, etc.
		 * so, use this function
		 * --------------------------------------
		 * @author	Arif Rahman Hakim
		 * @since	Version 3.1.0
		 * @access	public
		 * @param	string, template file name
		 * @return	chained object
		 */

		function set_template( $template_file = 'template.php' )
		{
			// make sure that $template_file has .php extension
			$template_file = substr( $template_file, -4 ) == '.php' ? $template_file : ( $template_file . ".php" );

	    $template_file_path = FCPATH . $this->theme_folder . '/' . $this->theme . "/" . $template_file;
	    if (is_file($template_file_path))
				$this->template = "../../{$this->theme_folder}/{$this->theme}/{$template_file}";
	    else
	    	$this->template = '../../themes/default/' . $template_file;
		}


		/* Set Breadcumbs
		 * sometime, we want to have Breadcumbs different from page title
		 * so, use this function
		 * --------------------------------------
		 * @author	Ahmad Afandi
		 * @since	Version 0.0.1
		 * @access	public
		 * @param	string
		 * @return	chained object
		 */

}

class Web_Controller extends MY_Controller
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->includes['folder_themes'] = '../../'.$this->theme_folder.'/'.$this->theme;
	}

	// Jika file theme/view tidak ada, gunakan file default/view
	// Supaya tidak semua layout atau partials harus diulangi untuk setiap tema
	public static function fallback_default($theme, $view)
	{
		$view = trim($view, '/');
		$theme_folder = self::get_instance()->theme_folder;
		$theme_view = "../../$theme_folder/$theme/$view";

		if (!is_file(APPPATH .'views/'. $theme_view))
		{
			$theme_view = "../../themes/default/$view";
		}

		return $theme_view;
	}
}

/**
 *
 */
class Admin_Controller extends MY_Controller
{
	public $grup;
	public $CI = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->CI = & get_instance();
 		$this->controller = strtolower($this->router->fetch_class());
		$this->load->model('user_model');
		$this->grup	= $this->user_model->sesi_grup($_SESSION['sesi']);

		$this->load->model('modul_model');
		if (!$this->modul_model->modul_aktif($this->controller))
		{
			session_error("Fitur ini tidak aktif");
			redirect('/');
		}
		if (!$this->user_model->hak_akses($this->grup, $this->controller, 'b'))
		{
			if (empty($this->grup))
			{
				$_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
				redirect('siteman');
			}
			else
			{
				session_error("Anda tidak mempunyai akses pada fitur ini");
				unset($_SESSION['request_uri']);
				redirect('/');
			}
		}
	}

	protected function redirect_hak_akses($akses, $redirect='', $controller='')
	{
		if (empty($controller))
			$controller = $this->controller;
		if (!$this->user_model->hak_akses($this->grup, $controller, $akses))
		{
			session_error("Anda tidak mempunyai akses pada fitur ini");
			if (empty($this->grup)) redirect('siteman');
			empty($redirect) ? redirect('/') : redirect($redirect);
		}
	}

	public function cek_hak_akses($akses, $controller='')
	{
		if (empty($controller))
			$controller = $this->controller;
		return $this->user_model->hak_akses($this->grup, $controller, $akses);
	}
}

