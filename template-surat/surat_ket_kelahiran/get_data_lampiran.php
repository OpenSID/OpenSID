<?php

    defined('BASEPATH') || exit('No direct script access allowed');

    // View untuk Permohonan Akta Kelahiran
    $tampil_data_anak      = true;
    $tampil_data_orang_tua = true;
    $tampil_data_pelapor   = true;
    $tampil_data_saksi     = true;

    // Pilih model yang digunakan untuk menampilkan data
    $format_f201 = 1;

    # Data ibu dan ayah kandung dari database penduduk
    if ($_SESSION['id_ibu']) {
        $ibu = $this->get_data_surat($_SESSION['id_ibu']);
        $array_replace = array(
            "[form_nama_ibu]"     	=> $ibu['nama'],
            "[nik_ibu]"       		=> $ibu['nik'],
            "[tempat_lahir_ibu]"  	=> $ibu['tempatlahir'],
            "[tanggal_lahir_ibu]"	=> tgl_indo_dari_str($ibu['tanggallahir']),
            "[umur_ibu]"  			=> $ibu['umur'],
            "[pekerjaanibu]" 		=> $ibu['pekerjaan'],
            "[alamat_ibu]"    		=> "RT $ibu[rt] / RW $ibu[rw] $ibu[dusun]",
            "[desaibu]"       		=> $config['nama_desa'],
            "[kecibu]"       		=> $config['nama_kecamatan'],
            "[kabibu]"       		=> $config['nama_kabupaten'],
            "[provinsiibu]"   		=> $config['nama_propinsi']
        );
        $buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);

        $ayah = $this->get_data_suami($ibu['id']);
        // Jika tidak ada ayah dari database, ambil dari form
        if ($ayah) {
            $array_replace = array(
                "[form_nama_ayah]"     	=> $ayah['nama'],
                "[nik_ayah]"       		=> $ayah['nik'],
                "[tempat_lahir_ayah]"  	=> $ayah['tempatlahir'],
                "[tanggal_lahir_ayah]"	=> tgl_indo_dari_str($ayah['tanggallahir']),
                "[umur_ayah]"  			=> $ayah['umur'],
                "[pekerjaanayah]" 		=> $ayah['pek'], // dari get_data_pribadi()
                "[alamat_ayah]"    		=> "RT $ayah[rt] / RW $ayah[rw] $ayah[dusun]",
                "[desaayah]"       		=> $config['nama_desa'],
                "[kecayah]"       		=> $config['nama_kecamatan'],
                "[kabayah]"       		=> $config['nama_kabupaten'],
                "[provinsiayah]"   		=> $config['nama_propinsi']
            );
            $buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
        }
    }

    if ($_SESSION['id_bayi']) {
        $individu = $this->get_data_surat($_SESSION['id_bayi']);
        $jenis_kelamin = '';
        if ($individu['sex_id'] == 1) {
            $jenis_kelamin = "LAKI-LAKI";
        }
        if ($individu['sex_id'] == 2) {
            $jenis_kelamin = "PEREMPUAN";
        }

        $individu['tanggallahir'] = $input['tanggallahir'];
        $individu['waktu_lahir'] = $input['waktu_lahir'];
        $individu['tempat_dilahirkan'] = $input['tempat_dilahirkan'];
        $individu['tempatlahir'] = $input['tempatlahir'];
        $individu['jenis_kelahiran'] = $input['jenis_kelahiran'];
        $individu['kelahiran_anak_ke'] = $input['kelahiran_anak_ke'];
        $individu['penolong_kelahiran'] = $input['penolong_kelahiran'];
        $individu['berat_lahir'] = $input['berat_lahir'];
        $individu['panjang_lahir'] = $input['panjang_lahir'];

        $array_replace = array(
            "[form_nama_sex]"					=> $jenis_kelamin,
            "[form_nama_bayi]" 					=> $individu['nama'],
        );
        $buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
    }

    if ($input['id_pelapor']) {
        $pelapor = $this->get_data_surat($input['id_pelapor']);
        $input['nik_pelapor'] = $pelapor['nik'];
        $input['nama_pelapor'] = $pelapor['nama'];
        $input['tanggal_lahir_pelapor']	= $pelapor['tanggallahir'];
        $input['umur_pelapor'] = str_pad($pelapor['umur'], 3, "0", STR_PAD_LEFT);
        $input['jkpelapor']	= $pelapor['sex_id'];
        $input['pekerjaanid_pelapor'] = str_pad($pelapor['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
        $input['pekerjaanpelapor'] = $pelapor['pekerjaan'];
        $input['alamat_pelapor'] = trim($pelapor['alamat'].' '.$pelapor['dusun']);
        $input['rt_pelapor'] = $pelapor['rt'];
        $input['rw_pelapor'] = $pelapor['rw'];
        $input['desapelapor']	= $config['nama_desa'];
        $input['kecpelapor'] = $config['nama_kecamatan'];
        $input['kabpelapor'] = $config['nama_kabupaten'];
        $input['provinsipelapor']	= $config['nama_propinsi'];

        // Tambahan
        $input['no_kk_pelapor']           = $pelapor['no_kk'];
        $input['kewarganegaraan_pelapor'] = $pelapor['warganegara'];
    } else {
        $input['pekerjaanid_pelapor'] = str_pad($input['pekerjaanid_pelapor'], 2, "0", STR_PAD_LEFT);
        $input['umur_pelapor'] = str_pad($input['umur_pelapor'], 3, "0", STR_PAD_LEFT);
    }

    if ($input['id_saksi1']) {
        $saksi1 = $this->get_data_surat($input['id_saksi1']);
        $input['nik_saksi1'] = $saksi1['nik'];
        $input['nama_saksi1'] = $saksi1['nama'];
        $input['tanggal_lahir_saksi1'] = $saksi1['tanggallahir'];
        $input['umur_saksi1']	= str_pad($saksi1['umur'], 3, "0", STR_PAD_LEFT);
        $input['jksaksi1'] = $saksi1['sex_id'];
        $input['pekerjaanid_saksi1'] = str_pad($saksi1['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
        $input['pekerjaansaksi1']	= $saksi1['pekerjaan'];
        $input['alamat_saksi1'] = trim($saksi1['alamat'].' '.$saksi1['dusun']);
        $input['rt_saksi1'] = $saksi1['rt'];
        $input['rw_saksi1'] = $saksi1['rw'];
        $input['desasaksi1'] = $config['nama_desa'];
        $input['kecsaksi1']	= $config['nama_kecamatan'];
        $input['kabsaksi1']	= $config['nama_kabupaten'];
        $input['provinsisaksi1'] = $config['nama_propinsi'];

        // Tambahan
        $input['no_kk_saksi1']           = $saksi1['no_kk'];
        $input['kewarganegaraan_saksi1'] = $saksi1['warganegara'];
    } else {
        $input['pekerjaanid_saksi1'] = str_pad($input['pekerjaanid_saksi1'], 2, "0", STR_PAD_LEFT);
        $input['umur_saksi1']	= str_pad($input['umur_saksi1'], 3, "0", STR_PAD_LEFT);
    }

    if ($input['id_saksi2']) {
        $saksi2 = $this->get_data_surat($input['id_saksi2']);
        $input['nik_saksi2'] = $saksi2['nik'];
        $input['nama_saksi2'] = $saksi2['nama'];
        $input['tanggal_lahir_saksi2'] = $saksi2['tanggallahir'];
        $input['umur_saksi2']	= str_pad($saksi2['umur'], 3, "0", STR_PAD_LEFT);
        $input['jksaksi2'] = $saksi2['sex_id'];
        $input['pekerjaanid_saksi2'] = str_pad($saksi2['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
        $input['pekerjaansaksi2']	= $saksi2['pekerjaan'];
        $input['alamat_saksi2'] = trim($saksi2['alamat'].' '.$saksi2['dusun']);
        $input['rt_saksi2'] = $saksi2['rt'];
        $input['rw_saksi2'] = $saksi2['rw'];
        $input['desasaksi2'] = $config['nama_desa'];
        $input['kecsaksi2']	= $config['nama_kecamatan'];
        $input['kabsaksi2']	= $config['nama_kabupaten'];
        $input['provinsisaksi2'] = $config['nama_propinsi'];

        // Tambahan
        $input['no_kk_saksi2']           = $saksi2['no_kk'];
        $input['kewarganegaraan_saksi2'] = $saksi2['warganegara'];
    } else {
        $input['pekerjaanid_saksi2'] = str_pad($input['pekerjaanid_saksi2'], 2, "0", STR_PAD_LEFT);
        $input['umur_saksi2']	= str_pad($input['umur_saksi2'], 3, "0", STR_PAD_LEFT);
    }

    include(FCPATH . "/template-surat/lampiran/f-2.01/data.php");

?>