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

use App\Enums\AktifEnum;
use App\Traits\Migrator;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\DataAwal\SettingAplikasi as SettingAplikasiSeeder;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev
{
    use Migrator;

    public function up()
    {

        $this->buatKolomConfigIdOtpToken();
        $this->ubahDataShortcut();
        $this->tambahSettingAplikasi();
        $this->tambahPengaturanMasaAktifTidakAktif();
        $this->allowNullSyaratPermohonanSurat();
        shortcut_cache();
    }

    /**
     * Menambahkan pengaturan masa berlaku akun tidak aktif.
     *
     * @return void
     */
    public function tambahPengaturanMasaAktifTidakAktif()
    {
        $this->createSetting([
            'judul'      => 'Masa Akun untuk Login',
            'key'        => 'masa_akun_pengguna',
            'value'      => AktifEnum::AKTIF,
            'keterangan' => 'Aktifkan masa berlaku akun pengguna untuk login.',
            'jenis'      => 'boolean',
            'option'     => null,
            'kategori'   => 'auth',
            'urut'       => 1,
            'attribute'  => null,
        ]);

         $this->createSetting([
             'judul'      => 'Masa akun tidak aktif (hari)',
             'key'        => 'masa_akun_tidak_aktif',
             'value'      => 30,
             'keterangan' => 'Batas waktu dalam hari sebuah akun pengguna dianggap tidak aktif. Setelah melewati batas ini, akun dapat dinonaktifkan secara otomatis oleh sistem.',
             'jenis'      => 'input-number',
             'option'     => null,
             'kategori'   => 'auth',
            'urut'       => 2,
            'attribute'  => json_encode([
                 'class' => 'required',
                 'min'   => 1,
                 'step'  => 1,
             ]),
         ]);

         $this->createSetting([
            'judul'      => 'Trigger Nonaktifkan Akun Otomatis',
            'key'        => 'jenis_trigger_nonaktifkan_akun',
            'value'      => 'manual',
            'keterangan' => 'Trigger untuk menjalankan proses nonaktifkan akun otomatis berdasarkan masa tidak aktif.',
            'jenis'      => 'option',
            'option'     => json_encode([
                'manual' => 'Manual',
                'cron'   => 'Cron Job',
            ]),
            'kategori'   => 'auth',
            'urut'       => 3,
            'attribute'  => null,
        ]);
    }


    public function buatKolomConfigIdOtpToken()
    {
        if(!Schema::hasColumn('otp_token', 'config_id')){
            Schema::table('otp_token', function ($table) {
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

        $dataDatabase    = SettingAplikasi::pluck('key')->toArray();
        $settingTidakAda = array_diff($dataSeeder, $dataDatabase);
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

    public function allowNullSyaratPermohonanSurat()
    {
        Schema::table('permohonan_surat', function ($table) {
            $table->text('syarat')->nullable()->change();
        });

        // bersihkan data syarat yang tidak valid menjadi null
        DB::table('permohonan_surat')
            ->where(function ($query) {
                $query
                    ->where('syarat', 'null')
                    ->orWhere('syarat', '"null"')
                    ->orWhere('syarat', '{}')
                    ->orWhere('syarat', '"{}"')
                    ->orWhere('syarat', '[]')
                    ->orWhere('syarat', '"[]"');
            })
            ->where('config_id', identitas('id'))
            ->update(['syarat' => null]);
    }
}

