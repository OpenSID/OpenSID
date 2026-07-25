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

use GuzzleHttp\Client;

/**
 * Klien HTTP tunggal untuk endpoint server Layanan (OpenDesa).
 *
 * Membungkus I/O keluar milik PelangganService/CekService di balik objek yang
 * dapat di-*inject* (GuzzleHttp\Client) agar dapat di-mock pada unit test tanpa
 * jaringan nyata. Base URL dibaca dari `config_item('server_layanan')`.
 */
class LayananClient
{
    private Client $http;

    public function __construct(?Client $http = null)
    {
        $this->http = $http ?? new Client();
    }

    /**
     * Ambil data pemesanan langganan terbaru (POST /api/v1/pelanggan/pemesanan).
     * Melempar exception Guzzle bila status non-2xx (ditangani pemanggil).
     *
     * @return mixed objek respons (body) hasil decode JSON
     */
    public function ambilPemesanan(string $token)
    {
        $response = $this->http->post($this->url('/api/v1/pelanggan/pemesanan'), [
            'headers' => [
                'Authorization'    => "Bearer {$token}",
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ],
        ]);

        return json_decode((string) $response->getBody());
    }

    /**
     * Laporkan percobaan akses tidak sah ke daftar hitam
     * (POST /api/v1/pelanggan/daftarhitam). Fire-and-forget: kegagalan jaringan
     * dicatat, tak menghentikan alur pemanggil.
     *
     * @param array<string, mixed> $formParams
     */
    public function laporDaftarHitam(array $formParams): void
    {
        $this->http->post($this->url('/api/v1/pelanggan/daftarhitam'), [
            'headers'     => ['X-Requested-With' => 'XMLHttpRequest'],
            'form_params' => $formParams,
        ])->getBody();
    }

    /**
     * Catat versi terpasang ke server Layanan (POST /api/v1/pelanggan/catat-versi).
     */
    public function catatVersi(string $kodeDesa, string $versi): void
    {
        $this->http->post($this->url('/api/v1/pelanggan/catat-versi'), [
            'headers'     => ['X-Requested-With' => 'XMLHttpRequest'],
            'form_params' => [
                'kode_desa' => kode_wilayah($kodeDesa),
                'versi'     => $versi,
            ],
        ])->getBody();
    }

    private function url(string $path): string
    {
        return config_item('server_layanan') . $path;
    }
}
