<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Cache extends CI_Cache {

	public function pakai_cache($callback, $cache_id, $lama)
	{
		if (! $data = $this->file->get($cache_id))
		{
			$data = call_user_func($callback);
			$this->file->save($cache_id, $data, $lama);
		}

		return $data;
	}

}
