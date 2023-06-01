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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\FormatSurat;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2306 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2305', false);
        $hasil = $hasil && $this->migrasi_tabel($hasil);
        $hasil = $hasil && $this->migrasi_data($hasil);

        $this->cache->hapus_cache_untuk_semua('_cache_modul');
        $this->cache->hapus_cache_untuk_semua('identitas');

        return $hasil && true;
    }

    protected function migrasi_tabel($hasil)
    {
        $hasil = $hasil && $this->migrasi_2023052452($hasil);
        $hasil = $hasil && $this->migrasi_2023052951($hasil);

        return $hasil && $this->migrasi_2023053051($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2023052351($hasil, $id);
            $hasil = $hasil && $this->migrasi_2023053053($hasil, $id);
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2023052451($hasil);
        $hasil = $hasil && $this->migrasi_2023052453($hasil);
        $hasil = $hasil && $this->migrasi_2023052454($hasil);
        $hasil = $hasil && $this->migrasi_2023052551($hasil);
        $hasil = $hasil && $this->migrasi_2023053052($hasil);

        return $hasil && true;
    }

    protected function migrasi_2023052351($hasil, $id)
    {
        $setting = [
            'judul'      => 'Warna Tema',
            'key'        => 'warna_tema',
            'value'      => DB::table('setting_aplikasi')->where('config_id', $id)->where('key', 'warna_tema')->first()->value ?: config_item('warna_tema') ?: SettingAplikasi::WARNA_TEMA,
            'keterangan' => 'Warna tema untuk halaman website',
            'jenis'      => 'color',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'hidden',
        ];

        return $hasil && $this->tambah_setting($setting, $id);
    }

    protected function migrasi_2023052451($hasil)
    {
        $hasil && $this->ubah_modul(['modul' => 'Klasfikasi Surat'], ['modul' => 'Klasifikasi Surat']);

        return $hasil && $this->ubah_modul(['slug' => 'klasfikasi-surat'], ['slug' => 'klasifikasi-surat']);
    }

    protected function migrasi_2023052452($hasil)
    {
        // migrasi index log_penduduk
        $hasil = $hasil && $this->tambahIndeks('log_penduduk', 'config_id', 'INDEX');
        $hasil = $hasil && $this->tambahIndeks('log_penduduk', 'id_pend', 'INDEX');
        $hasil = $hasil && $this->tambahIndeks('log_penduduk', 'kode_peristiwa', 'INDEX');

        return $hasil && $this->tambahIndeks('log_penduduk', 'tgl_peristiwa', 'INDEX');
    }

    protected function migrasi_2023052453($hasil)
    {
        $result = $this->db
            ->select('id')
            ->where('parrent !=', 0)
            ->where('parrent not in (select id from kategori where parrent = 0)')
            ->get('kategori')
            ->result_array();

        if ($result) {
            $hasil = $hasil && $this->db->where_in('id', collect($result)->pluck('id')->all())->delete('kategori');
        }

        return $hasil;
    }

    protected function migrasi_2023052454($hasil)
    {
        DB::table('tweb_penduduk_asuransi')
            ->updateOrInsert(
                ['id' => 4],
                ['nama' => 'BPJS Bantuan Daerah']
            );

        return $hasil;
    }

    protected function migrasi_2023052551($hasil)
    {
        $surat = FormatSurat::withoutGlobalScope('App\Scopes\ConfigIdScope')
            ->select(['id', 'url_surat', 'kode_isian'])
            ->whereRaw("kode_isian LIKE '%rquired%'")
            ->where('jenis', FormatSurat::TINYMCE_SISTEM)
            ->get();

        foreach ($surat as $key => $value) {
            FormatSurat::whereId($value->id)
                ->update([
                    'kode_isian' => str_replace('rquired', 'required', json_encode($value->kode_isian)),
                ]);
        }

        return $hasil;
    }

    protected function migrasi_2023052951($hasil)
    {
        if (! $this->db->field_exists('No_RPJM', 'keuangan_ta_rpjm_visi')) {
            $hasil = $hasil && $this->dbforge->add_column('keuangan_ta_rpjm_visi', [
                'No_RPJM' => [
                    'type'       => 'varchar',
                    'constraint' => 100,
                    'after'      => 'No_Visi',
                ],
                'Tgl_RPJM' => [
                    'type'       => 'varchar',
                    'constraint' => 100,
                    'after'      => 'No_RPJM',
                ],
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2023053051($hasil)
    {
        return $hasil && $this->dbforge->modify_column('inventaris_gedung', [
            'kontruksi_bertingkat' => [
                'type'       => 'varchar',
                'constraint' => 255,
                'null'       => true,
            ],
            'harga' => [
                'type' => 'double',
                'null' => true,
            ],
        ]);
    }

    protected function migrasi_2023053052($hasil)
    {
        $modul = DB::table('setting_modul')->where('slug', 'administrasi-keuangan')->pluck('id')->toArray();
        DB::table('setting_modul')->whereIn('id', $modul)->delete();
        DB::table('grup_akses')->whereIn('id_modul', $modul)->delete();

        // Hapus cache menu navigasi
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        return $hasil;
    }

    protected function migrasi_2023053053($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'rentang_waktu_kehadiran',
            'judul'      => 'Rentang Waktu Kehadiran',
            'value'      => '10',
            'jenis'      => 'text',
            'attribute'  => 'class="bilangan" placeholder="10"',
            'keterangan' => 'Rentang waktu kehadiran ketika keluar. (satuan: menit)',
            'kategori'   => 'kehadiran',
        ]);
    }
}
