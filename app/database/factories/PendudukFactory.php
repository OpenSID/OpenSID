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

use App\Enums\JenisKelaminEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penduduk>
 */
class PendudukFactory extends Factory
{
    protected $model = Penduduk::class;

    public function definition(): array
    {
        return [
            'config_id'      => 1,
            'nama'           => $this->faker->name,
            'nik'            => (string) $this->faker->unique()->numerify('33##############'),
            'sex'            => $this->faker->randomElement([JenisKelaminEnum::LAKI_LAKI, JenisKelaminEnum::PEREMPUAN]),
            'tempatlahir'    => $this->faker->city,
            'tanggallahir'   => $this->faker->dateTimeBetween('-70 years', '-17 years')->format('Y-m-d'),
            'agama_id'       => $this->faker->numberBetween(1, 6),
            'kk_level'       => SHDKEnum::KEPALA_KELUARGA,
            'status'         => 1,
            'status_dasar'   => StatusDasarEnum::HIDUP,
            'warganegara_id' => 1,
            'created_by'     => 1,
            'updated_by'     => 1,
        ];
    }

    /**
     * State penduduk hidup (status_dasar = HIDUP).
     */
    public function hidup(): static
    {
        return $this->state(fn () => ['status_dasar' => StatusDasarEnum::HIDUP]);
    }

    /**
     * State penduduk mati (status_dasar = MATI).
     */
    public function mati(): static
    {
        return $this->state(fn () => ['status_dasar' => StatusDasarEnum::MATI]);
    }

    /**
     * State penduduk aktif (status = 1).
     */
    public function aktif(): static
    {
        return $this->state(fn () => ['status' => 1]);
    }

    /**
     * State penduduk tidak aktif (status = 0).
     */
    public function tidakAktif(): static
    {
        return $this->state(fn () => ['status' => 0]);
    }

    /**
     * State laki-laki.
     */
    public function lakiLaki(): static
    {
        return $this->state(fn () => ['sex' => JenisKelaminEnum::LAKI_LAKI]);
    }

    /**
     * State perempuan.
     */
    public function perempuan(): static
    {
        return $this->state(fn () => ['sex' => JenisKelaminEnum::PEREMPUAN]);
    }

    /**
     * State kepala keluarga (kk_level = 1).
     */
    public function kepalaKeluarga(): static
    {
        return $this->state(fn () => ['kk_level' => SHDKEnum::KEPALA_KELUARGA]);
    }

    /**
     * State anak (kk_level = 4).
     */
    public function anak(): static
    {
        return $this->state(fn () => ['kk_level' => SHDKEnum::ANAK]);
    }

    /**
     * State suami (kk_level = SUAMI, laki-laki).
     */
    public function suami(): static
    {
        return $this->state(fn () => [
            'kk_level' => SHDKEnum::SUAMI,
            'sex'      => JenisKelaminEnum::LAKI_LAKI,
        ]);
    }

    /**
     * State istri (kk_level = ISTRI, perempuan).
     */
    public function istri(): static
    {
        return $this->state(fn () => [
            'kk_level' => SHDKEnum::ISTRI,
            'sex'      => JenisKelaminEnum::PEREMPUAN,
        ]);
    }

    /**
     * State untuk keluarga tertentu (set id_kk + ikut config & cluster keluarga).
     */
    public function forKeluarga(Keluarga $keluarga): static
    {
        return $this->state(fn () => [
            'config_id'  => $keluarga->config_id,
            'id_kk'      => $keluarga->id,
            'id_cluster' => $keluarga->id_cluster,
        ]);
    }

    /**
     * State untuk wilayah/cluster tertentu.
     */
    public function forWilayah(Wilayah $wilayah): static
    {
        return $this->state(fn () => [
            'config_id'  => $wilayah->config_id,
            'id_cluster' => $wilayah->id,
        ]);
    }

    /**
     * State untuk config tertentu.
     */
    public function forConfig(int $configId): static
    {
        return $this->state(fn () => ['config_id' => $configId]);
    }
}
