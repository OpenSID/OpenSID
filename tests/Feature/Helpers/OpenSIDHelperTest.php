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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Feature\Helpers;

use Tests\BaseTestCase;
use Illuminate\Support\Facades\Cache;

final class OpenSIDHelperTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__ . '/../../../donjo-app/helpers/opensid_helper.php';
    }

    // Mulai Email
    public function testEmailCleansAndValidatesCorrectly()
    {
        $email = 'andi_fahruddin?@gmail.com';
        $cleanedEmail = email($email);
        $expectedEmail = 'andi_fahruddin@gmail.com';
        $this->assertSame($expectedEmail, $cleanedEmail);
        $this->assertTrue(filter_var($cleanedEmail, FILTER_VALIDATE_EMAIL) !== false);
    }

    public function testEmailWithNoInvalidCharacters()
    {
        $email = 'andi_fahruddin@gmail.com';
        $cleanedEmail = email($email);
        $this->assertSame($email, $cleanedEmail);
        $this->assertTrue(filter_var($cleanedEmail, FILTER_VALIDATE_EMAIL) !== false);
    }

    public function testEmailWithMultipleInvalidCharacters()
    {
        $email = 'andi_fahruddin!!@gmail.com';
        $cleanedEmail = email($email);
        $expectedEmail = 'andi_fahruddin@gmail.com';
        $this->assertSame($expectedEmail, $cleanedEmail);
        $this->assertTrue(filter_var($cleanedEmail, FILTER_VALIDATE_EMAIL) !== false);
    }

    public function testEmailWithEmptyString()
    {
        $email = '';
        $cleanedEmail = email($email);
        $this->assertSame('', $cleanedEmail);
        $this->assertFalse(filter_var($cleanedEmail, FILTER_VALIDATE_EMAIL) !== false);
    }
    // Akhir Email

    // Mulai isPHP
    public function testIsPHPDetectsDangerousExtensions()
    {
        $this->assertTrue(isPHP(null, 'shell.php'));
        $this->assertTrue(isPHP(null, 'shell.PHP'));
        $this->assertTrue(isPHP(null, 'shell.php5'));
        $this->assertTrue(isPHP(null, 'shell.phtml'));
        $this->assertTrue(isPHP(null, 'shell.phar'));
        $this->assertTrue(isPHP(null, 'shell.pht'));
        $this->assertTrue(isPHP(null, 'shell.php.'));
        $this->assertTrue(isPHP(null, 'shell.php '));
    }

    public function testIsPHPDetectsDangerousConfigFiles()
    {
        $this->assertTrue(isPHP(null, '.htaccess'));
        $this->assertTrue(isPHP(null, '.user.ini'));
        $this->assertTrue(isPHP(null, 'web.config'));
    }

    public function testIsPHPDetectsDoubleExtensions()
    {
        $this->assertTrue(isPHP(null, 'shell.php.jpg'));
        $this->assertTrue(isPHP(null, 'shell.phtml.png'));
        $this->assertTrue(isPHP(null, 'test.phar.pdf'));
    }

    public function testIsPHPDetectsNullByteInjection()
    {
        $this->assertTrue(isPHP(null, "shell.php\0.jpg"));
    }

    public function testIsPHPAllowsSafeFilenames()
    {
        $this->assertFalse(isPHP(null, 'laporan-desa.pdf'));
        $this->assertFalse(isPHP(null, 'foto-penduduk.jpg'));
        $this->assertFalse(isPHP(null, 'icon.png'));
        $this->assertFalse(isPHP(null, 'panduan-php.docx'));
    }

    public function testIsPHPDetectsDangerousContent()
    {
        $tmp = tempnam(sys_get_temp_dir(), 'test_php_');

        // Standard <?php
        file_put_contents($tmp, '<?php phpinfo(); ?>');
        $this->assertTrue(isPHP($tmp, 'file.txt'));

        // Short echo tag <?=
        file_put_contents($tmp, '<?=$_GET["cmd"]?>');
        $this->assertTrue(isPHP($tmp, 'image.jpg'));

        // Backticks inside <?=
        file_put_contents($tmp, '<?=`id`?>');
        $this->assertTrue(isPHP($tmp, 'image.png'));

        // <?= with system() call
        file_put_contents($tmp, '<?=system($_GET[0]);?>');
        $this->assertTrue(isPHP($tmp, 'doc.pdf'));

        // Short open tag <? with whitespace
        file_put_contents($tmp, "<? \n phpinfo(); ?>");
        $this->assertTrue(isPHP($tmp, 'image.jpg'));

        // Short open tag <? with function
        file_put_contents($tmp, '<?eval($_POST[1]);?>');
        $this->assertTrue(isPHP($tmp, 'image.jpg'));

        // Script tag
        file_put_contents($tmp, '<script>alert("XSS")</script>');
        $this->assertTrue(isPHP($tmp, 'image.jpg'));

        // Script tag with language="php"
        file_put_contents($tmp, '<script language="php">phpinfo();</script>');
        $this->assertTrue(isPHP($tmp, 'image.jpg'));

        // __halt_compiler()
        file_put_contents($tmp, '__halt_compiler();');
        $this->assertTrue(isPHP($tmp, 'image.jpg'));

        // HTML tag
        file_put_contents($tmp, '<html><body>hello</body></html>');
        $this->assertTrue(isPHP($tmp, 'image.jpg'));

        // Apache PHP handler directive
        file_put_contents($tmp, 'SetHandler application/x-httpd-php');
        $this->assertTrue(isPHP($tmp, 'config.txt'));

        @unlink($tmp);
    }

    public function testIsPHPAllowsSafeContent()
    {
        $tmp = tempnam(sys_get_temp_dir(), 'test_safe_');

        // Safe XML / SVG
        file_put_contents($tmp, '<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg"><circle r="10"/></svg>');
        $this->assertFalse(isPHP($tmp, 'icon.svg'));

        // Safe XMP packet metadata (commonly found in JPEGs / PDFs)
        file_put_contents($tmp, '<?xpacket begin="" id="W5M0MpCehiHzreSzNTczkc9d"?><x:xmpmeta></x:xmpmeta><?xpacket end="w"?>');
        $this->assertFalse(isPHP($tmp, 'photo.jpg'));

        // Normal plain text
        file_put_contents($tmp, 'Dokumen laporan tahunan keuangan desa 2026.');
        $this->assertFalse(isPHP($tmp, 'laporan.txt'));

        @unlink($tmp);
    }
    // Akhir isPHP
}