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
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_beta
{
    use Migrator;

    public function up()
    {
        $this->pengaturanHariLiburKehadiran();
        $this->tambahKolomQRCodeTte();
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
}
