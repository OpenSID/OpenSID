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

use App\Models\SettingAplikasi;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025091351
{
    use Migrator;

    public function up()
    {
        $this->ubahRelasiUserArtikelOnDeleteSetNull();
        $this->updatePengaturanPetaStatusValue();
        $this->perbaikiIsianFormPermohonanSurat();
        $this->ubahUrutanSettingAplikasi();
    }

    protected function updatePengaturanPetaStatusValue()
    {
        try {
            // isi data NULL dengan default
            DB::table('point')->where('enabled', 2)->update(['enabled' => 0]);
            DB::table('garis')->where('enabled', 2)->update(['enabled' => 0]);
            DB::table('lokasi')->where('enabled', 2)->update(['enabled' => 0]);
            DB::table('area')->where('enabled', 2)->update(['enabled' => 0]);
            DB::table('polygon')->where('enabled', 2)->update(['enabled' => 0]);

        } catch (Exception $e) {
            log_message('error', 'Gagal memperbarui kolom enabled: ' . $e->getMessage());
        }
    }

    public function ubahRelasiUserArtikelOnDeleteSetNull()
    {
        Schema::table('artikel', static function (Blueprint $table) {
            $table->dropForeign('artikel_kategori_id_user_fk');
            $table->foreign('id_user', 'artikel_kategori_id_user_fk')
                ->references('id')
                ->on('user')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function perbaikiIsianFormPermohonanSurat()
    {
        $permohonanSuratList = DB::table('permohonan_surat')
            ->select('id', 'isian_form')
            ->whereNotNull('isian_form')
            ->where('isian_form', '!=', '')
            ->where('isian_form', 'like', '"%')
            ->where('config_id', identitas('id'))
            ->get();

        foreach ($permohonanSuratList as $permohonan) {
            $firstDecode = json_decode($permohonan->isian_form, true);

            if (is_string($firstDecode)) {
                $secondDecode = json_decode($firstDecode, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($secondDecode)) {
                    DB::table('permohonan_surat')
                        ->where('id', $permohonan->id)
                        ->where('config_id', identitas('id'))
                        ->update(['isian_form' => json_encode($secondDecode, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);
                }
            }
        }
    }

    public function ubahUrutanSettingAplikasi()
    {
        $settings = [
            // Umum
            'website_title'        => 1,
            'login_title'          => 2,
            'motto_desa'           => 3,
            'web_artikel_per_page' => 4,

            // Mobile
            'branding_desa' => 5,

            // Admin
            'admin_title' => 6,

            // Sebutan
            'sebutan_desa'              => 7,
            'sebutan_kecamatan'         => 8,
            'sebutan_kecamatan_singkat' => 9,
            'sebutan_camat'             => 10,
            'sebutan_kabupaten'         => 11,
            'sebutan_kabupaten_singkat' => 12,
            'sebutan_provinsi'          => 13,
            'sebutan_provinsi_singkat'  => 14,

            // Umum lanjutan
            'timezone'         => 15,
            'warna_tema_admin' => 16,
            'enable_track'     => 17,
            'offline_mode'     => 18,
            'inspect_element'  => 19,

            // Google ReCAPTCHA
            'google_recaptcha'            => 20,
            'google_recaptcha_site_key'   => 21,
            'google_recaptcha_secret_key' => 22,

            // Notifikasi
            // Email
            'email_notifikasi' => 23,
            'email_protocol'   => 24,
            'email_smtp_host'  => 25,
            'email_smtp_user'  => 26,
            'email_smtp_pass'  => 27,
            'email_smtp_port'  => 28,

            // Telegram
            'telegram_notifikasi' => 29,
            'telegram_token'      => 30,
            'telegram_user_id'    => 31,

            'notifikasi_reset_pin'       => 32,
            'notifikasi_pengajuan_surat' => 34,
            'notifikasi_koneksi'         => 33,

            'current_version' => 35, // urutan terakhir
        ];

        foreach ($settings as $key => $urut) {
            DB::table('setting_aplikasi')
                ->where('key', $key)
                ->update(['urut' => $urut]);
        }

        DB::table('setting_aplikasi')
            ->where('key', 'libreoffice_path')
            ->delete();

        DB::table('setting_aplikasi')
            ->where('key', 'sebutan_nip_desa')
            ->update(['kategori' => 'Pemerintah Desa', 'urut' => 5]);

        (new SettingAplikasi())->flushQueryCache();
    }
}
