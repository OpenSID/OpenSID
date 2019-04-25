<?php
class Keuangan_model extends CI_model {

	public function __construct()
	{
		parent::__construct();
	  $this->load->library('upload');
    $this->load->helper('donjolib');
    $this->load->helper('pict_helper');
    $this->uploadConfig = array(
      'upload_path' => LOKASI_KEUANGAN_ZIP,
      'allowed_types' => 'zip',
      'max_size' => max_upload()*1024,
    );
	}

	public function extract($path)
	{
		$this->upload->initialize($this->uploadConfig);
		$adaLampiran = !empty($_FILES['satuan']['name']);
		if ($this->upload->do_upload('keuangan')){
				$data      = $this->upload->data();
				$zip_file  = new ZipArchive;
				$full_path = $data['full_path'];
				$zip_file->extractTo(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name']);
				$handle = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Version.csv', "r");
				$header = fgetcsv($handle);
				$jml_kolom = count($header);
				while (($csv = fgetcsv($handle)) !== FALSE)
				{
					for ($c=0; $c < $jml_kolom; $c++)
					{
						$dataku[$header[$c]] = $csv[$c];
					}
				}
				$data2['versi_database'] = $dataku['Versi'];
				$data2['tahun_anggaran'] = $_POST['tahun_anggaran'];
				$data2['aktif'] = 1;
				if ($this->db->insert('keuangan_master', $data2)) {
						$id_master_keuangan = $this->db->insert_id();
						//insert ref desa
						$csvLines= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Desa.csv', "r");
				    while (($data3 = fgetcsv($csvLines)) !== FALSE) {
									$rows[] = $data3;
				    }
						foreach ($rows as $value) {
										$data_ref_desa['id_keuangan_master'] = $id_master_keuangan;
										$data_ref_desa['kd_kec'] = $value[0];
										$data_ref_desa['kd_desa'] = $value[1];
										$data_ref_desa['nama_desa'] = $value[2];
										$this->db->insert('keuangan_ref_desa', $data_ref_desa);
						}
						//insert ta desa
						$csvTaDesa= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Desa.csv', "r");
				    while (($dataTaDesa = fgetcsv($csvTaDesa)) !== FALSE) {
									$ta_desa[] = $dataTaDesa;
				    }
						$i2 = 1;
						foreach ($ta_desa as $value) {
							if ($i2 > 1) {
								//insert ta desa
								$data_ta_desa['id_keuangan_master'] = $id_master_keuangan;
								$data_ta_desa['kd_desa'] = $value[1];
								$data_ta_desa['nm_kades'] = $value[2];
								$data_ta_desa['jbt_kades'] = $value[3];
								$data_ta_desa['nm_sekdes'] = $value[4];
								$data_ta_desa['nip_sekdes'] = $value[5];
								$data_ta_desa['jbt_sekdes'] = $value[6];
								$data_ta_desa['nm_kaur_keu'] = $value[7];
								$data_ta_desa['jbt_kaur_keu'] = $value[8];
								$data_ta_desa['nm_bendahara'] = $value[9];
								$data_ta_desa['jbt_bendahara'] = $value[10];
								$data_ta_desa['no_perdes'] = $value[11];
								$data_ta_desa['tgl_perdes'] = $value[12];
								$data_ta_desa['no_perdes_pb'] = $value[13];
								$data_ta_desa['tgl_perdes_pb'] = $value[14];
								$data_ta_desa['no_predes_pj'] = $value[15];
								$data_ta_desa['tgl_perdes_pj'] = $value[16];
								$data_ta_desa['alamat'] = $value[17];
								$data_ta_desa['ibukota'] = $value[18];
								$data_ta_desa['status'] = $value[19];
								$data_ta_desa['npwp'] = $value[10];
								$this->db->insert('keuangan_ta_desa', $data_ta_desa);
							}
							$i2++;
						}
						//insert Ref Rek 4
						$csvRef4= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Rek4.csv', "r");
				    while (($dataRef4 = fgetcsv($csvRef4)) !== FALSE) {
									$ref_rek4[] = $dataRef4;
				    }
						$i3 = 1;
						foreach ($ref_rek4 as $value) {
							if ($i3 > 1) {
								//insert ta desa
								$data_ref_rek4['id_keuangan_master'] = $id_master_keuangan;
								$data_ref_rek4['jenis'] = $value[0];
								$data_ref_rek4['obyek'] = $value[1];
								$data_ref_rek4['nama_obyek'] = $value[2];
								$data_ref_rek4['peraturan'] = $value[3];
								$this->db->insert('keuangan_ref_rek4', $data_ref_rek4);
							}
							$i3++;
						}

						$i3 = 1;
						foreach ($ref_rek4 as $value) {
							if ($i3 > 1) {
								//insert ta desa
								$data_ref_rek4['id_keuangan_master'] = $id_master_keuangan;
								$data_ref_rek4['jenis'] = $value[0];
								$data_ref_rek4['obyek'] = $value[1];
								$data_ref_rek4['nama_obyek'] = $value[2];
								$data_ref_rek4['peraturan'] = $value[3];
								$this->db->insert('keuangan_ref_rek4', $data_ref_rek4);
							}
							$i3++;
						}

						//insert TBP Rinci
						$csvTBPRinci= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_TBPRinci.csv', "r");
						while (($data_tbp_rinci = fgetcsv($csvTBPRinci)) !== FALSE) {
									$ta_tbrinci[] = $data_tbp_rinci;
						}
						$i4 = 1;
						foreach ($ta_tbrinci as $value) {
							if ($i4 > 1) {
								$data_tbrinci['id_keuangan_master'] = $id_master_keuangan;
								$data_tbrinci['no_bukti'] = $value[1];
								$data_tbrinci['kd_desa'] = $value[2];
								$data_tbrinci['kd_keg'] = $value[3];
								$data_tbrinci['kd_rincian'] = $value[4];
								$data_tbrinci['rincian_sd'] = $value[5];
								$data_tbrinci['sumber_dana'] = $value[6];
								$data_tbrinci['nilai'] = $value[7];
								$this->db->insert('keuangan_tbp_rinci', $data_tbrinci);
							}
							$i4++;
						}

						//insert Ta STS
						$csvTa_STS= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_STS.csv', "r");
						while (($data_ta_sts = fgetcsv($csvTa_STS)) !== FALSE) {
									$ta_sts[] = $data_ta_sts;
						}
						$i5 = 1;
						foreach ($ta_sts as $value) {
							if ($i5 > 1) {
								//insert ta desa
								$data_sts['id_keuangan_master'] = $id_master_keuangan;
								$data_sts['no_bukti'] = $value[1];
								$data_sts['tgl_bukti'] = $value[2];
								$data_sts['kd_desa'] = $value[3];
								$data_sts['uraian'] = $value[4];
								$data_sts['no_rek_bank'] = $value[5];
								$data_sts['nama_bank'] = $value[6];
								$data_sts['jumlah'] = $value[7];
								$data_sts['nm_bendahara'] = $value[8];
								$data_sts['jbt_bendahara'] = $value[9];
								$this->db->insert('keuangan_sts', $data_sts);
							}
							$i5++;
						}

						//insert Ta Mutasi
						$csvTa_Mutasi= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Mutasi.csv', "r");
						while (($data_ta_mutasi = fgetcsv($csvTa_Mutasi)) !== FALSE) {
									$ta_mutasi[] = $data_ta_mutasi;
						}
						$i6 = 1;
						foreach ($ta_mutasi as $value) {
							if ($i6 > 1) {
								//insert ta desa
								$data_mutasi['id_keuangan_master'] = $id_master_keuangan;
								$data_mutasi['kd_desa'] = $value[1];
								$data_mutasi['no_bukti'] = $value[2];
								$data_mutasi['tgl_bukti'] = $value[3];
								$data_mutasi['keterangan'] = $value[4];
								$data_mutasi['kd_bank'] = $value[5];
								$data_mutasi['kd_rincian'] = $value[6];
								$data_mutasi['kd_keg'] = $value[7];
								$data_mutasi['sumberdana'] = $value[8];
								$data_mutasi['kd_mutasi'] = $value[9];
								$data_mutasi['nilai'] = $value[10];
								$this->db->insert('keuangan_mutasi', $data_mutasi);
							}
							$i6++;
						}

						//insert TA Pencairan
						$csvTa_Pencairan= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Pencairan.csv', "r");
						while (($data_ta_pencairan = fgetcsv($csvTa_Mutasi)) !== FALSE) {
									$ta_pencairan[] = $data_ta_pencairan;
						}
						$i7 = 1;
						foreach ($ta_pencairan as $value) {
							if ($i7 > 1) {
								//insert ta desa
								$data_pencairan['id_keuangan_master'] = $id_master_keuangan;
								$data_pencairan['no_cek'] = $value[1];
								$data_pencairan['no_spp'] = $value[2];
								$data_pencairan['tgl_cek'] = $value[3];
								$data_pencairan['kd_desa'] = $value[4];
								$data_pencairan['keterangan'] = $value[5];
								$data_pencairan['jumlah'] = $value[6];
								$data_pencairan['potongan'] = $value[7];
								$data_pencairan['kdbayar'] = $value[8];
								$this->db->insert('keuangan_pencairan', $data_pencairan);
							}
							$i7++;
						}

						//insert Ta SPPBukti
						$csvTa_SPPBukti= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPPBukti.csv', "r");
						while (($data_ta_sppbukti = fgetcsv($csvTa_SPPBukti)) !== FALSE) {
									$ta_sppbukti[] = $data_ta_sppbukti;
						}
						$i8 = 1;
						foreach ($ta_sppbukti as $value) {
							if ($i8 > 1) {
								//insert ta desa
								$data_sppbukti['id_keuangan_master'] = $id_master_keuangan;
								$data_sppbukti['kd_desa'] = $value[1];
								$data_sppbukti['no_spp'] = $value[2];
								$data_sppbukti['kd_keg'] = $value[3];
								$data_sppbukti['kd_rincian'] = $value[4];
								$data_sppbukti['sumberdana'] = $value[5];
								$data_sppbukti['no_bukti'] = $value[6];
								$data_sppbukti['tgl_bukti'] = $value[7];
								$data_sppbukti['nm_penerima'] = $value[8];
								$data_sppbukti['alamat'] = $value[9];
								$data_sppbukti['rek_bank'] = $value[10];
								$data_sppbukti['nm_bank'] = $value[11];
								$data_sppbukti['npwp'] = $value[12];
								$data_sppbukti['keterangan'] = $value[13];
								$data_sppbukti['nilai'] = $value[14];
								$this->db->insert('keuangan_sppbukti', $data_sppbukti);
							}
							$i8++;
						}

						//insert Ta TBP
						$csvTa_TBP= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_TBP.csv', "r");
						while (($data_ta_tbp = fgetcsv($csvTa_TBP)) !== FALSE) {
									$ta_tbp[] = $data_ta_tbp;
						}
						$i9 = 1;
						foreach ($ta_tbp as $value) {
							if ($i9 > 1) {
								//insert ta desa
								$data_tbp['id_keuangan_master'] = $id_master_keuangan;
								$data_tbp['no_bukti'] = $value[1];
								$data_tbp['tgl_bukti'] = $value[2];
								$data_tbp['kd_desa'] = $value[3];
								$data_tbp['uraian'] = $value[4];
								$data_tbp['nm_penyetor'] = $value[5];
								$data_tbp['alamat_penyetor'] = $value[6];
								$data_tbp['ttd_penyetor'] = $value[7];
								$data_tbp['norek_bank'] = $value[8];
								$data_tbp['nama_bank'] = $value[9];
								$data_tbp['jumlah'] = $value[10];
								$data_tbp['nm_bendahara'] = $value[11];
								$data_tbp['jbt_bendahara'] = $value[12];
								$data_tbp['status'] = $value[13];
								$data_tbp['kdbayar'] = $value[14];
								$data_tbp['ref_bayar'] = $value[14];
								$this->db->insert('keuangan_tbp', $data_tbp);
							}
							$i9++;
						}
						//insert Ta SPPPot
						$csvTa_SPPPot= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPPPot.csv', "r");
						while (($data_ta_spppot = fgetcsv($csvTa_SPPPot)) !== FALSE) {
									$ta_spppot[] = $data_ta_spppot;
						}
						$i10 = 1;
						foreach ($ta_spppot as $value) {
							if ($i10 > 1) {
								//insert ta desa
								$data_spppot['id_keuangan_master'] = $id_master_keuangan;
								$data_spppot['kd_desa'] = $value[1];
								$data_spppot['no_spp'] = $value[2];
								$data_spppot['kd_keg'] = $value[3];
								$data_spppot['no_bukti'] = $value[4];
								$data_spppot['kd_rincian'] = $value[5];
								$data_spppot['nilai'] = $value[6];
								$this->db->insert('keuangan_spppot', $data_spppot);
							}
							$i10++;
						}

						fclose($handle);
						$zip_file->close();
						$_SESSION['success'] = 1;
						$_SESSION['error_msg'] = 'Berhasil';

				}

		}else{
					$uploadError = $this->upload->display_errors(NULL, NULL);
					$_SESSION['success'] = -1;
					$_SESSION['error_msg'] = $uploadError;
					redirect('keuangan/import_data');
		}
	}


	public function anggaran_keuangan()
	{
		$this->db->select_sum('anggaran');
		$this->db->where('keuangan_ta_rab.id_keuangan_master ', 1);
		$result = $this->db->get('keuangan_ta_rab')->row();
		return $result->anggaran;
	}

	public function data_grafik()
	{
		$query  = "SELECT  A.Tgl_Bukti, 1 AS Kode, A.No_Bukti, A.Uraian, D.Kd_Rincian, keuangan_ref_rek4.Nama_Obyek AS Nama_Rincian, D.Nilai AS Debet, 0 AS Kredit, B.Kd_Desa, B.Nama_Desa, C.Nm_Bendahara, C.Jbt_Bendahara, C.Nm_Kades, C.Jbt_Kades FROM (((keuangan_tbp AS A
INNER JOIN keuangan_ref_desa  AS B ON A.Kd_Desa = B.Kd_Desa)
INNER JOIN keuangan_ta_desa  AS C ON B.Kd_Desa = C.Kd_Desa)
INNER JOIN keuangan_tbp_rinci  AS D ON A.No_Bukti = D.No_Bukti)
INNER JOIN  	keuangan_ref_rek4  ON D.Kd_Rincian =  	keuangan_ref_rek4 .Obyek WHERE (((A.KdBayar)=1))
UNION ALL
SELECT  A.Tgl_Bukti, 2 AS Kode, A.No_Bukti, A.Uraian, '1.1.1.01.' AS Kd_Rincian, 'Kas di Bendahara' AS Nama_Rincian, 0 AS Debet, A.Jumlah AS Kredit, B.Kd_Desa, B.Nama_Desa, C.Nm_Bendahara, C.Jbt_Bendahara, C.Nm_Kades, C.Jbt_Kades
FROM  	keuangan_sts  AS A
INNER JOIN (keuangan_ref_desa AS B
INNER JOIN keuangan_ta_desa AS C ON B.Kd_Desa = C.Kd_Desa) ON A.Kd_Desa = B.Kd_Desa
UNION ALL
SELECT  A.Tgl_Bukti, 3 AS Kode, A.No_Bukti, A.Keterangan AS Uraian, '1.1.1.01.' AS Kd_Rincian, 'Kas di Bendahara' AS Nama_Rincian, A.Nilai AS Debet, 0 AS Kredit, B.Kd_Desa, B.Nama_Desa, C.Nm_Bendahara, C.Jbt_Bendahara, C.Nm_Kades, C.Jbt_Kades
FROM ( 	keuangan_mutasi AS A
      INNER JOIN keuangan_ref_desa AS B ON A.Kd_Desa = B.Kd_Desa)
      INNER JOIN keuangan_ta_desa AS C ON B.Kd_Desa = C.Kd_Desa WHERE (((A.Kd_Mutasi)=1))
      UNION ALL
 SELECT  A.Tgl_Bukti, 4 AS Kode, A.No_Bukti, A.Keterangan AS Uraian, '1.1.1.01.' AS Kd_Rincian, 'Kas di Bendahara' AS Nama_Rincian, 0 AS Debet, A.Nilai AS Kredit, B.Kd_Desa, B.Nama_Desa, C.Nm_Bendahara, C.Jbt_Bendahara, C.Nm_Kades, C.Jbt_Kades
 FROM (keuangan_mutasi AS A
       INNER JOIN keuangan_ref_desa AS B ON A.Kd_Desa = B.Kd_Desa)
       INNER JOIN keuangan_ta_desa AS C ON B.Kd_Desa = C.Kd_Desa
       WHERE (((A.Kd_Mutasi)=2)) UNION ALL
 SELECT  A.Tgl_Cek AS Tgl_Bukti, 5 AS Kode, A.No_Cek AS No_Bukti, A.Keterangan AS Uraian, '1.1.1.01.' AS Kd_Rincian, 'Kas di Bendahara' AS Nama_Rincian, 0 AS Debet, A.Jumlah-A.Potongan AS Kredit, B.Kd_Desa, B.Nama_Desa, C.Nm_Bendahara, C.Jbt_Bendahara, C.Nm_Kades, C.Jbt_Kades
 FROM (keuangan_pencairan AS A
       INNER JOIN keuangan_ref_desa AS B ON A.Kd_Desa = B.Kd_Desa)
       INNER JOIN keuangan_ta_desa AS C ON B.Kd_Desa = C.Kd_Desa
       WHERE (((A.KdBayar)=1)) UNION ALL
 SELECT  A.Tgl_Bukti AS Tgl_Bukti, 1 AS Kode, A.No_Bukti, A.Keterangan AS Uraian, E.Kd_Rincian, F.Nama_Obyek AS Nama_Rincian, E.Nilai AS Debet, 0 AS Kredit, B.Kd_Desa, B.Nama_Desa, C.Nm_Bendahara, C.Jbt_Bendahara, C.Nm_Kades, C.Jbt_Kades
 FROM ((((keuangan_sppbukti  AS A
          INNER JOIN keuangan_ref_desa AS B ON A.Kd_Desa = B.Kd_Desa)
         INNER JOIN keuangan_pencairan AS D ON A.No_SPP = D.No_SPP)
        INNER JOIN keuangan_ta_desa AS C ON B.Kd_Desa = C.Kd_Desa)
       INNER JOIN keuangan_spppot  AS E ON A.No_Bukti = E.No_Bukti)
        INNER JOIN keuangan_ref_rek4 AS F ON E.Kd_Rincian = F.Obyek
        WHERE (((D.KdBayar)=1))
        UNION ALL
     SELECT  A.Tgl_Bukti AS Tgl_Bukti, 1 AS Kode, A.No_Bukti, A.Keterangan AS Uraian, E.Kd_Rincian, F.Nama_Obyek AS Nama_Rincian, E.Nilai AS Debet, 0 AS Kredit, B.Kd_Desa, B.Nama_Desa, C.Nm_Bendahara, C.Jbt_Bendahara, C.Nm_Kades, C.Jbt_Kades FROM ((keuangan_sppbukti  AS A
     INNER JOIN keuangan_ref_desa AS B ON A.Kd_Desa = B.Kd_Desa)
     INNER JOIN keuangan_ta_desa AS C ON B.Kd_Desa = C.Kd_Desa)
     INNER JOIN (keuangan_spjpot  AS E
     INNER JOIN keuangan_ref_rek4 AS F ON E.Kd_Rincian = F.Obyek) ON A.No_Bukti = E.No_Bukti
     UNION ALL
     SELECT  A.Tgl_Bukti AS Tgl_Bukti, 1 AS Kode, A.No_Bukti, A.Keterangan AS Uraian, '' AS Kd_Rincian, 'Pengambalian Panjar' AS Nama_Rincian, A.Nilai AS Debet, 0 AS Kredit, B.Kd_Desa, B.Nama_Desa, C.Nm_Bendahara, C.Jbt_Bendahara, C.Nm_Kades, C.Jbt_Kades
     FROM (keuangan_spj_sisa  AS A
           INNER JOIN keuangan_ref_desa AS B ON A.Kd_Desa = B.Kd_Desa)
           INNER JOIN keuangan_ta_desa AS C ON B.Kd_Desa = C.Kd_Desa";
		// print_r($query);die();

		$this->db->select_sum('anggaran');
		$this->db->where('keuangan_ta_rab.id_keuangan_master ', 1);
		$result = $this->db->get('keuangan_ta_rab')->row();
		return $result->anggaran;
	}

	public function anggaranPAK()
	{
		$this->db->select_sum('anggaranPAK');
		$this->db->where('keuangan_ta_rab.id_keuangan_master ', 1);
		$result = $this->db->get('keuangan_ta_rab')->row();
		return $result->anggaranPAK;
	}

	public function anggaranStlhPAK()
	{
		$this->db->select_sum('anggaranStlhPAK');
		$this->db->where('keuangan_ta_rab.id_keuangan_master ', 1);
		$result = $this->db->get('keuangan_ta_rab')->row();
		return $result->anggaranStlhPAK;
	}

	public function cekMasterKeuangan($versi_database,$tahun_anggaran)
	{
		$this->db->select('id');
		$result = $this->db->get('keuangan_master')->row();
		return $result;
	}

}
