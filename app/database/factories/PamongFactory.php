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

use App\Models\Pamong;
use App\Models\Penduduk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pamong>
 */
class PamongFactory extends Factory
{
    protected $model = Pamong::class;

    public function definition(): array
    {
        return [
            'config_id'      => 1,
            'pamong_nama'    => $this->faker->name(),
            'pamong_nik'     => (string) $this->faker->unique()->numerify('33##############'),
            'pamong_status'  => Pamong::LOCK,
            'jabatan_id'     => 1,
            'id_pend'        => null,
            'pamong_sex'     => $this->faker->randomElement([1, 2]),
            'urut'           => $this->faker->numberBetween(1, 50),
            'kehadiran'      => 1,
            'gelar_depan'    => null,
            'gelar_belakang' => null,
        ];
    }

    /**
     * State pamong aktif (pamong_status = LOCK).
     */
    public function aktif(): static
    {
        return $this->state(fn () => ['pamong_status' => Pamong::LOCK]);
    }

    /**
     * State pamong tidak aktif.
     */
    public function tidakAktif(): static
    {
        return $this->state(fn () => ['pamong_status' => Pamong::UNLOCK]);
    }

    /**
     * Pamong yang tertaut ke data penduduk.
     */
    public function forPenduduk(Penduduk $penduduk): static
    {
        return $this->state(fn () => ['id_pend' => $penduduk->id]);
    }

    /**
     * State jabatan tertentu.
     */
    public function jabatan(int $jabatanId): static
    {
        return $this->state(fn () => ['jabatan_id' => $jabatanId]);
    }

    /**
     * State untuk config tertentu.
     */
    public function forConfig(int $configId): static
    {
        return $this->state(fn () => ['config_id' => $configId]);
    }
}
