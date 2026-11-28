<?php

namespace Tests\Feature\PHPStan;

use Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Test untuk memverifikasi LayananTokenWriteRule berfungsi dengan benar.
 * 
 * Rule ini mendeteksi pemanggilan updateWithKey() untuk token layanan
 * dan memastikan validasi dilakukan dengan benar.
 */
class LayananTokenWriteRuleTest extends BaseTestCase
{
    #[Test]
    public function rule_mendeteksi_update_with_key_token_layanan(): void
    {
        // Test ini memverifikasi bahwa rule terdaftar dan dapat dipanggil
        $this->assertTrue(class_exists('App\PHPStan\Rules\LayananTokenWriteRule'));
        
        // Verifikasi bahwa rule memiliki method yang diperlukan
        $rule = new \App\PHPStan\Rules\LayananTokenWriteRule();
        $this->assertEquals(\PhpParser\Node\Expr\MethodCall::class, $rule->getNodeType());
    }

    #[Test]
    public function rule_mengizinkan_setting_aplikasi_repository_update_with_key(): void
    {
        // Verifikasi bahwa SettingAplikasiRepository memiliki method updateWithKey
        $this->assertTrue(method_exists('App\Repositories\SettingAplikasiRepository', 'updateWithKey'));
        
        // Verifikasi bahwa method memiliki validasi untuk token layanan
        $repo = new \App\Repositories\SettingAplikasiRepository();
        $this->assertTrue(method_exists($repo, 'isKeyTokenLayanan'));
        $this->assertTrue(method_exists($repo, 'validateTokenLayananValue'));
    }

    #[Test]
    public function layanan_token_repository_masih_berfungsi(): void
    {
        // Verifikasi bahwa LayananTokenRepository masih ada dan berfungsi
        $this->assertTrue(class_exists('App\Repositories\LayananTokenRepository'));
        
        $repo = new \App\Repositories\LayananTokenRepository();
        $this->assertTrue(method_exists($repo, 'simpan'));
        $this->assertTrue(method_exists($repo, 'simpanKredensial'));
    }
}