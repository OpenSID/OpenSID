<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Program Bantuan
 *
 * donjo-app/controllers/Program_bantuan.php
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

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

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

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Row;

class Program_bantuan extends Admin_Controller {

	private $_set_page;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['program_bantuan_model', 'config_model']);
		$this->modul_ini = 6;
		$this->_set_page = ['20', '50', '100'];
	}

	public function clear()
	{
		$this->session->per_page = $this->_set_page[0];
		$this->session->unset_userdata('sasaran');
		redirect('program_bantuan');
	}

	public function filter($filter)
	{
		$value = $this->input->post($filter);
		if ($value != '')
			$this->session->$filter = $value;
		else $this->session->unset_userdata($filter);
		redirect('program_bantuan');
	}

	public function index($p = 1)
	{
		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data = $this->program_bantuan_model->get_program($p, FALSE);
		$data['list_sasaran'] = unserialize(SASARAN);
		$data['func'] = 'index';
		$data['per_page'] = $this->session->per_page;
		$data['set_page'] = $this->_set_page;
		$data['set_sasaran'] = $this->session->sasaran;

		$this->render('program_bantuan/program', $data);
	}

	public function form($program_id = 0)
	{
		$data['program'] = $this->program_bantuan_model->get_program(1, $program_id);
		$sasaran = $data['program'][0]['sasaran'];
		$nik = $this->input->post('nik');

		if (isset($nik))
		{
			$data['individu'] = $this->program_bantuan_model->get_peserta($nik, $sasaran);
			$data['individu']['program'] = $this->program_bantuan_model->get_peserta_program($sasaran, $data['individu']['id_peserta']);
		}
		else
		{
			$data['individu'] = NULL;
		}

		$data['form_action'] = site_url("program_bantuan/add_peserta/".$program_id);

		$this->render('program_bantuan/form', $data);
	}

	public function panduan()
	{
		$this->render('program_bantuan/panduan');
	}

	public function detail($program_id = 0, $p = 1)
	{
		$per_page = $this->input->post('per_page');
		if (isset($per_page))
			$this->session->per_page = $per_page;

		$data['cari'] = $this->session->cari ?: '';
		$data['program'] = $this->program_bantuan_model->get_program($p, $program_id);
		$data['keyword'] = $this->program_bantuan_model->autocomplete($program_id, $this->input->post('cari'));
		$data['paging'] = $data['program'][0]['paging'];
		$data['p'] = $p;
		$data['func'] = "detail/$program_id";
		$data['per_page'] = $this->session->per_page;
		$data['set_page'] = $this->_set_page;
		$this->set_minsidebar(1);

		$this->render('program_bantuan/detail', $data);
	}

	// $id = program_peserta.id
	public function peserta($cat = 0, $id = 0)
	{
		$data = $this->program_bantuan_model->get_peserta_program($cat, $id);

		$this->render('program_bantuan/peserta', $data);
	}

	// $id = program_peserta.id
	public function data_peserta($id = 0)
	{
		$data['peserta'] = $this->program_bantuan_model->get_program_peserta_by_id($id);

		switch ($data['peserta']['sasaran'])
		{
			case '1':
			case '2':
				$peserta_id = $data['peserta']['kartu_id_pend'];
				break;
			case '3':
			case '4':
				$peserta_id = $data['peserta']['peserta'];
				break;
		}
		$data['individu'] = $this->program_bantuan_model->get_peserta($peserta_id, $data['peserta']['sasaran']);
		$data['individu']['program'] = $this->program_bantuan_model->get_peserta_program($data['peserta']['sasaran'], $data['peserta']['peserta']);
		$data['detail'] = $this->program_bantuan_model->get_data_program($data['peserta']['program_id']);
		$this->set_minsidebar(1);

		$this->render('program_bantuan/data_peserta', $data);
	}

	public function add_peserta($program_id = 0)
	{
		$this->program_bantuan_model->add_peserta($program_id);

		$redirect = ($this->session->userdata('aksi') != 1) ? $_SERVER['HTTP_REFERER'] : "program_bantuan/detail/$program_id";

		$this->session->unset_userdata('aksi');

		redirect($redirect);
	}

	public function aksi($aksi = '', $program_id = 0)
	{
		$this->session->set_userdata('aksi', $aksi);

		redirect("program_bantuan/form/$program_id");
	}

	public function hapus_peserta($program_id = 0, $peserta_id = '')
	{
		$this->redirect_hak_akses('h', "program_bantuan/detail/$program_id");
		$this->program_bantuan_model->hapus_peserta($peserta_id);
		redirect("program_bantuan/detail/$program_id");
	}

	public function delete_all($program_id = 0)
	{
		$this->redirect_hak_akses('h', "program_bantuan/detail/$program_id");
		$this->program_bantuan_model->delete_all();
		redirect("program_bantuan/detail/$program_id");
	}

	// $id = program_peserta.id
	public function edit_peserta($id = 0)
	{
		$this->program_bantuan_model->edit_peserta($id);
		$program_id = $this->input->post('program_id');
		redirect("program_bantuan/detail/$program_id");
	}

	// $id = program_peserta.id
	public function edit_peserta_form($id = 0)
	{
		$data = $this->program_bantuan_model->get_program_peserta_by_id($id);
		$data['form_action'] = site_url("program_bantuan/edit_peserta/$id");
		$this->load->view('program_bantuan/edit_peserta', $data);
	}

	public function create()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Program', 'required');
		$this->form_validation->set_rules('sdate', 'Tanggal awal', 'required');
		$this->form_validation->set_rules('edate', 'Tanggal akhir', 'required');
		$this->form_validation->set_rules('asaldana', 'Asal Dana', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');

		$data['asaldana'] = unserialize(ASALDANA);

		if ($this->form_validation->run() === FALSE)
		{
			$this->render('program_bantuan/create', $data);
		}
		else
		{
			$this->program_bantuan_model->set_program();
			redirect("program_bantuan");
		}
	}

	// $id = program.id
	public function edit($id = 0)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cid', 'Sasaran', 'required');
		$this->form_validation->set_rules('nama', 'Nama Program', 'required');
		$this->form_validation->set_rules('sdate', 'Tanggal awal', 'required');
		$this->form_validation->set_rules('edate', 'Tanggal akhir', 'required');
		$this->form_validation->set_rules('asaldana', 'Asal Dana', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');

		$data['asaldana'] = unserialize(ASALDANA);
		$data['program'] = $this->program_bantuan_model->get_program(1, $id);
		$data['jml'] = $this->program_bantuan_model->jml_peserta_program($id);

		if ($this->form_validation->run() === FALSE)
		{
			$this->render('program_bantuan/edit', $data);
		}
		else
		{
			$this->program_bantuan_model->update_program($id);
			redirect("program_bantuan");
		}
	}

	// $id = program.id
	public function update($id)
	{
		$this->program_bantuan_model->update_program($id);
		redirect("program_bantuan/detail/$id");
	}

	// $id = program.id
	public function hapus($id)
	{
		$this->redirect_hak_akses('h', "program_bantuan");
		$this->program_bantuan_model->hapus_program($id);
		redirect("program_bantuan");
	}

	/*
	 * $aksi = cetak/unduh
	 */
	public function daftar($program_id = 0, $aksi = '')
	{
		if ($program_id > 0)
		{
			$temp = $this->session->per_page;
			$this->session->per_page = 1000000000; // Angka besar supaya semua data terunduh
			$data["sasaran"] = unserialize(SASARAN);

			$data['config'] = $this->config_model->get_data();
			$data['peserta'] = $this->program_bantuan_model->get_program(1, $program_id);
			$data['aksi'] = $aksi;
			$this->session->per_page = $temp;

			$this->load->view("program_bantuan/$aksi", $data);
		}
	}

	public function search($program_id = 0)
	{
		$cari = $this->input->post('cari');

		if ($cari != '')
			$this->session->cari = $cari;
		else $this->session->unset_userdata('cari');

		redirect("program_bantuan/detail/$program_id");
	}

	// TODO: function ini terlalu panjang dan sebaiknya dipecah menjadi beberapa method
	public function impor()
	{
		$this->load->library('upload');

		$config['upload_path']		= LOKASI_DOKUMEN;
		$config['allowed_types']	= 'xls|xlsx|xlsm';
		//$config['max_size']				= max_upload() * 1024;
		$config['file_name']			= namafile('Impor Peserta Program Bantuan');

		$this->upload->initialize($config);

		if ($this->upload->do_upload('userfile'))
		{
			$program_id = '';
			// Data Program Bantuan
			$temp = $this->session->per_page;
			$this->session->per_page = 1000000000;
			$ganti_program = $this->input->post('ganti_program');
			$kosongkan_peserta = $this->input->post('kosongkan_peserta');
			$ganti_peserta = $this->input->post('ganti_peserta');
			$rand_kartu_peserta = $this->input->post('rand_kartu_peserta');

			$upload = $this->upload->data();
			$file = LOKASI_DOKUMEN . $upload['file_name'];

			$reader = ReaderEntityFactory::createXLSXReader();
			$reader->open($file);

			$data_program = [];
			$data_peserta = [];
			$data_diubah = '';

			foreach ($reader->getSheetIterator() as $sheet)
			{
				$no_baris = 0;
				$no_gagal = 0;
				$no_sukses = 0;
				$pesan ='';

				// Sheet Program
				if ($sheet->getName() == 'Program')
				{
					$ambil_program = $this->program_bantuan_model->get_program(1, FALSE);
					$daftar_program = str_replace("'", "", explode (", ", sql_in_list(array_column($ambil_program['program'], 'id'))));

					$field = ['id', 'nama', 'sasaran', 'ndesc', 'asaldana', 'sdate', 'edate'];

					foreach ($sheet->getRowIterator() as $row)
					{
						$cells = $row->getCells();
						$title = (string) $cells[0];
						$value = (string) $cells[1];

						// Data terakhir
						if ($title == '###') break;

						switch (true)
						{
							/**
							 * baris 1 == id
							 * id bernilai NULL/Kosong( )/Strip(-)/tdk valid, buat program baru dan tampilkan notifkasi tambah program
							 * id bernilai id dan valid, update data program dan tampilkan notifkasi update program
							 */
							case ($no_baris == 0 && in_array($value, $daftar_program) && $ganti_program == 1):
								$program_id = $value;
								$pesan .= "- Data program dengan <b> id = " . ($value) . "</b> ditemukan, data lama diganti dengan data baru <br>";
								break;

							case ($no_baris == 0 && in_array($value, $daftar_program) && $ganti_program != 1):
								$program_id = $value;
								$pesan .= "- Data program dengan <b> id = " . ($value) . "</b> ditemukan, data lama tetap digunakan <br>";
								break;

							case ($no_baris == 0 && ! in_array($value, $daftar_program)):
								$program_id = NULL;
								$pesan .= "- Data program dengan <b> id = " . ($value) . "</b> tidak ditemukan, program baru ditambahkan secara otomatis) <br>";
								break;

							default:
								$data_program = array_merge($data_program, [$field[$no_baris] => $value]);
								break;
						}
						$no_baris++;
					}

					// Proses impor program
					$program_id = $this->program_bantuan_model->impor_program($program_id, $data_program, $ganti_program);
				}

				// Sheet Peserta
				else
				{
					$ambil_peserta = $this->program_bantuan_model->get_program(1, $program_id);
					$sasaran = $ambil_peserta[0]['sasaran'];
					$terdaftar_peserta = str_replace("'", "", explode (", ", sql_in_list(array_column($ambil_peserta[1], 'peserta'))));

					if ($kosongkan_peserta == 1)
					{
						$pesan .= "- Data peserta " . ($ambil_peserta[0]['nama']) . " sukses dikosongkan<br>";
						$terdaftar_peserta = NULL;
					}

					foreach ($sheet->getRowIterator() as $row)
					{
						$no_baris++;
						$cells = $row->getCells();
						$peserta = (string) $cells[0];
						$nik = (string) $cells[2];

						// Data terakhir
						if ($peserta == '###') break;

						// Abaikan baris pertama / judul
						if ($no_baris <= 1) continue;

						// Cek valid data peserta sesuai sasaran
						$cek_peserta = $this->program_bantuan_model->cek_peserta($peserta, $sasaran);
						if ( ! in_array($nik, $cek_peserta['valid']))
						{
							$no_gagal++;
							$pesan .= "- Data peserta baris <b> Ke-" . ($no_baris) . " / " . $cek_peserta['sasaran_peserta'] . " = " . $peserta . "</b> tidak ditemukan <br>";
							continue;
						}

						// Cek valid data penduduk sesuai nik
						$cek_penduduk = $this->penduduk_model->get_penduduk_by_nik($nik);
						if ( ! $cek_penduduk['id'])
						{
							$no_gagal++;
							$pesan .= "- Data peserta baris <b> Ke-" . ($no_baris) . " / NIK = " . $nik . "</b> yang terdaftar tidak ditemukan <br>";
							continue;
						}

						// Cek data peserta yg akan dimpor dan yg sudah ada
						if (in_array($peserta, $terdaftar_peserta) && $ganti_peserta != 1)
						{
							$no_gagal++;
							$pesan .= "- Data peserta baris <b> Ke-" . ($no_baris) . "</b> sudah ada <br>";
							continue;
						}

						if (in_array($peserta, $terdaftar_peserta) && $ganti_peserta == 1)
						{
							$data_diubah .= ", " . $peserta;
							$pesan .= "- Data peserta baris <b> Ke-" . ($no_baris) . "</b> ditambahkan menggantikan data lama <br>";
						}

						// Random no. kartu peserta
						if ($rand_kartu == 1) $no_id_kartu = 'acak_' . random_int(1, 1000);

						// Ubaha data peserta menjadi id (untuk saat ini masih data kelompok yg menggunakan id)
						// Berkaitan dgn issue #3417
						if ($sasaran == 4) $peserta = $cek_peserta['id'];

						// Simpan data peserta yg diimpor dalam bentuk array
						$simpan = [
							'peserta' => $peserta,
							'program_id' => $program_id,
							'no_id_kartu' => ((string) $cells[1]) ? $cells[1] : $no_id_kartu,
							'kartu_nik' => $nik,
							'kartu_nama' => ((string) $cells[3]) ? $cells[3] : $cek_penduduk['nama'],
							'kartu_tempat_lahir' => ((string) $cells[4]) ? $cells[4] : $cek_penduduk['tempatlahir'],
							'kartu_tanggal_lahir' => ((string) $cells[5]) ? $cells[5] : $cek_penduduk['tanggallahir'],
							'kartu_alamat' => ((string) $cells[6]) ? $cells[6] : $cek_penduduk['alamat_wilayah'],
							'kartu_id_pend' => $cek_penduduk['id'],
						];

						array_push($data_peserta, $simpan);
						$no_sukses++;
					}

					// Proses impor peserta
					if ($no_baris <= 0)
					{
						$pesan .= "- Data peserta tidak tersedia<br>";
					}
					else
					{
						$this->program_bantuan_model->impor_peserta($program_id, $data_peserta, $kosongkan_peserta, $data_diubah);
					}
				}
			}
			$reader->close();
			unlink($file);

			$notif = [
				'gagal' => $no_gagal,
				'sukses' => $no_sukses,
				'pesan' => $pesan,
				'total' => $total,
			];

			$this->session->set_flashdata('notif', $notif);
			$this->session->per_page = $temp;

			redirect("program_bantuan/detail/$program_id");
		}
		else
		{
			$this->session->error_msg = $this->upload->display_errors();
			$this->session->success = -1;
		}

	}

	// TODO: function ini terlalu panjang dan sebaiknya dipecah menjadi beberapa method
	public function expor($program_id = '')
	{
		// Data Program Bantuan
		$temp = $this->session->per_page;
		$this->session->per_page = 1000000000;
		$data = $this->program_bantuan_model->get_program(1, $program_id);
		$tbl_program = $data[0];
		$tbl_peserta = $data[1];

		//Nama File
		$writer = WriterEntityFactory::createXLSXWriter();
		$fileName = namafile('program_bantuan_' . $tbl_program['nama']) . '.xlsx';
		$writer->openToBrowser($fileName);

		// Sheet Program
		$writer->getCurrentSheet()->setName('Program');
		$data_program = [
			['id', $tbl_program['id']],
			['Nama Program', $tbl_program['nama']],
			['Sasaran Program', $tbl_program['sasaran']],
			['Keterangan', $tbl_program['ndesc']],
			['Asal Dana', $tbl_program['asaldana']],
			['Rentang Waktu (Awal)', $tbl_program['sdate']],
			['Rentang Waktu (Akhir)', $tbl_program['edate']],
		];

		foreach ($data_program as $row)
		{
			$expor_program = [$row[0], $row[1]];
			$rowFromValues = WriterEntityFactory::createRowFromArray($expor_program);
			$writer->addRow($rowFromValues);
		}

		// Sheet Peserta
		$writer->addNewSheetAndMakeItCurrent()->setName('Peserta');
		$judul_peserta = ['Peserta', 'No. Peserta', 'NIK', 'Nama', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat'];
		$style = (new StyleBuilder())
			->setFontBold()
			->setFontSize(12)
			->setBackgroundColor(Color::YELLOW)
			->build();
		$header = WriterEntityFactory::createRowFromArray($judul_peserta, $style);
		$writer->addRow($header);

		//Isi Tabel
		foreach ($tbl_peserta as $row)
		{
			$peserta = $row['peserta'];
			// Ubah id menjadi kode untuk data kelompok
			// Berkaitan dgn issue #3417
			// Cari data kelompok berdasarkan id
			if ($tbl_program['sasaran'] == 4)
			{
				$this->load->model('kelompok_model');
				$kelompok = $this->kelompok_model->get_kelompok($peserta);
				$peserta = $kelompok['kode'];
			}

			$data_peserta = [
				$peserta,
				$row['no_id_kartu'],
				$row['kartu_nik'],
				$row['kartu_nama'],
				$row['kartu_tempat_lahir'],
				$row['kartu_tanggal_lahir'],
				$row['kartu_alamat'],
			];
			$rowFromValues = WriterEntityFactory::createRowFromArray($data_peserta);
			$writer->addRow($rowFromValues);
		}
		$writer->close();

		$this->session->per_page = $temp;
	}

}
