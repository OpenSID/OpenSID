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

use App\Models\FormatSurat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormatSurat>
 */
class FormatSuratFactory extends Factory
{
    protected $model = FormatSurat::class;

    public function definition(): array
    {
        $nama = 'Surat ' . $this->faker->unique()->words(2, true);

        return [
            'config_id'           => 1,
            'nama'                => $nama,
            'url_surat'           => $this->faker->unique()->slug(2),
            'jenis'               => FormatSurat::TINYMCE_SISTEM, // 3 (bukan RTF [1,2], lolos RemoveRtfScope)
            'kunci'               => 0,
            'favorit'             => 0,
            'mandiri'             => 0,
            'margin'              => json_encode(['kiri' => 2, 'atas' => 2, 'kanan' => 2, 'bawah' => 2]),
            'format_nomor'        => '[nomor_surat]/[kode_surat]/[tahun]',
            'format_nomor_global' => 1,
            'kode_isian'          => '[]',
            'form_isian'          => '[]',
        ];
    }

    public function mandiri(): static
    {
        return $this->state(fn () => ['mandiri' => FormatSurat::MANDIRI]);
    }

    public function favorit(): static
    {
        return $this->state(fn () => ['favorit' => FormatSurat::FAVORIT]);
    }

    public function terkunci(): static
    {
        return $this->state(fn () => ['kunci' => FormatSurat::KUNCI]);
    }

    public function jenis(int $jenis): static
    {
        return $this->state(fn () => ['jenis' => $jenis]);
    }

    public function forConfig(int $configId): static
    {
        return $this->state(fn () => ['config_id' => $configId]);
    }
}
