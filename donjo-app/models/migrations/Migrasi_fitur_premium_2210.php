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

use App\Models\FormatSurat;
use App\Models\LogSurat;
use App\Models\Pamong;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2210 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2209');
        $hasil = $hasil && $this->migrasi_2022090751($hasil);
        $hasil = $hasil && $this->migrasi_2022090851($hasil);
        $hasil = $hasil && $this->migrasi_2022091251($hasil);
        $hasil = $hasil && $this->migrasi_2022091551($hasil);
        $hasil = $hasil && $this->migrasi_2022091651($hasil);
        $hasil = $hasil && $this->migrasi_2022091951($hasil);

        return $hasil && $this->migrasi_2022092351($hasil);
    }

    protected function migrasi_2022090751($hasil)
    {
        // Cek apakah pamong dengan pamong_id = 1 ada
        if (! Pamong::find(1)) {
            // Jika tidak ada, ganti id_pamong = 1 pada log_surat dengan kepala desa yang aktif
            $pamongId = Pamong::kepalaDesa()->first()->pamong_id;
            LogSurat::where('id_pamong', 1)->update(['id_pamong' => $pamongId]);
        }

        return $hasil;
    }

    protected function migrasi_2022090851($hasil)
    {
        // Sesuaikan surat status surat rtf dan tinymce mengikuti alur baru
        LogSurat::status(LogSurat::CETAK)->whereNull('verifikasi_operator')->update(['verifikasi_operator' => 1]);

        // Sesuaikan surat status konsep tapi masuk ke arsip
        $surat_tiny_mce = FormatSurat::jenis(FormatSurat::TINYMCE)->pluck('id');
        if ($surat_tiny_mce) {
            LogSurat::whereIn('id_format_surat', $surat_tiny_mce)->status(LogSurat::KONSEP)->update(['verifikasi_operator' => 0]);
        }

        return $hasil;
    }

    protected function migrasi_2022091251($hasil)
    {
        // Hapus tabel ref_font_surat
        if ($this->db->table_exists('ref_font_surat')) {
            $hasil = $hasil && $this->dbforge->drop_table('ref_font_surat');
        }

        return $hasil;
    }

    protected function migrasi_2022091551($hasil)
    {
        return $hasil && $this->dbforge->modify_column('anjungan', [
            'created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
        ]);
    }

    protected function migrasi_2022091651($hasil)
    {
        return $hasil && $this->dbforge->modify_column('sys_traffic', [
            'ipAddress' => [
                'type' => 'LONGTEXT',
                'null' => false,
            ],
            'Jumlah' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'null'       => false,
            ],
        ]);
    }

    protected function migrasi_2022091951($hasil)
    {
        // Tambahkan kode kelompok / lembaga jika masih kosong
        $daftar_data = DB::table('kelompok')->where('kode', '')->pluck('tipe', 'id');

        foreach ($daftar_data as $key => $value) {
            DB::table('kelompok')->where('id', $key)->update(['kode' => $value . '-' . $key]);
        }

        return $hasil;
    }

    protected function migrasi_2022092351($hasil)
    {
        DB::table('tweb_wil_clusterdesa')->where('rt', '=', '0')->where('rw', '=', '')->update(['rw' => '0']);

        return $hasil;
    }
}
