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

use App\Traits\Migrator;
use Database\Seeders\DataAwal\SettingAplikasi as SettingAplikasiSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev
{
    use Migrator;

    public function up()
    {
        $this->buatKolomConfigIdOtpToken();
        $this->ubahDataShortcut();
        $this->tambahSettingAplikasi();
        $this->tambahKolomStatusBukuTamu();
        shortcut_cache();
    }

    public function buatKolomConfigIdOtpToken()
    {
        if (! Schema::hasColumn('otp_token', 'config_id')) {
            Schema::table('otp_token', static function ($table) {
                $table->configId();
            });
        }
    }

    public function ubahDataShortcut()
    {
        DB::table('shortcut')->where('raw_query', 'Verifikasi Layanan Mandiri')->update(['raw_query' => 'Verifikasi Layanan Mandiri (Semua)']);
    }

    public function tambahSettingAplikasi()
    {
        $seeder     = new SettingAplikasiSeeder();
        $dataSeeder = collect($seeder->getData())
            ->whereNotIn('key', $seeder->unusedKeys())
            ->pluck('key')
            ->toArray();

        $dataDatabase                = SettingAplikasi::pluck('key')->toArray();
        $settingTidakAda             = array_diff($dataSeeder, $dataDatabase);
        $settingAplikasiTidakLengkap = collect($seeder->getData())->whereIn('key', $settingTidakAda)->values()->toArray();

        if (count($settingAplikasiTidakLengkap) > 0) {
            foreach ($settingAplikasiTidakLengkap as $setting) {
                $this->createSetting([
                    'judul'      => $setting['judul'],
                    'key'        => $setting['key'],
                    'value'      => $setting['value'],
                    'keterangan' => $setting['keterangan'],
                    'jenis'      => $setting['jenis'],
                    'option'     => $setting['option'],
                    'attribute'  => $setting['attribute'],
                    'kategori'   => $setting['kategori'],
                ]);

                logger()->info("Setting aplikasi '{$setting['key']}' telah ditambahkan.");
            }
        }

        (new SettingAplikasi())->flushQueryCache();
    }

    public function tambahKolomStatusBukuTamu()
    {
        if (! Schema::hasColumn('buku_tamu', 'status')) {
            Schema::table('buku_tamu', static function ($table) {
                $table->tinyInteger('status')->after('keperluan')->default(0)->comment('0: Baru, 1: Selesai');
            });
        }
    }
}
