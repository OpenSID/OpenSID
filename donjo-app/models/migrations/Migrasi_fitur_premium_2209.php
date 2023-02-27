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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2209 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2208');
        $hasil = $hasil && $this->migrasi_2022080451($hasil);

        return $hasil && $this->migrasi_2022081951($hasil);
    }

    public function migrasi_2022080451($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'notifikasi_koneksi',
            'value'      => 1,
            'keterangan' => 'Ingatkan jika aplikasi tidak terhubung dengan internet.',
            'jenis'      => 'boolean',
        ]);
    }

    protected function migrasi_2022081951($hasil)
    {
        // Semua penduduk yang memiliki foto
        $daftar_penduduk = DB::table('tweb_penduduk')->where('foto', '!=', '')->get(['id', 'nik', 'foto']);

        if ($daftar_penduduk) {
            foreach ($daftar_penduduk as $data) {
                // Ganti nama file jika nama file sama dengan nik penduduk
                if (preg_match("/{$data->nik}/i", $data->foto) && (file_exists(FCPATH . LOKASI_USER_PICT . $data->foto) || file_exists(FCPATH . LOKASI_USER_PICT . 'kecil_' . $data->foto))) {
                    $nama_baru = time() . mt_rand(10000, 999999) . get_extension($data->foto);

                    if (DB::table('tweb_penduduk')->where('id', $data->id)->update(['foto' => $nama_baru])) {
                        rename(FCPATH . LOKASI_USER_PICT . $data->foto, FCPATH . LOKASI_USER_PICT . $nama_baru);
                        rename(FCPATH . LOKASI_USER_PICT . 'kecil_' . $data->foto, FCPATH . LOKASI_USER_PICT . 'kecil_' . $nama_baru);
                    }
                }
            }
        }

        // Semua aparatur penduduk luar desa
        $daftar_pamong = DB::table('tweb_desa_pamong')->where('foto', '!=', '')->get(['pamong_id', 'pamong_nik', 'foto']);

        if ($daftar_pamong) {
            foreach ($daftar_pamong as $data) {
                // Ganti nama file jika nama file sama dengan nik penduduk
                if (null === $data->id_pend && preg_match("/{$data->pamong_nik}/i", $data->foto) && (file_exists(FCPATH . LOKASI_USER_PICT . $data->foto) || file_exists(FCPATH . LOKASI_USER_PICT . 'kecil_' . $data->foto))) {
                    $nama_baru = 'pamong_' . time() . mt_rand(10000, 999999) . get_extension($data->foto);

                    if (DB::table('tweb_desa_pamong')->where('pamong_id', $data->pamong_id)->update(['foto' => $nama_baru])) {
                        rename(FCPATH . LOKASI_USER_PICT . $data->foto, FCPATH . LOKASI_USER_PICT . $nama_baru);
                        rename(FCPATH . LOKASI_USER_PICT . 'kecil_' . $data->foto, FCPATH . LOKASI_USER_PICT . 'kecil_' . $nama_baru);
                    }
                }
            }
        }

        return $hasil;
    }
}
