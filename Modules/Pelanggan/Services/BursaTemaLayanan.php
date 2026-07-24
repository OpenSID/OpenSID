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

use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Penyedia bursa tema (sisi add-on Pelanggan).
 *
 * Berisi klien langganan tema yang sebelumnya menyatu di core `Theme` controller:
 * mengambil katalog tema bursa, memverifikasi pesanan, dan mengunduh ZIP-nya —
 * semua terautentikasi dengan token langganan (`layanan_opendesa_token`) ke host
 * penyedia bursa (`config('bursa.url_penyedia')`). Didaftarkan ke seam netral
 * {@see \App\Services\Theme\BursaTema} saat boot; core hanya memanggil seam.
 */
class BursaTemaLayanan
{
    /**
     * Ambil & petakan katalog tema bursa ke bentuk baris tema core.
     *
     * @return array<int, array<string, mixed>>
     */
    public function daftar(?string $kategori): array
    {
        $themeApi = [];

        try {
            $page = 1;

            do {
                $response = Http::withToken(setting('layanan_opendesa_token'))
                    ->acceptJson()
                    ->get(config('bursa.url_penyedia') . '/api/v1/themes', [
                        'kategori' => match ($kategori) {
                            'umum'    => 1,
                            'premium' => 2,
                            default   => null,
                        },
                        'page'     => $page,
                        'per_page' => 100,
                    ])
                    ->throw()
                    ->json();

                $data     = $response['data'] ?? [];
                $themeApi = array_merge($themeApi, $data);

                $lastPage = $response['meta']['last_page'] ?? 1;
                $page++;
            } while ($page <= $lastPage);

            return collect($themeApi)->map(static fn ($theme) => [
                'id'           => null,
                'config_id'    => null,
                'nama'         => $theme['name'],
                'slug'         => "desa-{$theme['alias']}",
                'versi'        => $theme['version'],
                'sistem'       => 0,
                'path'         => null,
                'status'       => false,
                'keterangan'   => $theme['description'],
                'opsi'         => null,
                'created_at'   => $theme['created_at'],
                'updated_at'   => $theme['updated_at'],
                'full_path'    => null,
                'view_path'    => null,
                'asset_path'   => null,
                'thumbnail'    => $theme['thumbnail'] ?? null,
                'price'        => $theme['price'] ?? null,
                'url'          => $theme['url'] ?? null,
                'totalInstall' => $theme['totalInstall'] ?? 0,
                'marketplace'  => true,
                'providers'    => $theme['providers'] ?? null,
            ])->toArray();
        } catch (Throwable $e) {
            logger()->error($e);

            return [];
        }
    }

    /**
     * Verifikasi nama tema terdaftar di pesanan desa; kembalikan pesan galat atau
     * `null` bila valid.
     */
    public function validasiPesanan(string $attribute, string $nama): ?string
    {
        $response = Http::withToken(setting('layanan_opendesa_token'))
            ->acceptJson()
            ->post(config('bursa.url_penyedia') . '/api/v1/themes', [$attribute => $nama]);

        if ($response->failed()) {
            return $response->json('message', 'Data pemesanan tidak terdaftar / salah');
        }

        return null;
    }

    /**
     * Unduh ZIP tema terautentikasi ke berkas sementara; kembalikan path-nya, atau
     * `null` bila gagal.
     */
    public function unduh(string $url): ?string
    {
        try {
            $path = sys_get_temp_dir() . '/' . mt_rand(1000, 9999) . '-tema.zip';

            Http::withToken(setting('layanan_opendesa_token'))
                ->acceptJson()
                ->withOptions(['sink' => $path])
                ->throw()
                ->get($url);

            return $path;
        } catch (Throwable $e) {
            logger()->error($e);

            return null;
        }
    }
}
