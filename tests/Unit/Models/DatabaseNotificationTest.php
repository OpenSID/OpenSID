<?php

namespace Tests\Unit\Models;

use App\Models\DatabaseNotification;
use Tests\BaseTestCase;

/**
 * Test untuk memastikan mutator getDataAttribute pada DatabaseNotification berfungsi sesuai kebutuhan
 *
 * Memverifikasi bahwa URL dalam data JSON ditransformasi dengan benar:
 * - Domain lama diganti dengan domain aplikasi saat ini
 * - Path dipertahankan dengan benar
 * - Query string dan fragment tetap tersimpan
 * - Format URL dengan /index.php atau //index.php ditangani dengan baik
 */
class DatabaseNotificationTest extends BaseTestCase
{
    /**
     * Test: URL transformation dengan single /index.php
     */
    public function test_transform_url_with_single_index_php(): void
    {
        $json = json_encode([
            'category' => 'buku_tamu',
            'label' => 'Buku Tamu',
            'icon' => 'fa-book',
            'color' => '#27ae60',
            'url' => 'https://berputar.opendesa.id/index.php/buku_tamu',
            'title' => 'Buku Tamu',
        ]);

        $notification = new DatabaseNotification(['data' => $json]);
        $data = $notification->data;

        $this->assertStringContainsString('buku_tamu', $data['url']);
        $this->assertStringNotContainsString('berputar.opendesa.id', $data['url']);
    }

    /**
     * Test: URL transformation dengan double //index.php
     */
    public function test_transform_url_with_double_index_php(): void
    {
        $json = json_encode([
            'category' => 'buku_tamu',
            'label' => 'Buku Tamu',
            'url' => 'https://berputar.opendesa.id//index.php/buku_tamu',
            'title' => 'Buku Tamu',
        ]);

        $notification = new DatabaseNotification(['data' => $json]);
        $data = $notification->data;

        $this->assertStringContainsString('buku_tamu', $data['url']);
        $this->assertStringNotContainsString('berputar.opendesa.id', $data['url']);
        $this->assertStringNotContainsString('//index.php', $data['url']);
    }

    /**
     * Test: URL transformation dengan query string
     */
    public function test_transform_url_with_query_string(): void
    {
        $json = json_encode([
            'category' => 'permohonansurat',
            'label' => 'Permohonan Surat',
            'url' => 'https://premium.test/index.php/keluar/masuk?status=0&type=draft',
            'title' => 'Permohonan Surat Masuk',
        ]);

        $notification = new DatabaseNotification(['data' => $json]);
        $data = $notification->data;

        $this->assertStringContainsString('keluar/masuk', $data['url']);
        $this->assertStringContainsString('status=0', $data['url']);
        $this->assertStringContainsString('type=draft', $data['url']);
        $this->assertStringNotContainsString('premium.test', $data['url']);
    }

    /**
     * Test: URL transformation tanpa /index.php
     */
    public function test_transform_url_without_index_php(): void
    {
        $json = json_encode([
            'category' => 'berita',
            'label' => 'Berita',
            'url' => 'https://opendesa.id/berita/terbaru',
            'title' => 'Berita Terbaru',
        ]);

        $notification = new DatabaseNotification(['data' => $json]);
        $data = $notification->data;

        $this->assertStringContainsString('berita/terbaru', $data['url']);
        $this->assertStringNotContainsString('opendesa.id', $data['url']);
    }

    /**
     * Test: Data tanpa URL field tidak memicu error
     */
    public function test_data_without_url_field(): void
    {
        $json = json_encode([
            'category' => 'notifikasi',
            'label' => 'Notifikasi',
            'title' => 'Notifikasi Penting',
        ]);

        $notification = new DatabaseNotification(['data' => $json]);
        $data = $notification->data;

        $this->assertArrayNotHasKey('url', $data);
        $this->assertEquals('notifikasi', $data['category']);
        $this->assertEquals('Notifikasi', $data['label']);
    }

    /**
     * Test: URL dengan multiple slashes ///index.php
     */
    public function test_transform_url_with_multiple_slashes(): void
    {
        $json = json_encode([
            'category' => 'surat',
            'label' => 'Surat',
            'url' => 'https://berputar.opendesa.id///index.php/surat/keluarga',
            'title' => 'Surat Keluarga',
        ]);

        $notification = new DatabaseNotification(['data' => $json]);
        $data = $notification->data;

        $this->assertStringContainsString('surat/keluarga', $data['url']);
        $this->assertStringNotContainsString('berputar.opendesa.id', $data['url']);
    }

    /**
     * Test: URL transformation menghasilkan URL dengan domain aplikasi saat ini
     */
    public function test_transformed_url_uses_current_application_domain(): void
    {
        $json = json_encode([
            'category' => 'buku_tamu',
            'label' => 'Buku Tamu',
            'url' => 'https://old-domain.com/index.php/buku_tamu/baru',
        ]);

        $notification = new DatabaseNotification(['data' => $json]);
        $data = $notification->data;

        $expectedUrl = url('buku_tamu/baru');

        $this->assertEquals($expectedUrl, $data['url']);
        $this->assertStringNotContainsString('old-domain.com', $data['url']);
    }

    /**
     * Test: Complex data structure dengan nested fields
     */
    public function test_complex_data_structure_with_nested_fields(): void
    {
        $json = json_encode([
            'category' => 'permohonansurat',
            'label' => 'Permohonan Surat Masuk',
            'icon' => 'fa-bell-o',
            'color' => '#e74c3c',
            'url' => 'https://berputar.opendesa.id//index.php/keluar/masuk',
            'title' => 'Permohonan Surat Masuk',
            'message' => 'Permohonan surat Keterangan Domisili dari KRISNA HANDOKO menunggu persetujuan',
            'data' => [
                'log_surat_id' => 8037,
                'nama_surat' => 'Keterangan Domisili',
                'pemohon' => 'KRISNA HANDOKO',
            ],
        ]);

        $notification = new DatabaseNotification(['data' => $json]);
        $data = $notification->data;

        // Verify URL transformation
        $this->assertStringContainsString('keluar/masuk', $data['url']);
        $this->assertStringNotContainsString('berputar.opendesa.id', $data['url']);

        // Verify other fields preserved
        $this->assertEquals('permohonansurat', $data['category']);
        $this->assertEquals('Permohonan Surat Masuk', $data['label']);
        $this->assertEquals('#e74c3c', $data['color']);
        $this->assertIsArray($data['data']);
        $this->assertEquals(8037, $data['data']['log_surat_id']);
    }

    /**
     * Test: URL dengan fragment (#section-1)
     */
    public function test_transform_url_with_fragment(): void
    {
        $json = json_encode([
            'category' => 'laporan',
            'label' => 'Laporan',
            'url' => 'https://opendesa.id/index.php/laporan/keuangan#section-1',
            'title' => 'Laporan Keuangan',
        ]);

        $notification = new DatabaseNotification(['data' => $json]);
        $data = $notification->data;

        $this->assertStringContainsString('laporan/keuangan', $data['url']);
        $this->assertStringNotContainsString('opendesa.id', $data['url']);
    }

    /**
     * Test: URL dengan port number
     */
    public function test_transform_url_with_port_number(): void
    {
        $json = json_encode([
            'category' => 'pengumuman',
            'label' => 'Pengumuman',
            'url' => 'https://dev.opendesa.id:8080/index.php/pengumuman/terbaru',
            'title' => 'Pengumuman Terbaru',
        ]);

        $notification = new DatabaseNotification(['data' => $json]);
        $data = $notification->data;

        $this->assertStringContainsString('pengumuman/terbaru', $data['url']);
        $this->assertStringNotContainsString('dev.opendesa.id:8080', $data['url']);
    }
}

