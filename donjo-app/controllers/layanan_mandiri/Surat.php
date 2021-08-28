<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Layanan Mandiri > Surat
 *
 * donjo-app/controllers/layanan_mandiri/Surat.php
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

class Surat extends Mandiri_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['keluar_model', 'permohonan_surat_model', 'surat_model', 'lapor_model', 'penduduk_model']);
	}

	// Kat 1 = Permohonan
	// Kat 2 = Arsip
	public function index($kat = 1)
	{
		$arsip = $this->keluar_model->list_data_perorangan($this->is_login->id_pend);
		$permohonan = $this->permohonan_surat_model->list_permohonan_perorangan($this->is_login->id_pend);

		$data = [
			'desa' => $this->header,
			'cek_anjungan' => $this->cek_anjungan,
			'kat' => $kat,
			'judul' => ($kat == 1) ? 'Permohonan Surat' : 'Arsip Surat',
			'main' => ($kat == 1) ? $permohonan : $arsip,
			'konten' => 'surat'
		];

		$this->load->view('layanan_mandiri/template', $data);
	}

	public function buat_surat($id_permohonan = '')
	{
		$data = [
			'desa' => $this->header,
			'cek_anjungan' => $this->cek_anjungan,
			'menu_surat_mandiri' => $this->surat_model->list_surat_mandiri(),
			'menu_dokumen_mandiri' => $this->lapor_model->get_surat_ref_all(),
			'permohonan' => $this->permohonan_surat_model->get_permohonan($id_permohonan),
			'list_dokumen' => $this->penduduk_model->list_dokumen($this->is_login->id_pend),
			'kk' => ($this->is_login->kk_level === '1') ? $this->keluarga_model->list_anggota($this->is_login->id_kk) : '', // Ambil data anggota KK, jika Kepala Keluarga
			'konten' => 'buat_surat'
		];

		$this->load->view('layanan_mandiri/template', $data);
	}

	public function cek_syarat()
	{
		$id_permohonan = $this->input->post('id_permohonan');
		$permohonan = $this->db
			->where('id', $id_permohonan)
			->get('permohonan_surat')
			->row_array();

		$syarat_permohonan = json_decode($permohonan['syarat'], true);
		$dokumen = $this->penduduk_model->list_dokumen($this->is_login->id_pend);
		$id = $this->input->post('id_surat');
		$syarat_surat = $this->surat_master_model->get_syarat_surat($id);
		$data = array();
		$no = $_POST['start'];

		foreach ($syarat_surat as $no_syarat => $baris)
		{
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $baris['ref_syarat_nama'];
			// Gunakan view sebagai string untuk mempermudah pembuatan pilihan
			$pilihan_dokumen = $this->load->view('layanan_mandiri/pilihan_syarat', array('dokumen' => $dokumen, 'syarat_permohonan' => $syarat_permohonan, 'syarat_id' => $baris['ref_syarat_id'], 'cek_anjungan' => $this->cek_anjungan), TRUE);
			$row[] = $pilihan_dokumen;
			$data[] = $row;
		}

		$output = array(
			"recordsTotal" => 10,
			"recordsFiltered" => 10,
			'data' => $data
		);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($output));
	}

	public function ajax_table_surat_permohonan()
	{
		$data = $this->penduduk_model->list_dokumen($this->is_login->id_pend);
		$jenis_syarat_surat = $this->referensi_model->list_by_id('ref_syarat_surat', 'ref_syarat_id');

		for ($i=0; $i < count($data); $i++)
		{
			$berkas = $data[$i]['satuan'];
			$list_dokumen[$i][] = $data[$i]['no'];
			$list_dokumen[$i][] = $data[$i]['id'];
			$list_dokumen[$i][] = '<a href="' . site_url('layanan-mandiri/unduh-berkas/' . $data[$i][id]) . '">' . $data[$i][nama] . '</a>';
			$list_dokumen[$i][] = $jenis_syarat_surat[$data[$i]['id_syarat']]['ref_syarat_nama'];
			$list_dokumen[$i][] = tgl_indo2($data[$i]['tgl_upload']);
			$list_dokumen[$i][] = $data[$i]['nama'];
			$list_dokumen[$i][] = $data[$i]['dok_warga'];
		}

		$list['data'] = count($list_dokumen) > 0 ? $list_dokumen : array();

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($list));
	}

	public function ajax_upload_dokumen_pendukung()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama Dokumen', 'required');

		if ($this->form_validation->run() !== true)
		{
			$data['success'] = -1;
			$data['message'] = validation_errors();

			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($data));
			return;
		}

		$this->session->unset_userdata(['success', 'error_msg']);
		$success_msg = 'Berhasil menyimpan data';

		if ($this->is_login->id_pend)
		{
			$_POST['id_pend'] = $this->is_login->id_pend;
			$id_dokumen = $this->input->post('id');
			unset($_POST['id']);

			if ($id_dokumen)
			{
				$hasil = $this->web_dokumen_model->update($id_dokumen, $this->is_login->id_pend, $mandiri = true);
				if ( ! $hasil)
				{
					$data['success'] = -1;
					$data['message'] = 'Gagal update';
				}
			}
			else
			{
				$_POST['dok_warga'] = 1; // Boleh diubah di layanan mandiri
				$this->web_dokumen_model->insert($mandiri = true);
			}
			$data['success'] = $this->session->success;
			$data['message'] = $data['success'] == -1 ? $this->session->error_msg : $success_msg;
		}
		else
		{
			$data['success'] = -1;
			$data['message'] = 'Anda tidak mempunyai hak akses itu';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	public function ajax_get_dokumen_pendukung()
	{
		$id_dokumen = $this->input->post('id_dokumen');
		$data = $this->web_dokumen_model->get_dokumen($id_dokumen, $this->is_login->id_pend);

		$data['anggota'] = $this->web_dokumen_model->get_dokumen_di_anggota_lain($id_dokumen);

		if (empty($data))
		{
			$data['success'] = -1;
			$data['message'] = 'Tidak ditemukan';
		}
		elseif ($this->is_login->id_pend != $data['id_pend'])
		{
			$data = ['message' => 'Anda tidak mempunyai hak akses itu'];
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	public function ajax_hapus_dokumen_pendukung()
	{
		$id_dokumen = $this->input->post('id_dokumen');
		$data = $this->web_dokumen_model->get_dokumen($id_dokumen);
		if (empty($data))
		{
			$data['success'] = -1;
			$data['message'] = 'Tidak ditemukan';
		}
		elseif ($this->is_login->id_pend != $data['id_pend'])
		{
			$data['success'] = -1;
			$data['message'] = 'Anda tidak mempunyai hak akses itu';
		}
		else
		{
			$this->web_dokumen_model->delete($id_dokumen);
			$data['success'] = $this->session->userdata('success') ? : '1';
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	// Proses permohonan surat
	public function form($id_permohonan = '')
	{
		$data = $this->input->post();
		$data_permohonan = array('data_permohonan' => array(
			'keterangan' => $data['keterangan'],
			'no_hp_aktif' => $data['no_hp_aktif'],
			'syarat' => $data['syarat']));
		$this->session->set_userdata($data_permohonan);

		if ($id_permohonan)
		{
			$data['permohonan'] = $this->permohonan_surat_model->get_permohonan($id_permohonan);
			$data['isian_form'] = json_encode($this->permohonan_surat_model->ambil_isi_form($data['permohonan']['isian_form']));
			$data['id_surat'] = $data['permohonan']['id_surat'];
		}

		$surat = $this->db->where('id', $data['id_surat'])
			->get('tweb_surat_format')
			->row_array();
		$data['url'] = $surat['url_surat'];
		$url = $data['url'];

		$data['list_dokumen'] = $this->penduduk_model->list_dokumen($this->is_login->id_pend);
		$data['individu'] = $this->surat_model->get_penduduk($this->is_login->id_pend);
		$data['anggota'] = $this->keluarga_model->list_anggota($data['individu']['id_kk']);
		$data['penduduk'] = $this->penduduk_model->get_penduduk($this->is_login->id_pend);
		$this->get_data_untuk_form($url, $data);
		$data['desa'] = $this->header;

		$data['surat_url'] = rtrim($_SERVER['REQUEST_URI'], "/clear");
		$data['form_action'] = site_url("surat/cetak/$url");
		$data['masa_berlaku'] = $this->surat_model->masa_berlaku_surat($url);
		$data['data'] = $data;
		$data['cek_anjungan'] = $this->cek_anjungan;

		$data['mandiri'] = 1; // Untuk tombol cetak/kirim surat
		$data['konten'] = 'permohonan_surat';
		$this->load->view('layanan_mandiri/template', $data);
	}

	public function kirim($id_permohonan = '')
	{
		$post = $this->input->post();
		$data = array();
		$data['id_pemohon'] = $post['nik'];
		$data['id_surat'] = $post['id_surat'];
		$data['isian_form'] = json_encode($post);
		$data_permohonan = $this->session->userdata('data_permohonan');
		$data['keterangan'] = $data_permohonan['keterangan'];
		$data['no_hp_aktif'] = $data_permohonan['no_hp_aktif'];
		$data['syarat'] = json_encode($data_permohonan['syarat']);

		if ($id_permohonan)
		{
			$data['status'] = 0; // kembalikan ke status 'sedang diperiksa'
			$this->permohonan_surat_model->update($id_permohonan, $data);
		}
		else
			$this->permohonan_surat_model->insert($data);

		redirect('layanan-mandiri/permohonan-surat');
	}

	private function get_data_untuk_form($url, &$data)
	{
		$this->load->model('pamong_model');
		$this->load->model('surat_model');
		$data['surat_terakhir'] = $this->surat_model->get_last_nosurat_log($url);
		$data['surat'] = $this->surat_model->get_surat($url);
		$data['input'] = $this->input->post();
		$data['input']['nomor'] = $data['surat_terakhir']['no_surat_berikutnya'];
		$data['format_nomor_surat'] = $this->penomoran_surat_model->format_penomoran_surat($data);
		$data['lokasi'] = $this->header['desa'];
		$data['pamong'] = $this->surat_model->list_pamong();
		$pamong_ttd = $this->pamong_model->get_ttd();
		$pamong_ub = $this->pamong_model->get_ub();
		$data_form = $this->surat_model->get_data_form($url);
		if (is_file($data_form))
			include($data_form);
	}

	public function batalkan($id)
	{
		$this->permohonan_surat_model->update_status($id, array('status' => 9));

		redirect('layanan-mandiri/permohonan-surat');
	}

}
