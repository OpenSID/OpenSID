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

$tampil_data_anak                           = false;
$tampil_data_orang_tua                      = false;
$tampil_data_pelapor                        = false;
$tampil_data_saksi                          = false;
$tampil_data_kematian                       = false;
$tampil_data_subjek_akta_1                  = false;
$tampil_data_subjek_akta_2                  = false;
$tampil_data_lahir_mati                     = false;
$tampil_data_perkawinan                     = false;
$tampil_data_perceraian                     = false;
$tampil_data_pengankatan_anak               = false;
$tampil_data_pengakuan_anak                 = false;
$tampil_data_pengesahan_anak                = false;
$tampil_data_perubahan_nama                 = false;
$tampil_data_perubahan_status_kewarganeraan = false;
$tampil_data_perubahan_peristiwa_lain       = false;
$tampil_data_perubahan_akta                 = false;
$tampil_data_pelaporan_luar_nkri            = false;

switch ($surat->url_surat) {
    case Illuminate\Support\Str::contains($surat->url_surat, 'surat-keterangan-kelahiran'):
        $format_f201            = 1;
        $tampil_data_anak       = true;
        $tampil_data_orang_tua  = true;
        $tampil_data_pelapor    = true;
        $tampil_data_saksi      = true;
        $tampil_data_lahir_mati = true;
        break;

    case Illuminate\Support\Str::contains($surat->url_surat, 'surat-keterangan-kematian'):
        $format_f201           = 7;
        $tampil_data_kematian  = true;
        $tampil_data_orang_tua = true;
        $tampil_data_pelapor   = true;
        $tampil_data_saksi     = true;
    break;

        default:
    // code...
    break;
}

// include data pelapor dan saksi
include STORAGEPATH . 'app/template/lampiran/kode_pelapor_saksi.php';

$individu['umur'] = str_pad($individu['umur'], 3, '0', STR_PAD_LEFT);

$ibu = $this->surat_model->surat_model->get_data_ibu($individu['id']);
if ($ibu) {
    $input['nik_ibu']             = get_nik($ibu['nik']);
    $input['nama_ibu']            = $ibu['nama'];
    $input['tempat_lahir_ibu']    = strtoupper($ibu['tempatlahir']);
    $input['tanggal_lahir_ibu']   = $ibu['tanggallahir'];
    $input['umur_ibu']            = str_pad($ibu['umur'], 3, '0', STR_PAD_LEFT);
    $input['pekerjaanid_ibu']     = str_pad($ibu['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanibu']        = $ibu['pek'];
    $input['alamat_ibu']          = trim($ibu['alamat'] . ' ' . $ibu['dusun']);
    $input['rt_ibu']              = $ibu['rt'];
    $input['rw_ibu']              = $ibu['rw'];
    $input['desaibu']             = $config['nama_desa'];
    $input['kecibu']              = $config['nama_kecamatan'];
    $input['kabibu']              = $config['nama_kabupaten'];
    $input['provinsiibu']         = $config['nama_propinsi'];
    $input['kewarganegaraan_ibu'] = $ibu['wn'];
} else {
    $input['pekerjaanid_ibu'] = str_pad($input['pekerjaanid_ibu'], 2, '0', STR_PAD_LEFT);
    $input['umur_ibu']        = str_pad($input['umur_ibu'], 3, '0', STR_PAD_LEFT);
}

$ayah = $this->surat_model->get_data_ayah($individu['id']);
if ($ayah) {
    $input['nik_ayah']             = get_nik($ayah['nik']);
    $input['nama_ayah']            = $ayah['nama'];
    $input['tempat_lahir_ayah']    = strtoupper($ayah['tempatlahir']);
    $input['tanggal_lahir_ayah']   = $ayah['tanggallahir'];
    $input['umur_ayah']            = str_pad($ayah['umur'], 3, '0', STR_PAD_LEFT);
    $input['pekerjaanid_ayah']     = str_pad($ayah['pekerjaan_id'], 2, '0', STR_PAD_LEFT);
    $input['pekerjaanayah']        = $ayah['pek'];
    $input['alamat_ayah']          = trim($ayah['alamat'] . ' ' . $ayah['dusun']);
    $input['rt_ayah']              = $ayah['rt'];
    $input['rw_ayah']              = $ayah['rw'];
    $input['desaayah']             = $config['nama_desa'];
    $input['kecayah']              = $config['nama_kecamatan'];
    $input['kabayah']              = $config['nama_kabupaten'];
    $input['provinsiayah']         = $config['nama_propinsi'];
    $input['kewarganegaraan_ayah'] = $ayah['wn'];
} else {
    $input['pekerjaanid_ayah'] = str_pad($input['pekerjaanid_ayah'], 2, '0', STR_PAD_LEFT);
    $input['umur_ayah']        = str_pad($input['umur_ayah'], 3, '0', STR_PAD_LEFT);
}

// Karena data F-2.01 berisi berbagai jenis lampiran, sehingga yang dimaksud data utama belum sesuai
$input['nik_kematian']      = get_nik($individu['nik']);
$input['nama_kematian']     = $individu['nama'];
$data_mati                  = $this->surat_model->get_data_mati($individu['id']);
$input['tanggal_kematian']  = $data_mati->tgl_peristiwa;
$input['jam_kematian']      = $data_mati->jam_mati;
$input['sebab_kematian']    = $data_mati->sebab;
$input['tempat_kematian']   = $data_mati->meninggal_di;
$input['penolong_kematian'] = $data_mati->penolong_mati;
