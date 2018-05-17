<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package     CodeIgniter
 * @category    Libraries
 * @author      Yusup Hambali <supalpuket@gmail.com>
 */

/**
 * CodeIgniter RBAC Class.
 *
 * This class manages controller permissions and user roles.
 */
class Rbac
{
	private $_ci;
	private $_roles = array();
	private $_permissions = array();
	private $_actions = array();
	private $_role_table = 'rbac_user_role';
	private $_action_table = 'rbac_user_action';
	/**
	 * @var int User role id
	 */
	public $role;

	/**
	 * Constructor
	 *
	 * @param array $config Configuration array. The keys allowed are:
	 * - role: user role id
	 * - role_table: role table name used in database
	 * - action_table: action table name used in database
	 * NOTE: role_table and action_table here have not sanitized. If they come from end user you must filter first then.
	 */
	public function __construct($config = array()) {
		$this->_ci = $ci = CI_Controller::get_instance();

		$this->role = (int)$config['role'];

		!empty($config['role_table']) && $this->_role_table = $config['role_table'];

		!empty($config['action_table']) && $this->_action_table = $config['action_table'];

		$this->load_user_role();

		log_message('debug', 'RBAC Class Initialized');
	}

	protected function load_user_role() {
		$sql = "SELECT id,name,action FROM {$this->_role_table} "
		     . "WHERE id=" . (int)$this->role ." OR name='?'"; // '?' is special guest role

		if ($query = $this->_ci->db->query($sql)) {
			$this->_roles = (array)$query->result_object();
		}
	}

	protected function match_role($role_name, $role) {
		if (strcasecmp($role_name, "@{$role->name}") === 0) {
			return $this->_permissions[] = $role_name;
		}
	}

	/**
	 * Test whether a role has a specified action.
	 *
	 * @param string $role Action rows from database
	 * @param string $action
	 */
	function role_has_action($role, $action) {
		$actions = $this->get_actions($role);

		foreach ($actions as $action_row) {
			if (fnmatch($action_row['name'], $action)) {
				$this->_permissions[] = $action;
				return true;
			}
		}
	}

	/**
	 * @param array $role Role row from database
	 * @return array
	 */
	function get_actions($role) {
		if (isset($this->_actions[$role->id]))
			return $this->_actions[$role->id];

		if (!strpos($role->action, ',')) {
			return $this->_actions[$role->id] = array('name' => $role->action);
		}

		$sql = "select name from {$this->_action_table} where id in ({$role->action})";
		$query = $this->_ci->db->query($sql);
		return $this->_actions[$role->id] = $query ? $query->result_array() : array();
	}

	/**
	 * Test for whether user has a permission or a role.
	 *
	 * ```php
	 * user_can_do('controller/method'); // user can run a method in controller.
	 * user_can_do('controller/*');      // all methods in controller
	 * user_can_do('*');                 // all actions
	 * user_can_do('@administrator');    // Role test, user can do all administrator permissions
	 * ```
	 *
	 * @param $perms User permission or role.
	 */
	function user_can_do($perms) {
		if (in_array($perms, $this->_permissions))
			return true;

		foreach ($this->_roles as $role) {
			if ($this->match_role($perms, $role))
				return true;

			if ($this->role_has_action($role, $perms))
				return true;
		}
	}

}

/**
 * @see User_access_control::run()
 */
class User_access_control
{
	private static $_ci;
	private static $_action;
	private static $_locker;

	/**
	 * If the user doesn't logged in (by checking user role) this function does nothing
	 * otherwise redirect user to locker page and also add current request uri to session data.
	 */
	private static function redirect_to_locker_page() {
		if (!self::$_ci->rbac->role) {
			log_message('debug', __METHOD__);
			$key_request_uri = self::$_ci->config->item('sess_key_request_uri', 'user');
			self::$_ci->session->__set($key_request_uri, uri_string());
			redirect(self::$_locker);
		}
	}

	private static function is_locker_page() {
		return self::$_action == self::$_locker
		/**/OR self::$_action == self::$_locker . '/index';
	}

	private static function show_403() {
		$url = uri_string();
		http_response_code(403);
		exit("<h1>Unauthorized</h1>You don't have permission to access /{$url} on this server.");
	}

	/**
	 * Ini akan mencocokan apakah pengguna punya hak akses untuk aksi yang dijalankan sekarang.
	 * Jika gagal, pengguna akan dialihkan ke laman masuk (login page).
	 * Jika laman masuk tidak ada, kode HTTP 403 akan dikirim ke browser.
	 *
	 * Penggunaan: Tempatkan fungsi sebelum menjalankan aksi (controller method) misalnya bisa sebagai hook ```post_controller_constructor```.
	 */
	static function run() {
		self::$_ci = CI_Controller::get_instance();
		self::$_action = implode('/', self::$_ci->uri->rsegments);
		self::$_locker = self::$_ci->config->item('locker_page', 'user');
		log_message('debug', 'Call ' . __METHOD__);

		switch(TRUE) { //  when one of the following lines is TRUE, function exit.
			case self::is_locker_page():
			case self::$_ci->rbac->user_can_do(self::$_action):
			case self::redirect_to_locker_page():
			case self::show_403():
		}
	}

}

if (!function_exists('user_can')) {
	/**
	 * Test for whether user ca do specified action or user has a role.
	 * @see Rbac::user_can_do() for more detail
	 *
	 * @param $action
	 */
	function user_can($action) {
		return get_instance()->rbac->user_can_do($action);
	}

}
