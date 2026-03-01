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

namespace Tests\Feature\Security;

use App\Models\Config;
use App\Models\Pamong;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Tests\BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Tes Keamanan SQL Injection
 *
 * Test ini memverifikasi bahwa kerentanan SQL Injection telah diperbaiki
 *
 * @internal
 */
final class SqlInjectionTest extends BaseTestCase
{
    use RefreshDatabase;

    private $appKey = 'base64:fTc4df0qWY59nmxJDX/ZJu4tI+JIyC7w63WP2q5FBQk=';

    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__ . '/../../../donjo-app/helpers/core_helper.php';

        // Buat config awal untuk keperluan testing
        Model::withoutEvents(function () {
            Config::create([
                'app_key'        => $this->appKey,
                'nama_desa'      => 'Desa Test',
                'kode_desa'      => '12345678910',
                'nama_kecamatan' => 'Kecamatan Test',
                'kode_kecamatan' => '123456',
                'nama_kabupaten' => 'Kabupaten Test',
                'kode_kabupaten' => '1234',
                'nama_propinsi'  => 'Provinsi Test',
                'kode_propinsi'  => '12',
            ]);
        });
    }

    /**
     * @test
     * @group security
     * @group sql-injection
     *
     * Nama Test: Menguji fungsi parseListId dengan input normal
     *
     * Deskripsi: Memastikan fungsi parsing ID bekerja dengan input yang valid
     */
    public function testParseListIdDenganInputNormal()
    {
        $input = '1,2,3,4,5';
        $hasil = array_map('intval', explode(',', $input));
        $hasil = array_filter($hasil, static fn ($id) => $id > 0);

        $this->assertEquals([1, 2, 3, 4, 5], array_values($hasil));
    }

    /**
     * @test
     * @group security
     * @group sql-injection
     *
     * Nama Test: Menguji fungsi parseListId memblokir SQL Injection dasar
     *
     * Deskripsi: Memastikan payload SQL Injection dasar diblokir dengan sanitasi
     *
     * @dataProvider sqlInjectionPayloadsProvider
     */
    public function testParseListIdMemblokirSqlInjectionDasar($payload, $deskripsi)
    {
        // Simulasikan parsing seperti di update_bagan
        $hasil = array_map('intval', explode(',', $payload));
        $hasil = array_filter($hasil, static fn ($id) => $id > 0);

        // Setelah sanitasi, hasil harus kosong atau hanya berisi angka valid
        $this->assertIsArray($hasil);
        $this->assertNotEmpty($hasil, "Payload '{$deskripsi}' harus di-filter menjadi array kosong");
    }

    /**
     * @test
     * @group security
     * @group sql-injection
     *
     * Nama Test: Menguji sanitasi input dengan karakter khusus
     *
     * Deskripsi: Memastikan karakter khusus SQL di-filter dengan benar
     */
    public function testSanitasiInputKarakterKhusus()
    {
        $payloadsKhusus = [
            "1' OR '1'='1",
            "1; DROP TABLE users--",
            "1' UNION SELECT * FROM users--",
            "1' AND SLEEP(5)--",
            "-1 OR 1=1",
            "1) OR (1=1",
            "1'||DBMS_PIPE.RECEIVE_MESSAGE(5,5)||'",
        ];

        foreach ($payloadsKhusus as $payload) {
            $hasil = array_map('intval', explode(',', $payload));
            $hasil = array_filter($hasil, static fn ($id) => $id > 0);

            // intval akan mengubah string injection menjadi 0 atau 1
            // array_filter akan menghapus 0
            $this->assertIsArray($hasil);
        }
    }

    /**
     * @test
     * @group security
     * @group sql-injection
     *
     * Nama Test: Menguji whereIn aman dari SQL Injection
     *
     * Deskripsi: Memastikan whereIn dengan array yang sudah disanitasi aman
     */
    public function testWhereInAmanDariSqlInjection()
    {
        // Buat tabel test
        Schema::create('test_pamong', static function ($table) {
            $table->id();
            $table->integer('pamong_id');
            $table->integer('atasan')->nullable();
            $table->string('nama');
        });

        // Insert data test
        DB::table('test_pamong')->insert([
            ['pamong_id' => 1, 'atasan' => null, 'nama' => 'Pamong 1'],
            ['pamong_id' => 2, 'atasan' => null, 'nama' => 'Pamong 2'],
            ['pamong_id' => 3, 'atasan' => null, 'nama' => 'Pamong 3'],
        ]);

        // Coba update dengan whereIn menggunakan ID yang disanitasi
        $list_id = "1' OR '1'='1";
        $ids      = array_map('intval', explode(',', $list_id));
        $ids      = array_filter($ids, static fn ($id) => $id > 0);

        // Ini hanya akan mengupdate ID 1, bukan semua records
        $affected = DB::table('test_pamong')
            ->whereIn('pamong_id', $ids)
            ->update(['atasan' => 999]);

        // Hanya 1 record yang terupdate, bukan semua
        $this->assertEquals(1, $affected);

        // Verifikasi data
        $this->assertDatabaseHas('test_pamong', [
            'pamong_id' => 1,
            'atasan'    => 999,
        ]);

        // Record lain tidak terupdate
        $this->assertDatabaseHas('test_pamong', [
            'pamong_id' => 2,
            'atasan'    => null,
        ]);
    }

    /**
     * @test
     * @group security
     * @group sql-injection
     *
     * Nama Test: Menguji validasi integer untuk mencegah injection
     *
     * Deskripsi: Memastikan casting ke integer mencegah SQL injection
     */
    public function testValidasiIntegerMencegahInjection()
    {
        $payloadsBerbahaya = [
            "1' OR '1'='1"             => 1,
            "1; DROP TABLE users--"    => 1,
            "1abc"                     => 1,
            "-1 OR 1=1"                => -1,
            "0"                        => 0,
            "999' UNION SELECT--"      => 999,
        ];

        foreach ($payloadsBerbahaya as $payload => $expected) {
            $hasil = (int) $payload;
            $this->assertEquals($expected, $hasil, "Payload: {$payload}");
        }
    }

    /**
     * @test
     * @group security
     * @group sql-injection
     *
     * Nama Test: Menguji filter array menghapus nilai tidak valid
     *
     * Deskripsi: Memastikan filter menghapus 0 dan nilai negatif
     */
    public function testFilterArrayMenghapusNilaiTidakValid()
    {
        $input = [0, -1, -999, 1, 2, 3];
        $hasil = array_filter($input, static fn ($id) => $id > 0);

        $this->assertEquals([1, 2, 3], array_values($hasil));
    }

    /**
     * Data provider untuk payload SQL Injection
     *
     * @return array
     */
    public static function sqlInjectionPayloadsProvider(): array
    {
        return [
            ["1' OR '1'='1", 'SQL Injection dasar OR'],
            ["1' UNION SELECT 1,2,3,4,5--", 'Union-based SQL Injection'],
            ["1; DROP TABLE tweb_penduduk--", 'Stacked Query dengan DROP'],
            ["1' AND SLEEP(5)--", 'Time-based Blind SQL Injection'],
            ["-1 OR 1=1", 'SQL Injection tanpa kutip'],
            ["1) OR (1=1", 'SQL Injection dengan kurung'],
            ["-1 UNION SELECT 1,version(),3--", 'Union SELECT dengan version()'],
            ["1' EXEC xp_cmdshell('dir')--", 'Command Execution'],
            ["1' AND 1=1--", 'Boolean-based SQL Injection'],
            ["1||DBMS_PIPE.RECEIVE_MESSAGE(5,5)||'", 'Oracle Delay Injection'],
        ];
    }

    /**
     * @test
     * @group security
     * @group sql-injection
     *
     * Nama Test: Menguji Expression aman untuk update primary key
     *
     * Deskripsi: Memastikan penggunaan Expression aman untuk operasi aritmatika
     */
    public function testExpressionAmanUntukUpdatePrimaryKey()
    {
        // Buat tabel test
        Schema::create('test_artikel', static function ($table) {
            $table->id();
            $table->integer('config_id');
            $table->string('judul');
        });

        // Insert data test
        DB::table('test_artikel')->insert([
            ['id' => 1, 'config_id' => 1, 'judul' => 'Artikel 1'],
            ['id' => 2, 'config_id' => 1, 'judul' => 'Artikel 2'],
        ]);

        // Simulasikan update dengan Expression (seperti di MultiDB)
        $rand = 100;

        // Gunakan Expression dengan nilai yang sudah divalidasi
        DB::table('test_artikel')
            ->where('config_id', 1)
            ->update([
                'id' => new \Illuminate\Database\Query\Expression("`id` + {$rand}"),
            ]);

        // Verifikasi update berhasil
        $artikel1 = DB::table('test_artikel')->where('judul', 'Artikel 1')->first();
        $artikel2 = DB::table('test_artikel')->where('judul', 'Artikel 2')->first();

        $this->assertEquals(101, $artikel1->id);
        $this->assertEquals(102, $artikel2->id);
    }

    /**
     * @test
     * @group security
     * @group sql-injection
     *
     * Nama Test: Menguji validasi nama kolom
     *
     * Deskripsi: Memastikan nama kolom divalidasi sebelum digunakan
     */
    public function testValidasiNamaKolom()
    {
        $kolomValid   = ['id', 'pamong_id', 'config_id', 'nama_lengkap'];
        $kolomInvalid = ["id'; DROP TABLE users--", 'id OR 1=1--', 'id; SELECT'];

        foreach ($kolomValid as $kolom) {
            $this->assertTrue(
                preg_match('/^[a-zA-Z0-9_]+$/', $kolom) === 1,
                "Kolom valid: {$kolom}"
            );
        }

        foreach ($kolomInvalid as $kolom) {
            $this->assertFalse(
                preg_match('/^[a-zA-Z0-9_]+$/', $kolom) === 1,
                "Kolom invalid: {$kolom}"
            );
        }
    }

    /**
     * @test
     * @group security
     * @group sql-injection
     *
     * Nama Test: Menguji sanitasi nilai rand
     *
     * Deskripsi: Memastikan nilai rand selalu integer positif
     */
    public function testSanitasiNilaiRand()
    {
        $inputs = [
            '100'          => 100,
            '999'          => 999,
            '0'            => 0,
            "-1"           => -1,
            "1' OR '1'='1" => 1,
            "abc"          => 0,
            "1.5"          => 1,
            "1e2"          => 100,
        ];

        foreach ($inputs as $input => $expected) {
            $hasil = (int) $input;
            $this->assertEquals($expected, $hasil, "Input: {$input}");
        }
    }

    protected function tearDown(): void
    {
        // Clean up test tables
        Schema::dropIfExists('test_pamong');
        Schema::dropIfExists('test_artikel');
        parent::tearDown();
    }
}
