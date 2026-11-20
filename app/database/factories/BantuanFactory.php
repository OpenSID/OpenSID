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

use App\Enums\SasaranEnum;
use App\Models\Bantuan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bantuan>
 */
class BantuanFactory extends Factory
{
    protected $model = Bantuan::class;

    public function definition(): array
    {
        return [
            'config_id' => 1,
            'nama'      => 'Program ' . $this->faker->unique()->words(3, true),
            'sasaran'   => SasaranEnum::PENDUDUK,
            'ndesc'     => $this->faker->sentence,
            'sdate'     => Carbon::now()->subMonths(2)->toDateString(),
            'edate'     => Carbon::now()->addMonths(2)->toDateString(),
        ];
    }

    /**
     * State program yang masih dalam masa aktif (sdate <= hari ini <= edate).
     */
    public function aktif(): static
    {
        return $this->state(fn () => [
            'sdate' => Carbon::now()->subMonth()->toDateString(),
            'edate' => Carbon::now()->addMonth()->toDateString(),
        ]);
    }

    /**
     * State program yang sudah berakhir (edate di masa lalu).
     */
    public function sudahBerakhir(): static
    {
        return $this->state(fn () => [
            'sdate' => Carbon::now()->subMonths(6)->toDateString(),
            'edate' => Carbon::now()->subMonths(3)->toDateString(),
        ]);
    }

    /**
     * State program yang belum mulai (sdate di masa depan).
     */
    public function belumMulai(): static
    {
        return $this->state(fn () => [
            'sdate' => Carbon::now()->addMonth()->toDateString(),
            'edate' => Carbon::now()->addMonths(3)->toDateString(),
        ]);
    }

    /**
     * State untuk sasaran tertentu (penduduk/keluarga/rtm/kelompok).
     */
    public function forSasaran(int $sasaran): static
    {
        return $this->state(fn () => ['sasaran' => $sasaran]);
    }

    /**
     * State untuk config tertentu.
     */
    public function forConfig(int $configId): static
    {
        return $this->state(fn () => ['config_id' => $configId]);
    }
}
