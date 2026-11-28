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

use App\Models\DokumenHidup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DokumenHidup>
 */
class DokumenHidupFactory extends Factory
{
    protected $model = DokumenHidup::class;

    public function definition(): array
    {
        return [
            'config_id' => 1,
            'nama'      => 'Dokumen ' . $this->faker->unique()->words(3, true),
            'attr'      => json_encode(['tgl_ditetapkan' => '2023-01-01', 'tgl_kep_kades' => '2023-02-01']),
            'enabled'   => 1,
            'kategori'  => 1,
            'id_pend'   => null,
            'deleted'   => 0,
            'tahun'     => (string) $this->faker->numberBetween(2018, 2024),
        ];
    }

    public function aktif(): static
    {
        return $this->state(fn () => ['enabled' => 1]);
    }

    public function nonAktif(): static
    {
        return $this->state(fn () => ['enabled' => 0]);
    }

    public function kategori(int $kategori): static
    {
        return $this->state(fn () => ['kategori' => $kategori]);
    }

    public function informasiPublik(): static
    {
        return $this->state(fn () => ['id_pend' => null]);
    }

    public function forConfig(int $configId): static
    {
        return $this->state(fn () => ['config_id' => $configId]);
    }
}
