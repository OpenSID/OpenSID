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

/**
 * Helper functions untuk unit test Tracker
 *
 * File ini berisi fungsi-fungsi yang dibutuhkan oleh Tracker::kirimData()
 * untuk keperluan testing.
 *
 * NOTE: Fungsi-fungsi ini harus berada di global scope (tanpa namespace)
 * untuk menghindari error redeclaration function.
 */

// Load fallback functions for CodeIgniter functions
require_once __DIR__ . '/../../Support/FallbackTraits.php';

/**
 * Mock function config_item()
 * Uses static properties to allow test control
 */
function config_item($item)
{
    static $configValues = [
        'demo_mode'       => false,
        'server_pantau'   => 'https://pantau.opendesa.id',
        'token_pantau'    => 'test_token',
        'server_layanan'  => 'https://layanan.opendesa.id',
    ];

    // Allow test to override values
    if (isset($GLOBALS['test_config_items'][$item])) {
        return $GLOBALS['test_config_items'][$item];
    }

    return $configValues[$item] ?? null;
}

/**
 * Set config_item value for testing
 */
function setTestConfigItem($item, $value)
{
    $GLOBALS['test_config_items'][$item] = $value;
}

/**
 * Reset config_item test values
 */
function resetTestConfigItems()
{
    $GLOBALS['test_config_items'] = [];
}

/**
 * Mock function get_instance()
 * Returns a mock CI instance for testing
 */
function &get_instance()
{
    static $instance = null;
    if ($instance === null) {
        $cacheMock = new class {
            public $file;
            public function __construct()
            {
                $this->file = new class {
                    public function get($key)
                    {
                        // Return test response for status_langganan cache
                        if ($key === 'status_langganan' && isset($GLOBALS['test_pelanggan_response'])) {
                            return $GLOBALS['test_pelanggan_response'];
                        }
                        return null;
                    }
                };
            }
        };

        $sessionMock = new class {
            public function userdata($key = null) { return null; }
            public function set_userdata($key, $value = null) {}
        };

        $instance = new class($cacheMock, $sessionMock) {
            public $load;
            public $cache;
            public $session;
            public $header = [];
            public $controller = 'pengguna';
            public $list_setting;
            public $cacheMock;
            public $sessionMock;

            public function __construct($cacheMock, $sessionMock)
            {
                $this->cacheMock = $cacheMock;
                $this->sessionMock = $sessionMock;
                $this->cache = $cacheMock;
                $this->session = $sessionMock;

                // Initialize load
                $this->load = new class($this) {
                    private $ci;
                    public $driver;

                    public function __construct($ci)
                    {
                        $this->ci = $ci;
                    }

                    public function driver($drivers)
                    {
                        // Mock driver loading - set up cache and session drivers
                        if (is_array($drivers)) {
                            foreach ($drivers as $driver) {
                                if ($driver === 'cache') {
                                    $this->ci->cache = $this->ci->cacheMock;
                                }
                                if ($driver === 'session') {
                                    $this->ci->session = $this->ci->sessionMock;
                                }
                            }
                        }
                        return $this;
                    }
                };

                $this->list_setting = new class {
                    public function firstWhere($key, $value)
                    {
                        return (object) ['value' => 'test_token'];
                    }
                };
            }

            public function __call($name, $args)
            {
                return $this;
            }
        };
    }
    return $instance;
}

/**
 * Mock function current_url()
 */
function current_url()
{
    return 'http://localhost/test';
}

/**
 * Mock function get_external_ip()
 */
function get_external_ip()
{
    return '192.168.1.1';
}

/**
 * Mock function AmbilVersi()
 */
function AmbilVersi()
{
    return '24.01';
}

/**
 * Mock function cek_anjungan()
 */
function cek_anjungan()
{
    return 0;
}

/**
 * Mock function theme_active()
 */
function theme_active()
{
    return (object) ['nama' => 'default'];
}

/**
 * Reset setting test values
 */
function resetTestSettings()
{
    $GLOBALS['test_settings'] = [];
}

/**
 * Mock function identitas()
 */
function identitas()
{
    $config = new \stdClass();
    $config->nama_desa = 'Desa Sukamaju';
    $config->kode_desa = '3201012001';
    $config->kode_pos = '32010';
    $config->nama_kecamatan = 'Kecamatan Majujaya';
    $config->kode_kecamatan = '320101';
    $config->nama_kabupaten = 'Kabupaten Bandarlama';
    $config->kode_kabupaten = '3201';
    $config->nama_propinsi = 'Provinsi Jawatengah';
    $config->kode_propinsi = '32';
    $config->lat = '-6.2088';
    $config->lng = '106.8456';
    $config->alamat_kantor = 'Jl. Merdeka No. 1';
    $config->email_desa = 'desa@contoh.com';
    $config->telepon = '02112345678';
    $config->nama_kontak = 'Admin Desa';
    $config->hp_kontak = '081234567890';
    $config->jabatan_kontak = 'Kepala Desa';

    return $config;
}

/**
 * Mock function kirim_versi_opensid()
 */
function kirim_versi_opensid($kode_desa)
{
    // Do nothing in test
    return;
}

/**
 * Mock PelangganService::apiPelangganPemesanan()
 * Returns empty response by default (umum service)
 */
function mockPelangganServiceApiPelangganPemesanan($response = null)
{
    if ($response === null) {
        // Default: return empty response (umum service)
        $response = (object) [
            'body' => (object) [
                'pemesanan' => []
            ]
        ];
    }

    $GLOBALS['test_pelanggan_response'] = $response;
}

/**
 * Get PelangganService response for testing
 */
function getTestPelangganServiceResponse()
{
    return $GLOBALS['test_pelanggan_response'] ?? null;
}

/**
 * Mock function httpPost()
 * Captures data being sent and returns controlled value
 * Note: This only works if loaded before the real httpPost function
 */
if (! function_exists('httpPost')) {
    function httpPost($url, $data)
    {
        return \Tests\Unit\Libraries\TrackerKirimDataTest::getCapturedData($data);
    }
}

/**
 * Set return value for httpPost mock
 */
function setTestHttpPostReturnValue($value)
{
    \Tests\Unit\Libraries\TrackerKirimDataTest::setReturnValue($value);
}

/**
 * Reset captured data
 */
function resetTestHttpPostCapturedData()
{
    \Tests\Unit\Libraries\TrackerKirimDataTest::resetCapturedData();
}
