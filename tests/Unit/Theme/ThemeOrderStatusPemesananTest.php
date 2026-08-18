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

namespace Tests\Unit\Theme;

use PHPUnit\Framework\TestCase;

/**
 * Regresi bug nyata (Layanan#1341): status_pemesanan melekat pada objek
 * Pemesanan INDUK, bukan tiap baris $layanan -- Theme.php::index() harus
 * memfilternya SEBELUM flatMap membuang objek induknya (sama seperti pola
 * yang sudah benar di App\Traits\ModulTrait::getLayananModul()), bukan
 * SESUDAHNYA (status_pemesanan sudah hilang setelah flatMap). Tanpa ini,
 * tema berlangganan berbatas waktu (mis. Lestari) tetap tampil
 * "sudah dipesan, bisa diunduh" walau sudah kedaluwarsa.
 */
class ThemeOrderStatusPemesananTest extends TestCase
{
    public function test_theme_order_excludes_line_items_from_inactive_pemesanan(): void
    {
        $pemesananAktif = (object) [
            'status_pemesanan' => 'aktif',
            'layanan' => [
                (object) ['nama' => 'Tema Esensi', 'nama_kategori' => 'Tema'],
            ],
        ];
        $pemesananKedaluwarsa = (object) [
            'status_pemesanan' => 'tidak aktif',
            'layanan' => [
                (object) ['nama' => 'Tema Lestari', 'nama_kategori' => 'Tema'],
            ],
        ];
        $pemesananModul = (object) [
            'status_pemesanan' => 'aktif',
            'layanan' => [
                (object) ['nama' => 'Modul Anjungan', 'nama_kategori' => 'Modul'],
            ],
        ];

        $pemesanan = collect([$pemesananAktif, $pemesananKedaluwarsa, $pemesananModul]);

        // Ekspresi sama persis dengan Theme.php::index() (setelah perbaikan).
        $themeOrder = $pemesanan
            ->filter(static fn ($item) => ($item->status_pemesanan ?? null) === 'aktif')
            ->flatMap(static fn ($item) => collect($item?->layanan ?? [])
                ->map(static fn ($layanan) => (array) $layanan))
            ->filter(static fn ($layanan) => ($layanan['nama_kategori'] ?? null) === 'Tema');

        $this->assertNotNull($themeOrder->firstWhere('nama', 'Tema Esensi'), 'Tema dari pemesanan aktif harus tetap ada');
        $this->assertNull($themeOrder->firstWhere('nama', 'Tema Lestari'), 'Tema dari pemesanan TIDAK aktif (kedaluwarsa) harus dibuang');
        $this->assertNull($themeOrder->firstWhere('nama', 'Modul Anjungan'), 'Baris kategori Modul tetap harus dibuang (filter nama_kategori)');
    }
}
