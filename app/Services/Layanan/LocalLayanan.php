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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Services\Layanan;

use App\Repositories\SettingAplikasiRepository;
use Throwable;

/**
 * Surrogat Layanan berbasis DATA LANGGANAN untuk PENGEMBANGAN — pelengkap
 * {@see \App\Services\Module\LocalMarketplace} (yang mensimulasi sisi bursa
 * paket). Sementara LocalMarketplace melayani katalog/instalasi modul tanpa
 * server Layanan, kelas ini melayani **status langganan pelanggan** tanpa
 * server Layanan: ia membangun respons pemesanan (seperti yang dikembalikan
 * `POST /api/v1/pelanggan/pemesanan` di Layanan) dari identitas desa lokal,
 * lalu menuliskannya ke cache `status_langganan` — cache yang sama yang dibaca
 * halaman Pelanggan (`/pelanggan`). Dengan begitu halaman menampilkan data
 * "seolah datang dari Layanan" saat tersambung ke bursa lokal.
 *
 * Vendor-netral & tak menyentuh modul: menulis langsung ke cache berkas CI3
 * (bentuk identik dengan yang ditulis klien langganan saat online), sehingga
 * inti tetap nol-pengetahuan terhadap `Modules\Pelanggan`.
 *
 * DEV-ONLY & di-`export-ignore` (tak ikut rilis). Dipanggil hanya dari
 * {@see \Dev_modul} (tab "Sumber" Paket Tambahan), yang juga dev-only.
 */
class LocalLayanan
{
    /** Kunci cache status langganan (dibaca modul Pelanggan pada `/pelanggan`). */
    private const CACHE_KEY = 'status_langganan';

    /** TTL cache: 30 tahun (≈ permanen), sama dengan penulisan klien langganan. */
    private const CACHE_TTL = 60 * 60 * 24 * 365 * 30;

    /** Token simulasi bila desa belum punya token langganan tersimpan. */
    private const TOKEN_SIMULASI = 'simulasi-layanan-lokal-token';

    /**
     * Isi cache `status_langganan` dengan data langganan simulasi + pastikan
     * ada token pelanggan (halaman `/pelanggan` menolak render tanpa token).
     * Idempoten: memanggil ulang menyegarkan data (mtime baru).
     */
    public function simulasikan(): void
    {
        $token = (string) setting('layanan_opendesa_token');

        if ($token === '') {
            $token = self::TOKEN_SIMULASI;
            (new SettingAplikasiRepository())->updateWithKey('layanan_opendesa_token', $token);
        }

        $data = (object) ['body' => $this->body($token)];

        $cache = $this->cache();
        $cache?->file->save(self::CACHE_KEY, $data, self::CACHE_TTL);
    }

    /**
     * Hapus data langganan simulasi (kosongkan cache). Token tak diutak-atik —
     * pengelolaannya lewat Pengaturan Pelanggan seperti biasa.
     */
    public function kosongkan(): void
    {
        $this->cache()?->file->delete(self::CACHE_KEY);
    }

    /**
     * Bangun body respons pemesanan (bentuk `apiPelangganPemesanan()->body`)
     * dari identitas desa lokal. `desa_id` dibuat cocok dengan kode wilayah
     * desa agar konsisten dengan pemeriksaan klien langganan.
     *
     * @return object bentuk stdClass bersarang (identik hasil json_decode)
     */
    public function body(string $token): object
    {
        $kodeDesa = (string) identitas('kode_desa');
        $mulai    = date('Y-m-d', strtotime('-1 year'));
        $akhir    = date('Y-m-d', strtotime('+1 year'));
        $hosting  = date('Y-m-d', strtotime('+6 months'));

        // Layanan dasar: Hosting (selalu ada agar struktur langganan lengkap).
        $layanan = [
            [
                'id'            => 1,
                'nama'          => 'Hosting (SIMULASI LOKAL)',
                'number'        => 'HOST-01',
                'nama_kategori' => 'Hosting',
                'kategori_id'   => 1,
                'tanggal_mulai' => $mulai,
                'tanggal_akhir' => $hosting,
            ],
        ];

        // Fitur berbayar yang aktif — DITURUNKAN dari modul premium yang benar-
        // benar TERPASANG lokal (bukan placeholder statis). Tiap modul premium jadi
        // satu layanan + penanda fitur 'aktif' di tanggal_berlangganan (dibaca
        // SumberStatusFitur::active(), mis. AnjunganEntitlement).
        $tanggalBerlangganan = ['mulai' => $mulai, 'akhir' => $akhir];
        $id                  = 2;

        foreach ($this->modulPremiumTerpasang() as $modul) {
            $layanan[] = [
                'id'            => $id,
                'nama'          => $modul['nama'] . ' (SIMULASI LOKAL)',
                'number'        => 'MOD-' . $id,
                'nama_kategori' => 'Premium',
                'kategori_id'   => 4, // KATEGORI_PREMIUM → tier 'premium'
                'tanggal_mulai' => $mulai,
                'tanggal_akhir' => $akhir,
            ];

            if ($modul['entitlement'] !== '') {
                $tanggalBerlangganan[$modul['entitlement']] = 'aktif';
            }

            $id++;
        }

        $payload = [
            'id'               => 1,
            'user_id'          => 1,
            'desa_id'          => kode_wilayah($kodeDesa),
            'desa'             => [
                'kode_desa' => $kodeDesa,
                'nama_desa' => (string) identitas('nama_desa'),
                'nama_kec'  => (string) identitas('nama_kecamatan'),
                'nama_kab'  => (string) identitas('nama_kabupaten'),
                'nama_prov' => (string) identitas('nama_propinsi'),
            ],
            'domain'           => $this->domain(),
            // Daftar kontak (blade meng-iterasi `kontak as $kontak` → `$kontak->nama`).
            'kontak'           => [
                ['nama' => (string) (identitas('nama_kontak') ?: 'Admin Desa')],
                ['nama' => (string) (identitas('email_desa') ?: 'admin@desa.example')],
            ],
            'token'            => $token,
            'status_langganan' => 'aktif',
            'tanggal_berlangganan' => $tanggalBerlangganan,
            'pemesanan' => [
                [
                    'id'                => 1001,
                    'faktur'            => 'INV-SIMULASI-0001',
                    'status_pemesanan'  => 'aktif',
                    'status_pembayaran' => 'lunas',
                    'tampilkan_faktur'  => 1,
                    'mitra_id'          => null,
                    'tgl_mulai'         => $mulai,
                    'tgl_akhir'         => $akhir,
                    'layanan'           => $layanan,
                ],
            ],
        ];

        // Round-trip JSON → stdClass bersarang (array JSON tetap array PHP),
        // sama persis dengan hasil json_decode respons HTTP Layanan asli.
        return json_decode((string) json_encode($payload));
    }

    /**
     * Modul premium (butuh entitlement) yang TERPASANG di folder `Modules/`.
     * Dipakai membangun langganan simulasi yang mencerminkan instalasi lokal.
     *
     * @return list<array{nama: string, entitlement: string, versi: string}>
     */
    private function modulPremiumTerpasang(): array
    {
        $dir = $this->modulesDir();
        if ($dir === '' || ! is_dir($dir)) {
            return [];
        }

        $hasil = [];

        foreach (glob(rtrim($dir, '/\\') . '/*/module.json') ?: [] as $manifest) {
            $meta = json_decode((string) file_get_contents($manifest), true);

            if (! is_array($meta) || empty($meta['requires_entitlement'])) {
                continue;
            }

            $hasil[] = [
                'nama'        => (string) ($meta['name'] ?? basename(dirname($manifest))),
                'entitlement' => (string) ($meta['entitlement'] ?? ''),
                'versi'       => (string) ($meta['version'] ?? ''),
            ];
        }

        return $hasil;
    }

    /**
     * Folder induk modul (`Modules/`), dari config CI3 bila ada.
     */
    private function modulesDir(): string
    {
        if (function_exists('config_item')) {
            $lokasi = config_item('modules_locations');
            if (is_array($lokasi) && $lokasi !== []) {
                return rtrim((string) array_key_first($lokasi), '/\\');
            }
        }

        return function_exists('base_path') ? base_path('Modules') : '';
    }

    /**
     * Domain desa (tanpa skema) untuk kolom `body->domain`.
     */
    private function domain(): string
    {
        $base = function_exists('base_url') ? (string) base_url() : (defined('APP_URL') ? (string) APP_URL : '');

        return (string) (parse_url(rtrim($base, '/'), PHP_URL_HOST) ?: $base);
    }

    /**
     * Driver cache CI3 (hidup di konteks web). Null di artisan murni.
     */
    private function cache(): ?object
    {
        try {
            $ci = function_exists('get_instance') ? get_instance() : null;
        } catch (Throwable) {
            return null;
        }

        if ($ci === null) {
            return null;
        }

        $ci->load->driver('cache');

        return $ci->cache ?? null;
    }
}
