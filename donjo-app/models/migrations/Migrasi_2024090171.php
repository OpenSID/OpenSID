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
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024090171 extends MY_Model
{
    use Migrator;

    public function up()
    {
        $this->migrasi_2024080851();
        $this->migrasi_2024080752();
        $this->migrasi_2024080753();
        $this->migrasi_2024081252();
        $this->migrasi_2024081151();
        $this->migrasi_2024080852();
        $this->migrasi_2024081651();
        $this->migrasi_2024082051();
        $this->migrasi_2024082651();
        $this->migrasi_2024082751();
        $this->migrasi_2024080651();
        $this->migrasi_2024082951();
        $this->migrasi_2024083051();
        $this->migrasi_2024083052();
    }

    protected function migrasi_2024080851()
    {
        // mutasi_inventaris_peralatan
        $this->hapus_foreign_key('inventaris_peralatan', 'FK_mutasi_inventaris_peralatan', 'mutasi_inventaris_peralatan');
        $this->tambahForeignKey('FK_mutasi_inventaris_peralatan', 'mutasi_inventaris_peralatan', 'id_inventaris_peralatan', 'inventaris_peralatan', 'id', true);
        // mutasi_inventaris_jalan
        $this->hapus_foreign_key('inventaris_jalan', 'FK_mutasi_inventaris_jalan', 'mutasi_inventaris_jalan');
        $this->tambahForeignKey('FK_mutasi_inventaris_jalan', 'mutasi_inventaris_jalan', 'id_inventaris_jalan', 'inventaris_jalan', 'id', true);
        // mutasi_inventaris_gedung
        $this->hapus_foreign_key('inventaris_gedung', 'FK_mutasi_inventaris_gedung', 'mutasi_inventaris_gedung');
        $this->tambahForeignKey('FK_mutasi_inventaris_gedung', 'mutasi_inventaris_gedung', 'id_inventaris_gedung', 'inventaris_gedung', 'id', true);
        // mutasi_inventaris_asset
        $this->hapus_foreign_key('inventaris_asset', 'FK_mutasi_inventaris_asset', 'mutasi_inventaris_asset');

        $this->tambahForeignKey('FK_mutasi_inventaris_asset', 'mutasi_inventaris_asset', 'id_inventaris_asset', 'inventaris_asset', 'id', true);
    }

    protected function migrasi_2024080752()
    {
        // sebenarnya constraint ini sudah ada, barangkali ada db yang gagal membuat constraint ini.
        $this->hapus_foreign_key('suplemen', 'suplemen_terdata_suplemen_1', 'suplemen_terdata');
        $this->hapus_foreign_key('suplemen', 'suplemen_terdata_suplemen_fk', 'suplemen_terdata');

        $this->tambahForeignKey('suplemen_terdata_suplemen_fk', 'suplemen_terdata', 'id_suplemen', 'suplemen', 'id', true);
    }

    protected function migrasi_2024080753()
    {
        $cek = count(DB::select("SHOW INDEX FROM kelompok WHERE Key_name = 'slug_config'"));

        if ($cek) {
            Schema::table('kelompok', static function (Blueprint $table) {
                $table->dropIndex('slug_config');
                $table->unique(['slug', 'config_id'], 'slug_config_tipe');
            });
        }

    }

    public function migrasi_2024080852()
    {
        $daftarKomentar = DB::table('komentar')->whereNull('id_artikel')->get();

        foreach ($daftarKomentar as $komentar) {
            $penduduk_id = DB::table('tweb_penduduk')->where('nik', $komentar->email)->value('id');
            if ($penduduk_id && $komentar->config_id) {
                DB::table('pesan_mandiri')->insert([
                    'uuid'        => Str::uuid(),
                    'config_id'   => $komentar->config_id,
                    'owner'       => $komentar->owner,
                    'penduduk_id' => $penduduk_id,
                    'subjek'      => $komentar->subjek,
                    'komentar'    => $komentar->komentar,
                    'tgl_upload'  => $komentar->tgl_upload,
                    'status'      => $komentar->status,
                    'tipe'        => $komentar->tipe,
                    'permohonan'  => $komentar->permohonan,
                    'created_at'  => $komentar->tgl_upload ?? now(),
                    'updated_at'  => $komentar->updated_at ?? now(),
                    'is_archived' => $komentar->is_archived,
                ]);
            }
            DB::table('komentar')->where('id', $komentar->id)->delete();
        }

    }

    protected function migrasi_2024081151()
    {
        if (! $this->db->field_exists('remember_token', 'user')) {
            $this->dbforge->add_column('user', [
                'remember_token' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'password',
                ],
            ]);
        }

    }

    protected function migrasi_2024081252()
    {
        if (DB::table('tweb_penduduk')->whereNull('tanggallahir')->exists()) {
            log_message('error', 'Terdapat data tanggallahir yang null pada tabel tweb_penduduk');
        } else {
            Schema::table('tweb_penduduk', static function (Blueprint $table) {
                $table->date('tanggallahir')->nullable(false)->change();
            });
        }

    }

    protected function migrasi_2024081651()
    {
        $tables = [
            'keuangan_ta_spp',
            'keuangan_ta_sppbukti',
            'keuangan_ta_spp',
            'keuangan_ta_jurnal_umum',
            'keuangan_ta_mutasi',
            'keuangan_ta_pajak',
            'keuangan_ta_pencairan',
            'keuangan_ta_spj',
            'keuangan_ta_spj_bukti',
            'keuangan_ta_spp',
        ];

        foreach ($tables as $table) {
            Schema::table($table, static function (Blueprint $table) {
                $table->text('Keterangan')->nullable()->change();
            });
        }

    }

    protected function migrasi_2024082051()
    {
        if (! Schema::hasColumn('log_surat', 'isi_surat_temp')) {
            Schema::table('log_surat', static function (Blueprint $table) {
                $table->longText('isi_surat_temp')->nullable()->after('isi_surat');
            });
        }

    }

    protected function migrasi_2024080651()
    {
        if (! Schema::hasColumn('config', 'nama_kontak')) {
            Schema::table('config', static function (Blueprint $table) {
                $table->string('nama_kontak', 80)->nullable();
                $table->string('hp_kontak', 20)->nullable();
                $table->string('jabatan_kontak', 80)->nullable();
            });
        }

    }

    protected function migrasi_2024082651()
    {
        if (! $this->db->field_exists('penduduk_id', 'suplemen_terdata')) {
            $this->dbforge->add_column('suplemen_terdata', [
                'penduduk_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'id_terdata'],
                'keluarga_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'id_terdata'],
            ]);

            $this->tambahForeignKey('suplemen_terdata_penduduk_fk', 'suplemen_terdata', 'penduduk_id', 'tweb_penduduk', 'id', true);
            $this->tambahForeignKey('suplemen_terdata_keluarga_fk', 'suplemen_terdata', 'keluarga_id', 'tweb_keluarga', 'id', true);
        }

        $config_id = identitas('id');

        DB::table('suplemen_terdata')
            ->where('config_id', $config_id)
            ->update([
                'penduduk_id' => DB::raw("
                    case
                        when sasaran = 1 then (select id from tweb_penduduk where config_id = {$config_id} and tweb_penduduk.id = suplemen_terdata.id_terdata)
                    end
                "),
                'keluarga_id' => DB::raw("
                    case
                        when sasaran = 2 then (select id from tweb_keluarga where config_id = {$config_id} and tweb_keluarga.id = suplemen_terdata.id_terdata)
                    end
                "),
            ]);

    }

    protected function migrasi_2024082751()
    {
        DB::table('kelompok_anggota')
            ->join('kelompok', 'kelompok_anggota.id_kelompok', '=', 'kelompok.id')
            ->where('kelompok_anggota.config_id', identitas('id'))
            ->whereColumn('kelompok_anggota.tipe', '!=', 'kelompok.tipe')
            ->update([
                'kelompok_anggota.tipe' => DB::raw('kelompok.tipe'),
            ]);

    }

    protected function migrasi_2024082951()
    {
        if (! Schema::hasTable('log_login')) {
            $this->runMigration([
                '2023_12_22_015242_create_log_login_table.php',
                '2023_12_22_015245_add_foreign_keys_to_log_login_table.php',
            ]);
        }

    }

    protected function migrasi_2024083051()
    {
        (new Filesystem())->copyDirectory('vendor/tecnickcom/tcpdf/fonts', LOKASI_FONT_DESA);

    }

    protected function migrasi_2024083052()
    {
        if (! $this->db->field_exists('foto', 'kelompok_anggota')) {
            $this->dbforge->add_column('kelompok_anggota', [
                'foto' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            ]);
        }

    }
}
