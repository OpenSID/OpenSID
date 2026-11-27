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

namespace Database\Factories;

use App\Models\LogSurat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LogSurat>
 */
class LogSuratFactory extends Factory
{
    protected $model = LogSurat::class;

    public function definition(): array
    {
        $tahun = (int) date('Y');

        return [
            'config_id'           => 1,
            'id_format_surat'     => null,
            'id_pend'             => null,
            'nama_surat'          => $this->faker->sentence(3),
            'no_surat'            => (string) $this->faker->unique()->numberBetween(1, 99999),
            'tanggal'             => Carbon::now()->toDateTimeString(),
            'tahun'               => $tahun,
            'status'              => 1,
            'verifikasi_operator' => 1,
            'input'               => json_encode(['keperluan' => $this->faker->sentence(4)]),
        ];
    }

    /**
     * State surat dengan status aktif (status = 1).
     */
    public function aktif(): static
    {
        return $this->state(fn () => ['status' => 1]);
    }

    /**
     * State surat dengan status tidak aktif (status = 0).
     */
    public function nonAktif(): static
    {
        return $this->state(fn () => ['status' => 0]);
    }

    /**
     * State surat yang ditolak operator (verifikasi_operator = -1).
     */
    public function ditolak(): static
    {
        return $this->state(fn () => ['verifikasi_operator' => -1]);
    }

    /**
     * State dengan isian input tertentu (untuk menguji accessor keterangan).
     */
    public function withInput(array $input): static
    {
        return $this->state(fn () => ['input' => json_encode($input)]);
    }

    /**
     * State untuk config tertentu.
     */
    public function forConfig(int $configId): static
    {
        return $this->state(fn () => ['config_id' => $configId]);
    }
}
