<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model Kader
 *
 * donjo-app/models/Kader_model.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

class Kader_model extends MY_Model
{
	protected $table = 'kader_pemberdayaan_masyarakat';

	const ORDER_ABLE = [
		3 => 'p.nama',
		4 => 'p.umur',
		5 => 'p.jk',
		6 => 'kd.kursus',
		7 => 'kd.bidang',
		8 => 'p.alamat',
		9 => 'p.keterangan',
	];

	public function get_data(string $search = '')
	{
		$sebutan_dusun = ucwords($this->setting->sebutan_dusun);

		$this->db
		->select("kd.*, (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = p.id) AS umur")
		->select("IF(p.sex=1, 'L', 'P') AS jk, p.nama, pd.nama AS pendidikan_sedang")
		->select("(
			case when (p.id_kk IS NULL or p.id_kk = 0)
				then
					case when (cp.dusun = '-' or cp.dusun = '')
						then CONCAT(COALESCE(p.alamat_sekarang, ''), ' RT ', cp.rt, ' / RW ', cp.rw)
						else CONCAT(COALESCE(p.alamat_sekarang, ''), ' {$sebutan_dusun} ', cp.dusun, ' RT ', cp.rt, ' / RW ', cp.rw)
					end
				else
					case when (ck.dusun = '-' or ck.dusun = '')
						then CONCAT(COALESCE(k.alamat, ''), ' RT ', ck.rt, ' / RW ', ck.rw)
						else CONCAT(COALESCE(k.alamat, ''), ' {$sebutan_dusun} ', ck.dusun, ' RT ', ck.rt, ' / RW ', ck.rw)
					end
			end) AS alamat")
		->from("{$this->table} kd")
		->join('tweb_penduduk p', 'kd.penduduk_id = p.id', 'left')
		->join('tweb_wil_clusterdesa cp', 'p.id_cluster = cp.id', 'left')
		->join('tweb_keluarga k', 'p.id_kk = k.id', 'left')
		->join('tweb_wil_clusterdesa ck', 'k.id_cluster = ck.id', 'left')
		->join('tweb_penduduk_pendidikan pd', 'p.pendidikan_kk_id = pd.id', 'left');


		if ($search)
		{
			$this->db
				->group_start()
					->like('p.nama', $search)
					->or_like('p.umur', $search)
					->or_like('p.jenis_kelamin', $search)
					->or_like('kd.kursus', $search)
					->or_like('kd.bidang', $search)
					->or_like('p.alamat', $search)
					->or_like('kd.keterangan', $search)
				->group_end();
		}

		return $this->db;
	}

	public function find($id = 0)
	{
		return $this->db->get_where($this->table, ['id' => $id])->row_array();
	}

	public function list_penduduk($id = 0)
	{
		$this->db->where("id NOT IN (SELECT penduduk_id FROM kader_pemberdayaan_masyarakat WHERE penduduk_id != $id)");

		return $this->db->select('id, nik, nama')->get('penduduk_hidup')->result_array();
	}

	public function tambah()
	{
		$data = $this->validasi();

		$outp = $this->db->insert($this->table, $data);

		status_sukses($outp);
	}

	public function ubah($id = 0)
	{
		$data = $this->validasi();

		$outp = $this->db->set($data)->update($this->table, ['id' => $id]);

		status_sukses($outp);
	}

	public function hapus($id = 0)
	{
		$outp = $this->db->delete($this->table, ['id' => $id]);

		status_sukses($outp);
	}

	public function hapus_semua()
	{
		foreach ($this->input->post('id_cb') as $id)
		{
			$this->hapus($id);
		}
	}

	private function validasi()
	{
		$post = $this->input->post();

		$data = [
			'penduduk_id' => bilangan($post['penduduk_id']),
			'kursus' => json_encode($post['kursus']),
			'bidang' => json_encode($post['bidang']),
			'keterangan' => $post['keterangan'],
		];

		return $data;
	}
}
