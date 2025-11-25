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

use App\Models\Modul;
use App\Models\SettingAplikasi;
use App\Models\Theme;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025020171
{
    use Migrator;

    public function up()
    {
        $this->tambahKolomDataFormIsian();
        $this->ubahKolomUserAgent();
        $this->tambahKolomDiArtikel();
        $this->hapusTabelRefPendudukSuku();
        $this->tambahKolomBorderDiWilayah();
        $this->dropColumnStatusProgramBantuan();
        $this->scanUlangTema();
        $this->updateDataKeuanganManualRefRek2();
        $this->setConfigIdNotNull();
        $this->tambahConstraintDokumenPenduduk();
        $this->pengaturanJumlahAduan();
        $this->hapusCredentialOpenDK();
        $this->updateUrlArsipSuratDinas();
        $this->tambahKolomArsip();
    }

    public function tambahKolomDataFormIsian()
    {
        if (! Schema::hasColumn('suplemen_terdata', 'data_form_isian')) {
            Schema::table('suplemen_terdata', static function (Blueprint $table) {
                $table->longText('data_form_isian')->nullable()->comment('Menyimpan data dinamis sebagai JSON atau teks');
            });
        }
    }

    public function ubahKolomUserAgent()
    {
        Schema::table('log_login', static function (Blueprint $table) {
            $table->text('user_agent')->change();
        });
    }

    public function tambahKolomDiArtikel()
    {
        if (! Schema::hasColumn('artikel', 'urut')) {
            Schema::table('artikel', static function (Blueprint $table) {
                $table->integer('urut')->nullable();
            });
        }

        if (! Schema::hasColumn('artikel', 'jenis_widget')) {
            Schema::table('artikel', static function (Blueprint $table) {
                $table->tinyInteger('jenis_widget')->default(3);
            });
        }
    }

    protected function hapusTabelRefPendudukSuku()
    {
        Schema::dropIfExists('ref_penduduk_suku');
    }

    public function tambahKolomBorderDiWilayah()
    {
        if (! Schema::hasColumn('tweb_wil_clusterdesa', 'border')) {
            Schema::table('tweb_wil_clusterdesa', static function (Blueprint $table) {
                $table->string('border', 25)->nullable();
            });
        }
    }

    public function dropColumnStatusProgramBantuan()
    {
        if (Schema::hasColumn('program', 'status')) {
            Schema::table('program', static function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    public function scanUlangTema()
    {
        if (Theme::whereIn('path', ['storage/app/themes/esensi', 'storage/app/themes/natra'])->count() == 0) {
            ci()->load->helper('theme');

            Theme::withoutConfigId(identitas('id'))->delete();
            theme_scan();
        }
    }

    public function updateDataKeuanganManualRefRek2()
    {
        $rek2 = DB::table('keuangan_manual_ref_rek2')
            ->where('Kelompok', '5.4.')
            ->where('Nama_Kelompok', 'Belanja Tidak Terduga')
            ->exists();

        $rek3 = DB::table('keuangan_manual_ref_rek3')
            ->where('Jenis', '5.4.1.')
            ->where('Nama_Jenis', 'Belanja Tidak Terduga')
            ->exists();

        if ($rek2) {
            // Update the existing record
            DB::table('keuangan_manual_ref_rek2')
                ->where('Kelompok', '5.4.')
                ->where('Nama_Kelompok', 'Belanja Tidak Terduga')
                ->update(['Nama_Kelompok' => 'Belanja Pemberdayaan Masyarakat']);

            // Insert a new record
            DB::table('keuangan_manual_ref_rek2')->insert([
                'Akun'          => '5.',
                'Kelompok'      => '5.5.',
                'Nama_Kelompok' => 'Belanja Tidak Terduga',
            ]);
        }

        if ($rek3) {
            // Update the existing record
            DB::table('keuangan_manual_ref_rek3')
                ->where('Jenis', '5.4.1.')
                ->update(['Nama_Jenis' => 'Belanja Pemberdayaan Masyarakat']);

            // Insert a new record
            DB::table('keuangan_manual_ref_rek3')->insert([
                'Kelompok'   => '5.5.',
                'Jenis'      => '5.5.1.',
                'Nama_Jenis' => 'Belanja Tidak Terduga',
            ]);
        }
    }

    public function tambahConstraintDokumenPenduduk()
    {
        $this->tambahForeignKey('id_pend_dokumen_fk', 'dokumen', 'id_pend', 'tweb_penduduk', 'id', true);
    }

    public function setConfigIdNotNull()
    {
        DB::table('log_penduduk')
            ->whereNull('log_penduduk.config_id')
            ->join('tweb_penduduk', 'log_penduduk.id_pend', '=', 'tweb_penduduk.id')
            ->select('log_penduduk.id', 'log_penduduk.id_pend', 'tweb_penduduk.config_id')
            ->get()->each(static function ($penduduk) {
                // Periksa apakah pembaruan akan menyebabkan duplikasi berdasarkan config_id dan id_pend
                $hasDuplicate = DB::table('log_penduduk')
                    ->where('config_id', $penduduk->config_id)
                    ->where('id_pend', $penduduk->id_pend)
                    ->exists();

                // Jika ada duplikasi, hapus data yang sudah ada; jika tidak, perbarui
                if ($hasDuplicate) {
                    DB::table('log_penduduk')
                        ->where('id', $penduduk->id)
                        ->delete();
                } else {
                    DB::table('log_penduduk')
                        ->where('id', $penduduk->id)
                        ->update(['config_id' => $penduduk->config_id]);
                }
            });

        DB::statement('ALTER TABLE `log_penduduk` CHANGE COLUMN `config_id` `config_id` INT(11) NOT NULL');
    }

    protected function pengaturanJumlahAduan()
    {
        $this->createSetting([
            'judul'      => 'Jumlah Aduan Pengguna',
            'key'        => 'jumlah_aduan_pengguna',
            'value'      => 1,
            'keterangan' => 'Jumlah aduan yang dapat diajukan oleh satu pengguna dalam satu hari',
            'jenis'      => 'input-number',
            'attribute'  => null,
            'option'     => null,
            'attribute'  => json_encode([
                'class'       => 'required',
                'min'         => 1,
                'max'         => 10,
                'step'        => 1,
                'placeholder' => '1',
            ]),
            'kategori' => 'Pengaduan',
        ]);
    }

    public function hapusCredentialOpenDK()
    {
        SettingAplikasi::whereIn('key', ['api_opendk_password', 'api_opendk_user'])->delete();
    }

    public function updateUrlArsipSuratDinas()
    {
        Modul::where('slug', 'arsip-surat-dinas')->update(['url' => 'surat_dinas_arsip']);
    }

    public function tambahKolomArsip()
    {
        if (! Schema::hasColumn('surat_keluar', 'arsip_id')) {
            Schema::table('surat_keluar', static function (Blueprint $table) {
                $table->integer('arsip_id')->nullable();
            });
        }
    }
}
