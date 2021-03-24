<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Layanan Mandiri
 *
 * donjo-app/controllers/Mandiri_web.php
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

class Mandiri_web extends Mandiri_Controller
{
	private $cek_anjungan;

	public function __construct()
	{
		parent::__construct();
		mandiri_timeout();
		$this->load->model(['web_dokumen_model', 'surat_model', 'penduduk_model', 'keluar_model', 'permohonan_surat_model', 'mailbox_model', 'penduduk_model', 'lapor_model', 'keluarga_model', 'mandiri_model', 'anjungan_model']);
		$this->load->helper('download');

		$this->cek_anjungan = $this->anjungan_model->cek_anjungan();
	}

	public function index()
	{
		if (isset($_SESSION['mandiri']) and 1 == $_SESSION['mandiri'])
		{
			redirect('mandiri_web/mandiri/1/1');
		}
		unset($_SESSION['balik_ke']);
		$data['header'] = $this->header;
		//Initialize Session ------------
		if (!isset($_SESSION['mandiri']))
		{
			// Belum ada session variable
			$this->session->set_userdata('mandiri', 0);
			$this->session->set_userdata('mandiri_try', 4);
			$this->session->set_userdata('mandiri_wait', 0);
		}
		$_SESSION['success'] = 0;
		//-------------------------------

		$data['cek_anjungan'] = $this->cek_anjungan;

		$this->load->view('mandiri_login', $data);
	}

	public function auth()
	{
		if ($this->session->mandiri_wait != 1)
		{
			$this->mandiri_model->siteman();
		}

		if ($this->session->lg == 1)
		{
			redirect('mandiri_web/ganti_pin');
		}

		if ($this->session->mandiri == 1)
		{
			redirect('mandiri_web/mandiri/1/1');
		}
		else
		{
			redirect('mandiri_web');
		}

	}

	public function logout()
	{
		$this->mandiri_model->logout();
		redirect('mandiri_web');
	}

	public function update_pin()
	{
		$this->mandiri_model->update_pin($this->session->nik);
		if ($this->session->success == -1)
		{
			redirect($_SERVER['HTTP_REFERER']);
		}
		else redirect('mandiri_web/logout');
	}

	public function ganti_pin()
	{
		$nik = $this->session->nik;
		if ($nik)
		{
			$data['main'] = $this->mandiri_model->get_penduduk($nik, TRUE);
			$data['header'] = $this->header;
			$data['cek_anjungan'] = $this->cek_anjungan;

			$this->load->view('mandiri_pin', $data);
		}
		else redirect('mandiri_web');
	}

	public function balik_first()
	{
		$this->mandiri_model->logout();
		redirect('first');
	}

	public function mandiri($p = 1, $m = 0, $kat = 1)
	{
		$data = $this->includes;
		$data['p'] = $p;
		$data['menu_surat_mandiri'] = $this->surat_model->list_surat_mandiri();
		$data['m'] = $m;
		$data['kat'] = $kat;

		/* nilai $m
			1 untuk menu profilku
			2 untuk menu layanan
			3 untuk menu lapor
			4 untuk menu bantuan
			5 untuk menu surat mandiri
		*/
		switch ($m)
		{
			case 1:
				$data['list_kelompok'] = $this->penduduk_model->list_kelompok($this->session->id);
				$data['list_dokumen'] = $this->penduduk_model->list_dokumen($this->session->id);
				break;
			case 21:
				$data['tab'] = 2;
				$data['m'] = 2;
			case 2:
				$data['surat_keluar'] = $this->keluar_model->list_data_perorangan($this->session->id);
				$data['permohonan'] = $this->permohonan_surat_model->list_permohonan_perorangan($this->session->id);
				break;
			case 3:
				$inbox = $this->mailbox_model->get_inbox_user($this->session->nik);
				$outbox = $this->mailbox_model->get_outbox_user($this->session->nik);
				$data['main_list'] = $kat == 1 ? $inbox : $outbox;
				$data['submenu'] = $this->mailbox_model->list_menu();
				$_SESSION['mailbox'] = $kat;
				break;
			case 4:
				$data['bantuan_penduduk'] = $this->program_bantuan_model->daftar_bantuan_yang_diterima($this->session->nik);
				break;
			case 5:
				$data['list_dokumen'] = $this->penduduk_model->list_dokumen($this->session->id);
				break;
			default:
				break;
		}

		$data['desa'] = $this->header;
		$data['penduduk'] = $this->penduduk_model->get_penduduk($this->session->id);
		$this->load->view('web/mandiri/layout.mandiri.php', $data);
	}

	public function mandiri_surat($id_permohonan = '')
	{
		$data = $this->includes;
		$data['menu_surat_mandiri'] = $this->surat_model->list_surat_mandiri();
		$data['menu_dokumen_mandiri'] = $this->lapor_model->get_surat_ref_all();
		$data['m'] = 5;
		$data['permohonan'] = $this->permohonan_surat_model->get_permohonan($id_permohonan);
		$data['list_dokumen'] = $this->penduduk_model->list_dokumen($this->session->id);
		$data['penduduk'] = $this->penduduk_model->get_penduduk($this->session->id);

		// Ambil data anggota KK
		if ($data['penduduk']['kk_level'] === '1') // Jika Kepala Keluarga
		{
			$data['kk'] = $this->keluarga_model->list_anggota($data['penduduk']['id_kk']);
		}

		$data['desa'] = $this->header;
		$data['cek_anjungan'] = $this->cek_anjungan;

		$this->load->view('web/mandiri/layout.mandiri.php', $data);
	}

	public function cetak_biodata()
	{
		$data['desa'] = $this->header;
		$data['penduduk'] = $this->penduduk_model->get_penduduk($this->session->id);

		$this->load->view('sid/kependudukan/cetak_biodata', $data);
	}

	public function cetak_kk()
	{
		$id_kk = $this->penduduk_model->get_id_kk($this->session->id);
		$data = $this->keluarga_model->get_data_cetak_kk($id_kk);

		$this->load->view("sid/kependudukan/cetak_kk_all", $data);
	}

	public function kartu_peserta($aksi = 'tampil', $id = 0)
	{
		$data = $this->program_bantuan_model->get_program_peserta_by_id($id);
		// Hanya boleh menampilkan data pengguna yang login
		// ** Bagi program sasaran pendududk **
		// TO DO : Ganti parameter nik menjadi id
		if ($data['peserta'] == $this->session->nik)
		{
			if ($aksi == 'tampil')
			{
				$this->load->view('web/mandiri/data_peserta', $data);
			}
			else
			{
				if ($data['kartu_peserta']) force_download(LOKASI_DOKUMEN . $data['kartu_peserta'], NULL);

				redirect('mandiri_web/mandiri/1/4');
			}
		}
	}

	public function cek_syarat()
	{
		$id_permohonan = $this->input->post('id_permohonan');
		$permohonan = $this->db->where('id', $id_permohonan)
			->get('permohonan_surat')
			->row_array();
		$syarat_permohonan = json_decode($permohonan['syarat'], true);
		$dokumen = $this->penduduk_model->list_dokumen($this->session->id);
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
			$pilihan_dokumen = $this->load->view('web/mandiri/pilihan_syarat.php', array('dokumen' => $dokumen, 'syarat_permohonan' => $syarat_permohonan, 'syarat_id' => $baris['ref_syarat_id']), TRUE);
			$row[] = $pilihan_dokumen;
			$data[] = $row;
		}

		$output = array(
			"recordsTotal" => 10,
			"recordsFiltered" => 10,
			'data' => $data
		);
		echo json_encode($output);
	}

	public function ajax_table_surat_permohonan()
	{
		$data = $this->penduduk_model->list_dokumen($this->session->id);
		$jenis_syarat_surat = $this->referensi_model->list_by_id('ref_syarat_surat', 'ref_syarat_id');
		for ($i=0; $i < count($data); $i++)
		{
			$berkas = $data[$i]['satuan'];
			$list_dokumen[$i][] = $data[$i]['no'];
			$list_dokumen[$i][] = $data[$i]['id'];
			$list_dokumen[$i][] = "<a href='".site_url("mandiri_web/unduh_berkas/".$data[$i][id])."/{$data[$i][id_pend]}"."'>".$data[$i]["nama"].'</a>';
			$list_dokumen[$i][] = $jenis_syarat_surat[$data[$i]['id_syarat']]['ref_syarat_nama'];
			$list_dokumen[$i][] = tgl_indo2($data[$i]['tgl_upload']);
			$list_dokumen[$i][] = $data[$i]['nama'];
			$list_dokumen[$i][] = $data[$i]['dok_warga'];
		}
		$list['data'] = count($list_dokumen) > 0 ? $list_dokumen : array();
		echo json_encode($list);
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
			echo json_encode($data);
			return;
		}

		$this->session->unset_userdata('success');
		$this->session->unset_userdata('error_msg');
		$success_msg = 'Berhasil menyimpan data';

		$id = $this->session->id;

		if ($id)
		{
			$_POST['id_pend'] = $id;
			$id_dokumen = $this->input->post('id');
			unset($_POST['id']);

			if ($id_dokumen)
			{
				$hasil = $this->web_dokumen_model->update($id_dokumen, $id, $mandiri = true);
				if (!$hasil)
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

		echo json_encode($data);
	}

	public function ajax_get_dokumen_pendukung()
	{
		$id_dokumen = $this->input->post('id_dokumen');
		$data = $this->web_dokumen_model->get_dokumen($id_dokumen, $this->session->id);

		$data['anggota'] = $this->web_dokumen_model->get_dokumen_di_anggota_lain($id_dokumen);

		if (empty($data))
		{
			$data['success'] = -1;
			$data['message'] = 'Tidak ditemukan';
		}
		elseif ($this->session->id != $data['id_pend'])
		{
			$data = ['message' => 'Anda tidak mempunyai hak akses itu'];
		}
		echo json_encode($data);
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
		elseif ($this->session->id != $data['id_pend'])
		{
			$data = [
				'success' => -1,
				'message' => 'Anda tidak mempunyai hak akses itu'
			];
		}
		else
		{
			$this->web_dokumen_model->delete($id_dokumen);
			$data['success'] = $this->session->userdata('success') ? : '1';
		}
		echo json_encode($data);
	}

	/**
	 * Unduh berkas berdasarkan kolom dokumen.id
	 * @param   integer  $id_dokumen  Id berkas pada koloam dokumen.id
	 * @return  void
	 */
	public function unduh_berkas($id_dokumen)
	{
		// Ambil nama berkas dari database
		$id = $this->session->id;
		$berkas = $this->web_dokumen_model->get_nama_berkas($id_dokumen, $id);
		if ($berkas && $id)
			ambilBerkas($berkas, NULL, NULL, LOKASI_DOKUMEN);
		else
			$this->output->set_status_header('404');
	}
}
