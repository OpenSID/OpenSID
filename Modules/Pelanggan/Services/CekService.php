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

namespace Modules\Pelanggan\Services;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class CekService
{
    /**
     * @var CI_Controller
     */
    protected $ci;

    protected $token;
    protected $kecuali = [
        'beranda', 'identitas_desa',  'pengguna', 'pelanggancontroller', 'pendaftarankerjasamacontroller', 'setting', 'notif', 'main', 'info_sistem',
    ];

    public function __construct()
    {
        $this->ci = app('ci');

        $this->token = $this->ci->setting->layanan_opendesa_token;

        if (! isset($this->ci->header['desa'])) {
            $this->ci->header['desa'] = identitas()->toArray();
        }
    }

    public function validasi(): bool
    {
        if ($this->isExceptController() || $this->isDemoMode()) {
            return true;
        }

        if (! $this->validasiAkses()) {
            redirect('peringatan');
        }

        $this->ci->session->unset_userdata(['error_premium', 'error_premium_pesan']);

        return true;
    }

    public function validasiAkses(): bool
    {
        $this->ci->session->unset_userdata('error_premium');

        if (empty($this->ci->header['desa']['kode_desa'])) {
            $this->ci->session->set_userdata('error_premium', 'Kode desa diperlukan.');

            return false;
        }

        if (empty($this->token)) {
            $this->ci->session->set_userdata('error_premium', 'Token pelanggan kosong / tidak valid.');

            return false;
        }

        $jwtPayload = $this->decodeTokenPayload($this->token);

        if ($this->isDesaIdMismatch($jwtPayload)) {
            $this->ci->session->set_userdata('error_premium', ucwords($this->ci->setting->sebutan_desa . ' ' . $this->ci->header['desa']['nama_desa']) . ' tidak terdaftar di ' . config_item('server_layanan') . ' atau Token yang di input tidak sesuai dengan kode desa');
            $this->daftarHitam();

            return false;
        }

        $berakhir   = $jwtPayload->tanggal_berlangganan->akhir;
        $disarankan = 'v' . str_replace('-', '', substr($berakhir, 2, 5)) . '.0.0-premium';

        if ($this->isPremiumVersionExpired($berakhir)) {
            if (empty($berakhir)) {
                $this->ci->session->set_userdata('error_premium', 'Token premium tidak valid.');
                $this->ci->session->set_userdata('error_premium_pesan', 'Langganan Premium tidak ditemukan. Silakan berlangganan terlebih dahulu atau gunakan versi umum.');
            } else {
                $this->ci->session->set_userdata('error_premium', 'Masa aktif berlangganan fitur premium sudah berakhir.');
                $this->ci->session->set_userdata('error_premium_pesan', "Hanya diperbolehkan menggunakan {$disarankan} (maupun versi revisinya) atau menggunakan versi rilis {$this->ci->versi_setara} umum.");
            }

            return false;
        }

        if ($this->isLocalIPAddress()) {
            return true;
        }

        if ($this->isDomainMismatch($jwtPayload)) {
            $this->ci->session->set_userdata('error_premium', 'Domain ' . get_domain(APP_URL) . ' tidak terdaftar di ' . config_item('server_layanan'));
            $this->daftarHitam();

            return false;
        }

        return true;
    }

    public function validasiVersi($install = false): bool
    {
        if ($this->isPremiumDisabled() || $install || $this->isDemoMode() || $this->isUmum()) {
            return true;
        }

        if (empty($this->token)) {
            $this->ci->session->token_kosong = true;
            redirect('token');
        }

        $jwtPayload = $this->decodeTokenPayload($this->token);
        $berakhir   = $jwtPayload->tanggal_berlangganan->akhir;
        $disarankan = 'v' . str_replace('-', '', substr($berakhir, 2, 5)) . '.0.0-premium';

        if ($this->isPremiumVersionExpired($berakhir)) {
            $versi_setara = date('Y-m-d', strtotime('+7 month', strtotime($berakhir)));
            $versi_setara = str_replace('-', '', substr($versi_setara, 2, 5)) . '.0.0';
            if (empty($berakhir)) {
                log_message('error', 'Token premium tidak valid.');
                log_message('error', 'Langganan Premium tidak ditemukan. Silakan berlangganan terlebih dahulu atau gunakan versi umum.');
            } else {
                log_message('error', 'Masa aktif berlangganan fitur premium sudah berakhir.');
                log_message('error', "Hanya diperbolehkan menggunakan {$disarankan} (maupun versi revisinya) atau menggunakan versi rilis {$versi_setara} umum.");
            }

            return false;
        }

        return true;
    }

    public function decodeTokenPayload($token)
    {
        $tokenParts   = explode('.', $token);
        $tokenPayload = base64_decode($tokenParts[1], true);

        return json_decode($tokenPayload, null);
    }

    private function isExceptController(): bool
    {
        return in_array(strtolower($this->ci->router->class), $this->kecuali);
    }

    private function isDemoMode(): bool
    {
        return ENVIRONMENT === 'development' || (config_item('demo_mode') && (in_array(get_domain(APP_URL), WEBSITE_DEMO)));
    }

    private function isUmum(): bool
    {
        return PREMIUM === false;
    }

    private function isDesaIdMismatch($jwtPayload): bool
    {
        return version_compare($jwtPayload->desa_id, kode_wilayah($this->ci->header['desa']['kode_desa']), '!=');
    }

    private function isPremiumVersionExpired($berakhir): bool
    {
        $date    = new DateTime('20' . str_replace('.', '-', currentVersion()) . '-01');
        $version = $date->format('Y-m-d');
        if (version_compare($version, $berakhir) > 0) {
            $this->ci->versi_setara = date('Y-m-d', strtotime('+7 month', strtotime($berakhir)));
            $this->ci->versi_setara = str_replace('-', '', substr($this->ci->versi_setara, 2, 5)) . '.0.0';

            return true;
        }

        return false;
    }

    private function isLocalIPAddress(): bool
    {
        return isLocalIPAddress($_SERVER['REMOTE_ADDR']);
    }

    /**
     * Menentukan apakah domain saat ini tidak cocok dengan domain yang diberikan dalam payload JWT.
     *
     * Fungsi ini memeriksa apakah domain dari APP_URL tidak cocok dengan
     * salah satu dari domain (`domain` atau `domain_alternatif`) yang terdapat di dalam JWT payload.
     *
     * @param object $jwtPayload
     */
    private function isDomainMismatch($jwtPayload): bool
    {
        $currentDomain = get_domain(APP_URL);

        if (isset($jwtPayload->domain)) {
            if ($currentDomain === get_domain($jwtPayload->domain)) {
                return false;
            }
        }

        if (isset($jwtPayload->domain_alternatif)) {
            if ($currentDomain === get_domain($jwtPayload->domain_alternatif)) {
                return false;
            }
        }

        return true;
    }

    private function isPremiumDisabled(): bool
    {
        return PREMIUM === false;
    }

    private function daftarHitam(): void
    {
        if (! config_item('demo_mode')) {
            $this->ci->load->library('user_agent');
            if ($this->ci->agent->is_browser()) {
                $browser = $this->ci->agent->browser() . ' ' . $this->ci->agent->version();
            } elseif ($this->ci->agent->is_robot()) {
                $browser = $this->ci->agent->robot();
            } elseif ($this->ci->agent->is_mobile()) {
                $browser = $this->ci->agent->mobile();
            } else {
                $browser = 'Unidentified User Agent';
            }

            $os = $this->ci->agent->platform();

            try {
                $client = new Client();
                $client->post(config_item('server_layanan') . '/api/v1/pelanggan/daftarhitam', [
                    'headers'     => ['X-Requested-With' => 'XMLHttpRequest'],
                    'form_params' => [
                        'kode_desa'  => kode_wilayah($this->ci->header['desa']['kode_desa']),
                        'ip_address' => $this->ci->input->ip_address(),
                        'token'      => $this->ci->setting->layanan_opendesa_token,
                        'waktu'      => date('Y-m-d h:i:sa'),
                        'browser'    => $browser,
                        'os'         => $os,
                        'domain'     => get_domain(APP_URL),
                    ],
                ])->getBody();
            } catch (ClientException $cx) {
                log_message('error', $cx);
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }
        }
    }
}
