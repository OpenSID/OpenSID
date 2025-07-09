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

namespace App\Services;

use App\Models\LogHapusPenduduk;
use Illuminate\Support\Facades\DB;

class DataEkspor
{
    public static function tambah_penduduk_sinkronasi_opendk()
    {
        $kodeDesa = kode_wilayah(identitas()->kode_desa);
        $data     = DB::table('tweb_penduduk as p')
            ->select([
                'k.alamat',
                'c.dusun',
                'c.rw',
                'c.rt',
                'p.nama',
                'k.no_kk as nomor_kk',
                'p.nik as nomor_nik',
                'p.sex as jenis_kelamin',
                'p.tempatlahir as tempat_lahir',
                'p.tanggallahir as tanggal_lahir',
                'p.agama_id as agama',
                'p.pendidikan_kk_id as pendidikan_dlm_kk',
                'p.pendidikan_sedang_id as pendidikan_sdg_ditempuh',
                'p.pekerjaan_id as pekerjaan',
                'p.status_kawin as kawin',
                'p.kk_level as hubungan_keluarga',
                'p.warganegara_id as kewarganegaraan',
                'p.nama_ayah',
                'p.nama_ibu',
                'p.golongan_darah_id as gol_darah',
                'p.akta_lahir',
                'p.dokumen_pasport as nomor_dokumen_pasport',
                'p.tanggal_akhir_paspor as tanggal_akhir_pasport',
                'p.dokumen_kitas as nomor_dokumen_kitas',
                'p.ayah_nik as nik_ayah',
                'p.ibu_nik as nik_ibu',
                'p.akta_perkawinan as nomor_akta_perkawinan',
                'p.tanggalperkawinan as tanggal_perkawinan',
                'p.akta_perceraian as nomor_akta_perceraian',
                'p.tanggalperceraian as tanggal_perceraian',
                'p.cacat_id as cacat',
                'p.cara_kb_id as cara_kb',
                'p.hamil',
                'p.ktp_el',
                'p.status_rekam',
                'p.alamat_sekarang',
                'p.id',
                'p.foto',
                'p.status_dasar',
                'p.created_at',
                'p.updated_at',
                DB::raw("CONCAT('{$kodeDesa}', '') as desa_id"),
            ])
            ->leftJoin('tweb_keluarga as k', 'k.id', '=', 'p.id_kk')
            ->leftJoin('tweb_wil_clusterdesa as c', 'c.id', '=', 'p.id_cluster')
            ->orderBy('k.no_kk', 'ASC')
            ->orderBy('p.kk_level', 'ASC')
            ->get()->map(static function ($item) {
        $item->foto = $item->foto ? 'kecil_' . $item->foto : null;

        return $item;
    })->toArray();

    return $data;
    }

    /**
     * Sinkronasi Data dan Foto Penduduk ke OpenDK.
     *
     * @return array
     */
    public static function hapus_penduduk_sinkronasi_opendk()
    {
        $kode_desa  = kode_wilayah(identitas()->kode_desa);
        $data_hapus = LogHapusPenduduk::data()->select(
            DB::raw("CONCAT('{$kode_desa}', '') as desa_id, id_pend as id_pend_desa, foto")
        )
            ->get()
            ->toArray();

        $response['hapus_penduduk'] = $data_hapus;

        return $response;
    }
}
