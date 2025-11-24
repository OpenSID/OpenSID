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

use App\Enums\StatusEnum;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_beta
{
    use Migrator;

    public function up()
    {
        $this->pengaturanHariLiburKehadiran();
        $this->tambahKolomQRCodeTte();
        $this->pindahkanPengaturanLayarAnjungan();
        $this->migrateAnjunganTipeToArray();

        cache()->flush();
    }

    public function pengaturanHariLiburKehadiran()
    {
        $this->createSetting([
            'judul'      => 'Ikuti Hari Libur Terdaftar',
            'key'        => 'ikuti_hari_libur_terdaftar',
            'value'      => StatusEnum::TIDAK,
            'urut'       => 10,
            'keterangan' => 'Jika diaktifkan, jam kerja akan otomatis berubah menjadi "Libur" ketika bertepatan dengan hari libur terdaftar.',
            'jenis'      => 'select-boolean',
            'option'     => null,
            'kategori'   => 'Kehadiran',
            'attribute'  => json_encode([
                'class' => 'required',
            ]),
        ]);
    }

    public function tambahKolomQRCodeTte()
    {
        try {
            if (! Schema::hasColumn('tweb_surat_format', 'qr_code_tte')) {
                Schema::table('tweb_surat_format', static function (Blueprint $table) {
                    $table->boolean('qr_code_tte')->default(false)->after('qr_code');
                });
                Log::info('Berhasil menambahkan kolom qr_code_tte pada tabel tweb_surat_format.');
            }
        } catch (Exception $e) {
            Log::error('Gagal menambahkan kolom qr_code_tte: ' . $e->getMessage());
        }
    }

    public function pindahkanPengaturanLayarAnjungan()
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

    private function migrateAnjunganTipeToArray()
    {
        Schema::table('anjungan', static function (Blueprint $table) {
            $table->text('tipe')->nullable()->change();
        });

        DB::table('anjungan')
            ->whereRaw("CAST(tipe AS CHAR) REGEXP '^[0-9]+$'")
            ->where('config_id', identitas('id'))
            ->update([
                'tipe' => DB::raw("CONCAT('[', tipe, ']')"),
            ]);
    }
}
