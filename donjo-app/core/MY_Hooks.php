<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Hooks extends CI_Hooks
{
// --------------------------------------------------------------------
	/**
	 * Call Hook
	 *
	 * Calls a particular hook. Called by CodeIgniter.php.
	 *
	 * @uses	CI_Hooks::_run_hook()
	 *
	 * @param	string	$which	Hook name
	 * @return	bool	TRUE on success or FALSE on failure
	 */
	public function call_hook($which = '', $data = null) {
		if (!is_array($data)) {
			return parent::call_hook($which);
		}

		if (!$this->enabled
			|| !isset($this->hooks[$which])
			|| $this->_in_progress
		) {
			return FALSE;
		}

		$this->_in_progress = true;
		$hooks = $this->hooks[$which];

		if (!is_array($hooks)) {
			$hooks = array($hooks);
		}

		$hook = new MY_Hook_Data();
		$hook->name = $which;
		$hook->data = $data;

		foreach ($hooks as $hook_callback) {
			$result = call_user_func($hook_callback, $hook);

			if ($result === false) {
				$hook->result === null && $hook->result = false;
				break;
			}
		}

		return $hook;
	}
}

class MY_Hook_Data
{
	public $name;
	public $result;
	public $data = array();

}
