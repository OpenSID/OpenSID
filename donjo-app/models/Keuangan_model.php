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

	public function extractUpdate($path)
	{
		$this->upload->initialize($this->uploadConfig);
		$adaLampiran = !empty($_FILES['keuangan']['name']);
		$id_master_keuangan = $_POST['id_keuangan_master'];
			if ($this->upload->do_upload('keuangan')){
				$data      = $this->upload->data();
				$zip_file  = new ZipArchive;
				$full_path = $data['full_path'];
				if ($zip_file->open($full_path) === TRUE){
					//upte keuangan_ref_desa
					$csvLines= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Desa.csv', "r");
					while (($data3 = fgetcsv($csvLines)) !== FALSE) {
								$rows[] = $data3;
					}
					foreach ($rows as $value) {
									$this->db->where('id_keuangan_master', $id_master_keuangan);
									$data_ref_desa['kd_kec'] = $value[0];
									$data_ref_desa['kd_desa'] = $value[1];
									$data_ref_desa['nama_desa'] = $value[2];
									$this->db->update('keuangan_ref_desa', $data_ref_desa);
					}

					//update keuangan_ta_desa
					$csvTaDesa= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Desa.csv', "r");
					while (($dataTaDesa = fgetcsv($csvTaDesa)) !== FALSE) {
								$ta_desa[] = $dataTaDesa;
					}
					$i2 = 1;
					foreach ($ta_desa as $value) {
						if ($i2 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
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
							$this->db->update('keuangan_ta_desa', $data_ta_desa);
						}
						$i2++;
					}

					//update keuangan_ref_rek4
					$csvRef4= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Rek4.csv', "r");
					while (($dataRef4 = fgetcsv($csvRef4)) !== FALSE) {
								$ref_rek4[] = $dataRef4;
					}
					$i3 = 1;
					foreach ($ref_rek4 as $value) {
						if ($i3 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$data_ref_rek4['jenis'] = $value[0];
							$data_ref_rek4['obyek'] = $value[1];
							$data_ref_rek4['nama_obyek'] = $value[2];
							$data_ref_rek4['peraturan'] = $value[3];
							$this->db->update('keuangan_ref_rek4', $data_ref_rek4);
						}
						$i3++;
					}

					$i3 = 1;
					foreach ($ref_rek4 as $value) {
						if ($i3 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$data_ref_rek4['jenis'] = $value[0];
							$data_ref_rek4['obyek'] = $value[1];
							$data_ref_rek4['nama_obyek'] = $value[2];
							$data_ref_rek4['peraturan'] = $value[3];
							$this->db->update('keuangan_ref_rek4', $data_ref_rek4);
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
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$data_tbrinci['no_bukti'] = $value[1];
							$data_tbrinci['kd_desa'] = $value[2];
							$data_tbrinci['kd_keg'] = $value[3];
							$data_tbrinci['kd_rincian'] = $value[4];
							$data_tbrinci['rincian_sd'] = $value[5];
							$data_tbrinci['sumber_dana'] = $value[6];
							$data_tbrinci['nilai'] = $value[7];
							$this->db->update('keuangan_ta_tbp_rinci', $data_tbrinci);
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
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$data_sts['no_bukti'] = $value[1];
							$data_sts['tgl_bukti'] = $value[2];
							$data_sts['kd_desa'] = $value[3];
							$data_sts['uraian'] = $value[4];
							$data_sts['no_rek_bank'] = $value[5];
							$data_sts['nama_bank'] = $value[6];
							$data_sts['jumlah'] = $value[7];
							$data_sts['nm_bendahara'] = $value[8];
							$data_sts['jbt_bendahara'] = $value[9];
							$this->db->update('keuangan_ta_sts', $data_sts);
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
							$this->db->where('id_keuangan_master', $id_master_keuangan);
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
							$this->db->update('keuangan_ta_mutasi', $data_mutasi);
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
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$data_pencairan['no_cek'] = $value[1];
							$data_pencairan['no_spp'] = $value[2];
							$data_pencairan['tgl_cek'] = $value[3];
							$data_pencairan['kd_desa'] = $value[4];
							$data_pencairan['keterangan'] = $value[5];
							$data_pencairan['jumlah'] = $value[6];
							$data_pencairan['potongan'] = $value[7];
							$data_pencairan['kdbayar'] = $value[8];
							$this->db->update('keuangan_ta_pencairan', $data_pencairan);
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
							$this->db->where('id_keuangan_master', $id_master_keuangan);
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
							$this->db->update('keuangan_ta_sppbukti', $data_sppbukti);
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
							$this->db->where('id_keuangan_master', $id_master_keuangan);
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
							$this->db->update('keuangan_ta_tbp', $data_tbp);
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
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$data_spppot['kd_desa'] = $value[1];
							$data_spppot['no_spp'] = $value[2];
							$data_spppot['kd_keg'] = $value[3];
							$data_spppot['no_bukti'] = $value[4];
							$data_spppot['kd_rincian'] = $value[5];
							$data_spppot['nilai'] = $value[6];
							$this->db->update('keuangan_ta_spppot', $data_spppot);
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
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$data_spjpot['kd_desa'] = $value[1];
							$data_spjpot['no_spj'] = $value[2];
							$data_spjpot['kd_keg'] = $value[3];
							$data_spjpot['no_bukti'] = $value[4];
							$data_spjpot['kd_rincian'] = $value[5];
							$data_spjpot['nilai'] = $value[6];
							$this->db->update('keuangan_ta_spjpot', $data_spjpot);
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
							$this->db->where('id_keuangan_master', $id_master_keuangan);
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
							$this->db->update('keuangan_ta_spj_sisa', $data_spjsisa);
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
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$data_rab['kd_desa'] = $value[1];
							$data_rab['kd_keg'] = $value[2];
							$data_rab['kd_rincian'] = $value[3];
							$data_rab['anggaran'] = $value[4];
							$data_rab['anggaranPAK'] = $value[5];
							$data_rab['anggaranStlhPAK'] = $value[6];
							$this->db->update('keuangan_ta_rab', $data_rab);
						}
						$i12++;
					}

					//update Ref_bank_desa
					$csvRefbankDesa= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Bank_Desa.csv', "r");
					while (($data_ref_bank_desa = fgetcsv($csvRefbankDesa)) !== FALSE) {
								$ta_ref_bank_desa[] = $data_ref_bank_desa;
					}
					$i15 = 1;
					foreach ($ta_ref_bank_desa as $value) {
						if ($i15 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$data_ref_bank_desa1['Kd_Desa'] = $value[1];
							$data_ref_bank_desa1['Kd_Rincian'] = $value[2];
							$data_ref_bank_desa1['NoRek_Bank '] = $value[3];
							$data_ref_bank_desa1['Nama_Bank'] = $value[4];
							$this->db->update('keuangan_ref_bank_desa', $data_ref_bank_desa1);
						}
						$i15++;
					}

					//update Ref_Bel_operasional
					$csvRefBelOps= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Bel_Operasional.csv', "r");
					while (($data_ref_bel = fgetcsv($csvRefBelOps)) !== FALSE) {
								$ta_ref_bel[] = $data_ref_bel;
					}
					$i16 = 1;
					foreach ($ta_ref_bel as $value) {
						if ($i16 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$data_ref_bel1['id_keg'] = $value[0];
							$this->db->update('keuangan_ref_bel_operasional', $data_ref_bel1);
						}
						$i16++;
					}

					//update Ref_Bidang
					$csvRefBidang= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Bidang.csv', "r");
					while (($data_ref_bidang = fgetcsv($csvRefBidang)) !== FALSE) {
								$ta_ref_bidang[] = $data_ref_bidang;
					}
					$i17 = 1;
					foreach ($ta_ref_bidang as $value) {
						if ($i17 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$data_ref_bidang1['kd_bid'] = $value[0];
							$data_ref_bidang1['nama_bidang'] = $value[1];
							$this->db->update('keuangan_ref_bidang', $data_ref_bidang1);
						}
						$i17++;
					}

					//update Ref_Bunga
					$csvRefBunga= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Bunga.csv', "r");
					while (($data_keuangan_ref_bunga = fgetcsv($csvRefBunga)) !== FALSE) {
								$ref_bunga[] = $data_keuangan_ref_bunga;
					}
					$i18 = 1;
					foreach ($ref_bunga as $value) {
						if ($i18 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$data_ref_bunga['Kd_Bunga'] = $value[0];
							$data_ref_bunga['Kd_Admin'] = $value[1];
							$this->db->update('keuangan_ref_bunga', $data_ref_bunga);
						}
						$i18++;
					}

					//update Ref_Kecamatan
					$csvRef_Kecamatan= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Kecamatan.csv', "r");
					while (($data_Ref_Kecamatan = fgetcsv($csvRef_Kecamatan)) !== FALSE) {
								$Ref_Kecamatan[] = $data_Ref_Kecamatan;
					}
					$i19 = 1;
					foreach ($Ref_Kecamatan as $value) {
						if ($i19 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_Kecamatan['Kd_Kec'] = $value[0];
							$insert_Ref_Kecamatan['Nama_Kecamatan'] = $value[1];
							$this->db->update('keuangan_ref_kecamatan', $insert_Ref_Kecamatan);
						}
						$i19++;
					}

					//update Ref_Kegiatan
					$csvRef_Kegiatan= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Kegiatan.csv', "r");
					while (($data_Ref_Kegiatann = fgetcsv($csvRef_Kegiatan)) !== FALSE) {
								$Ref_Kegiatan[] = $data_Ref_Kegiatann;
					}
					$i20 = 1;
					foreach ($Ref_Kegiatan as $value) {
						if ($i20 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_Kegiatan['Kd_Bid'] = $value[0];
							$insert_Ref_Kegiatan['ID_Keg'] = $value[1];
							$insert_Ref_Kegiatan['Nama_Kegiatan'] = $value[2];
							$this->db->update('keuangan_ref_kegiatan', $insert_Ref_Kegiatan);
						}
						$i20++;
					}

					//update Ref_Korolari
					$csvRef_Korolari= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Korolari.csv', "r");
					while (($data_Ref_Korolari = fgetcsv($csvRef_Korolari)) !== FALSE) {
								$Ref_Korolari[] = $data_Ref_Korolari;
					}
					$i21 = 1;
					foreach ($Ref_Korolari as $value) {
						if ($i21 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_Korolari['Kd_Rincian'] = $value[0];
							$insert_Ref_Korolari['Kd_RekDB'] = $value[1];
							$insert_Ref_Korolari['Kd_RekKD'] = $value[2];
							$insert_Ref_Korolari['Jenis'] = $value[3];
							$this->db->update('keuangan_ref_korolari', $insert_Ref_Korolari);
						}
						$i21++;
					}

					//update Ref_NeracaClose
					$csvRef_NeracaClose= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_NeracaClose.csv', "r");
					while (($data_Ref_NeracaClose = fgetcsv($csvRef_NeracaClose)) !== FALSE) {
								$Ref_NeracaClose[] = $data_Ref_NeracaClose;
					}
					$i22 = 1;
					foreach ($Ref_NeracaClose as $value) {
						if ($i22 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_NeracaClose['Kd_Rincian'] = $value[0];
							$insert_Ref_NeracaClose['Kelompok'] = $value[1];
							$this->db->update('keuangan_ref_neraca_close ', $insert_Ref_NeracaClose);
						}
						$i22++;
					}

					//update Ref_NeracaClose
					$csvRef_NeracaClose= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_NeracaClose.csv', "r");
					while (($data_Ref_NeracaClose = fgetcsv($csvRef_NeracaClose)) !== FALSE) {
								$Ref_NeracaClose[] = $data_Ref_NeracaClose;
					}
					$i22 = 1;
					foreach ($Ref_NeracaClose as $value) {
						if ($i22 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_NeracaClose['Kd_Rincian'] = $value[0];
							$insert_Ref_NeracaClose['Kelompok'] = $value[1];
							$this->db->update('keuangan_ref_neraca_close ', $insert_Ref_NeracaClose);
						}
						$i22++;
					}

					//update Ref_Perangkat
					$csvRef_Perangkat= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Perangkat.csv', "r");
					while (($data_Ref_Perangkat = fgetcsv($csvRef_Perangkat)) !== FALSE) {
								$Ref_Perangkat[] = $data_Ref_Perangkat;
					}
					$i23 = 1;
					foreach ($Ref_Perangkat as $value) {
						if ($i23 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_Perangkat['Kode'] = $value[0];
							$insert_Ref_Perangkat['Nama_Perangkat'] = $value[1];
							$this->db->update('keuangan_ref_perangkat ', $insert_Ref_Perangkat);
						}
						$i23++;
					}

					//update Ref_Potongan
					$csvRef_Potongan= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Potongan.csv', "r");
					while (($data_Ref_Potongan = fgetcsv($csvRef_Potongan)) !== FALSE) {
								$Ref_Potongan[] = $data_Ref_Potongan;
					}
					$i24 = 1;
					foreach ($Ref_Potongan as $value) {
						if ($i24 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_Potongan['Kd_Rincian'] = $value[0];
							$insert_Ref_Potongan['Kd_Potongan'] = $value[1];
							$this->db->update('keuangan_ref_potongan', $insert_Ref_Potongan);
						}
						$i24++;
					}

					//update Ref_Rek1
					$csvRef_Rek1= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Rek1.csv', "r");
					while (($data_Ref_Rek1 = fgetcsv($csvRef_Rek1)) !== FALSE) {
								$Ref_Rek1[] = $data_Ref_Rek1;
					}
					$i25 = 1;
					foreach ($Ref_Rek1 as $value) {
						if ($i25 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_keuangan_ref_rek1['Akun'] = $value[0];
							$insert_keuangan_ref_rek1['Nama_Akun'] = $value[1];
							$insert_keuangan_ref_rek1['NoLap'] = $value[2];
							$this->db->update('keuangan_ref_rek1', $insert_keuangan_ref_rek1);
						}
						$i25++;
					}

					//update Ref_Rek2
					$csvRef_Rek2= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Rek2.csv', "r");
					while (($data_Ref_Rek2 = fgetcsv($csvRef_Rek2)) !== FALSE) {
								$Ref_Rek2[] = $data_Ref_Rek2;
					}
					$i26 = 1;
					foreach ($Ref_Rek2 as $value) {
						if ($i26 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_Rek2['Akun'] = $value[0];
							$insert_Ref_Rek2['Kelompok'] = $value[1];
							$insert_Ref_Rek2['Nama_Kelompok'] = $value[2];
							$this->db->update('keuangan_ref_rek2', $insert_Ref_Rek2);
						}
						$i26++;
					}

					//update Ref_Rek3
					$csvRef_Rek3= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Rek3.csv', "r");
					while (($data_Ref_Rek3 = fgetcsv($csvRef_Rek3)) !== FALSE) {
								$Ref_Rek3[] = $data_Ref_Rek3;
					}
					$i27 = 1;
					foreach ($Ref_Rek3 as $value) {
						if ($i27 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_Rek3['Kelompok'] = $value[0];
							$insert_Ref_Rek3['Jenis'] = $value[1];
							$insert_Ref_Rek3['Nama_Jenis'] = $value[2];
							$insert_Ref_Rek3['Formula'] = $value[3];
							$this->db->update('keuangan_ref_rek3', $insert_Ref_Rek3);
						}
						$i27++;
					}

					//update Ref_Rek3
					$csvRef_Rek3= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Rek3.csv', "r");
					while (($data_Ref_Rek3 = fgetcsv($csvRef_Rek3)) !== FALSE) {
								$Ref_Rek3[] = $data_Ref_Rek3;
					}
					$i27 = 1;
					foreach ($Ref_Rek3 as $value) {
						if ($i27 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_Rek3['Kelompok'] = $value[0];
							$insert_Ref_Rek3['Jenis'] = $value[1];
							$insert_Ref_Rek3['Nama_Jenis'] = $value[2];
							$insert_Ref_Rek3['Formula'] = $value[3];
							$this->db->update('keuangan_ref_rek3', $insert_Ref_Rek3);
						}
						$i27++;
					}

					//update Ref_SBU
					$csvRef_SBU= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_SBU.csv', "r");
					while (($data_Ref_SBU = fgetcsv($csvRef_SBU)) !== FALSE) {
								$Ref_SBU[] = $data_Ref_SBU;
					}
					$i28 = 1;
					foreach ($Ref_SBU as $value) {
						if ($i28 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_SBU['Kd_Rincian'] = $value[0];
							$insert_Ref_SBU['Kode_SBU'] = $value[1];
							$insert_Ref_SBU['NoUrut_SBU'] = $value[2];
							$insert_Ref_SBU['Nama_SBU'] = $value[3];
							$insert_Ref_SBU['Nilai'] = $value[4];
							$insert_Ref_SBU['Satuan'] = $value[5];
							$this->db->update('keuangan_ref_sbu', $insert_Ref_SBU);
						}
						$i28++;
					}

					//update Ref_SBU
					$csvRef_SBU= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_SBU.csv', "r");
					while (($data_Ref_SBU = fgetcsv($csvRef_SBU)) !== FALSE) {
								$Ref_SBU[] = $data_Ref_SBU;
					}
					$i28 = 1;
					foreach ($Ref_SBU as $value) {
						if ($i28 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_SBU['Kd_Rincian'] = $value[0];
							$insert_Ref_SBU['Kode_SBU'] = $value[1];
							$insert_Ref_SBU['NoUrut_SBU'] = $value[2];
							$insert_Ref_SBU['Nama_SBU'] = $value[3];
							$insert_Ref_SBU['Nilai'] = $value[4];
							$insert_Ref_SBU['Satuan'] = $value[5];
							$this->db->update('keuangan_ref_sbu', $insert_Ref_SBU);
						}
						$i28++;
					}

					//update Ref_Sumber
					$csvRef_Sumber= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ref_Sumber.csv', "r");
					while (($data_Ref_Sumber = fgetcsv($csvRef_Sumber)) !== FALSE) {
								$Ref_Sumber[] = $data_Ref_Sumber;
					}
					$i29 = 1;
					foreach ($Ref_Sumber as $value) {
						if ($i29 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ref_Sumber['Kode'] = $value[0];
							$insert_Ref_Sumber['Nama_Sumber'] = $value[1];
							$insert_Ref_Sumber['Urut'] = $value[2];
							$this->db->update('keuangan_ref_sumber', $insert_Ref_Sumber);
						}
						$i29++;
					}

					//update Ta_Anggaran
					$csvTa_Anggaran= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Anggaran.csv', "r");
					while (($data_Ta_Anggaran = fgetcsv($csvTa_Anggaran)) !== FALSE) {
								$Ta_Anggaran[] = $data_Ta_Anggaran;
					}
					$i30 = 1;
					foreach ($Ta_Anggaran as $value) {
						if ($i30 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_Anggaran['KURincianSD'] = $value[2];
							$insert_Ta_Anggaran['KD_Rincian'] = $value[3];
							$insert_Ta_Anggaran['RincianSD'] = $value[4];
							$insert_Ta_Anggaran['anggaran'] = $value[5];
							$insert_Ta_Anggaran['anggaranPAK'] = $value[6];
							$insert_Ta_Anggaran['anggaranStlhPAK'] = $value[7];
							$insert_Ta_Anggaran['Belanja'] = $value[8];
							$insert_Ta_Anggaran['Kd_keg'] = $value[9];
							$insert_Ta_Anggaran['SumberDana'] = $value[10];
							$insert_Ta_Anggaran['kd_desa'] = $value[11];
							$insert_Ta_Anggaran['tgl_posting'] = $value[12];
							$this->db->update('keuangan_ta_anggaran', $insert_Ta_Anggaran);
						}
						$i30++;
					}

					//update Ta_AnggaranLog
					$csvTa_AnggaranLog= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_AnggaranLog.csv', "r");
					while (($data_Ta_AnggaranLog = fgetcsv($csvTa_AnggaranLog)) !== FALSE) {
								$Ta_AnggaranLog[] = $data_Ta_AnggaranLog;
					}
					$i31 = 1;
					foreach ($Ta_AnggaranLog as $value) {
						if ($i31 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_AnggaranLog['KdPosting'] = $value[0];
							$insert_Ta_AnggaranLog['Tahun'] = $value[1];
							$insert_Ta_AnggaranLog['Kd_Desa'] = $value[2];
							$insert_Ta_AnggaranLog['No_Perdes'] = $value[3];
							$insert_Ta_AnggaranLog['TglPosting'] = $value[4];
							$insert_Ta_AnggaranLog['UserID'] = $value[5];
							$insert_Ta_AnggaranLog['Kunci'] = $value[6];
							$this->db->update('keuangan_ta_anggaran_log', $insert_Ta_AnggaranLog);
						}
						$i31++;
					}

					//update Ta_AnggaranLog
					$csvTa_AnggaranLog= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_AnggaranLog.csv', "r");
					while (($data_Ta_AnggaranLog = fgetcsv($csvTa_AnggaranLog)) !== FALSE) {
								$Ta_AnggaranLog[] = $data_Ta_AnggaranLog;
					}
					$i31 = 1;
					foreach ($Ta_AnggaranLog as $value) {
						if ($i31 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_AnggaranLog['KdPosting'] = $value[0];
							$insert_Ta_AnggaranLog['Tahun'] = $value[1];
							$insert_Ta_AnggaranLog['Kd_Desa'] = $value[2];
							$insert_Ta_AnggaranLog['No_Perdes'] = $value[3];
							$insert_Ta_AnggaranLog['TglPosting'] = $value[4];
							$insert_Ta_AnggaranLog['UserID'] = $value[5];
							$insert_Ta_AnggaranLog['Kunci'] = $value[6];
							$this->db->update('keuangan_ta_anggaran_log', $insert_Ta_AnggaranLog);
						}
						$i31++;
					}

					//update Ta_AnggaranRinci
					$csvTa_AnggaranRinci= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_AnggaranRinci.csv', "r");
					while (($data_Ta_AnggaranRinci = fgetcsv($csvTa_AnggaranRinci)) !== FALSE) {
								$Ta_AnggaranRinci[] = $data_Ta_AnggaranRinci;
					}
					$i32 = 1;
					foreach ($Ta_AnggaranRinci as $value) {
						if ($i32 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_AnggaranRinci['KdPosting'] = $value[0];
							$insert_Ta_AnggaranRinci['Tahun'] = $value[1];
							$insert_Ta_AnggaranRinci['Kd_Desa'] = $value[2];
							$insert_Ta_AnggaranRinci['Kd_Keg'] = $value[3];
							$insert_Ta_AnggaranRinci['Kd_Rincian'] = $value[4];
							$insert_Ta_AnggaranRinci['Kd_SubRinci'] = $value[5];
							$insert_Ta_AnggaranRinci['No_Urut'] = $value[6];
							$insert_Ta_AnggaranRinci['Uraian'] = $value[7];
							$insert_Ta_AnggaranRinci['SumberDana'] = $value[8];
							$insert_Ta_AnggaranRinci['JmlSatuan'] = $value[9];
							$insert_Ta_AnggaranRinci['HrgSatuan'] = $value[10];
							$insert_Ta_AnggaranRinci['Satuan'] = $value[11];
							$insert_Ta_AnggaranRinci['Anggaran'] = $value[12];
							$insert_Ta_AnggaranRinci['JmlSatuanPAK'] = $value[13];
							$insert_Ta_AnggaranRinci['HrgSatuanPAK'] = $value[14];
							$insert_Ta_AnggaranRinci['AnggaranStlhPAK'] = $value[15];
							$insert_Ta_AnggaranRinci['AnggaranPAK'] = $value[16];
							$this->db->update('keuangan_ta_anggaran_rinci', $insert_Ta_AnggaranRinci);
						}
						$i32++;
					}

					//update Ta_Bidang
					$csvTa_Bidang= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Bidang.csv', "r");
					while (($data_Ta_Bidang = fgetcsv($csvTa_Bidang)) !== FALSE) {
								$Ta_Bidang[] = $data_Ta_Bidang;
					}
					$i33 = 1;
					foreach ($Ta_Bidang as $value) {
						if ($i33 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_Bidang['Kd_Desa'] = $value[1];
							$insert_Ta_Bidang['Kd_Bid'] = $value[2];
							$insert_Ta_Bidang['Nama_Bidang'] = $value[3];
							$this->db->update('keuangan_ta_bidang', $insert_Ta_Bidang);
						}
						$i33++;
					}

					//update Ta_JurnalUmum
					$csvTa_JurnalUmum= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_JurnalUmum.csv', "r");
					while (($data_Ta_JurnalUmum = fgetcsv($csvTa_JurnalUmum)) !== FALSE) {
								$Ta_JurnalUmum[] = $data_Ta_JurnalUmum;
					}
					$i34 = 1;
					foreach ($Ta_JurnalUmum as $value) {
						if ($i34 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_JurnalUmum['KdBuku'] = $value[1];
							$insert_Ta_JurnalUmum['Kd_Desa'] = $value[2];
							$insert_Ta_JurnalUmum['Tanggal'] = $value[3];
							$insert_Ta_JurnalUmum['JnsBukti'] = $value[4];
							$insert_Ta_JurnalUmum['NoBukti'] = $value[5];
							$insert_Ta_JurnalUmum['Keterangan'] = $value[6];
							$insert_Ta_JurnalUmum['DK'] = $value[7];
							$insert_Ta_JurnalUmum['Debet'] = $value[8];
							$insert_Ta_JurnalUmum['Kredit'] = $value[9];
							$insert_Ta_JurnalUmum['Jenis'] = $value[10];
							$insert_Ta_JurnalUmum['Posted'] = $value[11];
							$this->db->update('keuangan_ta_jurnal_umum', $insert_Ta_JurnalUmum);
						}
						$i34++;
					}

					//update Ta_JurnalUmumRinci
					$csvTa_JurnalUmumRinci= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_JurnalUmumRinci.csv', "r");
					while (($data_Ta_JurnalUmumRinci = fgetcsv($csvTa_JurnalUmumRinci)) !== FALSE) {
								$Ta_JurnalUmumRinci[] = $data_Ta_JurnalUmumRinci;
					}
					$i35 = 1;
					foreach ($Ta_JurnalUmumRinci as $value) {
						if ($i35 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_JurnalUmumRinci['NoBukti'] = $value[1];
							$insert_Ta_JurnalUmumRinci['Kd_Keg'] = $value[2];
							$insert_Ta_JurnalUmumRinci['RincianSD'] = $value[3];
							$insert_Ta_JurnalUmumRinci['NoID'] = $value[4];
							$insert_Ta_JurnalUmumRinci['Kd_Desa'] = $value[5];
							$insert_Ta_JurnalUmumRinci['Akun'] = $value[6];
							$insert_Ta_JurnalUmumRinci['Kd_Rincian'] = $value[7];
							$insert_Ta_JurnalUmumRinci['Sumberdana'] = $value[8];
							$insert_Ta_JurnalUmumRinci['DK'] = $value[9];
							$insert_Ta_JurnalUmumRinci['Debet'] = $value[10];
							$insert_Ta_JurnalUmumRinci['Kredit'] = $value[11];
							$this->db->update('keuangan_ta_jurnal_umum_rinci', $insert_Ta_JurnalUmumRinci);
						}
						$i35++;
					}

					//update Ta_Kegiatan
					$csvTa_Kegiatan = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Kegiatan.csv', "r");
					while (($data_Ta_Kegiatan = fgetcsv($csvTa_Kegiatan)) !== FALSE) {
								$Ta_Kegiatan[] = $data_Ta_Kegiatan;
					}
					$i36 = 1;
					foreach ($Ta_Kegiatan as $value) {
						if ($i36 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_Kegiatan['Kd_Desa'] = $value[1];
							$insert_Ta_Kegiatan['Kd_Bid'] = $value[2];
							$insert_Ta_Kegiatan['Kd_Keg'] = $value[3];
							$insert_Ta_Kegiatan['ID_Keg'] = $value[4];
							$insert_Ta_Kegiatan['Nama_Kegiatan'] = $value[5];
							$insert_Ta_Kegiatan['Pagu'] = $value[6];
							$insert_Ta_Kegiatan['Pagu_PAK'] = $value[7];
							$insert_Ta_Kegiatan['Nm_PPTKD'] = $value[8];
							$insert_Ta_Kegiatan['NIP_PPTKD'] = $value[9];
							$insert_Ta_Kegiatan['Lokasi'] = $value[10];
							$insert_Ta_Kegiatan['Waktu'] = $value[11];
							$insert_Ta_Kegiatan['Keluaran'] = $value[12];
							$insert_Ta_Kegiatan['Sumberdana'] = $value[13];
							$this->db->update('keuangan_ta_kegiatan', $insert_Ta_Kegiatan);
						}
						$i36++;
					}

					//update Ta_Pajak
					$csvTa_Pajak = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Pajak.csv', "r");
					while (($data_Ta_Pajak = fgetcsv($csvTa_Pajak)) !== FALSE) {
								$Ta_Pajak[] = $data_Ta_Pajak;
					}
					$i37 = 1;
					foreach ($Ta_Pajak as $value) {
						if ($i37 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_Pajak['Kd_Desa'] = $value[1];
							$insert_Ta_Pajak['No_SSP'] = $value[2];
							$insert_Ta_Pajak['Tgl_SSP'] = $value[3];
							$insert_Ta_Pajak['Keterangan'] = $value[4];
							$insert_Ta_Pajak['Nama_WP'] = $value[5];
							$insert_Ta_Pajak['Alamat_WP'] = $value[6];
							$insert_Ta_Pajak['NPWP'] = $value[7];
							$insert_Ta_Pajak['Kd_MAP'] = $value[8];
							$insert_Ta_Pajak['Nm_Penyetor'] = $value[9];
							$insert_Ta_Pajak['Jn_Transaksi'] = $value[10];
							$insert_Ta_Pajak['Kd_Rincian'] = $value[11];
							$insert_Ta_Pajak['Jumlah'] = $value[12];
							$insert_Ta_Pajak['KdBayar'] = $value[13];
							$this->db->update('keuangan_ta_pajak', $insert_Ta_Pajak);
						}
						$i37++;
					}

					//update Ta_PajakRinci
					$csvTa_PajakRinci = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_PajakRinci.csv', "r");
					while (($data_Ta_PajakRinci = fgetcsv($csvTa_PajakRinci)) !== FALSE) {
								$Ta_PajakRinci[] = $data_Ta_PajakRinci;
					}
					$i38 = 1;
					foreach ($Ta_PajakRinci as $value) {
						if ($i38 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_PajakRinci['Kd_Desa'] = $value[1];
							$insert_Ta_PajakRinci['No_SSP'] = $value[2];
							$insert_Ta_PajakRinci['No_Bukti'] = $value[3];
							$insert_Ta_PajakRinci['Kd_Rincian'] = $value[4];
							$insert_Ta_PajakRinci['Nilai'] = $value[5];
							$this->db->update('keuangan_ta_pajak_rinci', $insert_Ta_PajakRinci);
						}
						$i38++;
					}

					//update Ta_Pemda
					$csvTa_Pemda = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Pemda.csv', "r");
					while (($data_Ta_Pemda = fgetcsv($csvTa_Pemda)) !== FALSE) {
								$Ta_Pemda[] = $data_Ta_Pemda;
					}
					$i39 = 1;
					foreach ($Ta_Pemda as $value) {
						if ($i39 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_Pemda['Kd_Prov'] = $value[1];
							$insert_Ta_Pemda['Kd_Kab'] = $value[2];
							$insert_Ta_Pemda['Nama_Pemda'] = $value[3];
							$insert_Ta_Pemda['Nama_Provinsi'] = $value[4];
							$insert_Ta_Pemda['Ibukota'] = $value[5];
							$insert_Ta_Pemda['Alamat'] = $value[6];
							$insert_Ta_Pemda['Nm_Bupati'] = $value[7];
							$insert_Ta_Pemda['Jbt_Bupati'] = $value[8];
							$insert_Ta_Pemda['Logo'] = $value[9];
							$insert_Ta_Pemda['C_Kode'] = $value[10];
							$insert_Ta_Pemda['C_Pemda'] = $value[11];
							$insert_Ta_Pemda['C_Data'] = $value[12];
							$this->db->update('keuangan_ta_pemda', $insert_Ta_Pemda);
						}
						$i39++;
					}

					//update Ta_Perangkat
					$csvTa_Perangkat = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Perangkat.csv', "r");
					while (($data_Ta_Perangkat = fgetcsv($csvTa_Perangkat)) !== FALSE) {
								$Ta_Perangkat[] = $data_Ta_Perangkat;
					}
					$i40 = 1;
					foreach ($Ta_Perangkat as $value) {
						if ($i40 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_Perangkat['Kd_Desa'] = $value[1];
							$insert_Ta_Perangkat['Kd_Jabatan'] = $value[2];
							$insert_Ta_Perangkat['No_ID'] = $value[3];
							$insert_Ta_Perangkat['Nama_Perangkat'] = $value[4];
							$insert_Ta_Perangkat['Alamat_Perangkat'] = $value[5];
							$insert_Ta_Perangkat['Nomor_HP'] = $value[6];
							$insert_Ta_Perangkat['Rek_Bank'] = $value[7];
							$insert_Ta_Perangkat['Nama_Bank'] = $value[8];
							$this->db->update('keuangan_ta_perangkat', $insert_Ta_Perangkat);
						}
						$i40++;
					}

					//update Ta_RABRinci
					$csvTa_RABRinci = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RABRinci.csv', "r");
					while (($data_ta_RABRinci = fgetcsv($csvTa_RABRinci)) !== FALSE) {
								$Ta_RABRinci[] = $data_ta_RABRinci;
					}
					$i41 = 1;
					foreach ($Ta_RABRinci as $value) {
						if ($i41 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_RABRinci['Kd_Desa'] = $value[1];
							$insert_Ta_RABRinci['Kd_Keg'] = $value[2];
							$insert_Ta_RABRinci['Kd_Rincian'] = $value[3];
							$insert_Ta_RABRinci['Kd_SubRinci'] = $value[4];
							$insert_Ta_RABRinci['No_Urut'] = $value[5];
							$insert_Ta_RABRinci['SumberDana'] = $value[6];
							$insert_Ta_RABRinci['Uraian'] = $value[7];
							$insert_Ta_RABRinci['Satuan'] = $value[8];
							$insert_Ta_RABRinci['JmlSatuan'] = $value[9];
							$insert_Ta_RABRinci['HrgSatuan'] = $value[10];
							$insert_Ta_RABRinci['Anggaran'] = $value[11];
							$insert_Ta_RABRinci['JmlSatuanPAK'] = $value[12];
							$insert_Ta_RABRinci['HrgSatuanPAK'] = $value[13];
							$insert_Ta_RABRinci['AnggaranStlhPAK'] = $value[14];
							$insert_Ta_RABRinci['AnggaranPAK'] = $value[15];
							$insert_Ta_RABRinci['Kode_SBU'] = $value[16];

							$this->db->update('keuangan_ta_rab_rinci', $insert_Ta_RABRinci);
						}
						$i41++;
					}

					//update Ta_RABSub
					$csvTa_RABSub = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RABSub.csv', "r");
					while (($data_Ta_RABSub = fgetcsv($csvTa_RABSub)) !== FALSE) {
								$Ta_RABSub[] = $data_Ta_RABSub;
					}
					$i42 = 1;
					foreach ($Ta_RABSub as $value) {
						if ($i42 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_RABSub['Kd_Desa'] = $value[1];
							$insert_Ta_RABSub['Kd_Keg'] = $value[2];
							$insert_Ta_RABSub['Kd_Rincian'] = $value[3];
							$insert_Ta_RABSub['Kd_SubRinci'] = $value[4];
							$insert_Ta_RABSub['Nama_SubRinci'] = $value[5];
							$insert_Ta_RABSub['Anggaran'] = $value[6];
							$insert_Ta_RABSub['AnggaranPAK'] = $value[7];
							$insert_Ta_RABSub['AnggaranStlhPAK'] = $value[8];
							$this->db->update('keuangan_ta_rab_sub', $insert_Ta_RABSub);
						}
						$i42++;
					}

					//update Ta_RPJM_Bidang
					$csvTa_RPJM_Bidang = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Bidang.csv', "r");
					while (($data_Ta_RPJM_Bidang = fgetcsv($csvTa_RPJM_Bidang)) !== FALSE) {
								$Ta_RPJM_Bidang[] = $data_Ta_RPJM_Bidang;
					}
					$i43 = 1;
					foreach ($Ta_RPJM_Bidang as $value) {
						if ($i43 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_RPJM_Bidang['Kd_Desa'] = $value[0];
							$insert_Ta_RPJM_Bidang['Kd_Bid'] = $value[1];
							$insert_Ta_RPJM_Bidang['Nama_Bidang'] = $value[2];
							$this->db->update('keuangan_ta_rpjm_bidang', $insert_Ta_RPJM_Bidang);
						}
						$i43++;
					}

					//update Ta_RPJM_Kegiatan
					$csvTa_RPJM_Kegiatan = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Kegiatan.csv', "r");
					while (($data_Ta_RPJM_Kegiatan = fgetcsv($csvTa_RPJM_Kegiatan)) !== FALSE) {
								$Ta_RPJM_Kegiatan[] = $data_Ta_RPJM_Kegiatan;
					}
					$i44 = 1;
					foreach ($Ta_RPJM_Kegiatan as $value) {
						if ($i44 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_RPJM_Kegiatan['Kd_Desa'] = $value[0];
							$insert_Ta_RPJM_Kegiatan['Kd_Bid'] = $value[1];
							$insert_Ta_RPJM_Kegiatan['Kd_Keg'] = $value[2];
							$insert_Ta_RPJM_Kegiatan['ID_Keg'] = $value[3];
							$insert_Ta_RPJM_Kegiatan['Nama_Kegiatan'] = $value[4];
							$insert_Ta_RPJM_Kegiatan['Lokasi'] = $value[5];
							$insert_Ta_RPJM_Kegiatan['Keluaran'] = $value[6];
							$insert_Ta_RPJM_Kegiatan['Kd_Sas'] = $value[7];
							$insert_Ta_RPJM_Kegiatan['Sasaran'] = $value[8];
							$insert_Ta_RPJM_Kegiatan['Tahun1'] = $value[9];
							$insert_Ta_RPJM_Kegiatan['Tahun2'] = $value[10];
							$insert_Ta_RPJM_Kegiatan['Tahun3'] = $value[11];
							$insert_Ta_RPJM_Kegiatan['Tahun4'] = $value[12];
							$insert_Ta_RPJM_Kegiatan['Tahun5'] = $value[13];
							$insert_Ta_RPJM_Kegiatan['Tahun6'] = $value[14];
							$insert_Ta_RPJM_Kegiatan['Swakelola'] = $value[15];
							$insert_Ta_RPJM_Kegiatan['Kerjasama'] = $value[16];
							$insert_Ta_RPJM_Kegiatan['Pihak_Ketiga'] = $value[17];
							$insert_Ta_RPJM_Kegiatan['Sumberdana'] = $value[18];
							$this->db->update('keuangan_ta_rpjm_kegiatan', $insert_Ta_RPJM_Kegiatan);
						}
						$i44++;
					}

					//update Ta_RPJM_Misi
					$csvTa_RPJM_Misi  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Misi.csv', "r");
					while (($data_Ta_RPJM_Misi = fgetcsv($csvTa_RPJM_Misi)) !== FALSE) {
								$Ta_RPJM_Misi[] = $data_Ta_RPJM_Misi;
					}
					$i45 = 1;
					foreach ($Ta_RPJM_Misi as $value) {
						if ($i45 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_RPJM_Misi['ID_Misi'] = $value[0];
							$insert_Ta_RPJM_Misi['Kd_Desa'] = $value[1];
							$insert_Ta_RPJM_Misi['ID_Visi'] = $value[2];
							$insert_Ta_RPJM_Misi['No_Misi'] = $value[3];
							$insert_Ta_RPJM_Misi['Uraian_Misi'] = $value[4];
							$this->db->update('keuangan_ta_rpjm_misi', $insert_Ta_RPJM_Misi);
						}
						$i45++;
					}

					//update Ta_RPJM_Pagu_Indikatif
					$csvTa_RPJM_Pagu_Indikatif  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Pagu_Indikatif.csv', "r");
					while (($data_Ta_RPJM_Pagu_Indikatif = fgetcsv($csvTa_RPJM_Pagu_Indikatif)) !== FALSE) {
								$Ta_RPJM_Pagu_Indikatif[] = $data_Ta_RPJM_Pagu_Indikatif;
					}
					$i46 = 1;
					foreach ($Ta_RPJM_Pagu_Indikatif as $value) {
						if ($i46 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_RPJM_Pagu_Indikatif['Kd_Desa'] = $value[0];
							$insert_Ta_RPJM_Pagu_Indikatif['Kd_Keg'] = $value[1];
							$insert_Ta_RPJM_Pagu_Indikatif['Kd_Sumber'] = $value[2];
							$insert_Ta_RPJM_Pagu_Indikatif['Tahun1'] = $value[3];
							$insert_Ta_RPJM_Pagu_Indikatif['Tahun2'] = $value[4];
							$insert_Ta_RPJM_Pagu_Indikatif['Tahun3'] = $value[5];
							$insert_Ta_RPJM_Pagu_Indikatif['Tahun4'] = $value[6];
							$insert_Ta_RPJM_Pagu_Indikatif['Tahun5'] = $value[7];
							$insert_Ta_RPJM_Pagu_Indikatif['Tahun6'] = $value[8];
							$insert_Ta_RPJM_Pagu_Indikatif['Pola'] = $value[9];
							$this->db->update('keuangan_ta_rpjm_pagu_indikatif', $insert_Ta_RPJM_Pagu_Indikatif);
						}
						$i46++;
					}

					//update Ta_RPJM_Pagu_Tahunan
					$csvTa_RPJM_Pagu_Tahunan  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Pagu_Tahunan.csv', "r");
					while (($data_Ta_RPJM_Pagu_Tahunan = fgetcsv($csvTa_RPJM_Pagu_Tahunan)) !== FALSE) {
								$Ta_RPJM_Pagu_Tahunan[] = $data_Ta_RPJM_Pagu_Tahunan;
					}
					$i47 = 1;
					foreach ($Ta_RPJM_Pagu_Tahunan as $value) {
						if ($i47 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_RPJM_Pagu_Tahunan['Kd_Desa'] = $value[0];
							$insert_Ta_RPJM_Pagu_Tahunan['Kd_Keg'] = $value[1];
							$insert_Ta_RPJM_Pagu_Tahunan['Kd_Tahun'] = $value[2];
							$insert_Ta_RPJM_Pagu_Tahunan['Kd_Sumber'] = $value[3];
							$insert_Ta_RPJM_Pagu_Tahunan['Biaya'] = $value[4];
							$insert_Ta_RPJM_Pagu_Tahunan['Volume'] = $value[5];
							$insert_Ta_RPJM_Pagu_Tahunan['Satuan'] = $value[6];
							$insert_Ta_RPJM_Pagu_Tahunan['Lokasi_Spesifik'] = $value[7];
							$insert_Ta_RPJM_Pagu_Tahunan['Jml_Sas_Pria'] = $value[8];
							$insert_Ta_RPJM_Pagu_Tahunan['Jml_Sas_Wanita'] = $value[9];
							$insert_Ta_RPJM_Pagu_Tahunan['Jml_Sas_ARTM'] = $value[10];
							$insert_Ta_RPJM_Pagu_Tahunan['Waktu'] = $value[11];
							$insert_Ta_RPJM_Pagu_Tahunan['Mulai'] = $value[12];
							$insert_Ta_RPJM_Pagu_Tahunan['Selesai'] = $value[13];
							$insert_Ta_RPJM_Pagu_Tahunan['Pola_Kegiatan'] = $value[14];
							$insert_Ta_RPJM_Pagu_Tahunan['Pelaksana'] = $value[15];
							$this->db->update('keuangan_ta_rpjm_pagu_tahunan', $insert_Ta_RPJM_Pagu_Tahunan);
						}
						$i47++;
					}

					//update Ta_RPJM_Sasaran
					$csvTa_RPJM_Sasaran  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Sasaran.csv', "r");
					while (($data_Ta_RPJM_Sasaran = fgetcsv($csvTa_RPJM_Sasaran)) !== FALSE) {
								$Ta_RPJM_Sasaran[] = $data_Ta_RPJM_Sasaran;
					}
					$i48 = 1;
					foreach ($Ta_RPJM_Sasaran as $value) {
						if ($i48 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_RPJM_Sasaran['ID_Sasaran '] = $value[0];
							$insert_Ta_RPJM_Sasaran['Kd_Desa'] = $value[1];
							$insert_Ta_RPJM_Sasaran['ID_Tujuan'] = $value[2];
							$insert_Ta_RPJM_Sasaran['No_Sasaran'] = $value[3];
							$insert_Ta_RPJM_Sasaran['Uraian_Sasaran'] = $value[4];
							$this->db->update('keuangan_ta_rpjm_sasaran', $insert_Ta_RPJM_Sasaran);
						}
						$i48++;
					}

					//update Ta_RPJM_Tujuan
					$csvTa_RPJM_Tujuan  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Tujuan.csv', "r");
					while (($data_Ta_RPJM_Tujuan = fgetcsv($csvTa_RPJM_Tujuan)) !== FALSE) {
								$Ta_RPJM_Tujuan[] = $data_Ta_RPJM_Tujuan;
					}
					$i49 = 1;
					foreach ($Ta_RPJM_Tujuan as $value) {
						if ($i49 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_RPJM_Tujuan['ID_Tujuan '] = $value[0];
							$insert_Ta_RPJM_Tujuan['Kd_Desa'] = $value[1];
							$insert_Ta_RPJM_Tujuan['ID_Misi'] = $value[2];
							$insert_Ta_RPJM_Tujuan['No_Tujuan'] = $value[3];
							$insert_Ta_RPJM_Tujuan['Uraian_Tujuan'] = $value[4];
							$this->db->update('keuangan_ta_rpjm_tujuan', $insert_Ta_RPJM_Tujuan);
						}
						$i49++;
					}

					//update Ta_RPJM_Visi
					$csvTa_RPJM_Visi  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Visi.csv', "r");
					while (($data_Ta_RPJM_Visi = fgetcsv($csvTa_RPJM_Visi)) !== FALSE) {
								$Ta_RPJM_Visi[] = $data_Ta_RPJM_Visi;
					}
					$i50 = 1;
					foreach ($Ta_RPJM_Visi as $value) {
						if ($i50 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_RPJM_Visi['ID_Visi '] = $value[0];
							$insert_Ta_RPJM_Visi['Kd_Desa'] = $value[1];
							$insert_Ta_RPJM_Visi['No_Visi'] = $value[2];
							$insert_Ta_RPJM_Visi['Uraian_Visi'] = $value[3];
							$insert_Ta_RPJM_Visi['TahunA'] = $value[4];
							$insert_Ta_RPJM_Visi['TahunN'] = $value[5];
							$this->db->update('keuangan_ta_rpjm_visi', $insert_Ta_RPJM_Visi);
						}
						$i50++;
					}

					//update Ta_SaldoAwal
					$csvTa_SaldoAwal  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SaldoAwal.csv', "r");
					while (($data_Ta_SaldoAwal = fgetcsv($csvTa_SaldoAwal)) !== FALSE) {
								$Ta_SaldoAwal[] = $data_Ta_SaldoAwal;
					}
					$i51 = 1;
					foreach ($Ta_SaldoAwal as $value) {
						if ($i51 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_SaldoAwal['Kd_Desa '] = $value[1];
							$insert_Ta_SaldoAwal['Kd_Rincian'] = $value[2];
							$insert_Ta_SaldoAwal['Jenis'] = $value[3];
							$insert_Ta_SaldoAwal['Anggaran'] = $value[4];
							$insert_Ta_SaldoAwal['Debet'] = $value[5];
							$insert_Ta_SaldoAwal['Kredit'] = $value[6];
							$insert_Ta_SaldoAwal['Tgl_Bukti'] = $value[7];
							$this->db->update('keuangan_ta_saldo_awal', $insert_Ta_SaldoAwal);
						}
						$i51++;
					}

					//update Ta_SPJ
					$csvTa_SPJ  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPJ.csv', "r");
					while (($data_Ta_SPJ = fgetcsv($csvTa_SPJ)) !== FALSE) {
								$Ta_SPJ[] = $data_Ta_SPJ;
					}
					$i52 = 1;
					foreach ($Ta_SPJ as $value) {
						if ($i52 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_SPJ['No_SPJ'] = $value[1];
							$insert_Ta_SPJ['Tgl_SPJ'] = $value[2];
							$insert_Ta_SPJ['Kd_Desa'] = $value[3];
							$insert_Ta_SPJ['No_SPP'] = $value[4];
							$insert_Ta_SPJ['Keterangan'] = $value[5];
							$insert_Ta_SPJ['Jumlah'] = $value[6];
							$insert_Ta_SPJ['Potongan'] = $value[7];
							$insert_Ta_SPJ['Status'] = $value[8];
							$this->db->update('keuangan_ta_spj', $insert_Ta_SPJ);
						}
						$i52++;
					}

					//update Ta_SPJBukti
					$csvTa_SPJBukti  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPJBukti.csv', "r");
					while (($data_Ta_SPJBukti = fgetcsv($csvTa_SPJBukti)) !== FALSE) {
								$Ta_SPJBukti[] = $data_Ta_SPJBukti;
					}
					$i53 = 1;
					foreach ($Ta_SPJBukti as $value) {
						if ($i53 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_SPJBukti['No_SPJ'] = $value[1];
							$insert_Ta_SPJBukti['Kd_Keg'] = $value[2];
							$insert_Ta_SPJBukti['Kd_Rincian'] = $value[3];
							$insert_Ta_SPJBukti['No_Bukti'] = $value[4];
							$insert_Ta_SPJBukti['Tgl_Bukti'] = $value[5];
							$insert_Ta_SPJBukti['Sumberdana'] = $value[6];
							$insert_Ta_SPJBukti['Kd_Desa'] = $value[7];
							$insert_Ta_SPJBukti['Nm_Penerima'] = $value[8];
							$insert_Ta_SPJBukti['Alamat'] = $value[9];
							$insert_Ta_SPJBukti['Rek_Bank'] = $value[10];
							$insert_Ta_SPJBukti['Nm_Bank'] = $value[11];
							$insert_Ta_SPJBukti['NPWP'] = $value[12];
							$insert_Ta_SPJBukti['Keterangan'] = $value[13];
							$insert_Ta_SPJBukti['Nilai'] = $value[14];
							$this->db->update('keuangan_ta_spj_bukti', $insert_Ta_SPJBukti);
						}
						$i53++;
					}

					//update Ta_SPJRinci
					$csvTa_SPJRinci  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPJRinci.csv', "r");
					while (($data_Ta_SPJRinci = fgetcsv($csvTa_SPJRinci)) !== FALSE) {
								$Ta_SPJRinci[] = $data_Ta_SPJRinci;
					}
					$i54 = 1;
					foreach ($Ta_SPJRinci as $value) {
						if ($i54 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_SPJRinci['No_SPJ'] = $value[0];
							$insert_Ta_SPJRinci['Kd_Keg'] = $value[1];
							$insert_Ta_SPJRinci['Kd_Rincian'] = $value[2];
							$insert_Ta_SPJRinci['Sumberdana'] = $value[3];
							$insert_Ta_SPJRinci['Kd_Desa'] = $value[4];
							$insert_Ta_SPJRinci['Sumberdana'] = $value[5];
							$insert_Ta_SPJRinci['No_SPP'] = $value[6];
							$insert_Ta_SPJRinci['JmlCair'] = $value[7];
							$insert_Ta_SPJRinci['Nilai'] = $value[8];
							$insert_Ta_SPJRinci['Sisa'] = $value[9];
							$this->db->update('keuangan_ta_spj_rinci', $insert_Ta_SPJRinci);
						}
						$i54++;
					}

					//update Ta_SPP
					$csvTa_SPP  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPP.csv', "r");
					while (($data_Ta_SPP = fgetcsv($csvTa_SPP)) !== FALSE) {
								$Ta_SPP[] = $data_Ta_SPP;
					}
					$i55 = 1;
					foreach ($Ta_SPP as $value) {
						if ($i55 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_SPP['No_SPP'] = $value[1];
							$insert_Ta_SPP['Tgl_SPP'] = $value[2];
							$insert_Ta_SPP['Jn_SPP'] = $value[3];
							$insert_Ta_SPP['Kd_Desa'] = $value[4];
							$insert_Ta_SPP['Keterangan'] = $value[5];
							$insert_Ta_SPP['Jumlah'] = $value[6];
							$insert_Ta_SPP['Potongan'] = $value[7];
							$insert_Ta_SPP['Status'] = $value[8];

							$this->db->update('keuangan_ta_spp', $insert_Ta_SPP);
						}
						$i55++;
					}

					//update Ta_SPPRinci
					$csvTa_SPPRinci  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPPRinci.csv', "r");
					while (($data_Ta_SPPRinci = fgetcsv($csvTa_SPPRinci)) !== FALSE) {
								$Ta_SPPRinci[] = $data_Ta_SPPRinci;
					}
					$i56 = 1;
					foreach ($Ta_SPPRinci as $value) {
						if ($i56 > 1) {
						$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_SPPRinci['Kd_Desa'] = $value[1];
							$insert_Ta_SPPRinci['No_SPP'] = $value[2];
							$insert_Ta_SPPRinci['Kd_Keg'] = $value[3];
							$insert_Ta_SPPRinci['Kd_Rincian'] = $value[4];
							$insert_Ta_SPPRinci['Sumberdana'] = $value[5];
							$insert_Ta_SPPRinci['Nilai'] = $value[6];
							$this->db->update('keuangan_ta_spp_rinci', $insert_Ta_SPPRinci);
						}
						$i56++;
					}

					//update Ta_STSRinci
					$csvTa_STSRinci  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_STSRinci.csv', "r");
					while (($data_Ta_STSRinci = fgetcsv($csvTa_STSRinci)) !== FALSE) {
								$Ta_STSRinci[] = $data_Ta_STSRinci;
					}
					$i57 = 1;
					foreach ($Ta_STSRinci as $value) {
						if ($i56 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_STSRinci['Kd_Desa'] = $value[1];
							$insert_Ta_STSRinci['No_Bukti'] = $value[2];
							$insert_Ta_STSRinci['No_TBP'] = $value[3];
							$insert_Ta_STSRinci['Uraian'] = $value[4];
							$insert_Ta_STSRinci['Nilai'] = $value[5];
							$this->db->update('keuangan_ta_sts_rinci', $insert_Ta_STSRinci);
						}
						$i56++;
					}

					//update Ta_Triwulan
					$csvTa_Triwulan  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Triwulan.csv', "r");
					while (($data_Ta_Triwulan = fgetcsv($csvTa_Triwulan)) !== FALSE) {
								$Ta_Triwulan[] = $data_Ta_Triwulan;
					}
					$i58 = 1;
					foreach ($Ta_Triwulan as $value) {
						if ($i58 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_Triwulan['KURincianSD'] = $value[0];
							$insert_Ta_Triwulan['Tahun'] = $value[1];
							$insert_Ta_Triwulan['Sifat'] = $value[2];
							$insert_Ta_Triwulan['SumberDana'] = $value[3];
							$insert_Ta_Triwulan['Kd_Desa'] = $value[4];
							$insert_Ta_Triwulan['Kd_Keg'] = $value[4];
							$insert_Ta_Triwulan['Kd_Rincian'] = $value[5];
							$insert_Ta_Triwulan['Anggaran'] = $value[6];
							$insert_Ta_Triwulan['AnggaranPAK'] = $value[7];
							$insert_Ta_Triwulan['Tw1Rinci'] = $value[8];
							$insert_Ta_Triwulan['Tw2Rinci'] = $value[9];
							$insert_Ta_Triwulan['Tw3Rinci'] = $value[10];
							$insert_Ta_Triwulan['Tw4Rinci'] = $value[11];
							$insert_Ta_Triwulan['KunciData'] = $value[12];
							$this->db->update('keuangan_ta_triwulan', $insert_Ta_Triwulan);
						}
						$i58++;
					}

					//update Ta_TriwulanArsip
					$csvTa_TriwulanArsip  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_TriwulanArsip.csv', "r");
					while (($data_Ta_TriwulanArsip = fgetcsv($csvTa_TriwulanArsip)) !== FALSE) {
								$Ta_TriwulanArsip[] = $data_Ta_TriwulanArsip;
					}
					$i59 = 1;
					foreach ($Ta_TriwulanArsip as $value) {
						if ($i59 > 1) {
							$this->db->where('id_keuangan_master', $id_master_keuangan);
							$insert_Ta_TriwulanArsip['KdPosting'] = $value[0];
							$insert_Ta_TriwulanArsip['KURincianSD'] = $value[1];
							$insert_Ta_TriwulanArsip['Tahun'] = $value[3];
							$insert_Ta_TriwulanArsip['Sifat'] = $value[4];
							$insert_Ta_TriwulanArsip['SumberDana'] = $value[5];
							$insert_Ta_TriwulanArsip['Kd_Desa'] = $value[6];
							$insert_Ta_TriwulanArsip['Kd_Keg'] = $value[7];
							$insert_Ta_TriwulanArsip['Kd_Rincian'] = $value[8];
							$insert_Ta_TriwulanArsip['Anggaran'] = $value[9];
							$insert_Ta_TriwulanArsip['AnggaranPAK'] = $value[10];
							$insert_Ta_TriwulanArsip['Tw1Rinci'] = $value[11];
							$insert_Ta_TriwulanArsip['Tw2Rinci'] = $value[12];
							$insert_Ta_TriwulanArsip['Tw3Rinci'] = $value[13];
							$insert_Ta_TriwulanArsip['Tw4Rinci'] = $value[14];
							$insert_Ta_TriwulanArsip['KunciData'] = $value[15];
							$this->db->update('keuangan_ta_triwulan_arsip', $insert_Ta_TriwulanArsip);
						}
						$i59++;
					}

					$zip_file->close();
					$_SESSION['success'] = 1;
					$_SESSION['error_msg'] = 'Berhasil';
					redirect('keuangan/import_data');
				}else{
					$_SESSION['success'] = -1;
					$_SESSION['error_msg'] = '';
					redirect('keuangan/import_data');
				}
			}else{
				$uploadError = $this->upload->display_errors(NULL, NULL);
				$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = $uploadError;
				redirect('keuangan/import_data');
			}
	}

	public function extract($path)
	{
		$this->upload->initialize($this->uploadConfig);
		$adaLampiran = !empty($_FILES['keuangan']['name']);
			if ($this->upload->do_upload('keuangan')){
					$data      = $this->upload->data();
					$zip_file  = new ZipArchive;
					$full_path = $data['full_path'];
			if ($zip_file->open($full_path) === TRUE){
					$zip_file->extractTo(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name']);
					$data2['versi_database'] = $_POST['versi_database'];
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
									$insert_keuangan_ref_rek1['id_keuangan_master'] = $id_master_keuangan;
									$insert_keuangan_ref_rek1['Akun'] = $value[0];
									$insert_keuangan_ref_rek1['Nama_Akun'] = $value[1];
									$insert_keuangan_ref_rek1['NoLap'] = $value[2];
									$this->db->insert('keuangan_ref_rek1', $insert_keuangan_ref_rek1);
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

							//insert Ta_Anggaran
							$csvTa_Anggaran= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Anggaran.csv', "r");
							while (($data_Ta_Anggaran = fgetcsv($csvTa_Anggaran)) !== FALSE) {
										$Ta_Anggaran[] = $data_Ta_Anggaran;
							}
							$i30 = 1;
							foreach ($Ta_Anggaran as $value) {
								if ($i30 > 1) {
									$insert_Ta_Anggaran['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_Anggaran['KURincianSD'] = $value[2];
									$insert_Ta_Anggaran['KD_Rincian'] = $value[3];
									$insert_Ta_Anggaran['RincianSD'] = $value[4];
									$insert_Ta_Anggaran['anggaran'] = $value[5];
									$insert_Ta_Anggaran['anggaranPAK'] = $value[6];
									$insert_Ta_Anggaran['anggaranStlhPAK'] = $value[7];
									$insert_Ta_Anggaran['Belanja'] = $value[8];
									$insert_Ta_Anggaran['Kd_keg'] = $value[9];
									$insert_Ta_Anggaran['SumberDana'] = $value[10];
									$insert_Ta_Anggaran['kd_desa'] = $value[11];
									$insert_Ta_Anggaran['tgl_posting'] = $value[12];
									$this->db->insert('keuangan_ta_anggaran', $insert_Ta_Anggaran);
								}
								$i30++;
							}

							//insert Ta_AnggaranLog
							$csvTa_AnggaranLog= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_AnggaranLog.csv', "r");
							while (($data_Ta_AnggaranLog = fgetcsv($csvTa_AnggaranLog)) !== FALSE) {
										$Ta_AnggaranLog[] = $data_Ta_AnggaranLog;
							}
							$i31 = 1;
							foreach ($Ta_AnggaranLog as $value) {
								if ($i31 > 1) {
									$insert_Ta_AnggaranLog['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_AnggaranLog['KdPosting'] = $value[0];
									$insert_Ta_AnggaranLog['Tahun'] = $value[1];
									$insert_Ta_AnggaranLog['Kd_Desa'] = $value[2];
									$insert_Ta_AnggaranLog['No_Perdes'] = $value[3];
									$insert_Ta_AnggaranLog['TglPosting'] = $value[4];
									$insert_Ta_AnggaranLog['UserID'] = $value[5];
									$insert_Ta_AnggaranLog['Kunci'] = $value[6];
									$this->db->insert('keuangan_ta_anggaran_log', $insert_Ta_AnggaranLog);
								}
								$i31++;
							}

							//insert Ta_AnggaranLog
							$csvTa_AnggaranLog= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_AnggaranLog.csv', "r");
							while (($data_Ta_AnggaranLog = fgetcsv($csvTa_AnggaranLog)) !== FALSE) {
										$Ta_AnggaranLog[] = $data_Ta_AnggaranLog;
							}
							$i31 = 1;
							foreach ($Ta_AnggaranLog as $value) {
								if ($i31 > 1) {
									$insert_Ta_AnggaranLog['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_AnggaranLog['KdPosting'] = $value[0];
									$insert_Ta_AnggaranLog['Tahun'] = $value[1];
									$insert_Ta_AnggaranLog['Kd_Desa'] = $value[2];
									$insert_Ta_AnggaranLog['No_Perdes'] = $value[3];
									$insert_Ta_AnggaranLog['TglPosting'] = $value[4];
									$insert_Ta_AnggaranLog['UserID'] = $value[5];
									$insert_Ta_AnggaranLog['Kunci'] = $value[6];
									$this->db->insert('keuangan_ta_anggaran_log', $insert_Ta_AnggaranLog);
								}
								$i31++;
							}

							//insert Ta_AnggaranRinci
							$csvTa_AnggaranRinci= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_AnggaranRinci.csv', "r");
							while (($data_Ta_AnggaranRinci = fgetcsv($csvTa_AnggaranRinci)) !== FALSE) {
										$Ta_AnggaranRinci[] = $data_Ta_AnggaranRinci;
							}
							$i32 = 1;
							foreach ($Ta_AnggaranRinci as $value) {
								if ($i32 > 1) {
									$insert_Ta_AnggaranRinci['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_AnggaranRinci['KdPosting'] = $value[0];
									$insert_Ta_AnggaranRinci['Tahun'] = $value[1];
									$insert_Ta_AnggaranRinci['Kd_Desa'] = $value[2];
									$insert_Ta_AnggaranRinci['Kd_Keg'] = $value[3];
									$insert_Ta_AnggaranRinci['Kd_Rincian'] = $value[4];
									$insert_Ta_AnggaranRinci['Kd_SubRinci'] = $value[5];
									$insert_Ta_AnggaranRinci['No_Urut'] = $value[6];
									$insert_Ta_AnggaranRinci['Uraian'] = $value[7];
									$insert_Ta_AnggaranRinci['SumberDana'] = $value[8];
									$insert_Ta_AnggaranRinci['JmlSatuan'] = $value[9];
									$insert_Ta_AnggaranRinci['HrgSatuan'] = $value[10];
									$insert_Ta_AnggaranRinci['Satuan'] = $value[11];
									$insert_Ta_AnggaranRinci['Anggaran'] = $value[12];
									$insert_Ta_AnggaranRinci['JmlSatuanPAK'] = $value[13];
									$insert_Ta_AnggaranRinci['HrgSatuanPAK'] = $value[14];
									$insert_Ta_AnggaranRinci['AnggaranStlhPAK'] = $value[15];
									$insert_Ta_AnggaranRinci['AnggaranPAK'] = $value[16];
									$this->db->insert('keuangan_ta_anggaran_rinci', $insert_Ta_AnggaranRinci);
								}
								$i32++;
							}

							//insert Ta_Bidang
							$csvTa_Bidang= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Bidang.csv', "r");
							while (($data_Ta_Bidang = fgetcsv($csvTa_Bidang)) !== FALSE) {
										$Ta_Bidang[] = $data_Ta_Bidang;
							}
							$i33 = 1;
							foreach ($Ta_Bidang as $value) {
								if ($i33 > 1) {
									$insert_Ta_Bidang['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_Bidang['Kd_Desa'] = $value[1];
									$insert_Ta_Bidang['Kd_Bid'] = $value[2];
									$insert_Ta_Bidang['Nama_Bidang'] = $value[3];
									$this->db->insert('keuangan_ta_bidang', $insert_Ta_Bidang);
								}
								$i33++;
							}

							//insert Ta_JurnalUmum
							$csvTa_JurnalUmum= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_JurnalUmum.csv', "r");
							while (($data_Ta_JurnalUmum = fgetcsv($csvTa_JurnalUmum)) !== FALSE) {
										$Ta_JurnalUmum[] = $data_Ta_JurnalUmum;
							}
							$i34 = 1;
							foreach ($Ta_JurnalUmum as $value) {
								if ($i34 > 1) {
									$insert_Ta_JurnalUmum['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_JurnalUmum['KdBuku'] = $value[1];
									$insert_Ta_JurnalUmum['Kd_Desa'] = $value[2];
									$insert_Ta_JurnalUmum['Tanggal'] = $value[3];
									$insert_Ta_JurnalUmum['JnsBukti'] = $value[4];
									$insert_Ta_JurnalUmum['NoBukti'] = $value[5];
									$insert_Ta_JurnalUmum['Keterangan'] = $value[6];
									$insert_Ta_JurnalUmum['DK'] = $value[7];
									$insert_Ta_JurnalUmum['Debet'] = $value[8];
									$insert_Ta_JurnalUmum['Kredit'] = $value[9];
									$insert_Ta_JurnalUmum['Jenis'] = $value[10];
									$insert_Ta_JurnalUmum['Posted'] = $value[11];
									$this->db->insert('keuangan_ta_jurnal_umum', $insert_Ta_JurnalUmum);
								}
								$i34++;
							}

							//insert Ta_JurnalUmumRinci
							$csvTa_JurnalUmumRinci= fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_JurnalUmumRinci.csv', "r");
							while (($data_Ta_JurnalUmumRinci = fgetcsv($csvTa_JurnalUmumRinci)) !== FALSE) {
										$Ta_JurnalUmumRinci[] = $data_Ta_JurnalUmumRinci;
							}
							$i35 = 1;
							foreach ($Ta_JurnalUmumRinci as $value) {
								if ($i35 > 1) {
									$insert_Ta_JurnalUmumRinci['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_JurnalUmumRinci['NoBukti'] = $value[1];
									$insert_Ta_JurnalUmumRinci['Kd_Keg'] = $value[2];
									$insert_Ta_JurnalUmumRinci['RincianSD'] = $value[3];
									$insert_Ta_JurnalUmumRinci['NoID'] = $value[4];
									$insert_Ta_JurnalUmumRinci['Kd_Desa'] = $value[5];
									$insert_Ta_JurnalUmumRinci['Akun'] = $value[6];
									$insert_Ta_JurnalUmumRinci['Kd_Rincian'] = $value[7];
									$insert_Ta_JurnalUmumRinci['Sumberdana'] = $value[8];
									$insert_Ta_JurnalUmumRinci['DK'] = $value[9];
									$insert_Ta_JurnalUmumRinci['Debet'] = $value[10];
									$insert_Ta_JurnalUmumRinci['Kredit'] = $value[11];
									$this->db->insert('keuangan_ta_jurnal_umum_rinci', $insert_Ta_JurnalUmumRinci);
								}
								$i35++;
							}

							//insert Ta_Kegiatan
							$csvTa_Kegiatan = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Kegiatan.csv', "r");
							while (($data_Ta_Kegiatan = fgetcsv($csvTa_Kegiatan)) !== FALSE) {
										$Ta_Kegiatan[] = $data_Ta_Kegiatan;
							}
							$i36 = 1;
							foreach ($Ta_Kegiatan as $value) {
								if ($i36 > 1) {
									$insert_Ta_Kegiatan['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_Kegiatan['Kd_Desa'] = $value[1];
									$insert_Ta_Kegiatan['Kd_Bid'] = $value[2];
									$insert_Ta_Kegiatan['Kd_Keg'] = $value[3];
									$insert_Ta_Kegiatan['ID_Keg'] = $value[4];
									$insert_Ta_Kegiatan['Nama_Kegiatan'] = $value[5];
									$insert_Ta_Kegiatan['Pagu'] = $value[6];
									$insert_Ta_Kegiatan['Pagu_PAK'] = $value[7];
									$insert_Ta_Kegiatan['Nm_PPTKD'] = $value[8];
									$insert_Ta_Kegiatan['NIP_PPTKD'] = $value[9];
									$insert_Ta_Kegiatan['Lokasi'] = $value[10];
									$insert_Ta_Kegiatan['Waktu'] = $value[11];
									$insert_Ta_Kegiatan['Keluaran'] = $value[12];
									$insert_Ta_Kegiatan['Sumberdana'] = $value[13];
									$this->db->insert('keuangan_ta_kegiatan', $insert_Ta_Kegiatan);
								}
								$i36++;
							}

							//insert Ta_Pajak
							$csvTa_Pajak = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Pajak.csv', "r");
							while (($data_Ta_Pajak = fgetcsv($csvTa_Pajak)) !== FALSE) {
										$Ta_Pajak[] = $data_Ta_Pajak;
							}
							$i37 = 1;
							foreach ($Ta_Pajak as $value) {
								if ($i37 > 1) {
									$insert_Ta_Pajak['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_Pajak['Kd_Desa'] = $value[1];
									$insert_Ta_Pajak['No_SSP'] = $value[2];
									$insert_Ta_Pajak['Tgl_SSP'] = $value[3];
									$insert_Ta_Pajak['Keterangan'] = $value[4];
									$insert_Ta_Pajak['Nama_WP'] = $value[5];
									$insert_Ta_Pajak['Alamat_WP'] = $value[6];
									$insert_Ta_Pajak['NPWP'] = $value[7];
									$insert_Ta_Pajak['Kd_MAP'] = $value[8];
									$insert_Ta_Pajak['Nm_Penyetor'] = $value[9];
									$insert_Ta_Pajak['Jn_Transaksi'] = $value[10];
									$insert_Ta_Pajak['Kd_Rincian'] = $value[11];
									$insert_Ta_Pajak['Jumlah'] = $value[12];
									$insert_Ta_Pajak['KdBayar'] = $value[13];
									$this->db->insert('keuangan_ta_pajak', $insert_Ta_Pajak);
								}
								$i37++;
							}

							//insert Ta_PajakRinci
							$csvTa_PajakRinci = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_PajakRinci.csv', "r");
							while (($data_Ta_PajakRinci = fgetcsv($csvTa_PajakRinci)) !== FALSE) {
										$Ta_PajakRinci[] = $data_Ta_PajakRinci;
							}
							$i38 = 1;
							foreach ($Ta_PajakRinci as $value) {
								if ($i38 > 1) {
									$insert_Ta_PajakRinci['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_PajakRinci['Kd_Desa'] = $value[1];
									$insert_Ta_PajakRinci['No_SSP'] = $value[2];
									$insert_Ta_PajakRinci['No_Bukti'] = $value[3];
									$insert_Ta_PajakRinci['Kd_Rincian'] = $value[4];
									$insert_Ta_PajakRinci['Nilai'] = $value[5];
									$this->db->insert('keuangan_ta_pajak_rinci', $insert_Ta_PajakRinci);
								}
								$i38++;
							}

							//insert Ta_Pemda
							$csvTa_Pemda = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Pemda.csv', "r");
							while (($data_Ta_Pemda = fgetcsv($csvTa_Pemda)) !== FALSE) {
										$Ta_Pemda[] = $data_Ta_Pemda;
							}
							$i39 = 1;
							foreach ($Ta_Pemda as $value) {
								if ($i39 > 1) {
									$insert_Ta_Pemda['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_Pemda['Kd_Prov'] = $value[1];
									$insert_Ta_Pemda['Kd_Kab'] = $value[2];
									$insert_Ta_Pemda['Nama_Pemda'] = $value[3];
									$insert_Ta_Pemda['Nama_Provinsi'] = $value[4];
									$insert_Ta_Pemda['Ibukota'] = $value[5];
									$insert_Ta_Pemda['Alamat'] = $value[6];
									$insert_Ta_Pemda['Nm_Bupati'] = $value[7];
									$insert_Ta_Pemda['Jbt_Bupati'] = $value[8];
									$insert_Ta_Pemda['Logo'] = $value[9];
									$insert_Ta_Pemda['C_Kode'] = $value[10];
									$insert_Ta_Pemda['C_Pemda'] = $value[11];
									$insert_Ta_Pemda['C_Data'] = $value[12];
									$this->db->insert('keuangan_ta_pemda', $insert_Ta_Pemda);
								}
								$i39++;
							}

							//insert Ta_Perangkat
							$csvTa_Perangkat = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Perangkat.csv', "r");
							while (($data_Ta_Perangkat = fgetcsv($csvTa_Perangkat)) !== FALSE) {
										$Ta_Perangkat[] = $data_Ta_Perangkat;
							}
							$i40 = 1;
							foreach ($Ta_Perangkat as $value) {
								if ($i40 > 1) {
									$insert_Ta_Perangkat['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_Perangkat['Kd_Desa'] = $value[1];
									$insert_Ta_Perangkat['Kd_Jabatan'] = $value[2];
									$insert_Ta_Perangkat['No_ID'] = $value[3];
									$insert_Ta_Perangkat['Nama_Perangkat'] = $value[4];
									$insert_Ta_Perangkat['Alamat_Perangkat'] = $value[5];
									$insert_Ta_Perangkat['Nomor_HP'] = $value[6];
									$insert_Ta_Perangkat['Rek_Bank'] = $value[7];
									$insert_Ta_Perangkat['Nama_Bank'] = $value[8];
									$this->db->insert('keuangan_ta_perangkat', $insert_Ta_Perangkat);
								}
								$i40++;
							}

							//insert Ta_RABRinci
							$csvTa_RABRinci = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RABRinci.csv', "r");
							while (($data_ta_RABRinci = fgetcsv($csvTa_RABRinci)) !== FALSE) {
										$Ta_RABRinci[] = $data_ta_RABRinci;
							}
							$i41 = 1;
							foreach ($Ta_RABRinci as $value) {
								if ($i41 > 1) {
									$insert_Ta_RABRinci['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_RABRinci['Kd_Desa'] = $value[1];
									$insert_Ta_RABRinci['Kd_Keg'] = $value[2];
									$insert_Ta_RABRinci['Kd_Rincian'] = $value[3];
									$insert_Ta_RABRinci['Kd_SubRinci'] = $value[4];
									$insert_Ta_RABRinci['No_Urut'] = $value[5];
									$insert_Ta_RABRinci['SumberDana'] = $value[6];
									$insert_Ta_RABRinci['Uraian'] = $value[7];
									$insert_Ta_RABRinci['Satuan'] = $value[8];
									$insert_Ta_RABRinci['JmlSatuan'] = $value[9];
									$insert_Ta_RABRinci['HrgSatuan'] = $value[10];
									$insert_Ta_RABRinci['Anggaran'] = $value[11];
									$insert_Ta_RABRinci['JmlSatuanPAK'] = $value[12];
									$insert_Ta_RABRinci['HrgSatuanPAK'] = $value[13];
									$insert_Ta_RABRinci['AnggaranStlhPAK'] = $value[14];
									$insert_Ta_RABRinci['AnggaranPAK'] = $value[15];
									$insert_Ta_RABRinci['Kode_SBU'] = $value[16];

									$this->db->insert('keuangan_ta_rab_rinci', $insert_Ta_RABRinci);
								}
								$i41++;
							}

							//insert Ta_RABSub
							$csvTa_RABSub = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RABSub.csv', "r");
							while (($data_Ta_RABSub = fgetcsv($csvTa_RABSub)) !== FALSE) {
										$Ta_RABSub[] = $data_Ta_RABSub;
							}
							$i42 = 1;
							foreach ($Ta_RABSub as $value) {
								if ($i42 > 1) {
									$insert_Ta_RABSub['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_RABSub['Kd_Desa'] = $value[1];
									$insert_Ta_RABSub['Kd_Keg'] = $value[2];
									$insert_Ta_RABSub['Kd_Rincian'] = $value[3];
									$insert_Ta_RABSub['Kd_SubRinci'] = $value[4];
									$insert_Ta_RABSub['Nama_SubRinci'] = $value[5];
									$insert_Ta_RABSub['Anggaran'] = $value[6];
									$insert_Ta_RABSub['AnggaranPAK'] = $value[7];
									$insert_Ta_RABSub['AnggaranStlhPAK'] = $value[8];
									$this->db->insert('keuangan_ta_rab_sub', $insert_Ta_RABSub);
								}
								$i42++;
							}

							//insert Ta_RPJM_Bidang
							$csvTa_RPJM_Bidang = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Bidang.csv', "r");
							while (($data_Ta_RPJM_Bidang = fgetcsv($csvTa_RPJM_Bidang)) !== FALSE) {
										$Ta_RPJM_Bidang[] = $data_Ta_RPJM_Bidang;
							}
							$i43 = 1;
							foreach ($Ta_RPJM_Bidang as $value) {
								if ($i43 > 1) {
									$insert_Ta_RPJM_Bidang['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_RPJM_Bidang['Kd_Desa'] = $value[0];
									$insert_Ta_RPJM_Bidang['Kd_Bid'] = $value[1];
									$insert_Ta_RPJM_Bidang['Nama_Bidang'] = $value[2];
									$this->db->insert('keuangan_ta_rpjm_bidang', $insert_Ta_RPJM_Bidang);
								}
								$i43++;
							}

							//insert Ta_RPJM_Kegiatan
							$csvTa_RPJM_Kegiatan = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Kegiatan.csv', "r");
							while (($data_Ta_RPJM_Kegiatan = fgetcsv($csvTa_RPJM_Kegiatan)) !== FALSE) {
										$Ta_RPJM_Kegiatan[] = $data_Ta_RPJM_Kegiatan;
							}
							$i44 = 1;
							foreach ($Ta_RPJM_Kegiatan as $value) {
								if ($i44 > 1) {
									$insert_Ta_RPJM_Kegiatan['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_RPJM_Kegiatan['Kd_Desa'] = $value[0];
									$insert_Ta_RPJM_Kegiatan['Kd_Bid'] = $value[1];
									$insert_Ta_RPJM_Kegiatan['Kd_Keg'] = $value[2];
									$insert_Ta_RPJM_Kegiatan['ID_Keg'] = $value[3];
									$insert_Ta_RPJM_Kegiatan['Nama_Kegiatan'] = $value[4];
									$insert_Ta_RPJM_Kegiatan['Lokasi'] = $value[5];
									$insert_Ta_RPJM_Kegiatan['Keluaran'] = $value[6];
									$insert_Ta_RPJM_Kegiatan['Kd_Sas'] = $value[7];
									$insert_Ta_RPJM_Kegiatan['Sasaran'] = $value[8];
									$insert_Ta_RPJM_Kegiatan['Tahun1'] = $value[9];
									$insert_Ta_RPJM_Kegiatan['Tahun2'] = $value[10];
									$insert_Ta_RPJM_Kegiatan['Tahun3'] = $value[11];
									$insert_Ta_RPJM_Kegiatan['Tahun4'] = $value[12];
									$insert_Ta_RPJM_Kegiatan['Tahun5'] = $value[13];
									$insert_Ta_RPJM_Kegiatan['Tahun6'] = $value[14];
									$insert_Ta_RPJM_Kegiatan['Swakelola'] = $value[15];
									$insert_Ta_RPJM_Kegiatan['Kerjasama'] = $value[16];
									$insert_Ta_RPJM_Kegiatan['Pihak_Ketiga'] = $value[17];
									$insert_Ta_RPJM_Kegiatan['Sumberdana'] = $value[18];
									$this->db->insert('keuangan_ta_rpjm_kegiatan', $insert_Ta_RPJM_Kegiatan);
								}
								$i44++;
							}

							//insert Ta_RPJM_Misi
							$csvTa_RPJM_Misi  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Misi.csv', "r");
							while (($data_Ta_RPJM_Misi = fgetcsv($csvTa_RPJM_Misi)) !== FALSE) {
										$Ta_RPJM_Misi[] = $data_Ta_RPJM_Misi;
							}
							$i45 = 1;
							foreach ($Ta_RPJM_Misi as $value) {
								if ($i45 > 1) {
									$insert_Ta_RPJM_Misi['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_RPJM_Misi['ID_Misi'] = $value[0];
									$insert_Ta_RPJM_Misi['Kd_Desa'] = $value[1];
									$insert_Ta_RPJM_Misi['ID_Visi'] = $value[2];
									$insert_Ta_RPJM_Misi['No_Misi'] = $value[3];
									$insert_Ta_RPJM_Misi['Uraian_Misi'] = $value[4];
									$this->db->insert('keuangan_ta_rpjm_misi', $insert_Ta_RPJM_Misi);
								}
								$i45++;
							}

							//insert Ta_RPJM_Pagu_Indikatif
							$csvTa_RPJM_Pagu_Indikatif  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Pagu_Indikatif.csv', "r");
							while (($data_Ta_RPJM_Pagu_Indikatif = fgetcsv($csvTa_RPJM_Pagu_Indikatif)) !== FALSE) {
										$Ta_RPJM_Pagu_Indikatif[] = $data_Ta_RPJM_Pagu_Indikatif;
							}
							$i46 = 1;
							foreach ($Ta_RPJM_Pagu_Indikatif as $value) {
								if ($i46 > 1) {
									$insert_Ta_RPJM_Pagu_Indikatif['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_RPJM_Pagu_Indikatif['Kd_Desa'] = $value[0];
									$insert_Ta_RPJM_Pagu_Indikatif['Kd_Keg'] = $value[1];
									$insert_Ta_RPJM_Pagu_Indikatif['Kd_Sumber'] = $value[2];
									$insert_Ta_RPJM_Pagu_Indikatif['Tahun1'] = $value[3];
									$insert_Ta_RPJM_Pagu_Indikatif['Tahun2'] = $value[4];
									$insert_Ta_RPJM_Pagu_Indikatif['Tahun3'] = $value[5];
									$insert_Ta_RPJM_Pagu_Indikatif['Tahun4'] = $value[6];
									$insert_Ta_RPJM_Pagu_Indikatif['Tahun5'] = $value[7];
									$insert_Ta_RPJM_Pagu_Indikatif['Tahun6'] = $value[8];
									$insert_Ta_RPJM_Pagu_Indikatif['Pola'] = $value[9];
									$this->db->insert('keuangan_ta_rpjm_pagu_indikatif', $insert_Ta_RPJM_Pagu_Indikatif);
								}
								$i46++;
							}

							//insert Ta_RPJM_Pagu_Tahunan
							$csvTa_RPJM_Pagu_Tahunan  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Pagu_Tahunan.csv', "r");
							while (($data_Ta_RPJM_Pagu_Tahunan = fgetcsv($csvTa_RPJM_Pagu_Tahunan)) !== FALSE) {
										$Ta_RPJM_Pagu_Tahunan[] = $data_Ta_RPJM_Pagu_Tahunan;
							}
							$i47 = 1;
							foreach ($Ta_RPJM_Pagu_Tahunan as $value) {
								if ($i47 > 1) {
									$insert_Ta_RPJM_Pagu_Tahunan['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_RPJM_Pagu_Tahunan['Kd_Desa'] = $value[0];
									$insert_Ta_RPJM_Pagu_Tahunan['Kd_Keg'] = $value[1];
									$insert_Ta_RPJM_Pagu_Tahunan['Kd_Tahun'] = $value[2];
									$insert_Ta_RPJM_Pagu_Tahunan['Kd_Sumber'] = $value[3];
									$insert_Ta_RPJM_Pagu_Tahunan['Biaya'] = $value[4];
									$insert_Ta_RPJM_Pagu_Tahunan['Volume'] = $value[5];
									$insert_Ta_RPJM_Pagu_Tahunan['Satuan'] = $value[6];
									$insert_Ta_RPJM_Pagu_Tahunan['Lokasi_Spesifik'] = $value[7];
									$insert_Ta_RPJM_Pagu_Tahunan['Jml_Sas_Pria'] = $value[8];
									$insert_Ta_RPJM_Pagu_Tahunan['Jml_Sas_Wanita'] = $value[9];
									$insert_Ta_RPJM_Pagu_Tahunan['Jml_Sas_ARTM'] = $value[10];
									$insert_Ta_RPJM_Pagu_Tahunan['Waktu'] = $value[11];
									$insert_Ta_RPJM_Pagu_Tahunan['Mulai'] = $value[12];
									$insert_Ta_RPJM_Pagu_Tahunan['Selesai'] = $value[13];
									$insert_Ta_RPJM_Pagu_Tahunan['Pola_Kegiatan'] = $value[14];
									$insert_Ta_RPJM_Pagu_Tahunan['Pelaksana'] = $value[15];
									$this->db->insert('keuangan_ta_rpjm_pagu_tahunan', $insert_Ta_RPJM_Pagu_Tahunan);
								}
								$i47++;
							}

							//insert Ta_RPJM_Sasaran
							$csvTa_RPJM_Sasaran  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Sasaran.csv', "r");
							while (($data_Ta_RPJM_Sasaran = fgetcsv($csvTa_RPJM_Sasaran)) !== FALSE) {
										$Ta_RPJM_Sasaran[] = $data_Ta_RPJM_Sasaran;
							}
							$i48 = 1;
							foreach ($Ta_RPJM_Sasaran as $value) {
								if ($i48 > 1) {
									$insert_Ta_RPJM_Sasaran['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_RPJM_Sasaran['ID_Sasaran '] = $value[0];
									$insert_Ta_RPJM_Sasaran['Kd_Desa'] = $value[1];
									$insert_Ta_RPJM_Sasaran['ID_Tujuan'] = $value[2];
									$insert_Ta_RPJM_Sasaran['No_Sasaran'] = $value[3];
									$insert_Ta_RPJM_Sasaran['Uraian_Sasaran'] = $value[4];
									$this->db->insert('keuangan_ta_rpjm_sasaran', $insert_Ta_RPJM_Sasaran);
								}
								$i48++;
							}

							//insert Ta_RPJM_Tujuan
							$csvTa_RPJM_Tujuan  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Tujuan.csv', "r");
							while (($data_Ta_RPJM_Tujuan = fgetcsv($csvTa_RPJM_Tujuan)) !== FALSE) {
										$Ta_RPJM_Tujuan[] = $data_Ta_RPJM_Tujuan;
							}
							$i49 = 1;
							foreach ($Ta_RPJM_Tujuan as $value) {
								if ($i49 > 1) {
									$insert_Ta_RPJM_Tujuan['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_RPJM_Tujuan['ID_Tujuan '] = $value[0];
									$insert_Ta_RPJM_Tujuan['Kd_Desa'] = $value[1];
									$insert_Ta_RPJM_Tujuan['ID_Misi'] = $value[2];
									$insert_Ta_RPJM_Tujuan['No_Tujuan'] = $value[3];
									$insert_Ta_RPJM_Tujuan['Uraian_Tujuan'] = $value[4];
									$this->db->insert('keuangan_ta_rpjm_tujuan', $insert_Ta_RPJM_Tujuan);
								}
								$i49++;
							}

							//insert Ta_RPJM_Visi
							$csvTa_RPJM_Visi  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_RPJM_Visi.csv', "r");
							while (($data_Ta_RPJM_Visi = fgetcsv($csvTa_RPJM_Visi)) !== FALSE) {
										$Ta_RPJM_Visi[] = $data_Ta_RPJM_Visi;
							}
							$i50 = 1;
							foreach ($Ta_RPJM_Visi as $value) {
								if ($i50 > 1) {
									$insert_Ta_RPJM_Visi['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_RPJM_Visi['ID_Visi '] = $value[0];
									$insert_Ta_RPJM_Visi['Kd_Desa'] = $value[1];
									$insert_Ta_RPJM_Visi['No_Visi'] = $value[2];
									$insert_Ta_RPJM_Visi['Uraian_Visi'] = $value[3];
									$insert_Ta_RPJM_Visi['TahunA'] = $value[4];
									$insert_Ta_RPJM_Visi['TahunN'] = $value[5];
									$this->db->insert('keuangan_ta_rpjm_visi', $insert_Ta_RPJM_Visi);
								}
								$i50++;
							}

							//insert Ta_SaldoAwal
							$csvTa_SaldoAwal  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SaldoAwal.csv', "r");
							while (($data_Ta_SaldoAwal = fgetcsv($csvTa_SaldoAwal)) !== FALSE) {
										$Ta_SaldoAwal[] = $data_Ta_SaldoAwal;
							}
							$i51 = 1;
							foreach ($Ta_SaldoAwal as $value) {
								if ($i51 > 1) {
									$insert_Ta_SaldoAwal['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_SaldoAwal['Kd_Desa '] = $value[1];
									$insert_Ta_SaldoAwal['Kd_Rincian'] = $value[2];
									$insert_Ta_SaldoAwal['Jenis'] = $value[3];
									$insert_Ta_SaldoAwal['Anggaran'] = $value[4];
									$insert_Ta_SaldoAwal['Debet'] = $value[5];
									$insert_Ta_SaldoAwal['Kredit'] = $value[6];
									$insert_Ta_SaldoAwal['Tgl_Bukti'] = $value[7];
									$this->db->insert('keuangan_ta_saldo_awal', $insert_Ta_SaldoAwal);
								}
								$i51++;
							}

							//insert Ta_SPJ
							$csvTa_SPJ  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPJ.csv', "r");
							while (($data_Ta_SPJ = fgetcsv($csvTa_SPJ)) !== FALSE) {
										$Ta_SPJ[] = $data_Ta_SPJ;
							}
							$i52 = 1;
							foreach ($Ta_SPJ as $value) {
								if ($i52 > 1) {
									$insert_Ta_SPJ['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_SPJ['No_SPJ'] = $value[1];
									$insert_Ta_SPJ['Tgl_SPJ'] = $value[2];
									$insert_Ta_SPJ['Kd_Desa'] = $value[3];
									$insert_Ta_SPJ['No_SPP'] = $value[4];
									$insert_Ta_SPJ['Keterangan'] = $value[5];
									$insert_Ta_SPJ['Jumlah'] = $value[6];
									$insert_Ta_SPJ['Potongan'] = $value[7];
									$insert_Ta_SPJ['Status'] = $value[8];
									$this->db->insert('keuangan_ta_spj', $insert_Ta_SPJ);
								}
								$i52++;
							}

							//insert Ta_SPJBukti
							$csvTa_SPJBukti  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPJBukti.csv', "r");
							while (($data_Ta_SPJBukti = fgetcsv($csvTa_SPJBukti)) !== FALSE) {
										$Ta_SPJBukti[] = $data_Ta_SPJBukti;
							}
							$i53 = 1;
							foreach ($Ta_SPJBukti as $value) {
								if ($i53 > 1) {
									$insert_Ta_SPJBukti['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_SPJBukti['No_SPJ'] = $value[1];
									$insert_Ta_SPJBukti['Kd_Keg'] = $value[2];
									$insert_Ta_SPJBukti['Kd_Rincian'] = $value[3];
									$insert_Ta_SPJBukti['No_Bukti'] = $value[4];
									$insert_Ta_SPJBukti['Tgl_Bukti'] = $value[5];
									$insert_Ta_SPJBukti['Sumberdana'] = $value[6];
									$insert_Ta_SPJBukti['Kd_Desa'] = $value[7];
									$insert_Ta_SPJBukti['Nm_Penerima'] = $value[8];
									$insert_Ta_SPJBukti['Alamat'] = $value[9];
									$insert_Ta_SPJBukti['Rek_Bank'] = $value[10];
									$insert_Ta_SPJBukti['Nm_Bank'] = $value[11];
									$insert_Ta_SPJBukti['NPWP'] = $value[12];
									$insert_Ta_SPJBukti['Keterangan'] = $value[13];
									$insert_Ta_SPJBukti['Nilai'] = $value[14];
									$this->db->insert('keuangan_ta_spj_bukti', $insert_Ta_SPJBukti);
								}
								$i53++;
							}

							//insert Ta_SPJRinci
							$csvTa_SPJRinci  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPJRinci.csv', "r");
							while (($data_Ta_SPJRinci = fgetcsv($csvTa_SPJRinci)) !== FALSE) {
										$Ta_SPJRinci[] = $data_Ta_SPJRinci;
							}
							$i54 = 1;
							foreach ($Ta_SPJRinci as $value) {
								if ($i54 > 1) {
									$insert_Ta_SPJRinci['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_SPJRinci['No_SPJ'] = $value[0];
									$insert_Ta_SPJRinci['Kd_Keg'] = $value[1];
									$insert_Ta_SPJRinci['Kd_Rincian'] = $value[2];
									$insert_Ta_SPJRinci['Sumberdana'] = $value[3];
									$insert_Ta_SPJRinci['Kd_Desa'] = $value[4];
									$insert_Ta_SPJRinci['Sumberdana'] = $value[5];
									$insert_Ta_SPJRinci['No_SPP'] = $value[6];
									$insert_Ta_SPJRinci['JmlCair'] = $value[7];
									$insert_Ta_SPJRinci['Nilai'] = $value[8];
									$insert_Ta_SPJRinci['Sisa'] = $value[9];
									$this->db->insert('keuangan_ta_spj_rinci', $insert_Ta_SPJRinci);
								}
								$i54++;
							}

							//insert Ta_SPP
							$csvTa_SPP  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPP.csv', "r");
							while (($data_Ta_SPP = fgetcsv($csvTa_SPP)) !== FALSE) {
										$Ta_SPP[] = $data_Ta_SPP;
							}
							$i55 = 1;
							foreach ($Ta_SPP as $value) {
								if ($i55 > 1) {
									$insert_Ta_SPP['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_SPP['No_SPP'] = $value[1];
									$insert_Ta_SPP['Tgl_SPP'] = $value[2];
									$insert_Ta_SPP['Jn_SPP'] = $value[3];
									$insert_Ta_SPP['Kd_Desa'] = $value[4];
									$insert_Ta_SPP['Keterangan'] = $value[5];
									$insert_Ta_SPP['Jumlah'] = $value[6];
									$insert_Ta_SPP['Potongan'] = $value[7];
									$insert_Ta_SPP['Status'] = $value[8];

									$this->db->insert('keuangan_ta_spp', $insert_Ta_SPP);
								}
								$i55++;
							}

							//insert Ta_SPPRinci
							$csvTa_SPPRinci  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_SPPRinci.csv', "r");
							while (($data_Ta_SPPRinci = fgetcsv($csvTa_SPPRinci)) !== FALSE) {
										$Ta_SPPRinci[] = $data_Ta_SPPRinci;
							}
							$i56 = 1;
							foreach ($Ta_SPPRinci as $value) {
								if ($i56 > 1) {
									$insert_Ta_SPPRinci['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_SPPRinci['Kd_Desa'] = $value[1];
									$insert_Ta_SPPRinci['No_SPP'] = $value[2];
									$insert_Ta_SPPRinci['Kd_Keg'] = $value[3];
									$insert_Ta_SPPRinci['Kd_Rincian'] = $value[4];
									$insert_Ta_SPPRinci['Sumberdana'] = $value[5];
									$insert_Ta_SPPRinci['Nilai'] = $value[6];


									$this->db->insert('keuangan_ta_spp_rinci', $insert_Ta_SPPRinci);
								}
								$i56++;
							}

							//insert Ta_STSRinci
							$csvTa_STSRinci  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_STSRinci.csv', "r");
							while (($data_Ta_STSRinci = fgetcsv($csvTa_STSRinci)) !== FALSE) {
										$Ta_STSRinci[] = $data_Ta_STSRinci;
							}
							$i57 = 1;
							foreach ($Ta_STSRinci as $value) {
								if ($i56 > 1) {
									$insert_Ta_STSRinci['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_STSRinci['Kd_Desa'] = $value[1];
									$insert_Ta_STSRinci['No_Bukti'] = $value[2];
									$insert_Ta_STSRinci['No_TBP'] = $value[3];
									$insert_Ta_STSRinci['Uraian'] = $value[4];
									$insert_Ta_STSRinci['Nilai'] = $value[5];
									$this->db->insert('keuangan_ta_sts_rinci', $insert_Ta_STSRinci);
								}
								$i56++;
							}

							//insert Ta_Triwulan
							$csvTa_Triwulan  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_Triwulan.csv', "r");
							while (($data_Ta_Triwulan = fgetcsv($csvTa_Triwulan)) !== FALSE) {
										$Ta_Triwulan = $data_Ta_Triwulan;
							}
							$i58 = 1;
							foreach ($Ta_Triwulan as $value) {
								if ($i58 > 1) {
									$insert_Ta_Triwulan['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_Triwulan['KURincianSD'] = $value[0];
									$insert_Ta_Triwulan['Tahun'] = $value[1];
									$insert_Ta_Triwulan['Sifat'] = $value[2];
									$insert_Ta_Triwulan['SumberDana'] = $value[3];
									$insert_Ta_Triwulan['Kd_Desa'] = $value[4];
									$insert_Ta_Triwulan['Kd_Keg'] = $value[4];
									$insert_Ta_Triwulan['Kd_Rincian'] = $value[5];
									$insert_Ta_Triwulan['Anggaran'] = $value[6];
									$insert_Ta_Triwulan['AnggaranPAK'] = $value[7];
									$insert_Ta_Triwulan['Tw1Rinci'] = $value[8];
									$insert_Ta_Triwulan['Tw2Rinci'] = $value[9];
									$insert_Ta_Triwulan['Tw3Rinci'] = $value[10];
									$insert_Ta_Triwulan['Tw4Rinci'] = $value[11];
									$insert_Ta_Triwulan['KunciData'] = $value[12];
									$this->db->insert('keuangan_ta_triwulan', $insert_Ta_Triwulan);
								}
								$i58++;
							}

							//insert Ta_TriwulanArsip
							$csvTa_TriwulanArsip  = fopen(LOKASI_KEUANGAN_ZIP.'/'.$_FILES['keuangan']['name'].'/'.'Ta_TriwulanArsip.csv', "r");
							while (($data_Ta_TriwulanArsip = fgetcsv($csvTa_TriwulanArsip)) !== FALSE) {
										$Ta_TriwulanArsip[] = $data_Ta_TriwulanArsip;
							}
							$i59 = 1;
							foreach ($Ta_TriwulanArsip as $value) {
								if ($i59 > 1) {
									$insert_Ta_TriwulanArsip['id_keuangan_master'] = $id_master_keuangan;
									$insert_Ta_TriwulanArsip['KdPosting'] = $value[0];
									$insert_Ta_TriwulanArsip['KURincianSD'] = $value[1];
									$insert_Ta_TriwulanArsip['Tahun'] = $value[3];
									$insert_Ta_TriwulanArsip['Sifat'] = $value[4];
									$insert_Ta_TriwulanArsip['SumberDana'] = $value[5];
									$insert_Ta_TriwulanArsip['Kd_Desa'] = $value[6];
									$insert_Ta_TriwulanArsip['Kd_Keg'] = $value[7];
									$insert_Ta_TriwulanArsip['Kd_Rincian'] = $value[8];
									$insert_Ta_TriwulanArsip['Anggaran'] = $value[9];
									$insert_Ta_TriwulanArsip['AnggaranPAK'] = $value[10];
									$insert_Ta_TriwulanArsip['Tw1Rinci'] = $value[11];
									$insert_Ta_TriwulanArsip['Tw2Rinci'] = $value[12];
									$insert_Ta_TriwulanArsip['Tw3Rinci'] = $value[13];
									$insert_Ta_TriwulanArsip['Tw4Rinci'] = $value[14];
									$insert_Ta_TriwulanArsip['KunciData'] = $value[15];
									$this->db->insert('keuangan_ta_triwulan_arsip', $insert_Ta_TriwulanArsip);
								}
								$i59++;
							}

							$zip_file->close();
							$_SESSION['success'] = 1;
							$_SESSION['error_msg'] = 'Berhasil';
							redirect('keuangan/import_data');
					}

			}else{
				$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = '';
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
		$this->db->where('keuangan_master.versi_database', $versi_database);
		$this->db->where('keuangan_master.tahun_anggaran', $tahun_anggaran);
		$this->db->where('keuangan_master.aktif', 1);
		$result = $this->db->get('keuangan_master')->row();
		return $result;
	}

}
