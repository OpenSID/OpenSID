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
						$csvTaDesa= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Desa.csv', "r");
				    while (($dataTaDesa = fgetcsv($csvTaDesa)) !== FALSE) {
									$ta_desa[] = $dataTaDesa;
				    }
						$i2 = 1;
						foreach ($ta_desa as $value) {
							if ($i2 > 1) {
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

						$csvRef4= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Rek4.csv', "r");
				    while (($dataRef4 = fgetcsv($csvRef4)) !== FALSE) {
									$ref_rek4[] = $dataRef4;
				    }
						$i3 = 1;
						foreach ($ref_rek4 as $value) {
							if ($i3 > 1) {
								$data_ref_rek4['id_keuangan_master'] = $id_master_keuangan;
								$data_ref_rek4['jenis'] = $value[0];
								$data_ref_rek4['obyek'] = $value[1];
								$data_ref_rek4['nama_obyek'] = $value[2];
								$data_ref_rek4['peraturan'] = $value[3];
								$this->db->insert('keuangan_ref_rek4', $data_ref_rek4);
							}
							$i3++;
						}

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
								$this->db->insert('keuangan_ta_tbp_rinci', $data_tbrinci);
							}
							$i4++;
						}

						$csvTa_STS= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_STS.csv', "r");
						while (($data_ta_sts = fgetcsv($csvTa_STS)) !== FALSE) {
									$ta_sts[] = $data_ta_sts;
						}
						$i5 = 1;
						foreach ($ta_sts as $value) {
							if ($i5 > 1) {
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
								$this->db->insert('keuangan_ta_sts', $data_sts);
							}
							$i5++;
						}

						$csvTa_Mutasi= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Mutasi.csv', "r");
						while (($data_ta_mutasi = fgetcsv($csvTa_Mutasi)) !== FALSE) {
									$ta_mutasi[] = $data_ta_mutasi;
						}
						$i6 = 1;
						foreach ($ta_mutasi as $value) {
							if ($i6 > 1) {
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
								$this->db->insert('keuangan_ta_mutasi', $data_mutasi);
							}
							$i6++;
						}

						$csvTa_Pencairan= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Pencairan.csv', "r");
						while (($data_ta_pencairan = fgetcsv($csvTa_Mutasi)) !== FALSE) {
									$ta_pencairan[] = $data_ta_pencairan;
						}
						$i7 = 1;
						foreach ($ta_pencairan as $value) {
							if ($i7 > 1) {
								$data_pencairan['id_keuangan_master'] = $id_master_keuangan;
								$data_pencairan['no_cek'] = $value[1];
								$data_pencairan['no_spp'] = $value[2];
								$data_pencairan['tgl_cek'] = $value[3];
								$data_pencairan['kd_desa'] = $value[4];
								$data_pencairan['keterangan'] = $value[5];
								$data_pencairan['jumlah'] = $value[6];
								$data_pencairan['potongan'] = $value[7];
								$data_pencairan['kdbayar'] = $value[8];
								$this->db->insert('keuangan_ta_pencairan', $data_pencairan);
							}
							$i7++;
						}

						$csvTa_SPPBukti= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPPBukti.csv', "r");
						while (($data_ta_sppbukti = fgetcsv($csvTa_SPPBukti)) !== FALSE) {
									$ta_sppbukti[] = $data_ta_sppbukti;
						}
						$i8 = 1;
						foreach ($ta_sppbukti as $value) {
							if ($i8 > 1) {
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
								$this->db->insert('keuangan_ta_sppbukti', $data_sppbukti);
							}
							$i8++;
						}

						$csvTa_TBP= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_TBP.csv', "r");
						while (($data_ta_tbp = fgetcsv($csvTa_TBP)) !== FALSE) {
									$ta_tbp[] = $data_ta_tbp;
						}
						$i9 = 1;
						foreach ($ta_tbp as $value) {
							if ($i9 > 1) {
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
								$this->db->insert('keuangan_ta_tbp', $data_tbp);
							}
							$i9++;
						}

						$csvTa_SPPPot= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPPPot.csv', "r");
						while (($data_ta_spppot = fgetcsv($csvTa_SPPPot)) !== FALSE) {
									$ta_spppot[] = $data_ta_spppot;
						}
						$i10 = 1;
						foreach ($ta_spppot as $value) {
							if ($i10 > 1) {
								$data_spppot['id_keuangan_master'] = $id_master_keuangan;
								$data_spppot['kd_desa'] = $value[1];
								$data_spppot['no_spp'] = $value[2];
								$data_spppot['kd_keg'] = $value[3];
								$data_spppot['no_bukti'] = $value[4];
								$data_spppot['kd_rincian'] = $value[5];
								$data_spppot['nilai'] = $value[6];
								$this->db->insert('keuangan_ta_spppot', $data_spppot);
							}
							$i10++;
						}

						$csvTa_SPJPOT= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPJPot.csv', "r");
						while (($data_ta_spjpot = fgetcsv($csvTa_SPJPOT)) !== FALSE) {
									$ta_spjpot[] = $data_ta_spjpot;
						}
						$i11 = 1;
						foreach ($ta_spjpot as $value) {
							if ($i11 > 1) {
								$data_spjpot['id_keuangan_master'] = $id_master_keuangan;
								$data_spjpot['kd_desa'] = $value[1];
								$data_spjpot['no_spj'] = $value[2];
								$data_spjpot['kd_keg'] = $value[3];
								$data_spjpot['no_bukti'] = $value[4];
								$data_spjpot['kd_rincian'] = $value[5];
								$data_spjpot['nilai'] = $value[6];
								$this->db->insert('keuangan_ta_spjpot', $data_spjpot);
							}
							$i11++;
						}

						$csvTa_SPJSisa= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPJSisa.csv', "r");
						while (($data_ta_spjsisa = fgetcsv($csvTa_SPJSisa)) !== FALSE) {
									$ta_spjsisa[] = $data_ta_spjsisa;
						}
						$i12 = 1;
						foreach ($ta_spjsisa as $value) {
							if ($i11 > 1) {
								$data_spjsisa['id_keuangan_master'] = $id_master_keuangan;
								$data_spjsisa['kd_desa'] = $value[1];
								$data_spjsisa['no_bukti'] = $value[2];
								$data_spjsisa['tgl_bukti'] = $value[3];
								$data_spjsisa['no_spj'] = $value[4];
								$data_spjsisa['tgl_spj'] = $value[5];
								$data_spjsisa['no_spp'] = $value[6];
								$data_spjsisa['tgl_spp'] = $value[7];
								$data_spjsisa['kd_keg'] = $value[8];
								$data_spjsisa['keterangan'] = $value[9];
								$data_spjsisa['nilai'] = $value[10];
								$this->db->insert('keuangan_ta_spj_sisa', $data_spjsisa);
							}
							$i11++;
						}

						$csvTa_RAB= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RAB.csv', "r");
						while (($data_ta_rab = fgetcsv($csvTa_RAB)) !== FALSE) {
									$ta_rab[] = $data_ta_rab;
						}
						$i12 = 1;
						foreach ($ta_rab as $value) {
							if ($i12 > 1) {
								$data_rab['id_keuangan_master'] = $id_master_keuangan;
								$data_rab['kd_desa'] = $value[1];
								$data_rab['kd_keg'] = $value[2];
								$data_rab['kd_rincian'] = $value[3];
								$data_rab['anggaran'] = $value[4];
								$data_rab['anggaranPAK'] = $value[5];
								$data_rab['anggaranStlhPAK'] = $value[6];
								$this->db->insert('keuangan_ta_rab', $data_rab);
							}
							$i12++;
						}

						//insert Ref_bank_desa
						$csvRefbankDesa= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Bank_Desa.csv', "r");
						while (($data_ref_bank_desa = fgetcsv($csvRefbankDesa)) !== FALSE) {
									$ta_ref_bank_desa[] = $data_ref_bank_desa;
						}
						$i15 = 1;
						foreach ($ta_ref_bank_desa as $value) {
							if ($i15 > 1) {
								$data_ref_bank_desa1['id_keuangan_master'] = $id_master_keuangan;
								$data_ref_bank_desa1['Kd_Desa'] = $value[1];
								$data_ref_bank_desa1['Kd_Rincian'] = $value[2];
								$data_ref_bank_desa1['NoRek_Bank '] = $value[3];
								$data_ref_bank_desa1['Nama_Bank'] = $value[4];
								$this->db->insert('keuangan_ref_bank_desa', $data_ref_bank_desa1);
							}
							$i15++;
						}

						//insert Ref_Bel_operasional
						$csvRefBelOps= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Bel_Operasional.csv', "r");
						while (($data_ref_bel = fgetcsv($csvRefBelOps)) !== FALSE) {
									$ta_ref_bel[] = $data_ref_bel;
						}
						$i16 = 1;
						foreach ($ta_ref_bel as $value) {
							if ($i16 > 1) {
								$data_ref_bel1['id_keuangan_master'] = $id_master_keuangan;
								$data_ref_bel1['id_keg'] = $value[0];
								$this->db->insert('keuangan_ref_bel_operasional', $data_ref_bel1);
							}
							$i16++;
						}

						//insert Ref_Bidang
						$csvRefBidang= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Bidang.csv', "r");
						while (($data_ref_bidang = fgetcsv($csvRefBidang)) !== FALSE) {
									$ta_ref_bidang[] = $data_ref_bidang;
						}
						$i17 = 1;
						foreach ($ta_ref_bidang as $value) {
							if ($i17 > 1) {
								$data_ref_bidang1['id_keuangan_master'] = $id_master_keuangan;
								$data_ref_bidang1['kd_bid'] = $value[0];
								$data_ref_bidang1['nama_bidang'] = $value[1];
								$this->db->insert('keuangan_ref_bidang', $data_ref_bidang1);
							}
							$i17++;
						}

						//insert Ref_Bunga
						$csvRefBunga= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Bunga.csv', "r");
						while (($data_keuangan_ref_bunga = fgetcsv($csvRefBunga)) !== FALSE) {
									$ref_bunga[] = $data_keuangan_ref_bunga;
						}
						$i18 = 1;
						foreach ($ref_bunga as $value) {
							if ($i18 > 1) {
								$data_ref_bunga['id_keuangan_master'] = $id_master_keuangan;
								$data_ref_bunga['Kd_Bunga'] = $value[0];
								$data_ref_bunga['Kd_Admin'] = $value[1];
								$this->db->insert('keuangan_ref_bunga', $data_ref_bunga);
							}
							$i18++;
						}

						//insert Ref_Kecamatan
						$csvRef_Kecamatan= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Kecamatan.csv', "r");
						while (($data_Ref_Kecamatan = fgetcsv($csvRef_Kecamatan)) !== FALSE) {
									$Ref_Kecamatan[] = $data_Ref_Kecamatan;
						}
						$i19 = 1;
						foreach ($Ref_Kecamatan as $value) {
							if ($i19 > 1) {
								$insert_Ref_Kecamatan['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_Kecamatan['Kd_Kec'] = $value[0];
								$insert_Ref_Kecamatan['Nama_Kecamatan'] = $value[1];
								$this->db->insert('keuangan_ref_kecamatan', $insert_Ref_Kecamatan);
							}
							$i19++;
						}

						//insert Ref_Kegiatan
						$csvRef_Kegiatan= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Kegiatan.csv', "r");
						while (($data_Ref_Kegiatann = fgetcsv($csvRef_Kegiatan)) !== FALSE) {
									$Ref_Kegiatan[] = $data_Ref_Kegiatann;
						}
						$i20 = 1;
						foreach ($Ref_Kegiatan as $value) {
							if ($i20 > 1) {
								$insert_Ref_Kegiatan['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_Kegiatan['Kd_Bid'] = $value[0];
								$insert_Ref_Kegiatan['ID_Keg'] = $value[1];
								$insert_Ref_Kegiatan['Nama_Kegiatan'] = $value[2];
								$this->db->insert('keuangan_ref_kegiatan', $insert_Ref_Kegiatan);
							}
							$i20++;
						}

						//insert Ref_Korolari
						$csvRef_Korolari= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Korolari.csv', "r");
						while (($data_Ref_Korolari = fgetcsv($csvRef_Korolari)) !== FALSE) {
									$Ref_Korolari[] = $data_Ref_Korolari;
						}
						$i21 = 1;
						foreach ($Ref_Korolari as $value) {
							if ($i21 > 1) {
								$insert_Ref_Korolari['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_Korolari['Kd_Rincian'] = $value[0];
								$insert_Ref_Korolari['Kd_RekDB'] = $value[1];
								$insert_Ref_Korolari['Kd_RekKD'] = $value[2];
								$insert_Ref_Korolari['Jenis'] = $value[3];
								$this->db->insert('keuangan_ref_korolari', $insert_Ref_Korolari);
							}
							$i21++;
						}

						//insert Ref_NeracaClose
						$csvRef_NeracaClose= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_NeracaClose.csv', "r");
						while (($data_Ref_NeracaClose = fgetcsv($csvRef_NeracaClose)) !== FALSE) {
									$Ref_NeracaClose[] = $data_Ref_NeracaClose;
						}
						$i22 = 1;
						foreach ($Ref_NeracaClose as $value) {
							if ($i22 > 1) {
								$insert_Ref_NeracaClose['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_NeracaClose['Kd_Rincian'] = $value[0];
								$insert_Ref_NeracaClose['Kelompok'] = $value[1];
								$this->db->insert('keuangan_ref_neraca_close ', $insert_Ref_NeracaClose);
							}
							$i22++;
						}

						//insert Ref_NeracaClose
						$csvRef_NeracaClose= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_NeracaClose.csv', "r");
						while (($data_Ref_NeracaClose = fgetcsv($csvRef_NeracaClose)) !== FALSE) {
									$Ref_NeracaClose[] = $data_Ref_NeracaClose;
						}
						$i22 = 1;
						foreach ($Ref_NeracaClose as $value) {
							if ($i22 > 1) {
								$insert_Ref_NeracaClose['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_NeracaClose['Kd_Rincian'] = $value[0];
								$insert_Ref_NeracaClose['Kelompok'] = $value[1];
								$this->db->insert('keuangan_ref_neraca_close ', $insert_Ref_NeracaClose);
							}
							$i22++;
						}

						//insert Ref_Perangkat
						$csvRef_Perangkat= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Perangkat.csv', "r");
						while (($data_Ref_Perangkat = fgetcsv($csvRef_Perangkat)) !== FALSE) {
									$Ref_Perangkat[] = $data_Ref_Perangkat;
						}
						$i23 = 1;
						foreach ($Ref_Perangkat as $value) {
							if ($i23 > 1) {
								$insert_Ref_Perangkat['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_Perangkat['Kode'] = $value[0];
								$insert_Ref_Perangkat['Nama_Perangkat'] = $value[1];
								$this->db->insert('keuangan_ref_perangkat ', $insert_Ref_Perangkat);
							}
							$i23++;
						}

						//insert Ref_Potongan
						$csvRef_Potongan= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Potongan.csv', "r");
						while (($data_Ref_Potongan = fgetcsv($csvRef_Potongan)) !== FALSE) {
									$Ref_Potongan[] = $data_Ref_Potongan;
						}
						$i24 = 1;
						foreach ($Ref_Potongan as $value) {
							if ($i24 > 1) {
								$insert_Ref_Potongan['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_Potongan['Kd_Rincian'] = $value[0];
								$insert_Ref_Potongan['Kd_Potongan'] = $value[1];
								$this->db->insert('keuangan_ref_potongan', $insert_Ref_Potongan);
							}
							$i24++;
						}

						//insert Ref_Rek1
						$csvRef_Rek1= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Rek1.csv', "r");
						while (($data_Ref_Rek1 = fgetcsv($csvRef_Rek1)) !== FALSE) {
									$Ref_Rek1[] = $data_Ref_Rek1;
						}
						$i25 = 1;
						foreach ($Ref_Rek1 as $value) {
							if ($i25 > 1) {
								$insert_Ref_Potongan['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_Potongan['Akun'] = $value[0];
								$insert_Ref_Potongan['Nama_Akun'] = $value[1];
								$insert_Ref_Potongan['NoLap'] = $value[2];
								$this->db->insert('keuangan_ref_rek1', $insert_Ref_Potongan);
							}
							$i25++;
						}

						//insert Ref_Rek2
						$csvRef_Rek2= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Rek2.csv', "r");
						while (($data_Ref_Rek2 = fgetcsv($csvRef_Rek2)) !== FALSE) {
									$Ref_Rek2[] = $data_Ref_Rek2;
						}
						$i26 = 1;
						foreach ($Ref_Rek2 as $value) {
							if ($i26 > 1) {
								$insert_Ref_Rek2['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_Rek2['Akun'] = $value[0];
								$insert_Ref_Rek2['Kelompok'] = $value[1];
								$insert_Ref_Rek2['Nama_Kelompok'] = $value[2];
								$this->db->insert('keuangan_ref_rek2', $insert_Ref_Rek2);
							}
							$i26++;
						}

						//insert Ref_Rek3
						$csvRef_Rek3= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Rek3.csv', "r");
						while (($data_Ref_Rek3 = fgetcsv($csvRef_Rek3)) !== FALSE) {
									$Ref_Rek3[] = $data_Ref_Rek3;
						}
						$i27 = 1;
						foreach ($Ref_Rek3 as $value) {
							if ($i27 > 1) {
								$insert_Ref_Rek3['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_Rek3['Kelompok'] = $value[0];
								$insert_Ref_Rek3['Jenis'] = $value[1];
								$insert_Ref_Rek3['Nama_Jenis'] = $value[2];
								$insert_Ref_Rek3['Formula'] = $value[3];
								$this->db->insert('keuangan_ref_rek3', $insert_Ref_Rek3);
							}
							$i27++;
						}

						//insert Ref_Rek3
						$csvRef_Rek3= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Rek3.csv', "r");
						while (($data_Ref_Rek3 = fgetcsv($csvRef_Rek3)) !== FALSE) {
									$Ref_Rek3[] = $data_Ref_Rek3;
						}
						$i27 = 1;
						foreach ($Ref_Rek3 as $value) {
							if ($i27 > 1) {
								$insert_Ref_Rek3['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_Rek3['Kelompok'] = $value[0];
								$insert_Ref_Rek3['Jenis'] = $value[1];
								$insert_Ref_Rek3['Nama_Jenis'] = $value[2];
								$insert_Ref_Rek3['Formula'] = $value[3];
								$this->db->insert('keuangan_ref_rek3', $insert_Ref_Rek3);
							}
							$i27++;
						}

						//insert Ref_SBU
						$csvRef_SBU= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_SBU.csv', "r");
						while (($data_Ref_SBU = fgetcsv($csvRef_SBU)) !== FALSE) {
									$Ref_SBU[] = $data_Ref_SBU;
						}
						$i28 = 1;
						foreach ($Ref_SBU as $value) {
							if ($i28 > 1) {
								$insert_Ref_SBU['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_SBU['Kd_Rincian'] = $value[0];
								$insert_Ref_SBU['Kode_SBU'] = $value[1];
								$insert_Ref_SBU['NoUrut_SBU'] = $value[2];
								$insert_Ref_SBU['Nama_SBU'] = $value[3];
								$insert_Ref_SBU['Nilai'] = $value[4];
								$insert_Ref_SBU['Satuan'] = $value[5];
								$this->db->insert('keuangan_ref_sbu', $insert_Ref_SBU);
							}
							$i28++;
						}

						//insert Ref_SBU
						$csvRef_SBU= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_SBU.csv', "r");
						while (($data_Ref_SBU = fgetcsv($csvRef_SBU)) !== FALSE) {
									$Ref_SBU[] = $data_Ref_SBU;
						}
						$i28 = 1;
						foreach ($Ref_SBU as $value) {
							if ($i28 > 1) {
								$insert_Ref_SBU['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_SBU['Kd_Rincian'] = $value[0];
								$insert_Ref_SBU['Kode_SBU'] = $value[1];
								$insert_Ref_SBU['NoUrut_SBU'] = $value[2];
								$insert_Ref_SBU['Nama_SBU'] = $value[3];
								$insert_Ref_SBU['Nilai'] = $value[4];
								$insert_Ref_SBU['Satuan'] = $value[5];
								$this->db->insert('keuangan_ref_sbu', $insert_Ref_SBU);
							}
							$i28++;
						}

						//insert Ref_Sumber
						$csvRef_Sumber= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Sumber.csv', "r");
						while (($data_Ref_Sumber = fgetcsv($csvRef_Sumber)) !== FALSE) {
									$Ref_Sumber[] = $data_Ref_Sumber;
						}
						$i29 = 1;
						foreach ($Ref_Sumber as $value) {
							if ($i29 > 1) {
								$insert_Ref_Sumber['id_keuangan_master'] = $id_master_keuangan;
								$insert_Ref_Sumber['Kode'] = $value[0];
								$insert_Ref_Sumber['Nama_Sumber'] = $value[1];
								$insert_Ref_Sumber['Urut'] = $value[2];
								$this->db->insert('keuangan_ref_sumber', $insert_Ref_Sumber);
							}
							$i29++;
						}



						fclose($handle);
						$zip_file->close();
						$_SESSION['success'] = 1;
						$_SESSION['error_msg'] = 'Berhasil';
						redirect('keuangan/import_data');
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
