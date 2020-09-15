<?php

define("KOLOM_IMPOR_SIAK", serialize(array(
  "no_kk" => "0",
  "nik" => "1",
  "nama"  => "2",
  "status_dasar" => "3",
  "tempatlahir" => "4",
  "tanggallahir" => "5",
  "sex"  => "6",
  "ayah_nik" => "7",
  "nama_ayah" => "8",
  "ibu_nik"  => "9",
  "nama_ibu" => "10",
  "status_kawin" => "11",
  "kk_level" => "12",
  "agama_id" => "13",
  "alamat"  => "16",
  "rw" => "17",
  "rt" => "18",
  "pendidikan_kk_id"  => "19",
  "pekerjaan_id" => "20",
  "golongan_darah_id" => "22",
  "cacat_id" => "23",
  "dokumen_pasport" => "27",
  "akta_lahir" => "28",
  "akta_perkawinan" => "29",
  "tanggalperkawinan" => "30",
  "akta_perceraian" => "31",
  "tanggalperceraian" => "32",
  "tgl_entri" => "36"
)));

require_once 'vendor/spout/src/Spout/Autoloader/autoload.php';
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Siak_model extends Import_model {

	public function __construct()
	{
		parent::__construct();
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
    $this->load->model('import_model');
		$this->load->model('penduduk_model');
	}

	/* 	======================================================
			IMPOR DATA DALAM FORMAT SIAK
			======================================================
	*/

	private function get_isi_baris($rowData)
	{
    $kolom_impor = unserialize(KOLOM_IMPOR_SIAK);
    $isi_baris['alamat'] = trim($rowData[$kolom_impor['alamat']]);
    // alamat berbentuk 'DSN LIWET'
    $pecah_alamat = preg_split("/DSN |DS |DUSUN |DSN\. |DS\. |DUSUN\. /i", $isi_baris['alamat']);
    $isi_baris['alamat'] = $pecah_alamat[0];
    $isi_baris['dusun'] = $pecah_alamat[1];
    if (empty($isi_baris['dusun'])) $isi_baris['dusun'] = $isi_baris['alamat'];

    $isi_baris['rw'] = ltrim(trim($rowData[$kolom_impor['rw']]), "'");
    $isi_baris['rt'] = ltrim(trim($rowData[$kolom_impor['rt']]), "'");

    $nama = trim($rowData[$kolom_impor['nama']]);
    $nama = preg_replace('/[^a-zA-Z0-9,\.\']/', ' ', $nama);
    $isi_baris['nama'] = $nama;

    $isi_baris['status_dasar'] = $this->get_konversi_kode($this->kode_status_dasar, trim($rowData[$kolom_impor['status_dasar']]));

    // Data Disdukcapil adakalanya berisi karakter tambahan pada no_kk dan nik
    // yang tidak tampak (non-printable characters),
    // jadi perlu dibuang
    $no_kk= trim($rowData[$kolom_impor['no_kk']]);
    $no_kk = preg_replace('/[^0-9]/', '', $no_kk);
    $isi_baris['no_kk'] = $no_kk;

    $isi_baris['nik'] = buang_nondigit($rowData[$kolom_impor['nik']]);
    $isi_baris['sex'] = $this->get_konversi_kode($this->kode_sex, trim($rowData[$kolom_impor['sex']]));
    $isi_baris['tempatlahir']= trim($rowData[$kolom_impor['tempatlahir']]);

    $isi_baris['tanggallahir'] = $this->format_tanggal($rowData[$kolom_impor['tanggallahir']]);

    $isi_baris['agama_id']= $this->get_konversi_kode($this->kode_agama, trim($rowData[$kolom_impor['agama_id']]));
    $isi_baris['pendidikan_kk_id']= $this->get_konversi_kode($this->kode_pendidikan, trim($rowData[$kolom_impor['pendidikan_kk_id']]));

    $isi_baris['pekerjaan_id']= $this->get_konversi_kode($this->kode_pekerjaan, trim($rowData[$kolom_impor['pekerjaan_id']]));
    $isi_baris['status_kawin']= $this->get_konversi_kode($this->kode_status, trim($rowData[$kolom_impor['status_kawin']]));
    $isi_baris['kk_level']= $this->get_konversi_kode($this->kode_hubungan, trim($rowData[$kolom_impor['kk_level']]));
    // TODO: belum ada kode_warganegara
    $isi_baris['warganegara_id']= trim($rowData[$kolom_impor['warganegara_id']]);

    $nama_ayah = trim($rowData[$kolom_impor['nama_ayah']]);
    if ($nama_ayah == "")
    {
      $nama_ayah = "-";
    }
    $isi_baris['nama_ayah'] = $nama_ayah;

    $nama_ibu = trim($rowData[$kolom_impor['nama_ibu']]);
    if ($nama_ibu == "")
    {
      $nama_ibu = "-";
    }
    $isi_baris['nama_ibu'] = $nama_ibu;

    $isi_baris['golongan_darah_id'] = $this->get_konversi_kode($this->kode_golongan_darah, trim($rowData[$kolom_impor['golongan_darah_id']]));
    $isi_baris['akta_lahir'] = trim($rowData[$kolom_impor['akta_lahir']]);
    $isi_baris['dokumen_pasport'] = trim($rowData[$kolom_impor['dokumen_pasport']]);

    $isi_baris['ayah_nik'] = buang_nondigit(trim($rowData[$kolom_impor['ayah_nik']]));
    $isi_baris['ibu_nik'] = buang_nondigit(trim($rowData[$kolom_impor['ibu_nik']]));
    $isi_baris['akta_perkawinan'] = trim($rowData[$kolom_impor['akta_perkawinan']]);
    $isi_baris['tanggalperkawinan'] = $this->format_tanggal($rowData[$kolom_impor['tanggalperkawinan']]);
    $isi_baris['akta_perceraian'] = trim($rowData[$kolom_impor['akta_perceraian']]);
    $isi_baris['tanggalperceraian'] = $this->format_tanggal($rowData[$kolom_impor['tanggalperceraian']]);
    $isi_baris['cacat_id'] = $this->get_konversi_kode($this->kode_cacat, trim($rowData[$kolom_impor['cacat_id']]));

    // Untuk tulis ke log_penduduk
    $isi_baris['status_dasar_orig'] = trim($rowData[$kolom_impor['status_dasar']]);
    $isi_baris['tgl_entri'] = $this->format_tanggal($rowData[$kolom_impor['tgl_entri']]);
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
	public function impor_data_bip($hapus=false)
	{
    $_SESSION['error_msg'] = '';
		$_SESSION['success'] = 1;
		if ($this->file_import_valid() == false)
		{
			return;
		}

    // Pengguna bisa menentukan apakah data penduduk yang ada dihapus dulu
    // atau tidak sebelum melakukan impor
    if ($hapus) { $this->hapus_data_penduduk(); }

    $numRows = 0;

    $reader = ReaderEntityFactory::createXLSXReader();
		$reader->setShouldPreserveEmptyRows(true);
    $reader->open($_FILES['userfile']['tmp_name']);

    foreach ($reader->getSheetIterator() as $sheet)
    {
      $gagal = 0;
      $baris_gagal = "";
      $baris_data = 0;
      $baris_pertama = false;
      $nomor_baris = 0;

      $gagal_penduduk = 0;
  		$total_keluarga = 0;
  		$total_penduduk = 0;

      foreach ($sheet->getRowIterator() as $row)
      {
      	$nomor_baris++;
        $rowData = [];
        $cells = $row->getCells();

        foreach ($cells as $cell)
        {
        	$rowData[] = $cell->getValue();
        }

	      // Baris dengan kolom dusun = '###' menunjukkan telah sampai pada baris data terakhir
	      if ($rowData[1] == '###') break;

	      // Baris dengan dusun/rw/rt kosong menandakan baris tanpa data
	      if ($rowData[1] == '' AND $rowData[2] == '' AND $rowData[3] == '') continue;

	      // Baris pertama diabaikan, berisi nama kolom
	      if (! $baris_pertama)
	      {
	      	$baris_pertama = true;
	      	continue;
	      }

        $baris_data++;

        $this->db->query("SET character_set_connection = utf8");
        $this->db->query("SET character_set_client = utf8");

        $isi_baris = $this->get_isi_baris($rowData);
        $error_validasi = $this->import_model->data_import_valid($isi_baris);
        if (empty($error_validasi))
        {
  				$this->import_model->tulis_tweb_wil_clusterdesa($isi_baris);
  				if ($this->import_model->tulis_tweb_keluarga($isi_baris))
  					$total_keluarga++;
  				$penduduk_baru = $this->import_model->tulis_tweb_penduduk($isi_baris);
  				if ($penduduk_baru)
  				{
  					$total_penduduk++;
  					// Tulis log kalau status dasar MATI, HILANG atau PINDAH
  					if (in_array($isi_baris['status_dasar'], array('2', '3', '4')))
  						$this->tulis_log_penduduk($isi_baris, $penduduk_baru);
  				}
          if ($error = $this->error_tulis_penduduk)
          {
	          $gagal_penduduk++;
	          $baris_gagal .= $nomor_baris." (".$error['message'].")<br>";
          }
  			}
  			else
  			{
  				$gagal_penduduk++;
  				$baris_gagal .= $nomor_baris." (".$error_validasi.")<br>";
  			}

      }

      if ($baris_data <= 0)
      {
        $_SESSION['error_msg'] .= " -> Tidak ada data";
        $_SESSION['success'] = -1;
        return;
      }

      if ($gagal_penduduk == 0)
  			$baris_gagal = "tidak ada data yang gagal di import.";
  		else $_SESSION['success'] = -1;

  		$_SESSION['gagal'] = $gagal_penduduk;
  		$_SESSION['total_keluarga'] = $total_keluarga;
  		$_SESSION['total_penduduk'] = $total_penduduk;
  		$_SESSION['baris'] = $baris_gagal;

    }
    $reader->close();
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
