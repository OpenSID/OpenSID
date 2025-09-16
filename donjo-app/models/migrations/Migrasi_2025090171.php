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

use App\Enums\AnalisisRefSubjekEnum;
use App\Enums\SasaranEnum;
use App\Enums\StatusEnum;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025090171
{
    use Migrator;

    public function up()
    {
        $this->tabelLogNotifikasiMandiri();
        $this->updatePinPendudukMandiri();
        $this->updateSuplemenTerdata();
        $this->perbaikiMigrasiModulKeuangan();
        $this->tambahKolomCatatanLogPenduduk();
        $this->perbaikiMigrasiDokumen();
        $this->updateKolomWajibPendudukTidakBolehNull();
        $this->tambahPengaturanPelaporPengaduan();
        $this->tambahKolomPekerjaMigran();
        $this->updateAnalisis();
    }

    protected function tabelLogNotifikasiMandiri()
    {
        if (! Schema::hasIndex('log_notifikasi_mandiri', 'log_notifikasi_mandiri_device_unique')) {
            return;
        }

        Schema::table('log_notifikasi_mandiri', static function (Blueprint $table) {
            $table->dropUnique('log_notifikasi_mandiri_device_unique');
        });
    }

    public function updatePinPendudukMandiri()
    {
        Schema::table('tweb_penduduk_mandiri', static function (Blueprint $table) {
            $table->string('pin')->change();
        });
    }

    protected function updateSuplemenTerdata()
    {
        if (! Schema::hasColumn('suplemen_terdata', 'penduduk_id') || ! Schema::hasColumn('suplemen_terdata', 'keluarga_id')) {
            Log::info('Migrasi 2024082651 tidak dijalankan, kolom penduduk_id atau keluarga_id tidak ditemukan.');

            return;
        }

        DB::beginTransaction();

        try {
            $config_id = identitas('id');

            // Isi penduduk_id jika sasaran = 1
            DB::table('suplemen_terdata AS st')
                ->join('tweb_penduduk AS p', static function ($join) {
                    $join->on('p.id', '=', 'st.id_terdata')
                        ->on('p.config_id', '=', 'st.config_id');
                })
                ->where('st.config_id', $config_id)
                ->where('st.sasaran', SasaranEnum::PENDUDUK)
                ->whereNull('st.penduduk_id')
                ->update([
                    'st.penduduk_id' => DB::raw('p.id'),
                ]);

            // Isi keluarga_id jika sasaran = 2
            DB::table('suplemen_terdata AS st')
                ->join('tweb_keluarga AS k', static function ($join) {
                    $join->on('k.id', '=', 'st.id_terdata')
                        ->on('k.config_id', '=', 'st.config_id');
                })
                ->where('st.config_id', $config_id)
                ->where('st.sasaran', SasaranEnum::KELUARGA)
                ->whereNull('st.keluarga_id')
                ->update([
                    'st.keluarga_id' => DB::raw('k.id'),
                ]);

            DB::commit(); // semua berhasil

        } catch (Exception $e) {
            DB::rollBack(); // batalkan semua
            Log::error('Migrasi 2024082651 gagal: ' . $e->getMessage());
        }
    }

    public function perbaikiMigrasiModulKeuangan()
    {
        require_once APPPATH . 'models/migrations/Migrasi_2025010171.php';

        (new Migrasi_2025010171())->up();
    }

    public function tambahKolomCatatanLogPenduduk()
    {
        if (! Schema::hasColumn('log_penduduk', 'catatan')) {
            Schema::table('log_penduduk', static function ($table) {
                $table->mediumText('catatan')->nullable()->after('tgl_peristiwa');
            });
        }
    }

    public function perbaikiMigrasiDokumen()
    {
        require_once APPPATH . 'models/migrations/Migrasi_2025040171.php';

        (new Migrasi_2025040171())->sesuaikanDokumenInformasiPublik();
    }

    /**
     * Set kolom-kolom wajib menjadi NOT NULL, dan set default jika diperlukan
     */
    protected function updateKolomWajibPendudukTidakBolehNull()
    {
        try {
            // isi data NULL dengan default
            DB::table('tweb_penduduk')->whereNull('nama_ayah')->where('config_id', identitas('id'))->update(['nama_ayah' => '-']);
            DB::table('tweb_penduduk')->whereNull('nama_ibu')->where('config_id', identitas('id'))->update(['nama_ibu' => '-']);
            DB::table('tweb_penduduk')->whereNull('dokumen_kitas')->where('config_id', identitas('id'))->update(['dokumen_kitas' => '-']);
            DB::table('tweb_penduduk')->whereNull('dokumen_pasport')->where('config_id', identitas('id'))->update(['dokumen_pasport' => '-']);

            Schema::table('tweb_penduduk', static function (Blueprint $table) {
                $table->string('nama')->nullable(false)->change();
                $table->string('nik')->nullable(false)->change();
                $table->unsignedTinyInteger('sex')->nullable(false)->change();
                $table->tinyInteger('kk_level')->nullable(false)->change();
                $table->string('tempatlahir')->nullable(false)->change();
                $table->date('tanggallahir')->nullable(false)->change();
                $table->integer('agama_id')->nullable(false)->change();
                $table->integer('pendidikan_kk_id')->nullable(false)->change();
                $table->integer('pekerjaan_id')->nullable(false)->change();
                $table->string('golongan_darah_id')->nullable(false)->change();
                $table->tinyInteger('status_kawin')->nullable(false)->change();
                $table->integer('warganegara_id')->nullable(false)->change();
                $table->string('nama_ayah')->default('-')->nullable(false)->change();
                $table->string('nama_ibu')->default('-')->nullable(false)->change();
                $table->string('dokumen_pasport')->default('-')->nullable(false)->change();
                $table->string('dokumen_kitas')->default('-')->nullable(false)->change();
            });
        } catch (Exception $e) {
            log_message('error', 'Gagal memperbarui kolom wajib penduduk: ' . $e->getMessage());
            set_session('warning', 'Gagal memperbarui kolom isian yang wajib pada tabel tweb_penduduk. Silakan cek dan perbaiki data pendudukan di halaman <a href="/periksa">periksa</a> sebelum jalankan migrasi lagi.');
        }
    }

    public function tambahPengaturanPelaporPengaduan()
    {
        $this->createSetting([
            'judul'      => 'Sembunyikan/sensor nama pelapor',
            'key'        => 'sembunyikan_sensor_nama_pelapor',
            'value'      => StatusEnum::YA,
            'urut'       => 2,
            'keterangan' => 'Menyembunyikan atau menyensor nama pelapor pada pengaduan yang masuk. Jika diaktifkan, nama pelapor akan disembunyikan atau disensor pada daftar pengaduan.',
            'jenis'      => 'select-boolean',
            'option'     => null,
            'kategori'   => 'Pengaduan',
            'attribute'  => json_encode([
                'class' => 'required',
            ]),
        ]);
    }

    public function tambahKolomPekerjaMigran()
    {
        if (! Schema::hasColumn('tweb_penduduk', 'pekerja_migran')) {
            Schema::table('tweb_penduduk', static function (Blueprint $table) {
                $table->string('pekerja_migran')->nullable()->after('adat');
            });
        }
    }

    public function updateAnalisis()
    {
        $this->hapusForeignKey('analisis_respon_bukti_subjek_fk', 'analisis_respon_bukti', 'analisis_ref_subjek');

        $columnMappings = [
            'penduduk_id' => 'tweb_penduduk',
            'keluarga_id' => 'tweb_keluarga',
            'kelompok_id' => 'kelompok',
            'rtm_id'      => 'tweb_rtm',
            'desa_id'     => 'config',
            'dusun_id'    => 'tweb_wil_clusterdesa',
            'rw_id'       => 'tweb_wil_clusterdesa',
            'rt_id'       => 'tweb_wil_clusterdesa',
        ];

        $subjekMappings = [
            AnalisisRefSubjekEnum::PENDUDUK     => 'penduduk_id',
            AnalisisRefSubjekEnum::KELUARGA     => 'keluarga_id',
            AnalisisRefSubjekEnum::RUMAH_TANGGA => 'rtm_id',
            AnalisisRefSubjekEnum::KELOMPOK     => 'kelompok_id',
            AnalisisRefSubjekEnum::DESA         => 'desa_id',
            AnalisisRefSubjekEnum::DUSUN        => 'dusun_id',
            AnalisisRefSubjekEnum::RW           => 'rw_id',
            AnalisisRefSubjekEnum::RT           => 'rt_id',
        ];

        $targetTables = [
            'analisis_respon',
            'analisis_respon_bukti',
            'analisis_respon_hasil',
        ];

        foreach ($targetTables as $tableName) {
            Schema::table($tableName, static function (Blueprint $table) use ($tableName, $columnMappings) {
                if (Schema::hasIndex($tableName, "{$tableName}_subjek_fk")) {
                    $table->dropIndex("{$tableName}_subjek_fk");
                }

                foreach ($columnMappings as $columnName => $referenceTable) {
                    if (! Schema::hasColumn($tableName, $columnName)) {
                        $table->integer($columnName)->nullable();
                        $table->foreign($columnName)
                            ->references('id')
                            ->on($referenceTable)
                            ->onUpdate('cascade')
                            ->onDelete('cascade');
                    }
                }
            });
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($targetTables as $table) {
            $caseUpdate = [];

            foreach ($subjekMappings as $subjekTipe => $columnName) {
                $caseUpdate[$columnName] = DB::raw("
                    CASE
                        WHEN analisis_master.subjek_tipe = {$subjekTipe} AND {$table}.{$columnName} IS NULL
                        THEN {$table}.id_subjek
                        ELSE {$table}.{$columnName}
                    END
                ");
            }

            DB::table($table)
                ->when(
                    $table === 'analisis_respon',
                    static function ($query) {
                        $query->join('analisis_periode', 'analisis_periode.id', '=', 'analisis_respon.id_periode')
                            ->join('analisis_master', 'analisis_master.id', '=', 'analisis_periode.id_master');
                    },
                    static function ($query) use ($table) {
                        $query->join('analisis_master', 'analisis_master.id', '=', "{$table}.id_master");
                    }
                )
                ->whereNotNull("{$table}.id_subjek")
                ->where("{$table}.config_id", identitas('id'))
                ->update($caseUpdate);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
