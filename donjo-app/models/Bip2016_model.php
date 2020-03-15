<?php

class Bip2016_model extends Import_model {

	public function __construct()
	{
		parent::__construct();
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
	}

	/* 	===============================
			IMPORT BUKU INDUK PENDUDUK 2016
			===============================
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
			// Baris dengan kolom[1] yang mulai dengan "No. KK" menunjukkan mulainya data keluarga dan anggotanya
			if (strpos($data_sheet[$i][1], 'No. KK') === 0)
			{
				$baris_kk = $i;
				break;
			}
		}
		return $baris_kk;
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
		// Contoh alamat: "Alamat : MERTAK PAOK, Nama Dusun : MERTAK PAOK, RT/RW : -/-"
		// $i = baris berisi data keluarga.
		$baris = $i;
		$alamat = $data_sheet[$baris][3];
		$pos_awal = strpos($alamat, 'Alamat :');
		if ($pos_awal !== false){
			$pos = $pos_awal + strlen('Alamat :');
			$data_keluarga['alamat'] = trim(substr($alamat, $pos, strpos($alamat, ',', $pos) - $pos));
		} else $data_keluarga['alamat'] = '';
		$pos_awal = strpos($alamat, 'Nama Dusun :');
		if ($pos_awal !== false)
		{
			$pos = $pos_awal + strlen('Nama Dusun :');
			$data_keluarga['dusun'] = trim(substr($alamat, $pos, strpos($alamat, ',', $pos) - $pos));
		}
		else $data_keluarga['dusun'] = 'LAINNYA';
		$pos_rtrw = strpos($alamat, 'RT/RW :');
		if ($pos_rtrw !== false)
		{
			$pos_rtrw = $pos_rtrw + strlen('RT/RW :');
			$pos_rw = strpos($alamat, '/', $pos_rtrw);
			$pos = $pos_rw + strlen('/');
			$data_keluarga['rw'] = trim(substr($alamat, $pos, strlen($alamat) - $pos));
		}
		else $data_keluarga['rw'] = '-';
		if ($data_keluarga['rw'] == '') $data_keluarga['rw'] = '-';
		if ($pos_rtrw !== false)
		{
			$data_keluarga['rt'] = trim(substr($alamat, $pos_rtrw, $pos_rw - $pos_rtrw));
		}
		else $data_keluarga['rt'] = '-';
		if ($data_keluarga['rt'] == '') $data_keluarga['rt'] = '-';
		// Contoh No. KK : 5202030102110012
		$no_kk = $data_sheet[$baris][1];
		$pos_awal = strpos($no_kk, 'No. KK :');
		if ($pos_awal !== false)
		{
			$pos = $pos_awal + strlen('No. KK :');
			$data_keluarga['no_kk'] = preg_replace('/[^0-9]/', '', trim(substr($no_kk, $pos, strlen($no_kk) - $pos)));
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
		// $i = baris data anggota keluarga
		$data_anggota = $data_keluarga;
		$data_anggota['nama'] = trim($data_sheet[$i][2]);
		$data_anggota['nik'] = preg_replace('/[^0-9]/', '', trim($data_sheet[$i][3]));
		$data_anggota['tempatlahir'] = trim($data_sheet[$i][4]);
		$tanggallahir = trim($data_sheet[$i][5]);
		$data_anggota['tanggallahir'] = $this->format_tanggal($tanggallahir);
		$data_anggota['sex'] = $this->get_kode($this->kode_sex, trim($data_sheet[$i][6]));
		$data_anggota['kk_level'] = $this->get_kode($this->kode_hubungan, strtolower(trim($data_sheet[$i][7])));
		$data_anggota['agama_id'] = $this->get_kode($this->kode_agama, strtolower(trim($data_sheet[$i][8])));
		$data_anggota['pendidikan_kk_id'] = $this->get_kode($this->kode_pendidikan, strtolower(trim($data_sheet[$i][9])));
		$data_anggota['pekerjaan_id'] = $this->get_kode($this->kode_pekerjaan, strtolower(trim($data_sheet[$i][10])));
		$nama_ibu = trim($data_sheet[$i][11]);
		$data_anggota['nama_ibu'] = ($nama_ibu == "") ? "-" : $nama_ibu;
		$nama_ayah = trim($data_sheet[$i][12]);
		$data_anggota['nama_ayah'] = ($nama_ayah == "") ? "-" : $nama_ayah;

		// Isi kolom default
		$data_anggota['status_kawin'] = "";
		$data_anggota['akta_lahir'] = "";
		$data_anggota['warganegara_id'] = "1";
		$data_anggota['golongan_darah_id'] = "13";
		$data_anggota['pendidikan_sedang_id'] = "";

		return $data_anggota;
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
				// Baris-baris keterangan ada di akhir berkas BIP 2016. Selesai apabila ketemu.
				if(strpos($data_sheet[$i][1], 'Keterangan:') === 0) break;

				// Cari keluarga berikutnya
				if(strpos($data_sheet[$i][1], 'No. KK') !== 0) continue;
				// Proses keluarga
				$data_keluarga = $this->get_bip_keluarga($data_sheet, $i);
				$this->tulis_tweb_wil_clusterdesa($data_keluarga);
				$this->tulis_tweb_keluarga($data_keluarga);
				$total_keluarga++;
				// Pergi ke data anggota keluarga
				$i = $i + 1;
				// Proses setiap anggota keluarga
				while (strpos($data_sheet[$i][1], 'No. KK') !== 0 AND $i <= $baris)
				{
					if (!is_numeric($data_sheet[$i][1])) break;
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
			$baris_gagal ="tidak ada data yang gagal di import.";
		else $_SESSION['success'] = -1;

		$_SESSION['gagal'] = $gagal_penduduk;
		$_SESSION['total_keluarga'] = $total_keluarga;
		$_SESSION['total_penduduk'] = $total_penduduk;
		$_SESSION['baris'] = $baris_gagal;
	}

}
?>
