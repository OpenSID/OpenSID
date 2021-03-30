<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Cache extends CI_Cache {

	// $lama, waktu simpan dalam detik
	public function pakai_cache($callback, $cache_id, $lama)
	{
		if (! $data = $this->file->get($cache_id))
		{
			$data = call_user_func($callback);
			$this->file->save($cache_id, $data, $lama);
		}

		return $data;
	}

	/* 	Untuk cache yg diberi prefix user_id, seperti "{$this->session->user}_cache_modul",
			hapus_cache_untuk_semua('_cache_modul') akan menghapus file cache untuk semua pengguna
	*/
	public function hapus_cache_untuk_semua($cache_id)
	{
		foreach ($this->file->cache_info() as $cache)
		{
			$file = $cache['server_path'];
			if (substr_compare($file, $cache_id, -strlen($cache_id)) === 0 && file_exists($file))
			{
				unlink($file);
			}
		}
	}
}
