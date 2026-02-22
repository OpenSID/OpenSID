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

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2026030101
{
    public function up()
    {
        if (! Schema::hasTable('kelompok_anggota')) {
            return;
        }

        $this->tambahKolomSumberAnggota();
        $this->ubahKolomIdPendudukNullable();
        $this->tambahKolomAnggotaLuarDesa();
        $this->migrasiDataSumberAnggota();
    }

    protected function tambahKolomSumberAnggota()
    {
        if (! Schema::hasColumn('kelompok_anggota', 'sumber_anggota')) {
            Schema::table('kelompok_anggota', static function (Blueprint $table) {
                $table->enum('sumber_anggota', ['penduduk', 'luar_desa'])->default('penduduk')->after('id_penduduk');
            });
        }
    }

    protected function ubahKolomIdPendudukNullable()
    {
        if (! Schema::hasColumn('kelompok_anggota', 'id_penduduk')) {
            return;
        }

        $columnType = DB::table('information_schema.columns')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'kelompok_anggota')
            ->where('column_name', 'id_penduduk')
            ->value('COLUMN_TYPE');

        if (! $columnType) {
            return;
        }

        DB::statement("ALTER TABLE `kelompok_anggota` MODIFY `id_penduduk` {$columnType} NULL");
    }

    protected function tambahKolomAnggotaLuarDesa()
    {
        $missingColumns = array_filter([
            'nama_luar'        => ! Schema::hasColumn('kelompok_anggota', 'nama_luar'),
            'nik_luar'         => ! Schema::hasColumn('kelompok_anggota', 'nik_luar'),
            'sex_luar'         => ! Schema::hasColumn('kelompok_anggota', 'sex_luar'),
            'alamat_luar'      => ! Schema::hasColumn('kelompok_anggota', 'alamat_luar'),
            'tempatlahir_luar' => ! Schema::hasColumn('kelompok_anggota', 'tempatlahir_luar'),
            'tanggallahir_luar'=> ! Schema::hasColumn('kelompok_anggota', 'tanggallahir_luar'),
        ]);

        if ($missingColumns === []) {
            return;
        }

        Schema::table('kelompok_anggota', static function (Blueprint $table) use ($missingColumns) {
            if (isset($missingColumns['nama_luar'])) {
                $table->string('nama_luar', 100)->nullable();
            }

            if (isset($missingColumns['nik_luar'])) {
                $table->string('nik_luar', 16)->nullable();
            }

            if (isset($missingColumns['sex_luar'])) {
                $table->unsignedTinyInteger('sex_luar')->nullable();
            }

            if (isset($missingColumns['alamat_luar'])) {
                $table->string('alamat_luar', 200)->nullable();
            }

            if (isset($missingColumns['tempatlahir_luar'])) {
                $table->string('tempatlahir_luar', 100)->nullable();
            }

            if (isset($missingColumns['tanggallahir_luar'])) {
                $table->date('tanggallahir_luar')->nullable();
            }
        });
    }

    protected function migrasiDataSumberAnggota()
    {
        if (! Schema::hasColumn('kelompok_anggota', 'sumber_anggota')) {
            return;
        }

        DB::table('kelompok_anggota')
            ->whereNull('sumber_anggota')
            ->orWhereNotIn('sumber_anggota', ['penduduk', 'luar_desa'])
            ->update(['sumber_anggota' => 'penduduk']);
    }
}
