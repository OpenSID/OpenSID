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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Repositories\SettingAplikasiRepository;
use CI_Controller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Anjungan\Models\Anjungan;

class PelangganService
{
    /**
     * @var Client HTTP Client
     */
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Ambil status langganan dari api layanan.opendeda.id
     */
    public static function statusLangganan(): ?array
    {
        if (empty($response = self::apiPelangganPemesanan()) || config_item('demo_mode')) {
            return null;
        }

        $tgl_akhir = $response->body->tanggal_berlangganan->akhir;

        if (empty($tgl_akhir)) { // pemesanan bukan premium
            if ($response->body->pemesanan) {
                foreach ($response->body->pemesanan as $pemesanan) {
                    $akhir[] = $pemesanan->tgl_akhir;
                }

                $masa_berlaku = calculate_date_intervals($akhir);
            }
        } else { // pemesanan premium
            $tgl_akhir    = strtotime($tgl_akhir);
            $masa_berlaku = round(($tgl_akhir - time()) / (60 * 60 * 24));
        }

        $status = match (true) {
            $masa_berlaku > 30 => ['status' => 1, 'warna' => 'lightgreen', 'ikon' => 'fa-battery-full'],
            $masa_berlaku > 10 => ['status' => 2, 'warna' => 'orange', 'ikon' => 'fa-battery-half'],
            default            => ['status' => 3, 'warna' => 'pink', 'ikon' => 'fa-battery-empty'],
        };
        $status['masa'] = $masa_berlaku;

        return $status;
    }

    public static function statusPercobaan(): ?array
    {
        $token = setting('layanan_opendesa_token');

        if (empty($token)) {
            return null;
        }

        $jwtPayload = (new CekService())->decodeTokenPayload($token);

        if (empty($jwtPayload->tanggal_berlangganan->percobaan) || $jwtPayload->tanggal_berlangganan->percobaan !== true) {
            return null; // bukan trial
        }

        $akhirPercobaan = $jwtPayload->tanggal_berlangganan->akhir_percobaan ?? null;
        if (empty($akhirPercobaan)) {
            return null;
        }

        $sisaHari = (strtotime($akhirPercobaan) - time()) / (60 * 60 * 24);

        if ($sisaHari < 0) {
            return null; // trial habis
        }

        return [
            'status' => 1,
            'akhir'  => $akhirPercobaan,
            'sisa'   => round($sisaHari),
        ];
    }

    /**
     * Ambil data pemesanan dari api layanan.opendeda.id
     *
     * @return mixed
     */
    public static function apiPelangganPemesanan()
    {
        $ci = get_instance();
        $ci->load->driver(['cache', 'session']);

        if (empty(setting('layanan_opendesa_token'))) {
            app('ci')->session->set_userdata('error_status_langganan', 'Token Pelanggan Kosong.');

            return null;
        }

        if ($cache = app('ci')->cache->file->get('status_langganan')) {
            // set_session('error_status_langganan', 'Tunggu sebentar, halaman akan dimuat ulang.');
            app('ci')->session->set_userdata('error_status_langganan', 'Tunggu sebentar, halaman akan dimuat ulang.');

            return $cache;
        }

        return null;
    }

    public static function perbaruiLangganan()
    {
        $ci = app()->make('ci');

        $perbaharui = $ci->header['perbaharui_langganan'] ?? null && $ci->controller != 'pengguna' && ! config_item('demo_mode');

        if ($perbaharui) {
            try {
                $response = Http::withHeaders([
                    'Authorization'    => "Bearer {$ci->list_setting->firstWhere('key', 'layanan_opendesa_token')?->value}",
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Accept'           => 'application/json',
                ])
                    ->throw()
                    ->post(config_item('server_layanan') . '/api/v1/pelanggan/pemesanan');

                static::pemesanan($ci, (object) ['body' => $response->object()]);
            } catch (Exception $e) {
                Log::error($e);
            }
        }
    }

    private static function pemesanan(CI_Controller $ci, object $data)
    {
        $ci->load->helper('file');

        $token      = $data->body->token ?? null;
        $desaId     = $data->body->desa_id ?? null;
        $kodeDesa   = kode_wilayah($ci->header['desa']['kode_desa']);
        $configPath = LOKASI_CONFIG_DESA . '/config.php';

        if (empty($token)) {
            logger()->error('Token tidak ditemukan dalam response API layanan. Harap periksa kembali response dari server.');

            return;
        }

        if (! isset($data->body) || empty($data->body)) {
            logger()->error('Response data pemesanan dari API layanan kosong atau tidak valid.');

            return;
        }

        if (config_item('demo_mode')) {
            logger()->error('Tidak dapat mengganti token pada website demo.');

            return;
        }

        if ($desaId != $kodeDesa) {
            $namaDesa = ucwords(setting('sebutan_desa') . ' ' . $ci->header['desa']['nama_desa']);
            $server   = config_item('server_layanan');

            logger()->error("{$namaDesa} tidak terdaftar di {$server} atau Token tidak sesuai dengan kode desa.");

            return;
        }

        // Hapus cache lama
        hapus_cache('status_langganan');
        cache()->forget('identitas_desa');

        // Update token di file config
        if (config_item('token_layanan')) {
            $config  = file($configPath);
            $updated = array_map(
                static fn ($line) => stristr($line, 'token_layanan')
                    ? "\$config['token_layanan']  = '{$token}';\n"
                    : $line,
                $config
            );
            file_put_contents($configPath, implode('', $updated));
        }

        // Simpan token ke DB
        (new SettingAplikasiRepository())->updateWithKey('layanan_opendesa_token', $token);

        // Simpan cache baru
        $ci->cache->pakai_cache(static fn () => $data, 'status_langganan', 60 * 60 * 24 * 365 * 30); // 30 tahun (forever)

        // Update status Anjungan
        Anjungan::where('tipe', '1')
            ->where('status', '0')
            ->where('status_alasan', 'tidak berlangganan anjungan')
            ->update(['status' => '1']);

        logger()->info('Token berhasil tersimpan.');
    }
}
