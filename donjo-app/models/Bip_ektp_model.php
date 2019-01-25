<?php

class Bip_ektp_model extends Import_model {

	public function __construct()
	{
		parent::__construct();
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
	}

	/* 	======================================================
			IMPORT BUKU INDUK PENDUDUK 2016 (LUWU TIMUR)
			======================================================
	*/

	/**
	 * Cari baris pertama mulainya blok keluarga
	 *
	 * @access	private
	 * @param		sheet			data excel berisi bip
	 * @param 	integer		jumlah baris di sheet
	 * @param 	integer		cari dari baris ini
	 * @return	integer 	baris pertama blok keluarga
	 */
	private function cari_bip_kk($data_sheet, $baris, $dari=1)
	{
		if ($baris <= 1 )
			return 0;

		$baris_kk = 0;
		for ($i=$dari; $i<=$baris; $i++)
		{
			// Baris dengan kolom[1] berisi No KK dan kolom[2] kosong menunjukkan mulainya data keluarga dan anggotanya
			if ($this->baris_awal_kk($data_sheet,$i))
			{
				$baris_kk = $i;
				break;
			}
		}
		return $baris_kk;
	}

	private function baris_awal_kk($data_sheet, $baris)
	{
		// Baris dengan kolom[1] berisi No KK dan kolom[2] kosong menunjukkan mulainya data keluarga dan anggotanya
		if (strlen(preg_replace("/[^0-9]/", '', $data_sheet[$baris][1])) == 16 AND
				trim($data_sheet[$baris][2]) == '') return true;
		else return false;
	}

	private function ambil_kolom($str, $awalan, $akhiran='')
	{
		$kolom = '';
		$pos_awal = strpos($str, $awalan);
		if ($pos_awal !== false)
		{
			$pos = $pos_awal + strlen($awalan);
			if (empty($akhiran))
				$kolom = trim(substr($str, $pos));
			else
				$kolom = trim(substr($str, $pos, strpos($str, $akhiran, $pos) - $pos));
		}
		return  $kolom;
	}

	// Normalkan kolom seperti "SLTP / SEDERAJAT" menjadi "sltp/sederajat"
	private function normalkan_data($str)
	{
		$str = preg_replace('/\s*\/\s*/', '/', strtolower(trim($str)));
		return $str;
	}

	/**
	 * Ambil data keluarga berikutnya
	 *
	 * @access	private
	 * @param		sheet		data excel berisi bip
	 * @param 	integer	cari dari baris ini
	 * @return	array 	data keluarga
	 */
	private function get_bip_keluarga($data_sheet, $i)
	{
		/* $i = baris berisi data keluarga.
		 * Contoh:
			 1605180812070010		NETI HERAWATI									DESA LUBUK BESAR RT/RW : 002/000 DUSUN : -
		 */
		$data_keluarga = array();
		$baris = $i;
		$data_keluarga['no_kk'] = trim($data_sheet[$baris][1]);
		// abaikan nama KK, karena ada di daftar anggota keluarga

		$alamat = $data_sheet[$baris][12];
		// Simpan desa pertama, karena penulisan desa tidak konsisten dan bisa kosong
		if (empty($this->desa))
		{
			$this->desa = $this->ambil_kolom($alamat, 'DESA ', 'RT/RW :');
		}

		$rtrw = $this->ambil_kolom($alamat, 'RT/RW :', ' DUSUN :');
		if ($rtrw)
		{
			list($data_keluarga['rt'],$data_keluarga['rw']) = explode('/',$rtrw);
		}

		$dusun = $this->ambil_kolom($alamat, 'DUSUN :');
		$dusun = trim(str_replace('-','',$dusun));
		if (!empty($dusun))
		{
			$data_keluarga['dusun'] = $dusun;
		}
		else
		{
			// Kalau dusun kosong dianggap sama dengan nama desa
			$data_keluarga['dusun'] = $this->desa;
		}

		return $data_keluarga;
	}

	/**
	 * Ambil data anggota keluarga berikutnya
	 *
	 * @access	private
	 * @param		sheet		data excel berisi bip
	 * @param 	integer	cari dari baris ini
	 * @param 	array		data keluarga untuk anggota yg dicari
	 * @return	array 	data anggota keluarga
	 */
	private function get_bip_anggota_keluarga($data_sheet, $i, $data_keluarga)
	{
		/* $i = baris data anggota keluarga
		 * Contoh:
1		2							3									4							5							6		7					8					9
NO	Nama Lengkap	NIK								Tempat Lahir 	Tanggal Lahir	JK	Stat Kwn	Gol. Drh	SHDK
1		NETI HERAWATI	1605186512620000	LUBUK BESAR		25-12-1962		Pr	CM				-					KEPALA KELUARGA

10		11
Agama	Pendidikan Terakhir
ISLAM	TAMAT SD / SEDERAJAT

12							13										14						15				16			17			18			19
No Akta Lahir		Pekerjaan							Nama Ibu			Nama Ayah	Wjb KTP	KTP-eL	Status	Stat Rkm
6767/TAMB/2002	BELUM / TIDAK BEKERJA	NETI HERAWATI	WARTA			WAJIB		KTP-eL	SDH DPT	CARD SHIPPED
		*/
		$data_anggota = $data_keluarga;
		$data_anggota['nama'] = trim($data_sheet[$i][2]);
		$data_anggota['nik'] = preg_replace('/[^0-9]/', '', trim($data_sheet[$i][3]));
		$data_anggota['tempatlahir'] = trim($data_sheet[$i][4]);
		$tanggallahir = trim($data_sheet[$i][5]);
		$data_anggota['tanggallahir'] = $this->format_tanggal($tanggallahir);
		$data_anggota['sex'] = $this->get_kode($this->kode_sex, trim($data_sheet[$i][6]));
		$data_anggota['status_kawin'] = $this->get_kode($this->kode_status, strtolower(trim($data_sheet[$i][7])));
		$data_anggota['golongan_darah_id'] = $this->get_kode($this->kode_golongan_darah, strtolower(trim($data_sheet[$i][8])));
		if (empty($data_anggota['golongan_darah_id']) OR $data_anggota['golongan_darah_id'] == '-')
			$data_anggota['golongan_darah_id'] = 13;
		$data_anggota['kk_level'] = $this->get_kode($this->kode_hubungan, strtolower(trim($data_sheet[$i][9])));
		$data_anggota['agama_id'] = $this->get_kode($this->kode_agama, strtolower(trim($data_sheet[$i][10])));
		$data_anggota['pendidikan_kk_id'] = $this->get_kode($this->kode_pendidikan, $this->normalkan_data($data_sheet[$i][11]));
		$data_anggota['akta_lahir'] = trim($data_sheet[$i][12]);
		$data_anggota['pekerjaan_id'] = $this->get_kode($this->kode_pekerjaan, $this->normalkan_data($data_sheet[$i][13]));
		$nama_ibu = trim($data_sheet[$i][14]);
		if ($nama_ibu == "")
		{
			$nama_ibu = "-";
		}
		$data_anggota['nama_ibu'] = $nama_ibu;
		$nama_ayah = trim($data_sheet[$i][15]);
		if ($nama_ayah == "")
		{
			$nama_ayah = "-";
		}
		$data_anggota['nama_ayah'] = $nama_ayah;
		/* Kolom 16-19 data eKTP; kolom 16 diabaikan karena ditentukan oleh tgl lahir
			 dan status kawin;
		   kolom 18 diabaikan karena pada dasarnya sama dgn kolom 19
		 */
		$data_anggota['ktp_el'] = $this->kode_ktp_el[strtolower(trim($data_sheet[$i][17]))];
		$data_anggota['status_rekam'] = $this->get_status_rekam($data_sheet, $i);

		// Isi kolom default
		$data_anggota['warganegara_id'] = "1";
		$data_anggota['pendidikan_sedang_id'] = "";

		return $data_anggota;
	}

	private function get_status_rekam($data_sheet, $i)
	{
		// Kolom status_rekam bisa ada karakter baris baru
		$status_rekam = preg_replace('/[^a-zA-Z, ]/', ' ', strtolower(trim($data_sheet[$i][19])));
		$status_rekam = preg_replace('/\s+/', ' ', $status_rekam);
		$kode_status_rekam = $this->kode_status_rekam[$status_rekam];
		// Mungkin bagian dari status rekam tampil di baris data berikutnya
		// (lewati footer dan kemungkinan baris kosong)
		$j = $i + 2;
		while (empty($kode_status_rekam) and ($j < $i+5))
		{
			$j++;
			$status_rekam_coba = $status_rekam.' '.preg_replace('/[^a-zA-Z, ]/', ' ', strtolower(trim($data_sheet[$j][19])));
			$status_rekam_coba = preg_replace('/\s+/', ' ', $status_rekam_coba);
			$kode_status_rekam = $this->kode_status_rekam[$status_rekam_coba];
		}
		return $kode_status_rekam;
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
		$gagal_penduduk = 0;
		$baris_gagal = "";
		$total_keluarga = 0;
		$total_penduduk = 0;

		// BIP bisa terdiri dari beberapa worksheet
		// Proses sheet satu-per-satu
		for ($sheet_index=0; $sheet_index<count($data->boundsheets); $sheet_index++)
		{
			// membaca jumlah baris di sheet ini
			$baris = $data->rowcount($sheet_index);
			$data_sheet = $data->sheets[$sheet_index]['cells'];
			if ($this->cari_bip_kk($data_sheet, $baris, 1) < 1)
			{
				// Tidak ada data keluarga
				continue;
			}
			// Import data sheet ini mulai baris pertama
			for ($i=1; $i<=$baris; $i++)
			{
				// Cari keluarga berikutnya
				if (!$this->baris_awal_kk($data_sheet,$i)) continue;
				// Proses keluarga
				$data_keluarga = $this->get_bip_keluarga($data_sheet, $i);
				$this->tulis_tweb_wil_clusterdesa($data_keluarga);
				$this->tulis_tweb_keluarga($data_keluarga);
				$total_keluarga++;
				// Pergi ke data anggota keluarga
				$i = $i + 1;
				// Proses setiap anggota keluarga
				while (trim($data_sheet[$i][1]) > 0 AND trim($data_sheet[$i][2]) != '' AND $i <= $baris)
				{
					$data_anggota = $this->get_bip_anggota_keluarga($data_sheet, $i, $data_keluarga);
					$error_validasi = $this->data_import_valid($data_anggota);
					if (empty($error_validasi))
					{
						$this->tulis_tweb_penduduk($data_anggota);
						$total_penduduk++;
					}
					else
					{
						$gagal_penduduk++;
						$baris_gagal .= $i." (".$error_validasi.")<br>";
					}
					$i++;
				}
				$i = $i - 1;
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

}
?>
