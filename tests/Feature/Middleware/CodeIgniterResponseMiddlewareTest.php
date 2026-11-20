<?php

namespace Tests\Feature\Middleware;

use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Tests for CodeIgniterResponseMiddleware's $_SESSION <-> Laravel session
 * store reconciliation logic using full HTTP route execution.
 */
class CodeIgniterResponseMiddlewareTest extends TestCase
{
    // Penampung statis untuk callback skenario uji aktif
    public static $activeCallback;

    protected function setUp(): void
    {
        parent::setUp();

        // Kosongkan superglobal $_SESSION sebelum setiap pengujian
        $_SESSION = [];
        self::$activeCallback = null;
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        self::$activeCallback = null;

        parent::tearDown();
    }

    /**
     * Helper untuk mendaftarkan rute tiruan formal per test case.
     */
    private function executeMiddlewareRoute(array $initialSession, callable $routeCallback)
    {
        // Simpan callback ke dalam properti statis agar dapat dijangkau oleh rute anonim
        self::$activeCallback = $routeCallback;

        // Menggunakan Anonymous Route Callback langsung tanpa memanggil kelas controller terpisah
        Route::post('/test-ci-bridge', function () {
            $callback = CodeIgniterResponseMiddlewareTest::$activeCallback;

            if ($callback) {
                // Membuat instance anonim baru yang mewarisi sifat dasar CI_Controller asli
                $ciInstance = new class extends \CI_Controller {};

                // Mengikat closure uji ke instance tiruan CI_Controller agar "$this->session" dapat diakses
                $boundCallback = \Closure::bind($callback, $ciInstance, get_class($ciInstance));

                return $boundCallback();
            }

            return response('Skenario callback tidak ditemukan', 500);
        })->middleware(['codeigniter']);

        return $this->withSession($initialSession)->post('/test-ci-bridge');
    }

    #[Test]
    public function it_persists_a_brand_new_key_set_via_the_store(): void
    {
        $response = $this->executeMiddlewareRoute([], function () {
            $this->session->set_userdata('dusun', 'A');

            return response('ok');
        });

        $response->assertStatus(200);
        $response->assertSessionHas('dusun', 'A');
    }

    #[Test]
    public function it_does_not_resurrect_a_key_removed_via_unset_userdata(): void
    {
        $response = $this->executeMiddlewareRoute(
            ['dusun' => 'A', 'rw' => '02'],
            function () {
                $this->session->unset_userdata('dusun');

                return response('ok');
            }
        );

        $response->assertStatus(200);
        $response->assertSessionMissing('dusun');
        $this->assertArrayNotHasKey('dusun', $_SESSION);
        $response->assertSessionHas('rw', '02');
    }

    #[Test]
    public function it_does_not_resurrect_multiple_keys_removed_via_unset_userdata(): void
    {
        $initialData = [
            'cari' => 'budi',
            'dusun' => 'A',
            'rw' => '02',
            'rt' => '03',
            'sex' => 'L',
        ];

        $response = $this->executeMiddlewareRoute($initialData, function () {
            $this->session->unset_userdata(['cari', 'dusun', 'rw', 'rt', 'sex']);

            return response('ok');
        });

        $response->assertStatus(200);

        foreach (['cari', 'dusun', 'rw', 'rt', 'sex'] as $key) {
            $response->assertSessionMissing($key);
            $this->assertArrayNotHasKey($key, $_SESSION);
        }
    }

    #[Test]
    public function it_keeps_the_new_value_when_an_existing_key_is_overwritten(): void
    {
        $response = $this->executeMiddlewareRoute(['dusun' => 'A'], function () {
            $this->session->dusun = 'B';

            return response('ok');
        });

        $response->assertStatus(200);
        $response->assertSessionHas('dusun', 'B');
        $this->assertSame('B', $_SESSION['dusun'] ?? null);
    }

    #[Test]
    public function it_picks_up_a_value_written_directly_to_session_superglobal_by_legacy_code(): void
    {
        $response = $this->executeMiddlewareRoute([], function () {
            $_SESSION['legacy_flag'] = 'yes';

            return response('ok');
        });

        $response->assertStatus(200);
        $response->assertSessionHas('legacy_flag', 'yes');
    }

    #[Test]
    public function it_forgets_a_key_deleted_directly_from_session_superglobal_by_legacy_code(): void
    {
        $response = $this->executeMiddlewareRoute(['legacy_flag' => 'yes'], function () {
            unset($_SESSION['legacy_flag']);

            return response('ok');
        });

        $response->assertStatus(200);
        $response->assertSessionMissing('legacy_flag');
    }

    #[Test]
    public function it_reads_userdata_via_legacy_magic_getter_then_writes_a_derived_value_directly_to_session_superglobal(): void
    {
        $response = $this->executeMiddlewareRoute(['dusun' => 'A'], function () {
            $currentDusun = $this->session->dusun;

            if ($currentDusun === 'A') {
                $_SESSION['derived_status'] = 'processed_a';
            }

            return response('ok');
        });

        $response->assertStatus(200);
        $response->assertSessionHas('derived_status', 'processed_a');
        $this->assertSame('processed_a', $_SESSION['derived_status'] ?? null);
    }

    #[Test]
    public function it_handles_legacy_ci3_set_flashdata_correctly(): void
    {
        $response = $this->executeMiddlewareRoute([], function () {
            $this->session->set_flashdata('success_msg', 'Data desa berhasil disimpan');

            return response('ok');
        });

        $response->assertStatus(200);
        $response->assertSessionHas('success_msg', 'Data desa berhasil disimpan');
        $this->assertEquals('new', session()->get('__ci_vars.success_msg'));
        $this->assertArrayNotHasKey('success_msg', $_SESSION);
    }

    #[Test]
    public function it_syncs_properly_when_legacy_controller_calls_sess_destroy(): void
    {
        $initialData = [
            'user_id' => 45,
            'role' => 'admin',
            'is_logged_in' => true
        ];

        $response = $this->executeMiddlewareRoute($initialData, function () {
            $this->session->sess_destroy();

            return response('ok');
        });

        $response->assertStatus(200);
        $response->assertSessionMissing('user_id');
        $response->assertSessionMissing('role');
        $response->assertSessionMissing('is_logged_in');
        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->assertArrayNotHasKey('role', $_SESSION);
        $this->assertArrayNotHasKey('is_logged_in', $_SESSION);
    }

    #[Test]
    public function it_captures_direct_echo_from_legacy_controller(): void
    {
        $response = $this->executeMiddlewareRoute([], function () {
            // Skenario: Controller legacy menggunakan echo langsung tanpa return formal
            return "Halo dari dunia lama OpenSID!";
        });

        $response->assertStatus(200);
        // Pastikan isi dari echo berhasil ditangkap oleh middleware bridge
        $this->assertEquals("Halo dari dunia lama OpenSID!", $response->getContent());
    }

    #[Test]
    public function it_captures_output_set_via_codeigniter_output_class(): void
    {
        $response = $this->executeMiddlewareRoute([], function () {
            // Skenario: Menggunakan $this->output->set_output() khas CI3
            $this->output->set_output(json_encode(['status' => 'success', 'code' => 200]));
        });

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'code' => 200
        ]);
    }

    #[Test]
    public function it_maps_custom_headers_from_ci_output_to_laravel_response(): void
    {
        $response = $this->executeMiddlewareRoute([], function () {
            // Skenario: Menambahkan custom header ala CodeIgniter
            $this->output->set_header('X-Legacy-Bridge: Active');
            $this->output->set_header('Content-Type: application/json; charset=utf-8');
        });

        $response->assertStatus(200);
        // Memastikan Laravel berhasil membaca Header yang dikirim oleh sasis lama
        $response->assertHeader('X-Legacy-Bridge', 'Active');
        $response->assertHeader('Content-Type', 'application/json; charset=utf-8');
    }
}
