<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    /**
     * Common data
     */
    public $user;
    public $settings;
    public $includes;
    public $current_uri;
    public $theme;
	public $template;
    public $template_dir;
    public $error;

    /**
     * Constructor
     */
    function __construct() {
        load_class('Hooks', 'core')->append('post_loader_init', array($this, 'set_theme'));
        parent::__construct();
    }

    function set_theme() {
        /* set default theme if not exist */
        if (empty($this->setting->web_theme)) {
            $this->theme = 'default';
            $this->theme_folder = 'themes';
        } else {
            $this->theme = preg_replace("/desa\//", "", strtolower($this->setting->web_theme));
            $this->theme_folder = preg_match("/desa\//", strtolower($this->setting->web_theme)) ? "desa/themes" : "themes";
        }

        $this->default_template_dir = FCPATH . $this->theme_folder . '/default';
        $this->template_dir = FCPATH . $this->theme_folder . '/' . $this->theme;
		$this->set_template();
    }

    // --------------------------------------------------------------------

    function set_title($page_title) {
        $this->includes['page_title'] = $page_title;

        /* check wether page_header has been set or has a value
         * if not, then set page_title as page_header
         */
        $this->includes['page_header'] = isset($this->includes['page_header']) ? $this->includes['page_header'] : $page_title;
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
    function set_page_header($page_header) {
        $this->includes['page_header'] = $page_header;
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
    function set_template($template_file = 'template.php') {
        $this->template = '/'. $template_file;
    }
}

class Web_Controller extends MY_Controller
{
    // Jika file theme/view tidak ada, gunakan file default/view
    // Supaya tidak semua layout atau partials harus diulangi untuk setiap tema
    function fallback_default($theme, $view) {
        if (is_object($this)) {
            $theme_folder = $this->theme_folder;
        } else {
            $tmp = new self();
            $theme_folder = $tmp->theme_folder;
        }

        $theme_file = FCPATH . $theme_folder . '/' . $theme . $view;
        if (!is_file($theme_file)) $theme_view = '../../themes/default' . $view;
        else $theme_view = '../../' . $theme_folder . '/' . $theme . $view;
        return $theme_view;
    }
}
