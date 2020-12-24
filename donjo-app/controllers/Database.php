<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Database
 *
 * donjo-app/controllers/Database.php
 *
 */
/*
 *  File ini bagian dari:
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

require_once 'vendor/spout/src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

class Database extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
		$this->load->library('zip');
		$this->load->model(['import_model', 'export_model', 'database_model']);

		$this->modul_ini = 11;
		$this->sub_modul_ini = 45;
	}

	public function clear()
	{
		redirect('export');
	}

	public function index()
	{
		// Untuk development: menghapus session tracking. Tidak ada kaitan dengan database.
		// Di sini untuk kemudahan saja.
		// TODO: cari tempat yang lebih cocok
		if (defined('ENVIRONMENT') AND ENVIRONMENT == 'development')
		{
			log_message('debug', "Reset tracking");
			unset($_SESSION['track_web']);
			unset($_SESSION['track_admin']);
			unset($_SESSION['siteman_timeout']);
		}

		$data['act_tab'] = 1;
		$data['content'] = 'export/exp';
		$this->load->view('database/database.tpl.php', $data);
	}

	public function import()
	{
		$data['form_action'] = site_url("database/import_dasar");
		$data['form_action3'] = site_url("database/ppls_individu");

		$data['act_tab'] = 2;
		$data['content'] = 'import/imp';
		$this->load->view('database/database.tpl.php', $data);
	}

	public function import_bip()
	{
		$data['form_action'] = site_url("database/import_data_bip");

		$data['act_tab'] = 3;
		$data['content'] = 'import/bip';
		$this->load->view('database/database.tpl.php', $data);
	}

	public function migrasi_cri()
	{
		$data['form_action'] = site_url("database/migrasi_db_cri");

		$data['act_tab'] = 5;
		$data['content'] = 'database/migrasi_cri';
		$this->load->view('database/database.tpl.php', $data);
	}

	public function backup()
	{
		$data['form_action'] = site_url("database/restore");

		$data['act_tab'] = 4;
		$data['content'] = 'database/backup';
		$this->load->view('database/database.tpl.php', $data);
	}

	public function kosongkan()
	{
		$data['act_tab'] = 6;
		$data['content'] = 'database/kosongkan';
		$this->load->view('database/database.tpl.php', $data);
	}

	/*
		$opendk - tidak kosong untuk header sesuai dengan format impor OpenDK
	*/
	public function export_excel($opendk = '')
	{
		$writer = WriterEntityFactory::createXLSXWriter();

		//Nama File
		$tgl =  date('d_m_Y');
		if ($opendk)
		{
			$lokasi = LOKASI_DOKUMEN . 'penduduk_' . $tgl . '_opendk.xlsx';
			$writer->openToFile($lokasi);
		}
		else
		{
			$fileName = 'penduduk_' . $tgl . '.xlsx';
			$writer->openToBrowser($fileName); // stream data directly to the browser
		}

		//Header Tabel
		$daftar_kolom = [
			['Alamat', 'alamat'],
			['Dusun', 'dusun'],
			['RW', 'rw'],
			['RT', 'rt'],
			['Nama', 'nama'],
			['Nomor KK', 'nomor_kk'],
			['Nomor NIK', 'nomor_nik'],
			['Jenis Kelamin', 'jenis_kelamin'],
			['Tempat Lahir', 'tempat_lahir'],
			['Tanggal Lahir', 'tanggal_lahir'],
			['Agama', 'agama'],
			['Pendidikan (dlm KK)', 'pendidikan_dlm_kk'],
			['Pendidikan (sdg ditempuh)', 'pendidikan_sdg_ditempuh'],
			['Pekerjaan', 'pekerjaan'],
			['Kawin', 'kawin'],
			['Hub. Keluarga', 'hubungan_keluarga'],
			['Kewarganegaraan', 'kewarganegaraan'],
			['Nama Ayah', 'nama_ayah'],
			['Nama Ibu', 'nama_ibu'],
			['Gol. Darah', 'gol_darah'],
			['Akta Lahir', 'akta_lahir'],
			['Nomor Dokumen Paspor', 'nomor_dokumen_pasport'],
			['Tanggal Akhir Paspor', 'tanggal_akhir_pasport'],
			['Nomor Dokumen KITAS', 'nomor_dokumen_kitas'],
			['NIK Ayah', 'nik_ayah'],
			['NIK Ibu', 'nik_ibu'],
			['Nomor Akta Perkawinan', 'nomor_akta_perkawinan'],
			['Tanggal Perkawinan', 'tanggal_perkawinan'],
			['Nomor Akta Perceraian', 'nomor_akta_perceraian'],
			['Tanggal Perceraian', 'tanggal_perceraian'],
			['Cacat', 'cacat'],
			['Cara KB', 'cara_kb'],
			['Hamil', 'hamil'],
			['KTP-el', 'ktp_el'],
			['Status Rekam', 'status_rekam'],
			['Alamat Sekarang', 'alamat_sekarang']
		];
		if ($opendk)
		{
			$judul = array_column($daftar_kolom, 1);
			// Kolom tambahan khusus OpenDK
			$judul[] = 'id';
			$judul[] = 'foto';
			$judul[] = 'status_dasar';
			$judul[] = 'created_at';
			$judul[] = 'updated_at';
			$judul[] = 'desa_id';
		}
		else
		{
			$judul = array_column($daftar_kolom, 0);
		}
		$header = WriterEntityFactory::createRowFromArray($judul);
		$writer->addRow($header);

		//Isi Tabel
		$get = $this->export_model->export_excel();
		if ($opendk)
		{
			foreach ($get as $row)
			{
				$penduduk = array(
					$row->alamat,
					$row->dusun,
					$row->rw,
					$row->rt,
					$row->nama,
					$row->no_kk,
					$row->nik,
					$row->sex,
					$row->tempatlahir,
					$row->tanggallahir,
					$row->agama_id,
					$row->pendidikan_kk_id,
					$row->pendidikan_sedang_id,
					$row->pekerjaan_id,
					$row->status_kawin,
					$row->kk_level,
					$row->warganegara_id,
					$row->nama_ayah,
					$row->nama_ibu,
					$row->golongan_darah_id,
					$row->akta_lahir,
					$row->dokumen_pasport,
					$row->tanggal_akhir_pasport,
					$row->dokumen_kitas,
					$row->ayah_nik,
					$row->ibu_nik,
					$row->akta_perkawinan,
					$row->tanggalperkawinan,
					$row->akta_perceraian,
					$row->tanggalperceraian,
					$row->cacat_id,
					$row->cara_kb_id,
					$row->hamil,
					$row->ktp_el,
					$row->status_rekam,
					$row->alamat_sekarang,
					$row->id,
					$row->foto,
					$row->status_dasar,
					$row->created_at,
					$row->updated_at,
					$row->desa_id,
				);


				$file_foto = LOKASI_USER_PICT . $row->foto;
				if (is_file($file_foto))
				{
					$this->zip->read_file($file_foto);
				}

				$rowFromValues = WriterEntityFactory::createRowFromArray($penduduk);
				$writer->addRow($rowFromValues);
			}

			$writer->close();
			$this->zip->read_file($lokasi);
			unlink($lokasi);
			$this->zip->download('penduduk_' . $tgl . '_opendk.zip');
		}
		else
		{
			foreach ($get as $row)
			{
				$penduduk = array(
					$row->alamat,
					$row->dusun,
					$row->rw,
					$row->rt,
					$row->nama,
					$row->no_kk,
					$row->nik,
					$row->sex,
					$row->tempatlahir,
					$row->tanggallahir,
					$row->agama_id,
					$row->pendidikan_kk_id,
					$row->pendidikan_sedang_id,
					$row->pekerjaan_id,
					$row->status_kawin,
					$row->kk_level,
					$row->warganegara_id,
					$row->nama_ayah,
					$row->nama_ibu,
					$row->golongan_darah_id,
					$row->akta_lahir,
					$row->dokumen_pasport,
					$row->tanggal_akhir_pasport,
					$row->dokumen_kitas,
					$row->ayah_nik,
					$row->ibu_nik,
					$row->akta_perkawinan,
					$row->tanggalperkawinan,
					$row->akta_perceraian,
					$row->tanggalperceraian,
					$row->cacat_id,
					$row->cara_kb_id,
					$row->hamil,
					$row->ktp_el,
					$row->status_rekam,
					$row->alamat_sekarang
				);
				$rowFromValues = WriterEntityFactory::createRowFromArray($penduduk);
				$writer->addRow($rowFromValues);
			}
			$writer->close();
		}

		redirect('database');
	}

	public function export_dasar()
	{
		$this->export_model->export_dasar();
	}

	public function import_dasar()
	{
		$hapus = isset($_POST['hapus_data']);
		$this->import_model->import_excel($hapus);
		redirect('database/import/1');
	}

	public function import_data_bip()
	{
		$hapus = isset($_POST['hapus_data']);
		$this->import_model->import_bip($hapus);
		redirect('database/import_bip/1');
	}

	public function migrasi_db_cri()
	{
		$this->database_model->migrasi_db_cri();
		redirect('database/migrasi_cri/1');
	}

	public function kosongkan_db()
	{
		$this->redirect_hak_akses('h', "database/kosongkan");
		$this->database_model->kosongkan_db();
		redirect('database/kosongkan');
	}

	// Impor Pengelompokan Data Rumah Tangga
	public function ppls_individu()
	{
		$this->import_model->pbdt_individu();
	}

	public function exec_backup()
	{
		$this->export_model->backup();
	}

	public function desa_backup()
	{
		$backup_folder = FCPATH.'desa/'; // Folder yg akan di backup
		$this->zip->read_dir($backup_folder, FALSE);
		$this->zip->download('backup_folder_desa_'.date('Y_m_d').'.zip');
	}

	public function restore()
	{
		$this->session->sedang_restore = 1;
		$this->redirect_hak_akses('h', "database/backup");
		$this->export_model->restore();
		redirect('database/backup');
		$this->session->sedang_restore = 0;
	}

	public function export_csv()
	{
		$data['main'] = $this->export_model->export_csv();
		$this->load->view('export/penduduk_csv', $data);
	}
}
