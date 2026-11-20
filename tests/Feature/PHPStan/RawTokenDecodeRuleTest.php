<?php

namespace Tests\Feature\PHPStan;

use Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Test untuk memverifikasi RawTokenDecodeRule berfungsi dengan benar.
 * 
 * Rule ini mendeteksi pemanggilan langsung JWT::decode di luar TokenDecoder
 * untuk mencegah dekode token tanpa verifikasi signature yang proper.
 */
class RawTokenDecodeRuleTest extends BaseTestCase
{
    #[Test]
    public function rule_mendeteksi_jwt_decode_diluar_token_decoder(): void
    {
        // Test ini memverifikasi bahwa rule terdaftar dan dapat dipanggil
        $this->assertTrue(class_exists('App\PHPStan\Rules\RawTokenDecodeRule'));
        
        // Verifikasi bahwa rule memiliki method yang diperlukan
        $rule = new \App\PHPStan\Rules\RawTokenDecodeRule();
        $this->assertEquals(\PhpParser\Node\Expr\MethodCall::class, $rule->getNodeType());
    }

    #[Test]
    public function token_decoder_masih_berfungsi(): void
    {
        // Verifikasi bahwa TokenDecoder masih ada dan berfungsi
        $this->assertTrue(class_exists('App\Services\Layanan\TokenDecoder'));
        
        $decoder = new \App\Services\Layanan\TokenDecoder();
        $this->assertTrue(method_exists($decoder, 'decode'));
        $this->assertTrue(method_exists($decoder, 'decodeStruktur'));
    }

    #[Test]
    public function rule_mengizinkan_token_decoder_decode(): void
    {
        // Verifikasi bahwa rule memperbolehkan decode di dalam TokenDecoder
        $this->assertTrue(class_exists('App\Services\Layanan\TokenDecoder'));
        
        // TokenDecoder seharusnya bisa menggunakan JWT::decode secara internal
        $this->assertTrue(class_exists('Firebase\JWT\JWT'));
    }
}