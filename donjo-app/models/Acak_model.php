<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acak_model extends CI_Model {

	protected $nama_wanita = ['Yuni', 'Fatima', 'Sarah', 'Dewi', 'Hasnah'];
	protected $nama_pria = ['Bambang', 'Abdul', 'Setiyadi', 'Dadang', 'Herman'];

	public function __construct()
	{
		parent::__construct();
		// ini_set('memory_limit', '512M');
		// set_time_limit(3600);
	}

	/**
	 * Acak data penduduk
	 *
	 * @access	public
	 */
	public function acak_penduduk()
	{
		$data = $this->db->select('id, nik, nama')->
			where('sex', 1)->
			get('tweb_penduduk')->result_array();
		$this->acak_untuk_gender($data);
		$data = $this->db->select('id, nik, nama')->
			where('sex <> 1')->
			get('tweb_penduduk')->result_array();
		$this->acak_untuk_gender($data);
	}

	private function acak_untuk_gender($data)
	{
		if (count($data) <= 1) return;

		$i = 1;
		foreach ($data as $penduduk)
		{
			if ($penduduk['nik'] == 0) continue;
			$nik = $penduduk['nik'];
			$urut = $this->acak_angka(substr($nik, 12));
			$nik_acak = substr_replace($nik, $urut, 12);
			$nama_acak = $this->acak_nama($i - 1, $data);
			echo $i.'. nik: '.$nik.' nik_acak: '.$nik_acak.
				' === '.'nama: '.$penduduk['nama'].' nama_acak: '.$nama_acak."<br>";
			$this->db->where('id', $penduduk['id'])->
				update('tweb_penduduk', array('nik' => $nik_acak, 'nama' => $nama_acak));
			$this->db->where('peserta', $nik)
				->update('program_peserta', ['peserta' => $nik_acak]);
			$i++;
		}
	}

	private function acak_nama($urut_penduduk, $data)
	{
		$nama = $data[$urut_penduduk]['nama'];
		$kata = preg_split('/\s+/', $nama);
		$nama_acak = '';
		for ($i=0; $i<count($kata); $i++)
		{
			// Ganti setiap kata dgn kata dari nama penduduk acak
			$urut_acak = $urut_penduduk;
			while ($urut_acak == $urut_penduduk)
			{
				$urut_acak = rand(0, count($data) - 1);
			}
			$kata_penduduk_acak = preg_split('/\s+/', $data[$urut_acak]['nama']);

			// Jangan gunakan gelar berisi '.' atau nama kurang dari 3 karakter
			$kata_acak = '.';
			while (strpos($kata_acak, '.') !== false or strlen($kata_acak) < 3)
			{
				// Kalau nama penduduk acak hanya terdiri dari satu kata, gunakan itu
				if (count($kata_penduduk_acak) == 1)
				{
					$kata_acak = $kata_penduduk_acak[0];
					break;
				}
				if ( count($kata_penduduk_acak) == 0) break;
				$urut_kata_acak = rand(0, count($kata_penduduk_acak) - 1);
				$kata_acak = $kata_penduduk_acak[$urut_kata_acak];
				// Hapus supaya kata ini tidak digunakan lagi
				unset($kata_penduduk_acak[$urut_kata_acak]);
				// https://www.codeproject.com/Questions/608574/unsetplusNotplusWorkingplusPHPplusArray
				$kata_penduduk_acak = array_values($kata_penduduk_acak);
			}
			if ($kata_acak != '.')
				$nama_acak .= ($i == 0) ? $kata_acak : ' ' . $kata_acak;
			else
			// Jika tidak ditemukan kata yg bisa dipakai gunakan nama sembarang
			{
				$nama_sembarang = $this->nama_sembarang($data['urut_penduduk']['sex']);
				$nama_acak .= ($i == 0) ? $nama_sembarang : ' ' . $nama_sembarang;
			}
		}
		return $nama_acak;
	}

	private function nama_sembarang($sex)
	{
		if ($sex == 1)
			return $this->nama_pria[rand(0, count($nama_pria) - 1)];
		else
			return $this->nama_wanita[rand(0, count($nama_pria) - 1)];
	}

	public function acak_keluarga()
	{
		$data = $this->db->select('k.id, k.no_kk, p.nama as nama_kk')->
			from('tweb_keluarga k')->
			join('tweb_penduduk p', 'k.nik_kepala = p.id', 'left')->
			get()->result_array();
		$i = 1;
		foreach ($data as $keluarga)
		{
			if ($keluarga['no_kk'] == 0) continue;

			$no_kk = $keluarga['no_kk'];
			$urut = $this->acak_angka(substr($no_kk, 12));
			$no_kk_acak = substr_replace($no_kk, $urut, 12);
			echo $i.'. no_kk: '.$no_kk.' no_kk_acak: '.$no_kk_acak."<br>";
			$this->db->where('id', $keluarga['id'])->
				update('tweb_keluarga', array('no_kk' => $no_kk_acak));
			// Juga ganti no_kk dan nama_kk di log_penduduk
			$this->db->where('no_kk', $no_kk)->
				update('log_penduduk', array('no_kk' => $no_kk_acak, 'nama_kk' => $keluarga['nama_kk']));
			// Dan ganti no_kk_sebelumnya di tweb_penduduk
			$this->db->where('no_kk_sebelumnya', $no_kk)->
				update('tweb_penduduk', array('no_kk_sebelumnya' => $no_kk_acak));
			$this->db->where('peserta', $no_kk)
				->update('program_peserta', ['peserta' => $no_kk_acak]);
			$i++;
		}
	}

	private function acak_angka($str)
	{
		$jangan = str_pad('', strlen($str), '0');
		$baru = $jangan;
		while (true)
		{
			for ($i=0; $i<strlen($str); $i++)
			{
				$baru[$i] = rand(0, 9);
			}
			if ($baru != $jangan) break;
			$baru = $jangan;
		}
		return $baru;
	}

}

?>
