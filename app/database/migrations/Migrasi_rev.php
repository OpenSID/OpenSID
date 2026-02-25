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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\GrupAkses;
use App\Models\Modul;
use App\Models\UserGrup;
use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->fixBackupRestoreSid();
    }

    public function fixBackupRestoreSid()
    {
        // tambahkan dan sesuaikan tanggal 0000-00-00 pada tabel tweb_penduduk
        $this->tanggal_invalid();

        // tambahkan kolom orientasi_layar pada tabel anjungan jika belum ada
        $this->orientasi_layar();

        // tambahkan kolom status pada tabel buku_tamu jika belum ada
        $this->status_buku_tamu();

        // tambahkan ulang FK pada tabel grup_akses jika belum ada
        $this->foreign_key_grup_akses();
    }

    public function tanggal_invalid()
    {
        // update tanggal '0000-00-00' menjadi null pada tabel tweb_penduduk kolom tanggallahir
        DB::table('tweb_penduduk')->where('tanggallahir', '0000-00-00')->update(['tanggallahir' => null]);

        // update tanggal '0000-00-00' menjadi null pada tabel tweb_penduduk kolom tanggalperkawinan
        DB::table('tweb_penduduk')->where('tanggalperkawinan', '0000-00-00')->update(['tanggalperkawinan' => null]);

        // update tanggal '0000-00-00' menjadi null pada tabel tweb_penduduk kolom tanggalperceraian
        DB::table('tweb_penduduk')->where('tanggalperceraian', '0000-00-00')->update(['tanggalperceraian' => null]);

        // update tanggal '0000-00-00' menjadi null pada tabel tweb_penduduk kolom tanggal_akhir_paspor
        DB::table('tweb_penduduk')->where('tanggal_akhir_paspor', '0000-00-00')->update(['tanggal_akhir_paspor' => null]);

        // update tanggal '0000-00-00' menjadi sekarang pada tabel surat_masuk kolom tanggal_surat, tanggal_penerimaan
        DB::table('surat_masuk')->where('tanggal_surat', '0000-00-00')->update(['tanggal_surat' => Carbon::now()->toDateString()]);
        DB::table('surat_masuk')->where('tanggal_penerimaan', '0000-00-00')->update(['tanggal_penerimaan' => Carbon::now()->toDateString()]);

        // update tanggal '0000-00-00' menjadi sekarang pada tabel surat_keluar kolom tanggal_surat
        DB::table('surat_keluar')->where('tanggal_surat', '0000-00-00')->update(['tanggal_surat' => Carbon::now()->toDateString()]);

        logger()->info('Tanggal dengan nilai 0000-00-00 telah diperbarui menjadi null atau tanggal sekarang sesuai konteksnya.');
        
        // update tanggal '0000-00-00 00:00:00' menjadi sekarang pada tabel analisis_respon_hasil kolom tgl_update
        DB::table('analisis_respon_hasil')->where('tgl_update', '0000-00-00 00:00:00')->update(['tgl_update' => Carbon::now()]);

        // update tanggal '0000-00-00 00:00:00' menjadi sekarang pada tabel outbox kolom InsertIntoDB, SendingDateTime, SendingTimeOut
        DB::table('outbox')->where('InsertIntoDB', '0000-00-00 00:00:00')->update(['InsertIntoDB' => Carbon::now()]);
        DB::table('outbox')->where('SendingDateTime', '0000-00-00 00:00:00')->update(['SendingDateTime' => Carbon::now()]);
        DB::table('outbox')->where('SendingTimeOut', '0000-00-00 00:00:00')->update(['SendingTimeOut' => Carbon::now()]);


    }

    public function orientasi_layar()
    {
        if (! Schema::hasColumn('anjungan', 'orientasi_layar')) {
            Schema::table('anjungan', static function (Blueprint $table) {
                $table->boolean('orientasi_layar')->default(1)->after('permohonan_surat_tanpa_akun');
            });

            $orientasiLayar = setting('anjungan_layar');

            DB::table('anjungan')->where('config_id', identitas('id'))->where('tipe', 1)->update([
                'orientasi_layar' => $orientasiLayar == 1,
            ]);

            DB::table('setting_aplikasi')->where('key', 'anjungan_layar')->delete();
        }
    }

    public function status_buku_tamu()
    {
        if (!Schema::hasColumn('buku_tamu', 'status')) {
            Schema::table('buku_tamu', function (Blueprint $table) {
                $table->string('status')->default('terkirim')->after('alamat');
            });
        }
    }

    public function foreign_key_grup_akses()
    {
        try {
            // Hapus data grup_akses yang tidak valid berdasarkan config_id, id_grup, dan id_modul
            $configId = identitas('id');
            $grupIds  = UserGrup::pluck('id');
            $modulIds = Modul::pluck('id');

            $cekConfigIdNotIn  = GrupAkses::whereNotIn('config_id', [$configId, null])->get()->pluck('id');
            $cekGrupNotIn      = GrupAkses::whereNotIn('id_grup', $grupIds)->get()->pluck('id');
            $cekModulNotIn     = GrupAkses::whereNotIn('id_modul', $modulIds)->get()->pluck('id');

            $idNotValid = $cekConfigIdNotIn->merge($cekGrupNotIn)->merge($cekModulNotIn);
            if ($idNotValid->isNotEmpty()) {
                GrupAkses::whereIn('id', $idNotValid)->delete();
            }
            
            // Perbaiki foreign key grup_akses jika belum ada
            if (!$this->foreignKeyExists('grup_akses', 'grup_akses_config_2026_fk')) {

                $this->hapusForeignKey('grup_akses_config_fk', 'grup_akses', 'config');

                Schema::table('grup_akses', function (Blueprint $table) {
                    $table->foreign(['config_id'], 'grup_akses_config_2026_fk')
                        ->references(['id'])
                        ->on('config')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');
                });
            }

            // Perbaiki foreign key grup_akses ke user_grup jika belum ada
            if (!$this->foreignKeyExists('grup_akses', 'grup_akses_modul_2026_fk')) {

                $this->hapusForeignKey('fk_id_grup', 'grup_akses', 'user_grup');

                Schema::table('grup_akses', function (Blueprint $table) {
                    $table->foreign(['id_grup'], 'grup_akses_user_grup_2026_fk')
                        ->references(['id'])
                        ->on('user_grup')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');
                });
            }

            // Perbaiki foreign key grup_akses ke setting_modul jika belum ada
            if (!$this->foreignKeyExists('grup_akses', 'grup_akses_modul_2026_fk')) {

                $this->hapusForeignKey('fk_id_modul', 'grup_akses', 'setting_modul');

                Schema::table('grup_akses', function (Blueprint $table) {
                    $table->foreign(['id_modul'], 'grup_akses_modul_2026_fk')
                        ->references(['id'])
                        ->on('setting_modul')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');
                });
            }
        } catch (\Exception $e) {
            logger()->error('Gagal memperbaiki foreign keys grup_akses: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
