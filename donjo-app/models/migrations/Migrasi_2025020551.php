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

use App\Enums\SumberDanaEnum;
use App\Models\Pembangunan;
use App\Scopes\ConfigIdScope;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025020551
{
    use Migrator;

    public function up()
    {
        $this->tambahKolomSumberPadaTabelPoint();
        $this->updateSumberDanaPembangunana();
        $this->updateProgramTable();
    }

    protected function tambahKolomSumberPadaTabelPoint()
    {
        if (! Schema::hasColumn('point', 'sumber')) {
            Schema::table('point', static function (Blueprint $table) {
                $table->enum('sumber', ['OpenSID', 'OpenKab'])->default('OpenSID');
            });
        }
    }

    public function updateSumberDanaPembangunana()
    {
        $enumValues = SumberDanaEnum::all();

        $mapping = [
            'Pendapatan Asli Daerah'                                        => $enumValues[SumberDanaEnum::PAD],
            'Alokasi Anggaran Pendapatan dan Belanja Negara (Dana Desa)'    => $enumValues[SumberDanaEnum::DANA_DESA],
            'Bagian Hasil Pajak Daerah dan Retribusi Daerah Kabupaten/Kota' => $enumValues[SumberDanaEnum::PAJAK_DAERAH],
            'Alokasi Dana Desa'                                             => $enumValues[SumberDanaEnum::ALOKASI_DANA_DESA],
            'Bantuan Keuangan dari APBD Provinsi dan APBD Kabupaten/Kota'   => $enumValues[SumberDanaEnum::BANTUAN_PROVINSI],
            'Hibah dan Sumbangan yang Tidak Mengikat dari Pihak Ketiga'     => $enumValues[SumberDanaEnum::BANTUAN_KAB_KOTA],
            'Lain-lain Pendapatan Desa yang Sah'                            => $enumValues[SumberDanaEnum::PENDAPATAN_LAIN],
        ];

        foreach ($mapping as $oldValue => $newValue) {
            Pembangunan::withoutGlobalScope(ConfigIdScope::class)
                ->where('sumber_dana', $oldValue)
                ->update(['sumber_dana' => $newValue]);
        }
    }

    public function updateProgramTable()
    {
        DB::table('program')->whereNull('sasaran')->update(['sasaran' => 0]);

        Schema::table('program', static function (Blueprint $table) {
            $table->integer('sasaran')->nullable(false)->change();
        });
    }
}
