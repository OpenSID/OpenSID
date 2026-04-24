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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Unit\Bugs\Issue11066;

use App\Models\Config;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use Tests\BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Unit Test untuk Bug #11066 - Data sebutan desa berubah menjadi desa saat ubah identitas desa
 *
 * Bug: Saat mengubah identitas desa, field "sebutan desa" selalu berubah menjadi "desa" tanpa peduli
 *      field tersebut diubah atau tidak.
 *
 * Cause: Observer updating() pada Config model SELALU memanggil updateOtomatisKelurahan()
 *        yang mengubah sebutan_desa berdasarkan status desa/kelurahan, tanpa menyimpan nilai sebelumnya.
 *
 * Fix: Hanya panggil updateOtomatisKelurahan() saat creating atau ketika kode_desa berubah (isDirty('kode_desa'))
 */
class ConfigSebutanDesaUnitTest extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Seed SettingAplikasi dan RefJabatan sebelum setiap test
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Seed SettingAplikasi untuk sebutan_desa dan sebutan_kepala_desa
        SettingAplikasi::create(['key' => 'sebutan_desa', 'value' => 'Desa']);
        SettingAplikasi::create(['key' => 'sebutan_kepala_desa', 'value' => 'Kepala Desa']);
        SettingAplikasi::create(['key' => 'sebutan_pj_kepala_desa', 'value' => 'Pj. Kepala Desa']);

        // Seed RefJabatan
        RefJabatan::create([
            'jenis'        => RefJabatan::KADES,
            'nama'         => 'Kepala Desa',
            'urutan'       => 1,
            'gaji_pokok'   => 0,
        ]);

        RefJabatan::create([
            'jenis'        => RefJabatan::SEKDES,
            'nama'         => 'Sekretaris Kepala Desa',
            'urutan'       => 2,
            'gaji_pokok'   => 0,
        ]);
    }

    /**
     * Test: Saat creating, sebutan_desa otomatis diupdate ke "Desa" untuk desa biasa
     */
    public function test_creating_config_updates_sebutan_desa_automatically(): void
    {
        // Arrange
        $data = [
            'app_key'           => 'test-app-key-123',
            'nama_desa'         => 'Test Desa',
            'kode_desa'         => '3200000001',
            'kode_desa_bps'     => '3200001001',
            'kode_pos'          => '12345',
            'nama_kecamatan'    => 'Test Kecamatan',
            'kode_kecamatan'    => '320000',
            'nama_kepala_camat' => 'Bapak Camat',
            'nip_kepala_camat'  => '123456789',
            'nama_kabupaten'    => 'Test Kabupaten',
            'kode_kabupaten'    => '3200',
            'nama_propinsi'     => 'Jawa Barat',
            'kode_propinsi'     => '32',
            'alamat_kantor'     => 'Jalan Test No 1',
            'email_desa'        => 'desa@test.com',
            'telepon'           => '02123456789',
            'website'           => 'https://test.desa.id',
        ];

        // Act
        $config = Config::create($data);

        // Assert
        $this->assertDatabaseHas('config', ['kode_desa' => '3200000001']);
        $this->assertDatabaseHas('setting_aplikasi', [
            'key'   => 'sebutan_desa',
            'value' => 'Desa',
        ]);
    }

    /**
     * Test: Saat update tanpa perubahan kode_desa, sebutan_desa TIDAK boleh berubah (Bug Fix)
     */
    public function test_updating_config_without_kode_desa_change_does_not_change_sebutan_desa(): void
    {
        // Arrange - Setup sebutan_desa dengan nilai custom "Kampung" bukan "Desa"
        SettingAplikasi::where('key', 'sebutan_desa')->update(['value' => 'Kampung']);

        $config = Config::create([
            'app_key'           => 'test-app-key-456',
            'nama_desa'         => 'Test Desa',
            'kode_desa'         => '3200000002',
            'kode_desa_bps'     => '3200001002',
            'kode_pos'          => '12345',
            'nama_kecamatan'    => 'Test Kecamatan',
            'kode_kecamatan'    => '320000',
            'nama_kepala_camat' => 'Bapak Camat',
            'nip_kepala_camat'  => '123456789',
            'nama_kabupaten'    => 'Test Kabupaten',
            'kode_kabupaten'    => '3200',
            'nama_propinsi'     => 'Jawa Barat',
            'kode_propinsi'     => '32',
            'alamat_kantor'     => 'Jalan Test No 1',
            'email_desa'        => 'desa@test.com',
            'telepon'           => '02123456789',
            'website'           => 'https://test.desa.id',
        ]);

        // Ubah sebutan_desa menjadi "Kampung" sebelum update
        SettingAplikasi::where('key', 'sebutan_desa')->update(['value' => 'Kampung']);

        // Act - Update config tanpa perubahan kode_desa (hanya update nama_desa)
        $config->update([
            'nama_desa'     => 'Test Desa Updated',
            'alamat_kantor' => 'Jalan Test No 2',
            'email_desa'    => 'desa.updated@test.com',
        ]);

        // Assert - sebutan_desa harus tetap "Kampung" (tidak berubah menjadi "Desa")
        $this->assertDatabaseHas('setting_aplikasi', [
            'key'   => 'sebutan_desa',
            'value' => 'Kampung',
        ]);
        $this->assertNotEquals('Desa', SettingAplikasi::where('key', 'sebutan_desa')->value('value'));
    }

    /**
     * Test: Saat update dengan perubahan kode_desa, sebutan_desa harus diupdate
     */
    public function test_updating_config_with_kode_desa_change_updates_sebutan_desa(): void
    {
        // Arrange - Setup sebutan_desa dengan nilai custom
        $config = Config::create([
            'app_key'           => 'test-app-key-789',
            'nama_desa'         => 'Test Desa',
            'kode_desa'         => '3200000003',
            'kode_desa_bps'     => '3200001003',
            'kode_pos'          => '12345',
            'nama_kecamatan'    => 'Test Kecamatan',
            'kode_kecamatan'    => '320000',
            'nama_kepala_camat' => 'Bapak Camat',
            'nip_kepala_camat'  => '123456789',
            'nama_kabupaten'    => 'Test Kabupaten',
            'kode_kabupaten'    => '3200',
            'nama_propinsi'     => 'Jawa Barat',
            'kode_propinsi'     => '32',
            'alamat_kantor'     => 'Jalan Test No 1',
            'email_desa'        => 'desa@test.com',
            'telepon'           => '02123456789',
            'website'           => 'https://test.desa.id',
        ]);

        // Ubah sebutan_desa menjadi "Kampung"
        SettingAplikasi::where('key', 'sebutan_desa')->update(['value' => 'Kampung']);

        // Act - Update config dengan perubahan kode_desa
        $config->update([
            'kode_desa'     => '3200000004',
            'nama_desa'     => 'Test Desa Changed',
        ]);

        // Assert - sebutan_desa harus berubah ke "Desa" (karena kode_desa berubah)
        $this->assertDatabaseHas('setting_aplikasi', [
            'key'   => 'sebutan_desa',
            'value' => 'Desa',
        ]);
    }

    /**
     * Test: Saat update multiple fields termasuk non-kode_desa, sebutan_desa tetap tidak berubah
     */
    public function test_updating_multiple_fields_without_kode_desa_change_preserves_sebutan_desa(): void
    {
        // Arrange
        $config = Config::create([
            'app_key'           => 'test-app-key-multi',
            'nama_desa'         => 'Test Desa',
            'kode_desa'         => '3200000005',
            'kode_desa_bps'     => '3200001005',
            'kode_pos'          => '12345',
            'nama_kecamatan'    => 'Test Kecamatan',
            'kode_kecamatan'    => '320000',
            'nama_kepala_camat' => 'Bapak Camat',
            'nip_kepala_camat'  => '123456789',
            'nama_kabupaten'    => 'Test Kabupaten',
            'kode_kabupaten'    => '3200',
            'nama_propinsi'     => 'Jawa Barat',
            'kode_propinsi'     => '32',
            'alamat_kantor'     => 'Jalan Test No 1',
            'email_desa'        => 'desa@test.com',
            'telepon'           => '02123456789',
            'website'           => 'https://test.desa.id',
        ]);

        // Set sebutan_desa ke nilai custom
        SettingAplikasi::where('key', 'sebutan_desa')->update(['value' => 'Negeri']);

        // Act - Update multiple fields tapi bukan kode_desa
        $config->update([
            'nama_desa'         => 'Desa Baru',
            'alamat_kantor'     => 'Jalan Baru No 2',
            'email_desa'        => 'desabaru@test.com',
            'telepon'           => '02123456780',
            'website'           => 'https://desabaru.id',
            'kode_pos'          => '54321',
        ]);

        // Assert
        $this->assertDatabaseHas('setting_aplikasi', [
            'key'   => 'sebutan_desa',
            'value' => 'Negeri',
        ]);
    }

    /**
     * Test: Verifikasi isDirty() method bekerja dengan benar pada kode_desa
     */
    public function test_is_dirty_method_detects_kode_desa_changes(): void
    {
        // Arrange
        $config = Config::create([
            'app_key'           => 'test-app-key-dirty',
            'nama_desa'         => 'Test Desa',
            'kode_desa'         => '3200000006',
            'kode_desa_bps'     => '3200001006',
            'kode_pos'          => '12345',
            'nama_kecamatan'    => 'Test Kecamatan',
            'kode_kecamatan'    => '320000',
            'nama_kepala_camat' => 'Bapak Camat',
            'nip_kepala_camat'  => '123456789',
            'nama_kabupaten'    => 'Test Kabupaten',
            'kode_kabupaten'    => '3200',
            'nama_propinsi'     => 'Jawa Barat',
            'kode_propinsi'     => '32',
            'alamat_kantor'     => 'Jalan Test No 1',
            'email_desa'        => 'desa@test.com',
            'telepon'           => '02123456789',
            'website'           => 'https://test.desa.id',
        ]);

        // Act - Ubah kode_desa
        $config->kode_desa = '3200000007';

        // Assert
        $this->assertTrue($config->isDirty('kode_desa'), 'isDirty() harus true untuk kode_desa');

        // Act - Ubah nama_desa tanpa ubah kode_desa
        $config2 = Config::find($config->id);
        $config2->nama_desa = 'Desa Lain';

        // Assert
        $this->assertFalse($config2->isDirty('kode_desa'), 'isDirty() harus false untuk kode_desa ketika hanya nama_desa berubah');
    }
}
