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

use Illuminate\Support\Facades\RateLimiter;
use Modules\Pelanggan\Services\Exceptions\TokenTidakValidException;
use Modules\Pelanggan\Services\TokenDecoder;

defined('BASEPATH') || exit('No direct script access allowed');

class TokenController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        // Halaman ini hanya boleh diakses melalui alur redirect resmi saat token
        // memang kosong/kedaluwarsa (CekService::validasiVersi), bukan diakses
        // langsung — dan tidak lagi bisa diakses begitu ada token tersimpan,
        // agar token yang sudah sah tidak bisa ditimpa lewat halaman ini.

        if ($this->session->token_kosong !== true || ! empty(setting('layanan_opendesa_token'))) {
            redirect('/');
        }
    }

    public function index()
    {
        return view('pelanggan::token.index');
    }

    public function update()
    {
        $key = 'token-update:' . $this->input->ip_address();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return redirect_with('error', 'Terlalu banyak percobaan. Silakan coba lagi beberapa saat lagi.', 'token');
        }

        RateLimiter::hit($key, 60);

        $token = (string) $this->input->post('token');

        try {
            $jwtPayload = app(TokenDecoder::class)->decode($token);
        } catch (TokenTidakValidException $e) {
            return redirect_with('error', $e->getMessage(), 'token');
        }

        if ($this->isPremiumVersionExpired($akhir = $jwtPayload->tanggal_berlangganan->akhir)) {
            return redirect_with('error', "Token Berlangganan sudah berakhir. Tanggal berlangganan sampai: {$akhir}", 'token');
        }

        // Simpan via repository agar cache resolveSetting() otomatis dibuang,
        // sehingga pembacaan setting() berikutnya (mis. di CekService setelah
        // redirect) mengambil nilai token terbaru.
        (new \App\Repositories\SettingAplikasiRepository())->updateWithKey('layanan_opendesa_token', $token);

        RateLimiter::clear($key);
        $this->session->unset_userdata('token_kosong');

        redirect('/');
    }

    private function isPremiumVersionExpired($berakhir): bool
    {
        $date    = new DateTime('20' . str_replace('.', '-', currentVersion()) . '-01');
        $version = $date->format('Y-m-d');

        return version_compare($version, $berakhir) > 0;
    }
}
