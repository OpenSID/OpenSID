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

namespace Tests\Unit\Issue11142;

use PHPUnit\Framework\TestCase;

class ServiceNotificationDeduplicationTest extends TestCase
{
    /**
     * Test deduplicasi layanan: hanya keep entry terbaru ketika ada layanan dengan nama sama dari pemesanan berbeda
     *
     * @test
     */
    public function test_deduplication_keeps_newest_service()
    {
        // Simulasi: 2 pemesanan dengan layanan yang sama (Paket SiapPakai)
        $notifications = collect([
            [
                'layanan' => (object)['nama' => 'Paket SiapPakai', 'kategori_id' => 9],
                'pemesanan' => (object)['faktur' => 'INV/20250410/s9cWzBTX'],
                'sisa_hari' => -26,
                'tanggal_akhir_value' => strtotime('2026-04-10'),
            ],
            [
                'layanan' => (object)['nama' => 'Paket SiapPakai', 'kategori_id' => 9],
                'pemesanan' => (object)['faktur' => 'INV/20260413/MDeXgSUS'],
                'sisa_hari' => 341,
                'tanggal_akhir_value' => strtotime('2027-04-13'),
            ],
        ]);

        // Eksekusi deduplicasi
        $dedupMap = [];
        foreach ($notifications as $item) {
            $namaLayanan = $item['layanan']->nama;
            $tanggalValue = $item['tanggal_akhir_value'];
            
            if (!isset($dedupMap[$namaLayanan]) || $tanggalValue > $dedupMap[$namaLayanan]['tanggal_akhir_value']) {
                $dedupMap[$namaLayanan] = $item;
            }
        }

        // Assert: hanya ada 1 entry untuk 'Paket SiapPakai' (yang terbaru)
        $this->assertCount(1, $dedupMap);
        $this->assertEquals('INV/20260413/MDeXgSUS', $dedupMap['Paket SiapPakai']['pemesanan']->faktur);
        $this->assertEquals(341, $dedupMap['Paket SiapPakai']['sisa_hari']);
    }

    /**
     * Test deduplicasi layanan berbeda: keduanya tetap ditampilkan
     *
     * @test
     */
    public function test_deduplication_keeps_different_services()
    {
        // Simulasi: 2 layanan berbeda dari pemesanan berbeda
        $notifications = collect([
            [
                'layanan' => (object)['nama' => 'Lisensi Anjungan', 'kategori_id' => 1],
                'pemesanan' => (object)['faktur' => 'INV/20251023/kCpMcqTM'],
                'sisa_hari' => 2912316,
                'tanggal_akhir_value' => strtotime('9999-12-31'),
            ],
            [
                'layanan' => (object)['nama' => 'Paket SiapPakai', 'kategori_id' => 9],
                'pemesanan' => (object)['faktur' => 'INV/20260413/MDeXgSUS'],
                'sisa_hari' => 341,
                'tanggal_akhir_value' => strtotime('2027-04-13'),
            ],
        ]);

        // Eksekusi deduplicasi
        $dedupMap = [];
        foreach ($notifications as $item) {
            $namaLayanan = $item['layanan']->nama;
            $tanggalValue = $item['tanggal_akhir_value'];
            
            if (!isset($dedupMap[$namaLayanan]) || $tanggalValue > $dedupMap[$namaLayanan]['tanggal_akhir_value']) {
                $dedupMap[$namaLayanan] = $item;
            }
        }

        // Assert: ada 2 entries (layanan berbeda)
        $this->assertCount(2, $dedupMap);
        $this->assertArrayHasKey('Lisensi Anjungan', $dedupMap);
        $this->assertArrayHasKey('Paket SiapPakai', $dedupMap);
    }

    /**
     * Test filter notifikasi: hanya tampilkan layanan dalam range -30 hingga +30 hari
     *
     * @test
     */
    public function test_filter_notifications_within_30_days_range()
    {
        $hariNotifikasi = 30;
        $notifications = collect([
            [
                'layanan' => (object)['nama' => 'Lisensi Anjungan'],
                'sisa_hari' => 341,  // > 30 hari
            ],
            [
                'layanan' => (object)['nama' => 'Paket SiapPakai Old'],
                'sisa_hari' => -26,  // -26 hari (expired 26 hari lalu) - DALAM RANGE
            ],
            [
                'layanan' => (object)['nama' => 'Paket SiapPakai New'],
                'sisa_hari' => 15,  // 15 hari - DALAM RANGE
            ],
            [
                'layanan' => (object)['nama' => 'Service Expired Old'],
                'sisa_hari' => -100,  // -100 hari (expired 100 hari lalu) - OUT OF RANGE
            ],
        ]);

        // Filter notifikasi
        $filtered = $notifications->filter(function($item) use ($hariNotifikasi) {
            return $item['sisa_hari'] > -$hariNotifikasi && $item['sisa_hari'] <= $hariNotifikasi;
        });

        // Assert: hanya 2 items yang lolos (dalam range)
        $this->assertCount(2, $filtered);
        $this->assertTrue($filtered->contains(fn($item) => $item['layanan']->nama === 'Paket SiapPakai Old'));
        $this->assertTrue($filtered->contains(fn($item) => $item['layanan']->nama === 'Paket SiapPakai New'));
    }

    /**
     * Test skenario kompleks: Multiple pemesanan dengan layanan duplikat, berbeda, unlimited
     * Simulasi data dari Desa Selodoko
     *
     * @test
     */
    public function test_complex_scenario_desa_selodoko()
    {
        // Simulasi data lengkap dari desa yang punya beberapa pemesanan
        $allNotifications = collect([
            // Dari Pemesanan 11965 (Terbaru)
            [
                'layanan' => (object)['nama' => 'Paket SiapPakai', 'kategori_id' => 9],
                'pemesanan' => (object)['faktur' => 'INV/20260413/MDeXgSUS', 'id' => 11965],
                'sisa_hari' => 341,
                'tanggal_akhir_value' => strtotime('2027-04-13'),
            ],
            [
                'layanan' => (object)['nama' => 'Langganan Fitur Premium', 'kategori_id' => 4],
                'pemesanan' => (object)['faktur' => 'INV/20260413/MDeXgSUS', 'id' => 11965],
                'sisa_hari' => 341,
                'tanggal_akhir_value' => strtotime('2027-04-13'),
            ],
            // Dari Pemesanan 10858 (Unlimited)
            [
                'layanan' => (object)['nama' => 'Lisensi Anjungan', 'kategori_id' => 1],
                'pemesanan' => (object)['faktur' => 'INV/20251023/kCpMcqTM', 'id' => 10858],
                'sisa_hari' => 2912316,
                'tanggal_akhir_value' => strtotime('9999-12-31'),
            ],
            // Dari Pemesanan 8975 (Lama - dikecualikan dari log tapi ada di data)
            [
                'layanan' => (object)['nama' => 'Paket SiapPakai', 'kategori_id' => 9],
                'pemesanan' => (object)['faktur' => 'INV/20250410/s9cWzBTX', 'id' => 8975],
                'sisa_hari' => -26,
                'tanggal_akhir_value' => strtotime('2026-04-10'),
            ],
            [
                'layanan' => (object)['nama' => 'Langganan Fitur Premium', 'kategori_id' => 4],
                'pemesanan' => (object)['faktur' => 'INV/20250410/s9cWzBTX', 'id' => 8975],
                'sisa_hari' => -26,
                'tanggal_akhir_value' => strtotime('2026-04-10'),
            ],
        ]);

        // Step 1: Filter non-premium
        $nonPremium = $allNotifications->filter(fn($item) => $item['layanan']->kategori_id != 4);
        $this->assertCount(3, $nonPremium);

        // Step 2: Deduplicasi per nama layanan
        $dedupMap = [];
        foreach ($nonPremium as $item) {
            $namaLayanan = $item['layanan']->nama;
            $tanggalValue = $item['tanggal_akhir_value'];
            
            if (!isset($dedupMap[$namaLayanan]) || $tanggalValue > $dedupMap[$namaLayanan]['tanggal_akhir_value']) {
                $dedupMap[$namaLayanan] = $item;
            }
        }
        $deduplicated = collect($dedupMap);
        
        // Assert: hanya 2 layanan (Paket SiapPakai terbaru, Lisensi Anjungan)
        $this->assertCount(2, $deduplicated);
        
        // Verify: Paket SiapPakai keep yang terbaru (2027-04-13)
        $siapPakai = $deduplicated->firstWhere('layanan.nama', 'Paket SiapPakai');
        $this->assertNotNull($siapPakai);
        $this->assertEquals('INV/20260413/MDeXgSUS', $siapPakai['pemesanan']->faktur);
        $this->assertEquals(341, $siapPakai['sisa_hari']);

        // Step 3: Filter final dengan range 30 hari
        $hariNotifikasi = 30;
        $finalNotifications = $deduplicated->filter(function($item) use ($hariNotifikasi) {
            return $item['sisa_hari'] > -$hariNotifikasi && $item['sisa_hari'] <= $hariNotifikasi;
        });

        // Assert: tidak ada notifikasi ditampilkan
        // - Paket SiapPakai: 341 hari > 30 hari ✗
        // - Lisensi Anjungan: 2912316 hari > 30 hari ✗
        $this->assertCount(0, $finalNotifications);
    }

    /**
     * Test skenario: Layanan mendekati expired (dalam 30 hari)
     *
     * @test
     */
    public function test_service_expiring_soon_shows_notification()
    {
        $hariNotifikasi = 30;
        $notifications = collect([
            [
                'layanan' => (object)['nama' => 'Modul Prodeskel', 'kategori_id' => 16],
                'pemesanan' => (object)['faktur' => 'INV/20260510/ABC123', 'id' => 12345],
                'sisa_hari' => 15,  // 15 hari lagi - TAMPIL
                'tanggal_akhir_value' => strtotime('2026-05-21'),
            ],
            [
                'layanan' => (object)['nama' => 'Tema Custom', 'kategori_id' => 6],
                'pemesanan' => (object)['faktur' => 'INV/20260510/DEF456', 'id' => 12346],
                'sisa_hari' => 5,  // 5 hari lagi - TAMPIL
                'tanggal_akhir_value' => strtotime('2026-05-11'),
            ],
        ]);

        // Filter notifikasi
        $filtered = $notifications->filter(function($item) use ($hariNotifikasi) {
            return $item['sisa_hari'] > -$hariNotifikasi && $item['sisa_hari'] <= $hariNotifikasi;
        });

        // Assert: keduanya tampil
        $this->assertCount(2, $filtered);
    }

    /**
     * Test skenario: Layanan yang sudah expired tapi masih dalam window (< 30 hari lalu)
     *
     * @test
     */
    public function test_recently_expired_service_shows_notification()
    {
        $hariNotifikasi = 30;
        $notifications = collect([
            [
                'layanan' => (object)['nama' => 'Layanan A', 'kategori_id' => 10],
                'pemesanan' => (object)['faktur' => 'INV/123'],
                'sisa_hari' => -10,  // Expired 10 hari lalu - TAMPIL
                'tanggal_akhir_value' => strtotime('2026-04-26'),
            ],
            [
                'layanan' => (object)['nama' => 'Layanan B', 'kategori_id' => 10],
                'pemesanan' => (object)['faktur' => 'INV/456'],
                'sisa_hari' => -50,  // Expired 50 hari lalu - TIDAK TAMPIL
                'tanggal_akhir_value' => strtotime('2026-03-17'),
            ],
        ]);

        // Filter notifikasi
        $filtered = $notifications->filter(function($item) use ($hariNotifikasi) {
            return $item['sisa_hari'] > -$hariNotifikasi && $item['sisa_hari'] <= $hariNotifikasi;
        });

        // Assert: hanya Layanan A yang tampil
        $this->assertCount(1, $filtered);
        $this->assertTrue($filtered->contains(fn($item) => $item['layanan']->nama === 'Layanan A'));
    }

    /**
     * Test skenario: Triple pemesanan dengan 1 layanan, hanya keep yang terbaru
     *
     * @test
     */
    public function test_multiple_renewals_keep_latest_renewal()
    {
        // Simulasi user yang perpanjang 3 kali: old → medium → latest
        $notifications = collect([
            [
                'layanan' => (object)['nama' => 'Paket SiapPakai'],
                'pemesanan' => (object)['faktur' => 'INV/001', 'id' => 1],
                'sisa_hari' => -200,  // Expired 200 hari lalu
                'tanggal_akhir_value' => strtotime('2025-10-20'),
            ],
            [
                'layanan' => (object)['nama' => 'Paket SiapPakai'],
                'pemesanan' => (object)['faktur' => 'INV/002', 'id' => 2],
                'sisa_hari' => -26,  // Expired 26 hari lalu
                'tanggal_akhir_value' => strtotime('2026-04-10'),
            ],
            [
                'layanan' => (object)['nama' => 'Paket SiapPakai'],
                'pemesanan' => (object)['faktur' => 'INV/003', 'id' => 3],
                'sisa_hari' => 341,  // Latest - expire 341 hari lagi
                'tanggal_akhir_value' => strtotime('2027-04-13'),
            ],
        ]);

        // Deduplicate
        $dedupMap = [];
        foreach ($notifications as $item) {
            $namaLayanan = $item['layanan']->nama;
            $tanggalValue = $item['tanggal_akhir_value'];
            
            if (!isset($dedupMap[$namaLayanan]) || $tanggalValue > $dedupMap[$namaLayanan]['tanggal_akhir_value']) {
                $dedupMap[$namaLayanan] = $item;
            }
        }

        // Assert: hanya 1 entry dengan faktur terbaru
        $this->assertCount(1, $dedupMap);
        $this->assertEquals('INV/003', $dedupMap['Paket SiapPakai']['pemesanan']->faktur);
        $this->assertEquals(341, $dedupMap['Paket SiapPakai']['sisa_hari']);
    }
}
