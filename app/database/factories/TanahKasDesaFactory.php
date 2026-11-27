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

use App\Enums\AsalTanahKasEnum;
use App\Enums\PeruntukanTanahKasEnum;
use App\Models\TanahKasDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TanahKasDesa>
 */
class TanahKasDesaFactory extends Factory
{
    protected $model = TanahKasDesa::class;

    public function definition(): array
    {
        return [
            'config_id'         => 1,
            'nama_pemilik_asal' => AsalTanahKasEnum::APB_DESA->value,
            'peruntukan'        => PeruntukanTanahKasEnum::SEWA->value,
            'kelas'             => 'A',
            'letter_c'          => (string) $this->faker->unique()->numberBetween(1, 99999),
            'lokasi'            => $this->faker->streetName(),
            'luas'              => $this->faker->numberBetween(100, 10000),
            'mutasi'            => 'Tetap',
            'keterangan'        => $this->faker->sentence(),
            'visible'           => 1,
        ];
    }

    public function visible(int $value = 1): static
    {
        return $this->state(fn () => ['visible' => $value]);
    }

    public function letterC(string $letterC): static
    {
        return $this->state(fn () => ['letter_c' => $letterC]);
    }

    public function forConfig(int $configId): static
    {
        return $this->state(fn () => ['config_id' => $configId]);
    }
}
