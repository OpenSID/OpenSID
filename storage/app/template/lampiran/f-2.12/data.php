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

if (count($individu) > 1) {
    // pengantian pria penduduk desa
    $input['nik_pria']             = $individu['nik'];
    $input['kk_pria']              = $individu['no_kk'];
    $input['nama_pria']            = $individu['nama'];
    $input['tanggal_lahir_pria']   = $individu['tanggallahir'];
    $input['tempat_lahir_pria']    = $individu['tempatlahir'];
    $input['umur_pria']            = str_pad($individu['umur'], 3, '0', STR_PAD_LEFT);
    $input['pekerjaanid_pria']     = str_pad($individu['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanpria']        = $individu['pekerjaan'];
    $input['alamat_pria']          = trim($individu['alamat'] . ' ' . $individu['dusun']);
    $input['rt_pria']              = $individu['rt'];
    $input['rw_pria']              = $individu['rw'];
    $input['status_kawin_pria']    = $individu['status_kawin'];
    $input['wn_pria']              = $individu['warganegara'];
    $input['desapria']             = $config['nama_desa'];
    $input['kecpria']              = $config['nama_kecamatan'];
    $input['kabpria']              = $config['nama_kabupaten'];
    $input['provinsipria']         = $config['nama_propinsi'];
    $input['pendidikan_pria']      = $individu['pendidikan'];
    $input['agama_pria']           = $individu['agama'];
    $input['anak_ke_pria']         = str_pad($individu['kelahiran_anak_ke'], 2, '0', STR_PAD_LEFT);
    $input['dokumen_pasport_pria'] = $input['paspor'];
    $input['telepon_pria']         = $input['telepon'];
    $input['penghayat_pria']       = $input['nama_organisasi_penghayat_kepercayaan'];
    $input['kawin_ke_pria']        = $input['perkawinan_ke'];
    $input['istri_ke_bagi_pria']   = $input['jika_beristri,_istri_ke'];
    $input['bangsa_pria']          = $input['kebangsaan_(bagi_wna)'];
} else {
    // pengantian pria penduduk luar desa
    $input['nik_pria']             = $input['individu']['nik'];
    $input['kk_pria']              = $input['individu']['no_kk'];
    $input['nama_pria']            = $input['individu']['nama'];
    $input['tanggal_lahir_pria']   = $input['individu']['tanggallahir'];
    $input['tempat_lahir_pria']    = $input['individu']['tempatlahir'];
    $input['umur_pria']            = str_pad(usia($input['individu']['tanggallahir']), 3, '0', STR_PAD_LEFT);
    $input['pekerjaanid_pria']     = str_pad($input['individu']['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanwanita']      = $input['individu']['pekerjaan'];
    $input['alamat_pria']          = trim($input['individu']['alamat_jalan'] . ' ' . $input['individu']['nama_dusun']);
    $input['rt_pria']              = $input['individu']['nama_rt'];
    $input['rw_pria']              = $input['individu']['nama_rw'];
    $input['status_kawin_pria']    = $input['individu']['status_kawin'];
    $input['wn_pria']              = $input['individu']['warganegara'];
    $input['desapria']             = $config['nama_desa'];
    $input['kecpria']              = $config['nama_kecamatan'];
    $input['kabpria']              = $config['nama_kabupaten'];
    $input['provinsipria']         = $config['nama_propinsi'];
    $input['pendidikan_pria']      = $input['individu']['pendidikan_kk'];
    $input['agama_pria']           = $input['individu']['agama'];
    $input['anak_ke_pria']         = str_pad($input['anak_ke'], 2, '0', STR_PAD_LEFT);
    $input['dokumen_pasport_pria'] = $input['passport'];
    $input['telepon_pria']         = $input['telepon'];
    $input['penghayat_pria']       = $input['nama_organisasi_penghayat_kepercayaan'];
    $input['kawin_ke_pria']        = $input['perkawinan_ke'];
    $input['bangsa_pria']          = $input['kebangsaan_(bagi_wna)'];
}

if ($input['id_pend_cpw']) {
    // pengantian wanita penduduk desa
    $wanita = $this->surat_model->get_data_surat($input['id_pend_cpw']);

    $input['nik_wanita']             = $wanita['nik'];
    $input['kk_wanita']              = $wanita['no_kk'];
    $input['nama_wanita']            = $wanita['nama'];
    $input['tanggal_lahir_wanita']   = $wanita['tanggallahir'];
    $input['tempat_lahir_wanita']    = $wanita['tempatlahir'];
    $input['umur_wanita']            = str_pad($wanita['umur'], 3, '0', STR_PAD_LEFT);
    $input['pekerjaanid_wanita']     = str_pad($wanita['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanwanita']        = $wanita['pekerjaan'];
    $input['alamat_wanita']          = trim($wanita['alamat'] . ' ' . $wanita['dusun']);
    $input['rt_wanita']              = $wanita['rt'];
    $input['rw_wanita']              = $wanita['rw'];
    $input['anak_ke_wanita']         = str_pad($wanita['kelahiran_anak_ke'], 2, '0', STR_PAD_LEFT);
    $input['status_kawin_wanita']    = $wanita['status_kawin'];
    $input['wn_wanita']              = $wanita['warganegara'];
    $input['desawanita']             = $config['nama_desa'];
    $input['kecwanita']              = $config['nama_kecamatan'];
    $input['kabwanita']              = $config['nama_kabupaten'];
    $input['provinsiwanita']         = $config['nama_propinsi'];
    $input['pendidikan_wanita']      = $wanita['pendidikan'];
    $input['agama_wanita']           = $wanita['agama'];
    $input['dokumen_pasport_wanita'] = $input['passport_cpw'];
    $input['telepon_wanita']         = $input['telepon_cpw'];
    $input['penghayat_wanita']       = $input['nama_organisasi_penghayat_kepercayaan_cpw'];
    $input['kawin_ke_wanita']        = $input['perkawinan_ke_cpw'];
    $input['bangsa_wanita']          = $input['kebangsaan_(bagi_wna)_cpw'];
} else {
    // pengantian wanita penduduk luar desa
    $input['nik_wanita']             = $input['cpw']['nik'];
    $input['kk_wanita']              = $input['cpw']['no_kk'];
    $input['nama_wanita']            = $input['cpw']['nama'];
    $input['tanggal_lahir_wanita']   = $input['cpw']['tanggallahir'];
    $input['tempat_lahir_wanita']    = $input['cpw']['tempatlahir'];
    $input['umur_wanita']            = str_pad(usia($input['cpw']['tanggallahir']), 3, '0', STR_PAD_LEFT);
    $input['pekerjaanid_wanita']     = str_pad($input['cpw']['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanwanita']        = $input['cpw']['pekerjaan'];
    $input['alamat_wanita']          = trim($input['cpw']['alamat_jalan'] . ' ' . $input['cpw']['nama_dusun']);
    $input['rt_wanita']              = $input['cpw']['nama_rt'];
    $input['rw_wanita']              = $input['cpw']['nama_rw'];
    $input['status_kawin_wanita']    = $input['cpw']['status_kawin'];
    $input['wn_wanita']              = $input['cpw']['warganegara'];
    $input['desawanita']             = $config['nama_desa'];
    $input['kecwanita']              = $config['nama_kecamatan'];
    $input['kabwanita']              = $config['nama_kabupaten'];
    $input['provinsiwanita']         = $config['nama_propinsi'];
    $input['pendidikan_wanita']      = $input['cpw']['pendidikan_kk'];
    $input['agama_wanita']           = $input['cpw']['agama'];
    $input['anak_ke_wanita']         = str_pad($wanita['anak_ke_cpw'], 2, '0', STR_PAD_LEFT);
    $input['dokumen_pasport_wanita'] = $input['passport_cpw'];
    $input['telepon_wanita']         = $input['telepon_cpw'];
    $input['penghayat_wanita']       = $input['nama_organisasi_penghayat_kepercayaan_cpw'];
    $input['kawin_ke_wanita']        = $input['perkawinan_ke_cpw'];
    $input['bangsa_wanita']          = $input['kebangsaan_(bagi_wna)_cpw'];
}

if ($input['id_pend_dipp']) {
    // ibu pengantian pria penduduk desa
    $ibu_pria = $this->surat_model->get_data_surat($input['id_pend_dipp']);

    $input['nik_ibu_pria']           = $ibu_pria['nik'];
    $input['nama_ibu_pria']          = $ibu_pria['nama'];
    $input['tanggal_lahir_ibu_pria'] = $ibu_pria['tanggallahir'];
    $input['tempat_lahir_ibu_pria']  = $ibu_pria['tempatlahir'];
    $input['pekerjaanid_ibu_pria']   = str_pad($ibu_pria['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanibu_pria']      = $ibu_pria['pekerjaan'];
    $input['alamat_ibu_pria']        = trim($ibu_pria['alamat'] . ' ' . $ibu_pria['dusun']);
    $input['rt_ibu_pria']            = $individu['rt'];
    $input['rw_ibu_pria']            = $individu['rw'];
    $input['desaibu_pria']           = $config['nama_desa'];
    $input['kecibu_pria']            = $config['nama_kecamatan'];
    $input['kabibu_pria']            = $config['nama_kabupaten'];
    $input['provinsiibu_pria']       = $config['nama_propinsi'];
    $input['agama_ibu_pria']         = $ibu_pria['agama'];
    $input['telepon_ibu_pria']       = $input['telepon_dipp'];
    $input['penghayat_ibu_pria']     = $input['nama_organisasi_penghayat_kepercayaan_dipp'];
} else {
    // ibu pengantian pria penduduk luar desa
    $input['nik_ibu_pria']           = $input['dipp']['nik'];
    $input['nama_ibu_pria']          = $input['dipp']['nama'];
    $input['tanggal_lahir_ibu_pria'] = $input['dipp']['tanggallahir'];
    $input['tempat_lahir_ibu_pria']  = $input['dipp']['tempatlahir'];
    $input['pekerjaanid_ibu_pria']   = str_pad($input['dipp']['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanibu_pria']      = $input['dipp']['pekerjaan'];
    $input['alamat_ibu_pria']        = trim($input['dipp']['alamat'] . ' ' . $input['dipp']['dusun']);
    $input['rt_ibu_pria']            = $individu['rt'];
    $input['rw_ibu_pria']            = $individu['rw'];
    $input['desaibu_pria']           = $config['nama_desa'];
    $input['kecibu_pria']            = $config['nama_kecamatan'];
    $input['kabibu_pria']            = $config['nama_kabupaten'];
    $input['provinsiibu_pria']       = $config['nama_propinsi'];
    $input['agama_ibu_pria']         = $input['dipp']['agama'];
    $input['telepon_ibu_pria']       = $input['telepon_dipp'];
    $input['penghayat_ibu_pria']     = $input['nama_organisasi_penghayat_kepercayaan_dipp'];
}

if ($input['id_pend_dapp']) {
    // ayah pengantian pria penduduk desa
    $ayah_pria = $this->surat_model->get_data_surat($input['id_pend_dapp']);

    $input['nik_ayah_pria']           = $ayah_pria['nik'];
    $input['nama_ayah_pria']          = $ayah_pria['nama'];
    $input['tanggal_lahir_ayah_pria'] = $ayah_pria['tanggallahir'];
    $input['tempat_lahir_ayah_pria']  = $ayah_pria['tempatlahir'];
    $input['pekerjaanid_ayah_pria']   = str_pad($ayah_pria['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanayah_pria']      = $ayah_pria['pekerjaan'];
    $input['alamat_ayah_pria']        = trim($ayah_pria['alamat'] . ' ' . $ayah_pria['dusun']);
    $input['rt_ayah_pria']            = $individu['rt'];
    $input['rw_ayah_pria']            = $individu['rw'];
    $input['desaayah_pria']           = $config['nama_desa'];
    $input['kecayah_pria']            = $config['nama_kecamatan'];
    $input['kabayah_pria']            = $config['nama_kabupaten'];
    $input['provinsiayah_pria']       = $config['nama_propinsi'];
    $input['agama_ayah_pria']         = $ayah_pria['agama'];
    $input['telepon_ayah_pria']       = $input['telepon_dipp'];
    $input['penghayat_ayah_pria']     = $input['nama_organisasi_penghayat_kepercayaan_dapp'];
} else {
    // ayah pengantian pria penduduk luar desa
    $input['nik_ayah_pria']           = $input['dapp']['nik'];
    $input['nama_ayah_pria']          = $input['dapp']['nama'];
    $input['tanggal_lahir_ayah_pria'] = $input['dapp']['tanggallahir'];
    $input['tempat_lahir_ayah_pria']  = $input['dapp']['tempatlahir'];
    $input['pekerjaanid_ayah_pria']   = str_pad($input['dapp']['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanayah_pria']      = $input['dapp']['pekerjaan'];
    $input['alamat_ayah_pria']        = trim($input['dapp']['alamat'] . ' ' . $input['dapp']['dusun']);
    $input['rt_ayah_pria']            = $individu['rt'];
    $input['rw_ayah_pria']            = $individu['rw'];
    $input['desaayah_pria']           = $config['nama_desa'];
    $input['kecayah_pria']            = $config['nama_kecamatan'];
    $input['kabayah_pria']            = $config['nama_kabupaten'];
    $input['provinsiayah_pria']       = $config['nama_propinsi'];
    $input['agama_ayah_pria']         = $input['dapp']['agama'];
    $input['telepon_ayah_pria']       = $input['telepon_dapp'];
    $input['penghayat_ayah_pria']     = $input['nama_organisasi_penghayat_kepercayaan_dapp'];
}

if ($input['id_pend_dipw']) {
    // ibu pengantian wanita penduduk desa
    $ibu_wanita = $this->surat_model->get_data_surat($input['id_pend_dipw']);

    $input['nik_ibu_wanita']           = $ibu_wanita['nik'];
    $input['nama_ibu_wanita']          = $ibu_wanita['nama'];
    $input['tanggal_lahir_ibu_wanita'] = $ibu_wanita['tanggallahir'];
    $input['tempat_lahir_ibu_wanita']  = $ibu_wanita['tempatlahir'];
    $input['pekerjaanid_ibu_wanita']   = str_pad($ibu_wanita['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanibu_wanita']      = $ibu_wanita['pekerjaan'];
    $input['alamat_ibu_wanita']        = trim($ibu_wanita['alamat'] . ' ' . $ibu_wanita['dusun']);
    $input['rt_ibu_wanita']            = $individu['rt'];
    $input['rw_ibu_wanita']            = $individu['rw'];
    $input['desaibu_wanita']           = $config['nama_desa'];
    $input['kecibu_wanita']            = $config['nama_kecamatan'];
    $input['kabibu_wanita']            = $config['nama_kabupaten'];
    $input['provinsiibu_wanita']       = $config['nama_propinsi'];
    $input['agama_ibu_wanita']         = $ibu_wanita['agama'];
    $input['telepon_ibu_wanita']       = $input['telepon_dipw'];
    $input['penghayat_ibu_wanita']     = $input['nama_organisasi_penghayat_kepercayaan_dipw'];
} else {
    // ibu pengantian wanita penduduk luar desa
    $input['nik_ibu_wanita']           = $input['dipw']['nik'];
    $input['nama_ibu_wanita']          = $input['dipw']['nama'];
    $input['tanggal_lahir_ibu_wanita'] = $input['dipw']['tanggallahir'];
    $input['tempat_lahir_ibu_wanita']  = $input['dipw']['tempatlahir'];
    $input['pekerjaanid_ibu_wanita']   = str_pad($input['dipw']['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanibu_wanita']      = $input['dipw']['pekerjaan'];
    $input['alamat_ibu_wanita']        = trim($input['dipw']['alamat'] . ' ' . $input['dipw']['dusun']);
    $input['rt_ibu_wanita']            = $individu['rt'];
    $input['rw_ibu_wanita']            = $individu['rw'];
    $input['desaibu_wanita']           = $config['nama_desa'];
    $input['kecibu_wanita']            = $config['nama_kecamatan'];
    $input['kabibu_wanita']            = $config['nama_kabupaten'];
    $input['provinsiibu_wanita']       = $config['nama_propinsi'];
    $input['agama_ibu_wanita']         = $input['dipw']['agama'];
    $input['telepon_ibu_wanita']       = $input['telepon_dipw'];
    $input['penghayat_ibu_wanita']     = $input['nama_organisasi_penghayat_kepercayaan_dipw'];
}

if ($input['id_pend_dapw']) {
    // ayah pengantian wanita penduduk desa
    $ayah_wanita = $this->surat_model->get_data_surat($input['id_pend_dapw']);

    $input['nik_ayah_wanita']           = $ayah_wanita['nik'];
    $input['nama_ayah_wanita']          = $ayah_wanita['nama'];
    $input['tanggal_lahir_ayah_wanita'] = $ayah_wanita['tanggallahir'];
    $input['tempat_lahir_ayah_wanita']  = $ayah_wanita['tempatlahir'];
    $input['pekerjaanid_ayah_wanita']   = str_pad($ayah_wanita['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanayah_wanita']      = $ayah_wanita['pekerjaan'];
    $input['alamat_ayah_wanita']        = trim($ayah_wanita['alamat'] . ' ' . $ayah_wanita['dusun']);
    $input['rt_ayah_wanita']            = $individu['rt'];
    $input['rw_ayah_wanita']            = $individu['rw'];
    $input['desaayah_wanita']           = $config['nama_desa'];
    $input['kecayah_wanita']            = $config['nama_kecamatan'];
    $input['kabayah_wanita']            = $config['nama_kabupaten'];
    $input['provinsiayah_wanita']       = $config['nama_propinsi'];
    $input['agama_ayah_wanita']         = $ayah_wanita['agama'];
    $input['telepon_ayah_wanita']       = $input['telepon_dipw'];
    $input['penghayat_ayah_wanita']     = $input['nama_organisasi_penghayat_kepercayaan_dapw'];
} else {
    // ayah pengantian wanita penduduk luar desa
    $input['nik_ayah_wanita']           = $input['dapw']['nik'];
    $input['nama_ayah_wanita']          = $input['dapw']['nama'];
    $input['tanggal_lahir_ayah_wanita'] = $input['dapw']['tanggallahir'];
    $input['tempat_lahir_ayah_wanita']  = $input['dapw']['tempatlahir'];
    $input['pekerjaanid_ayah_wanita']   = str_pad($input['dapw']['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanayah_wanita']      = $input['dapw']['pekerjaan'];
    $input['alamat_ayah_wanita']        = trim($input['dapw']['alamat'] . ' ' . $input['dapw']['dusun']);
    $input['rt_ayah_wanita']            = $individu['rt'];
    $input['rw_ayah_wanita']            = $individu['rw'];
    $input['desaayah_wanita']           = $config['nama_desa'];
    $input['kecayah_wanita']            = $config['nama_kecamatan'];
    $input['kabayah_wanita']            = $config['nama_kabupaten'];
    $input['provinsiayah_wanita']       = $config['nama_propinsi'];
    $input['agama_ayah_wanita']         = $input['dapw']['agama'];
    $input['telepon_ayah_wanita']       = $input['telepon_dapw'];
    $input['penghayat_ayah_wanita']     = $input['nama_organisasi_penghayat_kepercayaan_dapw'];
}

if ($input['id_pend_saksi_i']) {
    // Saksi 1
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
    $input['telepon_saksi1']         = $input['telepon_saksi_i'];
    $input['penghayat_saksi1']       = $input['nama_organisasi_penghayat_kepercayaan_saksi_i'];
} else {
    // Saksi 1
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
    $input['telepon_saksi1']         = $input['telepon_saksi_i'];
    $input['penghayat_saksi1']       = $input['nama_organisasi_penghayat_kepercayaan_saksi_i'];
}

if ($input['id_pend_saksi_ii']) {
    // Saksi 2
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
    $input['telepon_saksi2']         = $input['telepon_saksi_ii'];
    $input['penghayat_saksi2']       = $input['nama_organisasi_penghayat_kepercayaan_saksi_ii'];
} else {
    // Saksi 2
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
    $input['telepon_saksi2']         = $input['telepon_saksi_ii'];
    $input['penghayat_saksi2']       = $input['nama_organisasi_penghayat_kepercayaan_saksi_ii'];
}

$input['tanggal_pemberkatan'] = $input['tanggal_pemberkatan_perkawinan_kawin'];
$input['tanggal_lapor']       = $input['tanggal_kawin'];
$input['hari_lapor']          = hari($input['tanggal_kawin']);
$input['jam_lapor']           = $input['jam_kawin'];
$input['agama_kawin']         = $input['agama/penghayat_kepercayaan_kawin'];
$input['penghayat_kawin']     = $input['nama_organisasi_penghayat_kepercayaan_kawin_kawin'];
$input['badan_peradilan']     = $input['nama_badan_peradilan_kawin'];
$input['nomor_putusan']       = $input['nomor_putusan_penetapan_pengadilan_kawin'];
$input['tanggal_putusan']     = $input['tanggal_putusan_penetapan_pengadilan_kawin'];
$input['nama_pemuka']         = $input['nama_pemuka_agama/pghyt_kepercayaan_kawin'];
$input['ijin_putusan']        = $input['ijin_perwakilan_bagi_wna_/_nomor_kawin'];
$input['jumlah_anak']         = $input['jumlah_anak_yang_telah_diakui_dan_disahkan_kawin'];
$input['nama_anak1']          = $input['nama_anak_pertama_kawin'];
$input['nama_anak2']          = $input['nama_anak_kedua_kawin'];
$input['nama_anak3']          = $input['nama_anak_ketiga_kawin'];
$input['nama_anak4']          = $input['nama_anak_ke_empat_kawin'];
$input['nama_anak5']          = $input['nama_anak_ke_lima_kawin'];
$input['nama_anak6']          = $input['nama_anak_ke_enam_kawin'];
$input['no_akta_anak1']       = $input['no_akta_lahir_anak_pertama_kawin'];
$input['no_akta_anak2']       = $input['no_akta_lahir_anak_kedua_kawin'];
$input['no_akta_anak3']       = $input['no_akta_lahir_anak_ketiga_kawin'];
$input['no_akta_anak4']       = $input['no_akta_lahir_anak_ke_empat_kawin'];
$input['no_akta_anak5']       = $input['no_akta_lahir_anak_ke_lima_kawin'];
$input['no_akta_anak6']       = $input['no_akta_lahir_anak_ke_enam_kawin'];
$input['tgl_akta_anak1']      = $input['tanggal_lahir_anak_pertama_kawin'];
$input['tgl_akta_anak2']      = $input['tanggal_lahir_anak_kedua_kawin'];
$input['tgl_akta_anak3']      = $input['tanggal_lahir_anak_ketiga_kawin'];
$input['tgl_akta_anak4']      = $input['tanggal_lahir_anak_ke_empat_kawin'];
$input['tgl_akta_anak5']      = $input['tanggal_lahir_anak_ke_lima_kawin'];
$input['tgl_akta_anak6']      = $input['tanggal_lahir_anak_ke_enam_kawin'];
