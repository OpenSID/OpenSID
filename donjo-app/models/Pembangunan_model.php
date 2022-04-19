<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model Pembangunan
 *
 * donjo-app/models/Pembangunan_model.php
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
class Pembangunan_model extends CI_Model
{
	protected $tipe = 'rencana';
	protected $table = 'pembangunan';

	const ENABLE = 1;
	const DISABLE = 0;

	const ORDER_ABLE = [
		2  => 'p.judul',
		3  => 'p.sumber_dana',
		4  => 'p.anggaran',
		5  => 'max_persentase',
		6  => 'p.volume',
		7  => 'p.tahun_anggaran',
		8  => 'p.pelaksana_kegiatan',
		9  => 'alamat',
	];

	public function set_tipe(string $tipe)
	{
		$this->tipe = $tipe;

		return $this;
	}

	public function get_data(string $search = '', $tahun = 'semua')
	{
		$this->lokasi_pembangunan_query();
		$this->db->select([
			'p.*',
			'IF(p.sifat_proyek = "BARU", "&#10004", "-") as sifat_proyek_baru',
			'IF(p.sifat_proyek = "LANJUTAN", "&#10004", "-") as sifat_proyek_lanjutan',
			'(CASE WHEN MAX(CAST(d.persentase as UNSIGNED INTEGER)) IS NOT NULL THEN CONCAT(MAX(CAST(d.persentase as UNSIGNED INTEGER)), "%") ELSE CONCAT("belum ada progres") END) AS max_persentase',
			'max(cast(d.persentase as unsigned integer)) as progress'
		])
		->from("{$this->table} p")
		->join('pembangunan_ref_dokumentasi d', 'd.id_pembangunan = p.id', 'left')
		->join('tweb_wil_clusterdesa w', 'p.id_lokasi = w.id', 'left')
		->group_by('p.id');

		$this->get_tipe();

		if ($search)
		{
			$this->db
				->group_start()
					->like('p.sumber_dana', $search)
					->or_like('p.judul', $search)
					->or_like('p.keterangan', $search)
					->or_like('p.volume', $search)
					->or_like('p.tahun_anggaran', $search)
					->or_like('p.pelaksana_kegiatan', $search)
					->or_like('p.lokasi', $search)
					->or_like('p.anggaran', $search)
				->group_end();
		}

		if ($tahun !== 'semua')
		{
			$this->db->where('p.tahun_anggaran', $tahun);
		}
		return $this->db;
	}

	public function list_lokasi_pembangunan()
	{
		$this->lokasi_pembangunan_query();
		$data = $this->db
			->select('p.*')
			->from('pembangunan p')
			->where('p.status = 1')
			->join('tweb_wil_clusterdesa w', 'p.id_lokasi = w.id', 'left')
			->get()
			->result();

		return $data;
	}

	public function insert()
	{
		$post = $this->input->post();
		$data = $this->validasi($post);

		if (empty($data['foto'])) unset($data['foto']);

		unset($data['file_foto']);
		unset($data['old_foto']);

		$outp = $this->db->insert('pembangunan', $data);

		status_sukses($outp);
	}

	public function update($id=0)
	{
		$post = $this->input->post();
		$data = $this->validasi($post);
		$data['updated_at'] = date('Y-m-d H:i:s');

		if (empty($data['foto'])) unset($data['foto']);

		unset($data['file_foto']);
		unset($data['old_foto']);

		$this->db->where('id', $id);
		$outp = $this->db->update('pembangunan', $data);

		status_sukses($outp);
	}

	private function validasi($post)
	{
		$data = [
			'sumber_dana' => $post['sumber_dana'],
			'judul' => $post['judul'],
			'volume' => $post['volume'],
			'waktu' => $post['waktu'],
			'tahun_anggaran' => $post['tahun_anggaran'],
			'pelaksana_kegiatan' => $post['pelaksana_kegiatan'],
			'id_lokasi' => $post['lokasi'] ? NULL : $post['id_lokasi'],
			'lokasi' => $post['id_lokasi'] ? NULL : $post['lokasi'],
			'keterangan' => $post['keterangan'],
			'foto' => $this->upload_gambar_pembangunan('foto'),
			'anggaran' => $post['anggaran'],
			'sumber_biaya_pemerintah' => $post['sumber_biaya_pemerintah'],
			'sumber_biaya_provinsi' => $post['sumber_biaya_provinsi'],
			'sumber_biaya_kab_kota' => $post['sumber_biaya_kab_kota'],
			'sumber_biaya_swadaya' => $post['sumber_biaya_swadaya'],
			'sumber_biaya_jumlah' => $post['sumber_biaya_pemerintah'] + $post['sumber_biaya_provinsi'] + $post['sumber_biaya_kab_kota'] + $post['sumber_biaya_swadaya'],
			'manfaat' => $post['manfaat'],
			'sifat_proyek' => $post['sifat_proyek'],
		];

		return $data;
	}

	private function upload_gambar_pembangunan($jenis)
	{
		$this->load->library('upload');
		$this->uploadConfig = array(
			'upload_path' => LOKASI_GALERI,
			'allowed_types' => 'gif|jpg|jpeg|png',
			'max_size' => max_upload() * 1024,
		);
		// Adakah berkas yang disertakan?
		$adaBerkas = !empty($_FILES[$jenis]['name']);
		if ($adaBerkas !== TRUE)
		{
			return NULL;
		}
		// Tes tidak berisi script PHP
		if (isPHP($_FILES['logo']['tmp_name'], $_FILES[$jenis]['name']))

		{
			$_SESSION['error_msg'] .= " -> Jenis file ini tidak diperbolehkan ";
			$_SESSION['success'] = -1;
			redirect('identitas_desa');
		}

		$uploadData = NULL;
		// Inisialisasi library 'upload'
		$this->upload->initialize($this->uploadConfig);
		// Upload sukses
		if ($this->upload->do_upload($jenis))
		{
			$uploadData = $this->upload->data();
			// Buat nama file unik agar url file susah ditebak dari browser
			$namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
			// Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
			$fileRenamed = rename(
				$this->uploadConfig['upload_path'].$uploadData['file_name'],
				$this->uploadConfig['upload_path'].$namaFileUnik
			);
			// Ganti nama di array upload jika file berhasil di-rename --
			// jika rename gagal, fallback ke nama asli
			$uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];
		}
		// Upload gagal
		else
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = $this->upload->display_errors(NULL, NULL);
		}
		return (!empty($uploadData)) ? $uploadData['file_name'] : NULL;
	}

	public function update_lokasi_maps($id, array $request)
	{
		return $this->db->where('id', $id)->update($this->table, [
			'lat'        => $request['lat'],
			'lng'        => $request['lng'],
			'updated_at' => date('Y-m-d H:i:s'),
		]);
	}

	public function delete($id)
	{
		return $this->db->where('id', $id)->delete($this->table);
	}

	public function find($id)
	{
		$this->lokasi_pembangunan_query();
		$data = $this->db->select('p.*')
			->from("{$this->table} p")
			->join('tweb_wil_clusterdesa w', 'p.id_lokasi = w.id', 'left')
			->where('p.id', $id)
			->get()
			->row();

		return $data;
	}

	public function list_filter_tahun()
	{
		$this->get_tipe();
		
		return $this->db
			->select('p.tahun_anggaran')
			->distinct()
			->from("{$this->table} p")
			->join('pembangunan_ref_dokumentasi d', 'd.id_pembangunan = p.id', 'left')
			->order_by('p.tahun_anggaran', 'desc')
			->get()
			->result();
	}

	public function unlock($id)
	{
		return $this->db->set('status', static::ENABLE)
			->where('id', $id)
			->update($this->table);
	}

	public function lock($id)
	{
		return $this->db->set('status', static::DISABLE)
			->where('id', $id)
			->update($this->table);
	}

	public function get_tipe()
	{
		if (empty($this->tipe)) return; // Untuk semua pembangunan

		if($this->tipe == 'kegiatan')
		{
			$this->db->where('d.persentase !=', NULL);
			$this->db->where('d.persentase !=', '100%');
		}

		if($this->tipe == 'rencana')
		{
			$this->db->where('d.persentase is NULL', null, false);
		}
	}

	protected function lokasi_pembangunan_query()
	{
		$this->db->select(
			"(CASE WHEN p.id_lokasi = w.id THEN CONCAT(
				(CASE WHEN w.rt != '0' THEN CONCAT('RT ', w.rt, ' / ') ELSE '' END),
				(CASE WHEN w.rw != '0' THEN CONCAT('RW ', w.rw, ' - ') ELSE '' END),
				w.dusun
			) ELSE CASE WHEN p.lokasi IS NOT NULL THEN p.lokasi ELSE '=== Lokasi Tidak Ditemukan ===' END END) AS alamat"
		);
	}
}
