<?php

namespace Tests\Unit;

use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingAplikasiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: SettingAplikasi tidak memiliki key covid_desa
     */
    public function test_setting_aplikasi_does_not_have_covid_desa_key(): void
    {
        // Act
        $exists = SettingAplikasi::where('key', 'covid_desa')->exists();

        // Assert
        $this->assertFalse($exists, 'SettingAplikasi tidak boleh memiliki key covid_desa');
    }

    /**
     * Test: SettingAplikasi tidak memiliki key covid_rss
     */
    public function test_setting_aplikasi_does_not_have_covid_rss_key(): void
    {
        // Act
        $exists = SettingAplikasi::where('key', 'covid_rss')->exists();

        // Assert
        $this->assertFalse($exists, 'SettingAplikasi tidak boleh memiliki key covid_rss');
    }

    /**
     * Test: SettingAplikasi tidak memiliki key link_feed
     */
    public function test_setting_aplikasi_does_not_have_link_feed_key(): void
    {
        // Act
        $exists = SettingAplikasi::where('key', 'link_feed')->exists();

        // Assert
        $this->assertFalse($exists, 'SettingAplikasi tidak boleh memiliki key link_feed');
    }
}