<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Enums\StatusValidasiEnum;
use App\Models\FormatSurat;
use App\Models\SuratDinas;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Gerbang status_validasi pada FormatSurat/SuratDinas::scopeCetak() / scopeSiapCetak() dan siapDicetak() --
 * lihat App\Services\Surat\ValidatorTemplateSurat dan issue #11541.
 */
class SuratValidasiTemplateTest extends TestCase
{
    private function buatFormatSurat(array $override = []): FormatSurat
    {
        $surat = new FormatSurat();
        $surat->setRawAttributes(array_merge([
            'nama'            => 'Uji Validasi ' . uniqid(),
            'url_surat'       => 'uji-validasi-' . uniqid(),
            'jenis'           => FormatSurat::TINYMCE_DESA,
            'kunci'           => FormatSurat::KUNCI_DISABLE,
            'status_validasi' => StatusValidasiEnum::BELUM_DIVALIDASI,
        ], $override));

        return $surat;
    }

    private function buatSuratDinas(array $override = []): SuratDinas
    {
        $surat = new SuratDinas();
        $surat->setRawAttributes(array_merge([
            'nama'            => 'Uji Validasi Dinas ' . uniqid(),
            'url_surat'       => 'uji-validasi-dinas-' . uniqid(),
            'jenis'           => SuratDinas::TINYMCE_DESA,
            'kunci'           => SuratDinas::KUNCI_DISABLE,
            'status_validasi' => StatusValidasiEnum::BELUM_DIVALIDASI,
        ], $override));

        return $surat;
    }

    #[Test]
    public function template_desa_belum_divalidasi_tidak_siap_cetak(): void
    {
        $surat = $this->buatFormatSurat(['status_validasi' => StatusValidasiEnum::BELUM_DIVALIDASI]);

        $this->assertFalse($surat->siapDicetak());
    }

    #[Test]
    public function template_desa_tidak_valid_tidak_siap_cetak(): void
    {
        $surat = $this->buatFormatSurat(['status_validasi' => StatusValidasiEnum::TIDAK_VALID]);

        $this->assertFalse($surat->siapDicetak());
    }

    #[Test]
    public function template_desa_valid_siap_cetak(): void
    {
        $surat = $this->buatFormatSurat(['status_validasi' => StatusValidasiEnum::VALID]);

        $this->assertTrue($surat->siapDicetak());
    }

    #[Test]
    public function surat_bawaan_sistem_selalu_siap_cetak_walau_belum_divalidasi(): void
    {
        $surat = $this->buatFormatSurat([
            'jenis'           => FormatSurat::TINYMCE_SISTEM,
            'status_validasi' => StatusValidasiEnum::BELUM_DIVALIDASI,
        ]);

        $this->assertTrue($surat->siapDicetak());
    }

    #[Test]
    public function surat_dinas_desa_belum_divalidasi_tidak_siap_cetak(): void
    {
        $surat = $this->buatSuratDinas(['status_validasi' => StatusValidasiEnum::BELUM_DIVALIDASI]);

        $this->assertFalse($surat->siapDicetak());
    }

    #[Test]
    public function surat_dinas_desa_tidak_valid_tidak_siap_cetak(): void
    {
        $surat = $this->buatSuratDinas(['status_validasi' => StatusValidasiEnum::TIDAK_VALID]);

        $this->assertFalse($surat->siapDicetak());
    }

    #[Test]
    public function surat_dinas_valid_siap_cetak(): void
    {
        $surat = $this->buatSuratDinas(['status_validasi' => StatusValidasiEnum::VALID]);

        $this->assertTrue($surat->siapDicetak());
    }

    #[Test]
    public function surat_dinas_sistem_selalu_siap_cetak_walau_belum_divalidasi(): void
    {
        $surat = $this->buatSuratDinas([
            'jenis'           => SuratDinas::TINYMCE_SISTEM,
            'status_validasi' => StatusValidasiEnum::BELUM_DIVALIDASI,
        ]);

        $this->assertTrue($surat->siapDicetak());
    }

    #[Test]
    public function scope_siap_cetak_dan_cetak_format_surat(): void
    {
        $query = FormatSurat::cetak('uji-validasi');
        $sql = $query->toSql();

        $this->assertStringContainsString('`jenis` in', $sql);
        $this->assertStringContainsString('`status_validasi` = ?', $sql);
        $this->assertStringContainsString('`kunci` = ?', $sql);
        $this->assertStringContainsString('`url_surat` = ?', $sql);

        $bindings = $query->getBindings();
        $this->assertContains(FormatSurat::VALIDASI_VALID, $bindings);
        $this->assertContains(FormatSurat::KUNCI_DISABLE, $bindings);
        $this->assertContains('uji-validasi', $bindings);
    }

    #[Test]
    public function scope_siap_cetak_dan_cetak_surat_dinas(): void
    {
        $query = SuratDinas::cetak('uji-dinas');
        $sql = $query->toSql();

        $this->assertStringContainsString('`jenis` in', $sql);
        $this->assertStringContainsString('`status_validasi` = ?', $sql);
        $this->assertStringContainsString('`kunci` = ?', $sql);
        $this->assertStringContainsString('`url_surat` = ?', $sql);

        $bindings = $query->getBindings();
        $this->assertContains(SuratDinas::VALIDASI_VALID, $bindings);
        $this->assertContains(SuratDinas::KUNCI_DISABLE, $bindings);
        $this->assertContains('uji-dinas', $bindings);
    }
}

