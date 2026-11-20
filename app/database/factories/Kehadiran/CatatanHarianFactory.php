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

namespace Database\Factories\Kehadiran;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Kehadiran\Models\CatatanHarian;
use Modules\Kehadiran\Models\CatatanHarianFoto;
use Modules\Kehadiran\Enums\StatusCatatan;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Kehadiran\Models\CatatanHarian>
 */
class CatatanHarianFactory extends Factory
{
    protected $model = CatatanHarian::class;

    public function definition(): array
    {
        $tanggal = $this->faker->dateTimeBetween('-1 month', 'now');
        $carbonDate = Carbon::instance($tanggal);

        return [
            'config_id' => 1,
            'pamong_id' => $this->faker->numberBetween(1, 10),
            'tanggal' => $carbonDate->format('Y-m-d'),
            'hari' => $carbonDate->englishDayOfWeek,
            'uraian_kegiatan' => $this->faker->sentence(10),
            'hasil_diharapkan' => $this->faker->sentence(8),
            'lokasi_kegiatan' => $this->faker->address,
            'catatan_kerja' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement([
                StatusCatatan::DRAFT->value,
                StatusCatatan::MENUNGGU->value,
                StatusCatatan::DISETUJUI->value,
                StatusCatatan::DITOLAK->value,
            ]),
            'approved_by' => null,
            'approved_at' => null,
            'alasan_penolakan' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * State untuk catatan DRAFT
     */
    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => StatusCatatan::DRAFT->value,
            'approved_by' => null,
            'approved_at' => null,
            'alasan_penolakan' => null,
        ]);
    }

    /**
     * State untuk catatan MENUNGGU PERSETUJUAN
     */
    public function menunggu(): static
    {
        return $this->state(fn () => [
            'status' => StatusCatatan::MENUNGGU->value,
            'approved_by' => null,
            'approved_at' => null,
            'alasan_penolakan' => null,
        ]);
    }

    /**
     * State untuk catatan DISETUJUI
     */
    public function disetujui(): static
    {
        return $this->state(fn () => [
            'status' => StatusCatatan::DISETUJUI->value,
            'approved_by' => $this->faker->numberBetween(1, 5),
            'approved_at' => $this->faker->dateTimeThisMonth(),
            'alasan_penolakan' => null,
        ]);
    }

    /**
     * State untuk catatan DITOLAK
     */
    public function ditolak(): static
    {
        return $this->state(fn () => [
            'status' => StatusCatatan::DITOLAK->value,
            'approved_by' => null,
            'approved_at' => null,
            'alasan_penolakan' => $this->faker->sentence(5),
        ]);
    }

    /**
     * State untuk periode tertentu
     */
    public function forMonth(string $month, string $year): static
    {
        return $this->state(fn () => [
            'tanggal' => $this->faker->dateTimeBetween(
                "{$year}-{$month}-01",
                "{$year}-{$month}-28"
            )->format('Y-m-d'),
        ]);
    }

    /**
     * State untuk pamong tertentu
     */
    public function forPamong(int $pamongId): static
    {
        return $this->state(fn () => [
            'pamong_id' => $pamongId,
        ]);
    }

    /**
     * State untuk config tertentu
     */
    public function forConfig(int $configId): static
    {
        return $this->state(fn () => [
            'config_id' => $configId,
        ]);
    }
}

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Kehadiran\Models\CatatanHarianFoto>
 */
class CatatanHarianFotoFactory extends Factory
{
    protected $model = CatatanHarianFoto::class;

    public function definition(): array
    {
        return [
            'catatan_id' => null,
            'file_path' => $this->faker->image(storage_path('app/public/catatan-harian'), 640, 480, null, false),
            'config_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * State untuk catatan tertentu
     */
    public function forCatatan(CatatanHarian $catatan): static
    {
        return $this->state(fn () => [
            'catatan_id' => $catatan->id,
            'config_id' => $catatan->config_id,
        ]);
    }

    /**
     * State untuk config tertentu
     */
    public function forConfig(int $configId): static
    {
        return $this->state(fn () => [
            'config_id' => $configId,
        ]);
    }
}
