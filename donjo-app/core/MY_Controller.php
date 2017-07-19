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
        $this->theme = strtolower($this->setting->web_theme) ;
		/* set default theme if not exist */
		$this->theme = empty($this->theme) ? 'default' : $this->theme;
        // declare main template
        $this->template = "../../themes/{$this->theme}/template.php";
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

    $template_file_path = FCPATH . 'themes/' . $this->theme . "/" . $template_file;
    if (is_file($template_file_path))
			$this->template = "../../themes/{$this->theme}/{$template_file}";
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

class Web_Controller extends MY_Controller {
	/**
  	* Constructor
 	*/
	function __construct()
  {
    parent::__construct();
		$this->includes['folder_themes'] = '../../themes/'.$this->theme;
  }

  // Jika file theme/view tidak ada, gunakan file default/view
  // Supaya tidak semua layout atau partials harus diulangi untuk setiap tema
  function fallback_default($theme, $view)
  {
    $theme_file = FCPATH . 'themes/' . $theme . $view;
    if (!is_file($theme_file)) $theme_view = '../../themes/default' . $view;
    else $theme_view = '../../themes/' . $theme . $view;
    return $theme_view;
  }

}
