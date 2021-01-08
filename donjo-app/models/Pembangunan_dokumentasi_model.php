<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model Pembangunan dokumentasi
 *
 * donjo-app/models/Pembangunan_dokumentasi_model.php
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

class Pembangunan_dokumentasi_model extends CI_Model
{
	protected $table = 'pembangunan_ref_dokumentasi';

	const ORDER_ABLE = [
		3 => 'd.persentase',
		4 => 'd.keterangan',
		5 => 'd.created_at',
	];

	public function get_data($id, string $search = '')
	{
		$builder = $this->db->select([
			'd.*',
		])
		->from("{$this->table} d")
		->join('pembangunan p', 'd.id_pembangunan = p.id')
		->where('d.id_pembangunan', $id);

		if (empty($search))
		{
			$condition = $builder;
		}
		else
		{
			$condition = $builder->group_start()
				->like('d.keterangan', $search)
				->or_like('keterangan', $search)
				->group_end();
		}

		return $condition;
	}

	public function insert(array $request, $gambar)
	{
		return $this->db->insert($this->table, [
			'id_pembangunan' => $request['id_pembangunan'],
			'gambar'         => $gambar,
			'persentase'     => $request['persentase'] ? $request['persentase'] : $request['id_persentase'],
			'keterangan'     => $request['keterangan'],
			'created_at'     => date('Y-m-d H:i:s'),
			'updated_at'     => date('Y-m-d H:i:s'),
		]);
	}

	public function update($id_pembangunan, $id, array $request, $gambar)
	{
		return $this->db->where([
			'id_pembangunan' => $id_pembangunan,
			'id'             => $id
		])
		->update($this->table, [
			'gambar'     => $gambar,
			'persentase' => $request['persentase'] ? $request['persentase'] : $request['id_persentase'],
			'keterangan' => $request['keterangan'],
			'updated_at' => date('Y-m-d H:i:s'),
		]);
	}

	public function delete($id)
	{
		return $this->db->where('id', $id)->delete($this->table);
	}

	public function find($id)
	{
		return $this->db->where('id', $id)
			->get($this->table)
			->row();
	}

	public function find_dokumentasi($id_pembangunan)
	{
		return $this->db->where('id_pembangunan', $id_pembangunan)
			->get($this->table)
			->result();
	}
}