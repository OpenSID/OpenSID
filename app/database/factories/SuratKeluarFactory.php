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

use App\Models\SuratKeluar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SuratKeluar>
 */
class SuratKeluarFactory extends Factory
{
    protected $model = SuratKeluar::class;

    public function definition(): array
    {
        return [
            'config_id'    => 1,
            'nomor_surat'  => (string) $this->faker->unique()->numerify('470/###/####'),
            'tujuan'       => $this->faker->company(),
            'isi_singkat'  => $this->faker->sentence(),
            'tanggal_surat'=> Carbon::now()->subDays($this->faker->numberBetween(1, 365))->toDateString(),
            'berkas_scan'  => null,
            'ekspedisi'    => 0,
        ];
    }

    /** Surat keluar yang masuk ekspedisi (dilihat model Ekspedisi). */
    public function ekspedisi(): static
    {
        return $this->state(fn () => ['ekspedisi' => 1]);
    }

    /** Punya berkas scan (untuk scope arsip fisik). */
    public function denganBerkas(): static
    {
        return $this->state(fn () => ['berkas_scan' => 'scan_' . $this->faker->uuid() . '.pdf']);
    }

    public function forConfig(int $configId): static
    {
        return $this->state(fn () => ['config_id' => $configId]);
    }
}
