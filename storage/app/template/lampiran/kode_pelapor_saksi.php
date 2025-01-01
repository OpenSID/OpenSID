<?php

/*
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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

if ($input['id_pend_pelapor']) {
    $pelapor                        = $this->surat_model->get_data_surat($input['id_pend_pelapor']);
    $input['nik_pelapor']           = get_nik($pelapor['nik']);
    $input['nama_pelapor']          = $pelapor['nama'];
    $input['tanggal_lahir_pelapor'] = $pelapor['tanggallahir'];
    $input['umur_pelapor']          = str_pad($pelapor['umur'], 3, '0', STR_PAD_LEFT);
    $input['jkpelapor']             = $pelapor['sex_id'];
    $input['pekerjaanid_pelapor']   = str_pad($pelapor['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanpelapor']      = $pelapor['pekerjaan'];
    $input['alamat_pelapor']        = trim($pelapor['alamat'] . ' ' . $pelapor['dusun']);
    $input['rt_pelapor']            = $pelapor['rt'];
    $input['rw_pelapor']            = $pelapor['rw'];
    $input['desapelapor']           = $config['nama_desa'];
    $input['kecpelapor']            = $config['nama_kecamatan'];
    $input['kabpelapor']            = $config['nama_kabupaten'];
    $input['provinsipelapor']       = $config['nama_propinsi'];

    // Tambahan
    $input['no_kk_pelapor']           = $pelapor['no_kk'];
    $input['kewarganegaraan_pelapor'] = $pelapor['warganegara'];
} else {
    $input['nik_pelapor']           = get_nik($input['pelapor']['nik']);
    $input['nama_pelapor']          = $input['pelapor']['nama'];
    $input['tanggal_lahir_pelapor'] = $input['pelapor']['tanggallahir'];
    $input['umur_pelapor']          = str_pad(usia($input['pelapor']['tanggallahir']), 3, '0', STR_PAD_LEFT);
    $input['jkpelapor']             = $input['pelapor']['jenis_kelamin'];
    $input['pekerjaanid_pelapor']   = str_pad($input['pelapor']['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanpelapor']      = $input['pelapor']['pekerjaan'];
    $input['alamat_pelapor']        = trim($input['pelapor']['alamat_jalan'] . ' ' . $input['pelapor']['nama_dusun']);
    $input['rt_pelapor']            = $input['pelapor']['nama_rt'];
    $input['rw_pelapor']            = $input['pelapor']['nama_rw'];
    $input['desapelapor']           = $input['pelapor']['pend_desa'];
    $input['kecpelapor']            = $input['pelapor']['pend_kecamatan'];
    $input['kabpelapor']            = $input['pelapor']['pend_kabupaten'];
    $input['provinsipelapor']       = $input['pelapor']['pend_propinsi'];

    // Tambahan
    // TODO: tambahkan no kk di kode isian penduduk luar
    // $input['no_kk_pelapor']           = $pelapor['no_kk'];
    $input['kewarganegaraan_pelapor'] = $input['pelapor']['warga_negara'];
}

if ($input['id_pend_saksi_i']) {
    $saksi1                        = $this->surat_model->get_data_surat($input['id_pend_saksi_i']);
    $input['nik_saksi1']           = get_nik($saksi1['nik']);
    $input['nama_saksi1']          = $saksi1['nama'];
    $input['tanggal_lahir_saksi1'] = $saksi1['tanggallahir'];
    $input['umur_saksi1']          = str_pad($saksi1['umur'], 3, '0', STR_PAD_LEFT);
    $input['jksaksi1']             = $saksi1['sex_id'];
    $input['pekerjaanid_saksi1']   = str_pad($saksi1['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaansaksi1']      = $saksi1['pekerjaan'];
    $input['alamat_saksi1']        = trim($saksi1['alamat'] . ' ' . $saksi1['dusun']);
    $input['rt_saksi1']            = $saksi1['rt'];
    $input['rw_saksi1']            = $saksi1['rw'];
    $input['desasaksi1']           = $config['nama_desa'];
    $input['kecsaksi1']            = $config['nama_kecamatan'];
    $input['kabsaksi1']            = $config['nama_kabupaten'];
    $input['provinsisaksi1']       = $config['nama_propinsi'];

    // Tambahan
    $input['no_kk_saksi1']           = $saksi1['no_kk'];
    $input['kewarganegaraan_saksi1'] = $saksi1['warganegara'];
} else {
    $input['nik_saksi1']           = get_nik($input['saksi_i']['nik']);
    $input['nama_saksi1']          = $input['saksi_i']['nama'];
    $input['tanggal_lahir_saksi1'] = $input['saksi_i']['tanggallahir'];
    $input['umur_saksi1']          = str_pad(usia($input['saksi_i']['tanggallahir']), 3, '0', STR_PAD_LEFT);
    $input['jksaksi11']            = $input['saksi_i']['jenis_kelamin'];
    $input['pekerjaanid_saksi1']   = str_pad($input['saksi_i']['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaansaksi1']      = $input['saksi_i']['pekerjaan'];
    $input['alamat_saksi1']        = trim($input['saksi_i']['alamat_jalan'] . ' ' . $input['saksi_i']['nama_dusun']);
    $input['rt_saksi1']            = $input['saksi_i']['nama_rt'];
    $input['rw_saksi1']            = $input['saksi_i']['nama_rw'];
    $input['desasaksi1']           = $input['saksi_i']['pend_desa'];
    $input['kecsaksi1']            = $input['saksi_i']['pend_kecamatan'];
    $input['kabsaksi1']            = $input['saksi_i']['pend_kabupaten'];
    $input['provinsisaksi1']       = $input['saksi_i']['pend_propinsi'];

    // Tambahan
    // TODO: tambahkan no kk di kode isian penduduk luar
    // $input["no_kk_saksi{$i}"]           = '';
    $input['kewarganegaraan_saksi1'] = $input['saksi_i']['warga_negara'];
}

if ($input['id_pend_saksi_ii']) {
    $saksi2                        = $this->surat_model->get_data_surat($input['id_pend_saksi_ii']);
    $input['nik_saksi2']           = get_nik($saksi2['nik']);
    $input['nama_saksi2']          = $saksi2['nama'];
    $input['tanggal_lahir_saksi2'] = $saksi2['tanggallahir'];
    $input['umur_saksi2']          = str_pad($saksi2['umur'], 3, '0', STR_PAD_LEFT);
    $input['jksaksi2']             = $saksi2['sex_id'];
    $input['pekerjaanid_saksi2']   = str_pad($saksi2['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaansaksi2']      = $saksi2['pekerjaan'];
    $input['alamat_saksi2']        = trim($saksi2['alamat'] . ' ' . $saksi2['dusun']);
    $input['rt_saksi2']            = $saksi2['rt'];
    $input['rw_saksi2']            = $saksi2['rw'];
    $input['desasaksi2']           = $config['nama_desa'];
    $input['kecsaksi2']            = $config['nama_kecamatan'];
    $input['kabsaksi2']            = $config['nama_kabupaten'];
    $input['provinsisaksi2']       = $config['nama_propinsi'];

    // Tambahan
    $input['no_kk_saksi2']           = $saksi2['no_kk'];
    $input['kewarganegaraan_saksi2'] = $saksi2['warganegara'];
} else {
    $input['nik_saksi2']           = get_nik($input['saksi_ii']['nik']);
    $input['nama_saksi2']          = $input['saksi_ii']['nama'];
    $input['tanggal_lahir_saksi2'] = $input['saksi_ii']['tanggallahir'];
    $input['umur_saksi2']          = str_pad(usia($input['saksi_ii']['tanggallahir']), 3, '0', STR_PAD_LEFT);
    $input['jksaksi12']            = $input['saksi_ii']['jenis_kelamin'];
    $input['pekerjaanid_saksi2']   = str_pad($input['saksi_ii']['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaansaksi2']      = $input['saksi_ii']['pekerjaan'];
    $input['alamat_saksi2']        = trim($input['saksi_ii']['alamat_jalan'] . ' ' . $input['saksi_ii']['nama_dusun']);
    $input['rt_saksi2']            = $input['saksi_ii']['nama_rt'];
    $input['rw_saksi2']            = $input['saksi_ii']['nama_rw'];
    $input['desasaksi2']           = $input['saksi_ii']['pend_desa'];
    $input['kecsaksi2']            = $input['saksi_ii']['pend_kecamatan'];
    $input['kabsaksi2']            = $input['saksi_ii']['pend_kabupaten'];
    $input['provinsisaksi2']       = $input['saksi_ii']['pend_propinsi'];

    // Tambahan
    // TODO: tambahkan no kk di kode isian penduduk luar
    // $input["no_kk_saksi{$i}"]           = '';
    $input['kewarganegaraan_saksi2'] = $input['saksi_ii']['warga_negara'];
}
