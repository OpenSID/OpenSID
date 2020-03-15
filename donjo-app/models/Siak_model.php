<?php

define("KOLOM_IMPOR_SIAK", serialize(array(
  "no_kk" => "1",
  "nik" => "2",
  "nama"  => "3",
  "status_dasar" => "4",
  "tempatlahir" => "5",
  "tanggallahir" => "6",
  "sex"  => "7",
  "ayah_nik" => "8",
  "nama_ayah" => "9",
  "ibu_nik"  => "10",
  "nama_ibu" => "11",
  "status_kawin" => "12",
  "kk_level" => "13",
  "agama_id" => "14",
  "alamat"  => "17",
  "rw" => "18",
  "rt" => "19",
  "pendidikan_kk_id"  => "20",
  "pekerjaan_id" => "21",
  "golongan_darah_id" => "23",
  "cacat_id" => "24",
  "dokumen_pasport" => "28",
  "akta_lahir" => "29",
  "akta_perkawinan" => "30",
  "tanggalperkawinan" => "31",
  "akta_perceraian" => "32",
  "tanggalperceraian" => "33",
  "tgl_entri" => "37"
)));

class Siak_model extends Import_model {

	public function __construct()
	{
		parent::__construct();
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$this->load->model('penduduk_model');
	}

	/* 	======================================================
			IMPOR DATA DALAM FORMAT SIAK
			======================================================
	*/

	private function cari_baris_pertama($data, $baris)
	{
		if ($baris <=1 )
			return 0;

		$baris_pertama = 1;
		for ($i=2; $i<=$baris; $i++)
		{
			// Baris dengan tiga kolom pertama kosong menandakan baris tanpa data
			if ($data->val($i, 1) == '' AND $data->val($i, 2) == '' AND $data->val($i, 3) == '')
			{
				continue;
			}
			else
			{
				// Ketemu baris data pertama
				$baris_pertama = $i;
				break;
			}
		}
		return $baris_pertama;
	}

	private function get_isi_baris($data, $i)
	{
		$kolom_impor = unserialize(KOLOM_IMPOR_SIAK);
		$isi_baris['alamat'] = trim($data->val($i, $kolom_impor['alamat']));
		// alamat berbentuk 'DSN LIWET'
		$pecah_alamat = preg_split("/DSN |DS |DUSUN |DSN\. |DS\. |DUSUN\. /i", $isi_baris['alamat']);
		$isi_baris['alamat'] = $pecah_alamat[0];
		$isi_baris['dusun'] = $pecah_alamat[1];
		if (empty($isi_baris['dusun'])) $isi_baris['dusun'] = $isi_baris['alamat'];

		$isi_baris['rw'] = ltrim(trim($data->val($i, $kolom_impor['rw'])), "'");
		$isi_baris['rt'] = ltrim(trim($data->val($i, $kolom_impor['rt'])), "'");

		$nama = trim($data->val($i, $kolom_impor['nama']));
		$nama = preg_replace("/[^a-zA-Z,\.'-]/", ' ', $nama);
		$isi_baris['nama'] = $nama;

		$isi_baris['status_dasar'] = $this->get_konversi_kode($this->kode_status_dasar, trim($data->val($i, $kolom_impor['status_dasar'])));

		// Data Disdukcapil adakalanya berisi karakter tambahan pada no_kk dan nik
		// yang tidak tampak (non-printable characters),
		// jadi perlu dibuang
		$no_kk = trim($data->val($i, $kolom_impor['no_kk']));
		$no_kk = preg_replace('/[^0-9]/', '', $no_kk);
		$isi_baris['no_kk'] = $no_kk;

		$isi_baris['nik'] = buang_nondigit($data->val($i, $kolom_impor['nik']));
		$isi_baris['sex'] = $this->get_konversi_kode($this->kode_sex, trim($data->val($i, $kolom_impor['sex'])));
		$isi_baris['tempatlahir']= trim($data->val($i, $kolom_impor['tempatlahir']));

		$isi_baris['tanggallahir'] = $this->format_tanggal($data->val($i, $kolom_impor['tanggallahir']));

		$isi_baris['agama_id']= $this->get_konversi_kode($this->kode_agama, trim($data->val($i, $kolom_impor['agama_id'])));
		$isi_baris['pendidikan_kk_id']= $this->get_konversi_kode($this->kode_pendidikan, $this->normalkan_data(trim($data->val($i, $kolom_impor['pendidikan_kk_id']))));

		$isi_baris['pekerjaan_id']= $this->get_konversi_kode($this->kode_pekerjaan, $this->normalkan_data(trim($data->val($i, $kolom_impor['pekerjaan_id']))));
		$isi_baris['status_kawin']= $this->get_konversi_kode($this->kode_status, trim($data->val($i, $kolom_impor['status_kawin'])));
		$isi_baris['kk_level']= $this->get_konversi_kode($this->kode_hubungan, trim($data->val($i, $kolom_impor['kk_level'])));
		// TODO: belum ada kode_warganegara
		$isi_baris['warganegara_id']= trim($data->val($i, $kolom_impor['warganegara_id']));

		$nama_ayah = trim($data->val($i,$kolom_impor['nama_ayah']));
		if ($nama_ayah == "")
		{
			$nama_ayah = "-";
		}
		$isi_baris['nama_ayah'] = $nama_ayah;

		$nama_ibu = trim($data->val($i,$kolom_impor['nama_ibu']));
		if ($nama_ibu == "")
		{
			$nama_ibu = "-";
		}
		$isi_baris['nama_ibu'] = $nama_ibu;

		$isi_baris['golongan_darah_id'] = $this->get_konversi_kode($this->kode_golongan_darah, trim($data->val($i, $kolom_impor['golongan_darah_id'])));
		$isi_baris['akta_lahir'] = trim($data->val($i, $kolom_impor['akta_lahir']));
		$isi_baris['dokumen_pasport'] = trim($data->val($i, $kolom_impor['dokumen_pasport']));

		$isi_baris['ayah_nik'] = buang_nondigit($data->val($i, $kolom_impor['ayah_nik']));
		$isi_baris['ibu_nik'] = buang_nondigit($data->val($i, $kolom_impor['ibu_nik']));
		$isi_baris['akta_perkawinan'] = trim($data->val($i, $kolom_impor['akta_perkawinan']));
		$isi_baris['tanggalperkawinan'] = $this->format_tanggal($data->val($i, $kolom_impor['tanggalperkawinan']));
		$isi_baris['akta_perceraian'] = trim($data->val($i, $kolom_impor['akta_perceraian']));
		$isi_baris['tanggalperceraian'] = $this->format_tanggal($data->val($i, $kolom_impor['tanggalperceraian']));
		$isi_baris['cacat_id'] = $this->get_konversi_kode($this->kode_cacat, trim($data->val($i, $kolom_impor['cacat_id'])));

		// Untuk tulis ke log_penduduk
		$isi_baris['status_dasar_orig'] = trim($data->val($i, $kolom_impor['status_dasar']));
		$isi_baris['tgl_entri'] = $this->format_tanggal($data->val($i, $kolom_impor['tgl_entri']));
		return $isi_baris;
	}

	// Normalkan kolom seperti "SLTP / SEDERAJAT" menjadi "sltp/sederajat"
	private function normalkan_data($str)
	{
		$str = preg_replace('/\s*\/\s*/', '/', strtolower(trim($str)));
		return $str;
	}

	/**
	 * Proses impor data bip
	 *
	 * @access	public
	 * @param		sheet		data excel berisi bip
	 * @return	setting $_SESSION untuk info hasil impor
			$_SESSION['gagal']=						jumlah baris yang gagal
			$_SESSION['total_keluarga']=	jumlah keluarga yang diimpor
			$_SESSION['total_penduduk']=	jumlah penduduk yang diimpor
			$_SESSION['baris']=						daftar baris yang gagal
	 */
	public function impor_data_bip($data)
	{
		// membaca jumlah baris dari data excel
		$baris = $data->rowcount($sheet_index = 0);
		if ($this->cari_baris_pertama($data, $baris) <= 1)
		{
			$_SESSION['error_msg'] .= " -> Tidak ada data";
			$_SESSION['success'] = -1;
			return;
		}

		$gagal_penduduk = 0;
		$baris_gagal = "";
		$total_keluarga = 0;
		$total_penduduk = 0;

		// Import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
		for ($i=2; $i<=$baris; $i++)
		{
			// Baris dengan tiga kolom pertama kosong menandakan baris tanpa data
			if ($data->val($i, 1) == '' AND $data->val($i, 2) == '' AND $data->val($i, 3) == '')
			{
				continue;
			}

			$isi_baris = $this->get_isi_baris($data, $i);
			$error_validasi = $this->data_import_valid($isi_baris);
			if (empty($error_validasi))
			{
				$this->tulis_tweb_wil_clusterdesa($isi_baris);
				if ($this->tulis_tweb_keluarga($isi_baris))
					$total_keluarga++;
				$penduduk_baru = $this->tulis_tweb_penduduk($isi_baris);
				if ($penduduk_baru)
				{
					$total_penduduk++;
					// Tulis log kalau status dasar MATI, HILANG atau PINDAH
					if (in_array($isi_baris['status_dasar'], array('2', '3', '4')))
						$this->tulis_log_penduduk($isi_baris, $penduduk_baru);
				}
			}
			else
			{
				$gagal_penduduk++;
				$baris_gagal .= $i." (".$error_validasi.")<br>";
			}
		}

		if ($gagal_penduduk == 0)
			$baris_gagal = "tidak ada data yang gagal di import.";
		else $_SESSION['success'] = -1;

		$_SESSION['gagal'] = $gagal_penduduk;
		$_SESSION['total_keluarga'] = $total_keluarga;
		$_SESSION['total_penduduk'] = $total_penduduk;
		$_SESSION['baris'] = $baris_gagal;
	}

	private function tulis_log_penduduk($data, $id)
	{
		// Tulis log_penduduk
		$log['id_pend'] = $id;
		$log['no_kk'] = $data['no_kk'];
		$log['tgl_peristiwa'] = $data['tgl_entri'];
		$log['id_detail'] = $data['status_dasar'];
		$log['bulan'] = date("m");
		$log['tahun'] = date("Y");
		$log['catatan'] = 'Status impor data SIAK: '.$data['status_dasar_orig'];

		$this->penduduk_model->tulis_log_penduduk_data($log);
	}

}
?>
