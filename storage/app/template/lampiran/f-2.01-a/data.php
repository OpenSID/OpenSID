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

use App\Models\LogPenduduk;
use App\Models\PendudukSaja;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

$tampil_data_anak                           = true;
$tampil_data_orang_tua                      = true;
$tampil_data_pelapor                        = true;
$tampil_data_saksi                          = true;
$tampil_data_kematian                       = true;
$tampil_data_subjek_akta_1                  = true;
$tampil_data_subjek_akta_2                  = false;
$tampil_data_lahir_mati                     = true;
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

$format_f201 = match (true) {
    Str::contains($surat->url_surat, '-kelahiran') => 1,
    Str::contains($surat->url_surat, '-kematian') => 7,
    default => null,
};

// Kondisi pengisian data berdasarkan jenis surat
$isi_data_anak = match (true) {
    Str::contains($surat->url_surat, '-kelahiran') => true,
    default => false,
};

$isi_data_kematian = match (true) {
    Str::contains($surat->url_surat, '-kematian') => true,
    default => false,
};

// include data pelapor dan saksi
include STORAGEPATH . 'app/template/lampiran/kode_pelapor_saksi.php';

// Tambahkan header surat Dispenduk Capil ke config
$config['header_surat_dispenduk'] = setting('header_surat_dispenduk');

$individu['umur'] = str_pad($individu['umur'], 3, '0', STR_PAD_LEFT);

$ibu = (new PendudukSaja())->dataIbu($individu['id']);
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

$ayah = (new PendudukSaja())->dataAyah($individu['id']);
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
if ($isi_data_kematian) {
    $input['nik_kematian']  = get_nik($individu['nik']);
    $input['nama_kematian'] = $individu['nama'];

    $data_mati = LogPenduduk::where('id_pend', $individu['id'])
        ->where('kode_peristiwa', '2')
        ->first();

    if ($data_mati) {
        $input['tanggal_kematian']  = $data_mati->tgl_peristiwa;
        $input['jam_kematian']      = $data_mati->jam_mati;
        $input['sebab_kematian']    = $data_mati->sebab;
        $input['tempat_kematian']   = $data_mati->meninggal_di;
        $input['penolong_kematian'] = $data_mati->penolong_mati;
    }
}

// Konversi berat bayi dari gram ke kg
if (!empty($individu['berat_lahir']) && is_numeric($individu['berat_lahir'])) {
    $individu['berat_lahir_kg'] = number_format($individu['berat_lahir'] / 1000, 2);
} else {
    $individu['berat_lahir_kg'] = '';
}
