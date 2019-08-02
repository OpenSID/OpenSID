<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class First extends Web_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();

		// Jika offline_mode dalam level yang menyembunyikan website,
		// tidak perlu menampilkan halaman website
		if ($this->setting->offline_mode == 2)
		{
			redirect('main');
		}
		elseif ($this->setting->offline_mode == 1)
		{
			// Hanya tampilkan website jika user mempunyai akses ke menu admin/web
			// Tampilkan 'maintenance mode' bagi pengunjung website
			$this->load->model('user_model');
			$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
			if (!$this->user_model->hak_akses($grup, 'web', 'b'))
			{
				redirect('main/maintenance_mode');
			}
		}

		mandiri_timeout();
		$this->load->model('header_model');
		$this->load->model('config_model');
		$this->load->model('first_m');
		$this->load->model('first_artikel_m');
		$this->load->model('first_gallery_m');
		$this->load->model('first_menu_m');
		$this->load->model('first_penduduk_m');
		$this->load->model('penduduk_model');
		$this->load->model('surat_model');
		$this->load->model('keluarga_model');
		$this->load->model('web_widget_model');
		$this->load->model('web_gallery_model');
		$this->load->model('laporan_penduduk_model');
		$this->load->model('track_model');
		$this->load->model('keluar_model');
		$this->load->model('keuangan_model');
	}

	public function auth()
	{
		if ($_SESSION['mandiri_wait'] != 1)
		{
			$this->first_m->siteman();
		}
		if ($_SESSION['mandiri'] == 1)
			redirect('first/mandiri/1/1');
		else
			redirect('first');
	}

	public function logout()
	{
		$this->first_m->logout();
		redirect('first');
	}

	public function ganti()
	{
		$this->first_m->ganti();
		redirect('first');
	}

	public function index($p=1)
	{
		$data = $this->includes;

		$data['p'] = $p;
		$data['paging'] = $this->first_artikel_m->paging($p);
		$data['paging_page'] = 'index';
		$data['paging_range'] = 3;
		$data['start_paging'] = max($data['paging']->start_link, $p - $data['paging_range']);
		$data['end_paging'] = min($data['paging']->end_link, $p + $data['paging_range']);
		$data['pages'] = range($data['start_paging'], $data['end_paging']);

		$data['artikel'] = $this->first_artikel_m->artikel_show(0,$data['paging']->offset,$data['paging']->per_page);
		$data['headline'] = $this->first_artikel_m->get_headline();

		$cari = trim($this->input->get('cari'));
		if ( ! empty($cari))
		{
			// Judul artikel bisa digunakan untuk serangan XSS
			$data["judul_kategori"] = html_escape("Hasil pencarian:". substr($cari, 0, 50));
		}

		$this->_get_common_data($data);
		$this->track_model->track_desa('first');

		$this->load->view($this->template, $data);
	}

	public function cetak_biodata($id='')
	{
		if ($_SESSION['mandiri'] != 1)
		{
			redirect('first');
			return;
		}
		// Hanya boleh mencetak data pengguna yang login
		$id = $_SESSION['id'];

		$data['desa'] = $this->config_model->get_data();
		$data['penduduk'] = $this->penduduk_model->get_penduduk($id);
		$this->load->view('sid/kependudukan/cetak_biodata',$data);
	}

	public function cetak_kk($id='')
	{
		if ($_SESSION['mandiri'] != 1)
		{
			redirect('first');
			return;
		}
		// Hanya boleh mencetak data pengguna yang login
		$id = $_SESSION['id'];

		// $id adalah id penduduk. Cari id_kk dulu
		$id_kk = $this->penduduk_model->get_id_kk($id);
		$data = $this->keluarga_model->get_data_cetak_kk($id_kk);

		$this->load->view("sid/kependudukan/cetak_kk_all", $data);
	}

	public function kartu_peserta($id=0)
	{
		if ($_SESSION['mandiri'] != 1)
		{
			redirect('first');
			return;
		}
		$this->load->model('program_bantuan_model');
		$data = $this->program_bantuan_model->get_program_peserta_by_id($id);
		// Hanya boleh menampilkan data pengguna yang login
		// ** Bagi program sasaran pendududk **
		if ($data['peserta'] == $_SESSION['nik'])
			$this->load->view('program_bantuan/kartu_peserta',$data);
	}

	public function mandiri($p=1, $m=0)
	{
		if ($_SESSION['mandiri'] != 1)
		{
			redirect('first');
		}

		$data = $this->includes;
		$data['p'] = $p;
		$data['menu_surat2'] = $this->surat_model->list_surat2();
		$data['m'] = $m;

		$this->_get_common_data($data);

		/* nilai $m
			1 untuk menu profilku
			2 untuk menu layanan
			3 untuk menu lapor
			4 untuk menu bantuan
		*/
		switch ($m)
		{
			case 1:
				$data['penduduk'] = $this->penduduk_model->get_penduduk($_SESSION['id']);
				$data['list_kelompok'] = $this->penduduk_model->list_kelompok($_SESSION['id']);
				$data['list_dokumen'] = $this->penduduk_model->list_dokumen($_SESSION['id']);
				break;
			case 2:
				$data['surat_keluar'] = $this->keluar_model->list_data_perorangan($_SESSION['id']);
				break;
			case 4:
				$this->load->model('program_bantuan_model','pb');
				$data['daftar_bantuan'] = $this->pb->daftar_bantuan_yang_diterima($_SESSION['nik']);
				break;
			default:
				break;
		}

		$this->set_template('layouts/mandiri.php');
		$this->load->view($this->template, $data);
	}

	public function artikel($id=0, $p=1)
	{
		$data = $this->includes;

		$data['p'] = $p;
		$data['paging']  = $this->first_artikel_m->paging($p);
		$data['artikel'] = $this->first_artikel_m->list_artikel(0,$data['paging']->offset, $data['paging']->per_page);
		$data['single_artikel'] = $this->first_artikel_m->get_artikel($id);
		// replace isi artikel dengan shortcodify
		$data['single_artikel']['isi'] = $this->shortcode($data['single_artikel']['isi']);
		$data['komentar'] = $this->first_artikel_m->list_komentar($id);
		$this->_get_common_data($data);

		// Validasi pengisian komentar di add_comment()
		// Kalau tidak ada error atau artikel pertama kali ditampilkan, kosongkan data sebelumnya
		if (empty($_SESSION['validation_error']))
		{
			$_SESSION['post']['owner'] = '';
			$_SESSION['post']['email'] = '';
			$_SESSION['post']['no_hp'] = '';
			$_SESSION['post']['komentar'] = '';
			$_SESSION['post']['captcha_code'] = '';
		}
		$this->set_template('layouts/artikel.tpl.php');
		$this->load->view($this->template,$data);
	}

	public function arsip($p=1)
	{
		$data = $this->includes;
		$data['p'] = $p;
		$data['paging']  = $this->first_artikel_m->paging_arsip($p);
		$data['farsip'] = $this->first_artikel_m->full_arsip($data['paging']->offset,$data['paging']->per_page);

		$this->_get_common_data($data);

		$this->set_template('layouts/arsip.tpl.php');
		$this->load->view($this->template,$data);
	}

	// Halaman arsip album galeri
	public function gallery($p=1)
	{
		$data = $this->includes;
		$data['p'] = $p;
		$data['paging'] = $this->first_gallery_m->paging($p);
		$data['paging_range'] = 3;
		$data['start_paging'] = max($data['paging']->start_link, $p - $data['paging_range']);
		$data['end_paging'] = min($data['paging']->end_link, $p + $data['paging_range']);
		$data['pages'] = range($data['start_paging'], $data['end_paging']);
		$data['gallery'] = $this->first_gallery_m->gallery_show($data['paging']->offset, $data['paging']->per_page);

		$this->_get_common_data($data);

		$this->set_template('layouts/gallery.tpl.php');
		$this->load->view($this->template, $data);
	}

	// halaman rincian tiap album galeri
	public function sub_gallery($gal=0, $p=1)
	{
		$data = $this->includes;
		$data['p'] = $p;
		$data['gal'] = $gal;
		$data['paging'] = $this->first_gallery_m->paging2($gal, $p);
		$data['paging_range'] = 3;
		$data['start_paging'] = max($data['paging']->start_link, $p - $data['paging_range']);
		$data['end_paging'] = min($data['paging']->end_link, $p + $data['paging_range']);
		$data['pages'] = range($data['start_paging'], $data['end_paging']);

		$data['gallery'] = $this->first_gallery_m->sub_gallery_show($gal,$data['paging']->offset, $data['paging']->per_page);
		$data['parrent'] = $this->first_gallery_m->get_parrent($gal);
		$data['mode'] = 1;

		$this->_get_common_data($data);

		$this->set_template('layouts/sub_gallery.tpl.php');
		$this->load->view($this->template, $data);
	}

	public function statistik($stat=0, $tipe=0)
	{
		$data = $this->includes;

		$data['heading'] = $this->laporan_penduduk_model->judul_statistik($stat);
		$data['jenis_laporan'] = $this->laporan_penduduk_model->jenis_laporan($stat);
		$data['stat'] = $this->laporan_penduduk_model->list_data($stat);
		$data['tipe'] = $tipe;
		$data['st'] = $stat;

		$this->_get_common_data($data);

		$this->set_template('layouts/stat.tpl.php');
		$this->load->view($this->template, $data);
	}

	public function data_analisis($stat="", $sb=0, $per=0)
	{
		$data = $this->includes;

		if ($stat == "")
		{
			$data['list_indikator'] = $this->first_penduduk_m->list_indikator();
			$data['list_jawab'] = null;
			$data['indikator'] = null;
		}
		else
		{
			$data['list_indikator'] = "";
			$data['list_jawab'] = $this->first_penduduk_m->list_jawab($stat, $sb, $per);
			$data['indikator'] = $this->first_penduduk_m->get_indikator($stat);
		}

		$this->_get_common_data($data);

		$this->set_template('layouts/analisis.tpl.php');
		$this->load->view($this->template, $data);
	}

	public function dpt()
	{
		$this->load->model('dpt_model');
		$data = $this->includes;
		$data['main'] = $this->dpt_model->statistik_wilayah();
		$data['total'] = $this->dpt_model->statistik_total();
		$data['tanggal_pemilihan'] = $this->dpt_model->tanggal_pemilihan();
		$this->_get_common_data($data);
		$data['tipe'] = 4;
		$this->set_template('layouts/stat.tpl.php');
		$this->load->view($this->template, $data);
	}

	public function wilayah()
	{
		$this->load->model('wilayah_model');
		$data = $this->includes;

		$data['main']    = $this->first_penduduk_m->wilayah();
		$data['heading']="Populasi Per Wilayah";
		$data['tipe'] = 3;
		$data['total'] = $this->wilayah_model->total();
		$data['st'] = 1;
		$this->_get_common_data($data);

		$this->set_template('layouts/stat.tpl.php');
		$this->load->view($this->template, $data);
	}

	public function agenda($stat=0)
	{
		$data = $this->includes;
		$data['artikel'] = $this->first_artikel_m->agenda_show();
		$this->_get_common_data($data);
		$this->load->view($this->template,$data);
	}

	public function kategori($kat=0, $p=1)
	{
		$data = $this->includes;

		$data['p'] = $p;
		$data["judul_kategori"] = $this->first_artikel_m->get_kategori($kat);
		$data['paging']  = $this->first_artikel_m->paging_kat($p, $kat);
		$data['paging_page']  = 'kategori/'.$kat;
		$data['paging_range'] = 3;
		$data['start_paging'] = max($data['paging']->start_link, $p - $data['paging_range']);
		$data['end_paging'] = min($data['paging']->end_link, $p + $data['paging_range']);
		$data['pages'] = range($data['start_paging'], $data['end_paging']);

		$data['artikel'] = $this->first_artikel_m->list_artikel($data['paging']->offset, $data['paging']->per_page, $kat);

		$this->_get_common_data($data);
		$this->load->view($this->template, $data);
	}

	public function add_comment($id=0)
	{
		// Periksa isian captcha
		include FCPATH . 'securimage/securimage.php';
		$securimage = new Securimage();
		$_SESSION['validation_error'] = false;
		if ($securimage->check($_POST['captcha_code']) == false)
		{
			$this->session->set_flashdata('flash_message', 'Kode anda salah. Silakan ulangi lagi.');
			$_SESSION['post'] = $_POST;
			$_SESSION['validation_error'] = true;
			redirect("first/artikel/$id#kolom-komentar");
		}

		$res = $this->first_artikel_m->insert_comment($id);
		$data['data_config'] = $this->config_model->get_data();
		// cek kalau berhasil disimpan dalam database
		if ($res)
		{
			$this->session->set_flashdata('flash_message', 'Komentar anda telah berhasil dikirim dan perlu dimoderasi untuk ditampilkan.');
		}
		else
		{
			$_SESSION['post'] = $_POST;
			if (!empty($_SESSION['validation_error']))
				$this->session->set_flashdata('flash_message', validation_errors());
			else
				$this->session->set_flashdata('flash_message', 'Komentar anda gagal dikirim. Silakan ulangi lagi.');
		}

		$_SESSION['sukses'] = 1;
		redirect("first/artikel/$id#kolom-komentar");
	}

	private function _get_common_data(&$data)
	{
		$data['desa'] = $this->first_m->get_data();
		$data['menu_atas'] = $this->first_menu_m->list_menu_atas();
		$data['menu_kiri'] = $this->first_menu_m->list_menu_kiri();
		$data['teks_berjalan'] = $this->first_artikel_m->get_teks_berjalan();
		$data['slide_artikel'] = $this->first_artikel_m->slide_show();
		$data['slider_gambar'] = $this->first_artikel_m->slider_gambar();
		$data['w_cos']  = $this->web_widget_model->get_widget_aktif();
		$this->web_widget_model->get_widget_data($data);
		$data['data_config'] = $this->config_model->get_data();
		$data['flash_message'] = $this->session->flashdata('flash_message');
	  $data['widget_keuangan'] = $this->keuangan_model->widget_keuangan();
		// Pembersihan tidak dilakukan global, karena artikel yang dibuat oleh
		// petugas terpecaya diperbolehkan menampilkan <iframe> dsbnya..
		$list_kolom = array(
			'arsip',
			'w_cos'
		);
		foreach ($list_kolom as $kolom)
		{
			$data[$kolom] = $this->security->xss_clean($data[$kolom]);
		}

	}

	// Ambil jenis shortcode
	public function shortcode($str = '')
	{
		$regex = "/\[\[(.*?)\]\]/";
		return preg_replace_callback($regex, function ($matches) {
			$result = array();

			$params_explode = explode(",", $matches[1]);
			$fnName = 'extract_shortcode';
			return $this->extract_shortcode($params_explode[0],$params_explode[1],$params_explode[2]);
		}, $str);
	}

	private function extract_shortcode($type, $smt, $thn)
	{
		if ($type == 'grafik-RP-APBD') 
		{
			$data = $this->keuangan_model->rp_apbd($smt, $thn);
			$jenisbelanja = array();
			foreach ($data['jenis_belanja'] as $j) 
			{
				$jenisbelanja[] = "'". $j['Nama_Akun']. "'";
			}
			$anggaran = array();
			foreach ($data['anggaran'] as $p) 
			{
				$anggaran[] = $p['AnggaranStlhPAK'];
			}
			$realisasi = array();
			foreach ($data['realisasi'] as $s) 
			{ 
				if(!empty($s['Nilai']) || !is_null($s['Nilai']))
				{ 
					$realisasi[] =  $s['Nilai']; 
				}
				else
				{ 
					$realisasi[] =  0; 
				}
			}
			return "<div id='" . $type . "-" . $smt . "-" . $thn . "' ></div>" .
			"<script type=\"text/javascript\">".
				"$(document).ready(function (){".
					"Highcharts.chart('".$type . "-" . $smt . "-" . $thn."', {
					    chart: {
					        type: 'bar'
					    },
					    title: {
					        text: 'Realisasi Pelaksanaan APBDesa'
					    },
					    subtitle: {
					        text: 'Semester ".$smt." Tahun ".$thn."'
					    },
					    xAxis: {
					        categories: ['(PA) Pendapatan Desa', '(PA) Belanja Desa', '(PA) Pembiayaan Desa'],
					    },
					    yAxis: {
					        min: 0,
					        title: {
					            text: 'Rupiah',
					            align: 'high'
					        },
					        labels: {
					            overflow: 'justify'
					        }
					    },
					    tooltip: {
					        valueSuffix: ''
					    },
					    plotOptions: {
					        bar: {
					            dataLabels: {
					                enabled: true
					            }
					        }
					    },
					    legend: {
					        layout: 'vertical',
					        align: 'bottom',
					        verticalAlign: 'bottom',
					        x: 0,
					        y: 0,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					        name: 'Anggaran',
									color: '#16a085',
					        data: [". join($anggaran, ",")."]
						}, {
						    name: 'Realisasi',
									color: '#f1c40f',
					        data: [". join($realisasi, ",")."]
					    }]".
					"});".
				"});".
			"</script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/exporting.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts-more.js"."'></script>";
		} 
		elseif ($type == 'grafik-R-PD') 
		{
			$data = $this->keuangan_model->r_pd($smt, $thn);
			$jp = array();
			foreach ($data['jenis_pendapatan'] as $b) 
			{
				$jp[] = "'". $b['Nama_Jenis']. "'";
			}
			$anggaran = array();
			foreach ($data['anggaran'] as $a) 
			{
				$anggaran[] = $a['Pagu'];
			}
			$realisasi = array();
			foreach ($data['realisasi'] as $r) 
			{ 
				if(!empty($r['Nilai']) || !is_null($r['Nilai']))
				{ 
					$realisasi[] =  $r['Nilai']; 
				}
				else
				{ 
					$realisasi[] =  0; 
				}
			}
			return "<div id='" . $type . "-" . $smt . "-" . $thn . "' ></div>" .
			"<script type=\"text/javascript\">".
				"$(document).ready(function (){".
					"Highcharts.chart('".$type . "-" . $smt . "-" . $thn."', {
					    chart: {
					        type: 'bar'
					    },
					    title: {
					        text: 'Realisasi Pendapatan Desa'
					    },
					    subtitle: {
					        text: 'Semester ".$smt." Tahun ".$thn."'
					    },
					    xAxis: {
					        categories: [".join($jp, ",")."],
					    },
					    yAxis: {
					        min: 0,
					        title: {
					            text: 'Rupiah',
					            align: 'high'
					        },
					        labels: {
					            overflow: 'justify'
					        }
					    },
					    tooltip: {
					        valueSuffix: ''
					    },
					    plotOptions: {
					        bar: {
					            dataLabels: {
					                enabled: true
					            }
					        }
					    },
					    legend: {
					        layout: 'vertical',
					        align: 'bottom',
					        verticalAlign: 'bottom',
					        x: 0,
					        y: 0,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					        name: 'Anggaran',
									color: '#3498db',
					        data: [".join($anggaran, ",")."]
				        },{
					        name: 'Realisasi',
									color: '#e67e22',
					        data: [".join($realisasi, ",")."]
					    }]".
					"});".
				"});".
			"</script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/exporting.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts-more.js"."'></script>";
		} 
		elseif ($type == 'grafik-R-BD') 
		{
			$data = $this->keuangan_model->r_bd($smt, $thn);
			$bidang = array();
			foreach ($data['bidang'] as $b) 
			{
				$bidang[] = "'". $b['Nama_Bidang']. "'";
			}
			$anggaran = array();
			foreach ($data['anggaran'] as $a) 
			{
				$anggaran[] = $a['Pagu'];
			}
			$realisasi = array();
			foreach ($data['realisasi'] as $r) 
			{ 
				if(!empty($r['Nilai']) || !is_null($r['Nilai']))
				{ 
					$realisasi[] =  $r['Nilai']; 
				}
				else
				{ 
					$realisasi[] =  0; 
				}
			}
			return "<div id='" . $type . "-" . $smt . "-" . $thn . "' ></div>" .
			"<script type=\"text/javascript\">".
				"$(document).ready(function (){".
					"Highcharts.chart('".$type . "-" . $smt . "-" . $thn."', {
					    chart: {
					        type: 'bar'
					    },
					    title: {
					        text: 'Realisasi Belanja Desa'
					    },
					    subtitle: {
					        text: 'Semester ".$smt." Tahun ".$thn."'
					    },
					    xAxis: {
					        categories: [". join($bidang, ",")."],
					    },
					    yAxis: {
					        min: 0,
					        title: {
					            text: 'Rupiah',
					            align: 'high'
					        },
					        labels: {
					            overflow: 'justify'
					        }
					    },
					    tooltip: {
					        valueSuffix: ''
					    },
					    plotOptions: {
					        bar: {
					            dataLabels: {
					                enabled: true
					            }
					        }
					    },
					    legend: {
					        layout: 'vertical',
					        align: 'bottom',
					        verticalAlign: 'bottom',
					        x: 0,
					        y: 0,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					        name: 'Anggaran',
									color: '#2E8B57',
					        data: [". join($anggaran, ",")."]
						},{
					        name: 'Realisasi',
									color: '#3461eb',
					        data: [". join($realisasi, ",")."]
					    }]".
					"});".
				"});".
			"</script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/exporting.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts-more.js"."'></script>";
		} 
		elseif($type == 'grafik-R-PEMDES') 
		{
			$data = $this->keuangan_model->r_pembiayaan($smt, $thn);
			$pembiayaan = array();
			foreach ($data['pembiayaan'] as $d) 
			{
				$pembiayaan[] = "'". $d['Nama_Kelompok']. "'";
			}
			$anggaran = array();
			foreach ($data['anggaran'] as $a) 
			{
				$anggaran[] = $a['Pagu'];
			}
			$realisasi = array();
			foreach ($data['realisasi'] as $r) 
			{ 
				if(!empty($r['Nilai']) || !is_null($r['Nilai']))
				{ 
					$realisasi[] =  $r['Nilai']; 
				}
				else
				{ 
					$realisasi[] =  0; 
				}
			}
			return "<div id='" . $type . "-" . $smt . "-" . $thn . "' ></div>" .
			"<script type=\"text/javascript\">".
				"$(document).ready(function (){".
					"Highcharts.chart('".$type . "-" . $smt . "-" . $thn."', {
					    chart: {
					        type: 'bar'
					    },
					    title: {
					        text: 'Pembiyaan Desa'
					    },
					    subtitle: {
					        text: 'Semester ".$smt." Tahun ".$thn."'
					    },
					    xAxis: {
					        categories: [". join($pembiayaan, ",")."],
					    },
					    yAxis: {
					        min: 0,
					        title: {
					            text: 'Rupiah',
					            align: 'high'
					        },
					        labels: {
					            overflow: 'justify'
					        }
					    },
					    tooltip: {
					        valueSuffix: ''
					    },
					    plotOptions: {
					        bar: {
					            dataLabels: {
					                enabled: true
					            }
					        }
					    },
					    legend: {
					        layout: 'vertical',
					        align: 'bottom',
					        verticalAlign: 'bottom',
					        x: 0,
					        y: 0,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					        name: 'Anggaran',
									color: '#2E8B57',
					        data: [". join($anggaran, ",")."]
						},{
					        name: 'Realisasi',
									color: '#3461eb',
					        data: [". join($realisasi, ",")."]
					    }]".
					"});".
				"});".
			"</script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/exporting.js"."'></script>".
			"<script src='". base_url() . "assets/js/highcharts/highcharts-more.js"."'></script>";
		} 
		elseif($type == 'lap-RP-APBD') 
		{
			$data = $this->keuangan_model->lap_rp_apbd($smt, $thn);
			ob_start();
			echo "<style>
					table.blueTable {
					  border: 1px solid #1C6EA4;
					  background-color: #EEEEEE;
					  width: 100%;
					  text-align: left;
					  border-collapse: collapse;
					}
					table.blueTable td, table.blueTable th {
					  border: 1px solid #AAAAAA;
					  padding: 3px 2px;
					}
					table.blueTable tbody td {
					  font-size: 13px;
					}
					table.blueTable tr:nth-child(even) {
					  background: #D0E4F5;
					}
					table.blueTable thead {
					  background: #1C6EA4;
					  background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
					  background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
					  background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
					  border-bottom: 2px solid #444444;
					}
					table.blueTable thead th {
					  font-size: 15px;
					  font-weight: bold;
					  color: #FFFFFF;
					  text-align: center;
					  border-left: 2px solid #D0E4F5;
					}
					table.blueTable thead th:first-child {
					  border-left: none;
					}
					.bold{
						font-weight: bold;
					}
					.highlighted{
						background-color: #FFFF00
					}
					</style>".
			"<table class='blueTable' width='100%'>".
			"<thead><tr><th colspan='5'>Uraian</th><th>Anggaran</th><th>Realisasi</th><th>Sisa Anggaran</th><th>Persentase (%)</th></tr></thead>";
			foreach ($data['pendapatan'] as $l) 
			{
				$i=0;
				echo "<tr class='bold highlighted'>".
				"<td colspan='5'>".$l['Akun']." ".$l['Nama_Akun']."</td>".
				"<td align='right'>".number_format($l['anggaran'][0]['pagu'])."</td>".
				"<td align='right'>".number_format($l['realisasi'][0]['realisasi'])."</td>".
				"<td align='right'>".number_format($l['anggaran'][0]['pagu'] - $l['realisasi'][0]['realisasi'])."</td>".
				"<td align='right'>".number_format($l['realisasi'][0]['realisasi']/$l['anggaran'][0]['pagu']*100, 2)."</td>";
				foreach ($l['sub_pendapatan'] as $s) 
				{
					$j=0;
					echo "<tr class='bold'>".
					"<td>".$s['Kelompok']."</td><td colspan='4'>".$s['Nama_Kelompok']."</td>".
					"<td align='right'>".number_format($s['anggaran'][0]['pagu'])."</td>".
					"<td align='right'>".number_format($s['realisasi'][0]['realisasi'])."</td>".
					"<td align='right'>".number_format($s['anggaran'][0]['pagu']-$s['realisasi'][0]['realisasi'])."</td>".
					"<td align='right'>".number_format($s['realisasi'][0]['realisasi']/$s['anggaran'][0]['pagu']*100, 2)."</td>".
					"</tr>";
					foreach ($s['sub_pendapatan2'] as $q) 
					{
						echo "<tr>".
						"<td></td><td colspan='3'>".$q['Jenis']."</td>".
						"<td>".$q['Nama_Jenis']."</td>".
						"<td align='right'>".number_format($q['anggaran'][0]['pagu'])."</td>".
						"<td align='right'>".number_format($q['realisasi'][0]['realisasi'])."</td>".
						"<td align='right'>".number_format($q['anggaran'][0]['pagu']-$q['realisasi'][0]['realisasi'])."</td>".
						"<td align='right'>".number_format($q['realisasi'][0]['realisasi']/$q['anggaran'][0]['pagu']*100, 2)."</td>".
						"</tr>";
					};
					$j++;
				};			
				echo "</tr>";
				$i++;
			}
			foreach ($data['belanja'] as $b) 
			{
				$k=0;
				echo "<tr class='bold highlighted'>".
				"<td colspan='5'>".$b['Akun']." ".$b['Nama_Akun']."</td>".
				"<td align='right'>".number_format($b['anggaran'][0]['pagu'])."</td>".
				"<td align='right'>".number_format($b['realisasi'][0]['realisasi'])."</td>".
				"<td align='right'>".number_format($b['anggaran'][0]['pagu'] - $b['realisasi'][0]['realisasi'])."</td>".
				"<td align='right'>".number_format($b['realisasi'][0]['realisasi']/$b['anggaran'][0]['pagu']*100, 2)."</td>";
				foreach ($b['sub_belanja'] as $sb) 
				{
					$l=0;
					echo "<tr class='bold'>".
					"<td align='right'>".substr($sb['Kd_Bid'], 6)."."."</td><td colspan='4'>".$sb['Nama_Bidang']."</td>".
					"<td align='right'>".number_format($sb['anggaran'][0]['pagu'])."</td>".
					"<td align='right'>".number_format($sb['realisasi'][0]['realisasi'])."</td>".
					"<td align='right'>".number_format($sb['anggaran'][0]['pagu']-$sb['realisasi'][0]['realisasi'])."</td>".
					"<td align='right'>".number_format($sb['realisasi'][0]['realisasi']/$sb['anggaran'][0]['pagu']*100, 2)."</td>".
					"</tr>";
					$l++;
					foreach ($sb['sub_belanja2'] as $sb2) 
					{
						$l=0;
						echo "<tr class='bold'>".
						"<td></td><td align='right'>".substr($sb2['Kd_Keg'], 6)."</td><td colspan='3'>".$sb2['Nama_Kegiatan']."</td>".
						"<td align='right'>".number_format($sb2['anggaran'][0]['pagu'])."</td>".
						"<td align='right'>".number_format($sb2['realisasi'][0]['realisasi'])."</td>".
						"<td align='right'>".number_format($sb2['anggaran'][0]['pagu']-$sb2['realisasi'][0]['realisasi'])."</td>".
						"<td align='right'>".number_format($sb2['realisasi'][0]['realisasi']/$sb2['anggaran'][0]['pagu']*100, 2)."</td>".
						"</tr>";
						$l++;
						foreach ($sb2['sub_belanja3'] as $sb3) 
						{
							$m=0;
							echo "<tr class=''>".
							"<td></td><td></td><td align='right'>".$sb3['Jenis']."</td><td colspan='2'>".$sb3['Nama_Jenis']."</td>".
							"<td align='right'>".number_format($sb3['anggaran'][0]['pagu'])."</td>".
							"<td align='right'>".number_format($sb3['realisasi'][0]['realisasi'])."</td>".
							"<td align='right'>".number_format($sb3['anggaran'][0]['pagu']-$sb3['realisasi'][0]['realisasi'])."</td>".
							"<td align='right'>".number_format($sb3['realisasi'][0]['realisasi']/$sb3['anggaran'][0]['pagu']*100, 2)."</td>".
							"</tr>";
							$m++;
						};
					};
				};
				echo "</tr>";
				$k++;
			}
			echo "<tr class='bold highlighted'><td colspan='5' align='center'>TOTAL</td><td align='right'>".number_format($data['pendapatan'][0]['total_anggaran'][0]['pagu'])."</td><td align='right'>".number_format($data['pendapatan'][0]['total_realisasi'][0]['realisasi'])."</td><td align='right'>".number_format($data['pendapatan'][0]['total_anggaran'][0]['pagu']-$data['pendapatan'][0]['total_realisasi'][0]['realisasi'])."</td><td align='right'>".number_format($data['pendapatan'][0]['total_realisasi'][0]['realisasi']/$data['pendapatan'][0]['total_anggaran'][0]['pagu']*100, 2)."</td></tr>".
			"</table>";
			$output = ob_get_clean();
			return $output;			
		}
	}

	public function tes($smt, $thn)
	{
		$data = $this->keuangan_model->lap_rp_apbd($smt, $thn);
		print_r($data);
	}
}
