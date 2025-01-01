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

use App\Models\SettingAplikasi;

defined('BASEPATH') || exit('No direct script access allowed');

class Token extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        if ($this->session->token_kosong === false) {
            redirect();
        }
    }

    public function index()
    {
        return view('token.index');
    }

    public function update()
    {
        $jwtPayload = $this->decodeTokenPayload($token = $this->input->post('token'));

        if ($this->isPremiumVersionExpired($akhir = $jwtPayload->tanggal_berlangganan->akhir)) {
            return redirect_with('error', "Token Berlangganan sudah berakhir. Tanggal berlangganan sampai: {$akhir}", 'token');
        }

        if ($token) {
            // ini bisa otomatis invalidated cache
            (SettingAplikasi::where('key', 'layanan_opendesa_token')->first())
                ->update(['value' => $token]);

            $this->session->unset_userdata('token_kosong');
        }

        redirect(site_url());
    }

    private function decodeTokenPayload($token)
    {
        $tokenParts = explode('.', (string) $token);

        if (count($tokenParts) !== 3) {
            return redirect_with('error', 'Jumlah segmen token salah', 'token');
        }

        $tokenParts = array_filter(array_map('trim', $tokenParts));

        if (count($tokenParts) !== 3 || implode('.', $tokenParts) !== $token) {
            return redirect_with('error', 'Token tidak sesuai', 'token');
        }

        $tokenPayload = base64_decode($tokenParts[1], true);

        return json_decode($tokenPayload, null);
    }

    private function isPremiumVersionExpired($berakhir): bool
    {
        $date    = new DateTime('20' . str_replace('.', '-', currentVersion()) . '-01');
        $version = $date->format('Y-m-d');

        return version_compare($version, $berakhir) > 0;
    }
}
