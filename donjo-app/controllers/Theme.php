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

use App\Actions\Theme\ActivateTheme;
use App\Models\Theme as ThemeModel;
use App\Services\Theme\BursaTema;
use App\Services\Theme\SumberTemaBursa;
use App\Traits\Upload;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

defined('BASEPATH') || exit('No direct script access allowed');

class Theme extends Admin_Controller
{
    use Upload;

    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'theme';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->helper('theme');
    }

    public function index()
    {
        theme_active();

        $kategori    = request()->get('kategori');
        $currentPage = request()->get('page', 1);
        $perPage     = 10;

        // status_pemesanan (aktif/tidak aktif) melekat pada objek Pemesanan
        // INDUK, bukan tiap baris $layanan -- HARUS difilter di sini,
        // sebelum flatMap membuang objek induknya, sama seperti pola yang
        // sudah benar di ModulTrait::getLayananModul() (Premium). Tanpa ini,
        // tema berlangganan berbatas waktu (mis. Lestari, lihat
        // Layanan#1341) akan tetap tampil sebagai "sudah dipesan, bisa
        // diunduh" bahkan setelah langganannya kedaluwarsa -- filter lama
        // cuma memastikan nama_kategori 'Tema', tak pernah memeriksa apakah
        // pesanannya masih aktif (perilaku ini tak pernah ketahuan karena
        // semua tema sebelum Lestari berlisensi perpetual, jadi
        // status_pemesanan selalu 'aktif').
        $themeOrder = collect(app(SumberTemaBursa::class)->pemesanan()?->body?->pemesanan ?? [])
            ->filter(static fn ($item) => ($item->status_pemesanan ?? null) === 'aktif')
            ->flatMap(static fn ($item) => collect($item?->layanan ?? [])
                ->map(static fn ($layanan) => (array) $layanan))
            ->filter(static fn ($layanan) => ($layanan['nama_kategori'] ?? null) === 'Tema');

        // Fetch all themes from database without pagination
        // Filter kategori (tab "Umum"/"Tema Pro") pakai kolom `kategori`
        // (label distribusi, dari theme.json paket tema) -- BUKAN `sistem`
        // (lokasi folder, tetap dipakai utk urutan + proteksi hapus/edit).
        // Padanan Premium -- lihat App\Models\Theme::KATEGORI_UMUM/
        // KATEGORI_PREMIUM. Tab "Tema Pro" mencakup KEDUA KATEGORI_PREMIUM
        // (bisa dibeli satuan) dan KATEGORI_PREMIUM_EKSKLUSIF (bonus
        // eksklusif langganan Premium, mis. Wira) -- beda hanya teks ribbon.
        $themeModel = ThemeModel::query()
            ->when($kategori == 'umum', static fn ($query) => $query->where('kategori', ThemeModel::KATEGORI_UMUM))
            ->when($kategori == 'premium', static fn ($query) => $query->whereIn('kategori', [ThemeModel::KATEGORI_PREMIUM, ThemeModel::KATEGORI_PREMIUM_EKSKLUSIF]))
            ->orderBy('sistem', 'desc')
            ->orderBy('versi', 'desc')
            ->get();

        $allThemes = $themeModel->toArray();

        // Katalog tema bursa dari penyedia add-on (mis. modul Pelanggan). Core OSS
        // tanpa modul → daftar kosong (hanya tema lokal ditampilkan).
        $themeApiMapped = app(BursaTema::class)->daftar($kategori);
        $allThemes      = collect($allThemes)->merge($themeApiMapped)->toArray();

        // Group themes by normalized slug and get the latest version of each theme
        $groupedThemes = collect($allThemes)
            ->groupBy(static function ($theme) {
                // Hapus prefix 'desa-' dari slug untuk pengelompokan yang konsisten
                return preg_replace('/^(desa-)+/', '', $theme['slug']);
            })
            ->map(static function ($group) {
                // Cari apakah ada data tema lokal (dari database) dan remote (dari API)
                $dbTheme = $group->first(static fn ($t) => !($t['marketplace'] ?? false));
                $apiTheme = $group->first(static fn ($t) => ($t['marketplace'] ?? false));

                if ($dbTheme && $apiTheme) {
                    $vDb = ltrim($dbTheme['versi'], 'vV');
                    $vApi = ltrim($apiTheme['versi'], 'vV');

                    // Jika versi API lebih baru, pakai versi API tapi pertahankan status instalasi lokal (id, path, status, dll)
                    if (version_compare($vApi, $vDb, '>')) {
                        return array_merge($dbTheme, [
                            'versi'      => $apiTheme['versi'],
                            'thumbnail'  => $apiTheme['thumbnail'] ?? $dbTheme['thumbnail'],
                            'keterangan' => $apiTheme['keterangan'] ?? $dbTheme['keterangan'],
                        ]);
                    }

                    return $dbTheme;
                }

                // Jika hanya ada salah satu (DB saja atau API saja), pilih versi terbaru di dalam grup tersebut
                return $group->reduce(
                    static function ($latest, $current) {
                        $vCurrent = ltrim($current['versi'], 'vV');
                        $vLatest = ltrim($latest['versi'], 'vV');
                        return version_compare($vCurrent, $vLatest, '>') ? $current : $latest;
                    },
                    $group->first()
                );
            })
            ->values()
            ->sortBy([
                ['status', 'desc'], // Aktif tema di atas
                ['sistem', 'desc'], // Tema sistem di atas tema premium
                ['nama', 'asc'], // Pengurutan berdasarkan nama untuk konsistensi paginasi
            ])
            ->toArray();

        // Apply pagination on the merged and deduplicated list
        $themeList = new LengthAwarePaginator(
            array_slice($groupedThemes, ($currentPage - 1) * $perPage, $perPage),
            count($groupedThemes),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.theme.index', compact('kategori', 'themeOrder', 'themeList'));
    }

    public function unggah()
    {
        // isMultiDB();
        // isSiapPakai();
        isCan('u');

        $form_action = site_url('theme/proses-unggah');

        return view('admin.theme.unggah', ['form_action' => $form_action]);
    }

    public function unduh()
    {
        isCan('u');

        // Host penyedia bursa (netral, dapat di-override) — validasi kepercayaan-asal
        // tetap di core; unduhan terautentikasi + verifikasi pesanan milik add-on.
        $penyediaHost = parse_url((string) config('bursa.url_penyedia'), PHP_URL_HOST);
        $bursaTema    = app(BursaTema::class);

        $data = $this->validated(request(), [
            'url' => [
                'required',
                'url',
                static function ($attribute, $value, $fail) use ($penyediaHost) {
                    $urlScheme = parse_url($value, PHP_URL_SCHEME);
                    $urlHost   = parse_url($value, PHP_URL_HOST);

                    if ($urlScheme !== 'https') {
                        $fail('URL harus menggunakan HTTPS');
                    }

                    if ($urlHost !== $penyediaHost) {
                        $fail("Domain URL harus sama dengan {$penyediaHost}");
                    }
                },
            ],
            'nama' => [
                'required',
                'string',
                static function ($attribute, $value, $fail) use ($bursaTema) {
                    if (($pesan = $bursaTema->validasiPesanan($attribute, $value)) !== null) {
                        $fail($pesan);
                    }
                },
            ],
        ]);

        try {
            $path = $bursaTema->unduh($data['url']);

            if ($path === null) {
                redirect_with('error', 'Gagal mengunduh tema');

                return;
            }

            $tema = $this->extractAndValidateTheme(['full_path' => $path]);

            redirect_with($tema['status'] ? 'success' : 'error', $tema['data']);
        } catch (Throwable $e) {
            logger()->error($e);

            redirect_with('error', 'Gagal mengunduh tema');
        }
    }

    public function proses_unggah(): void
    {
        // isMultiDB();
        // isSiapPakai();
        isCan('u');

        $tema = $this->unggah_tema();

        redirect_with($tema['status'] ? 'success' : 'error', $tema['data']);
    }

    public function pengaturan($id = '')
    {
        isCan('u');

        $tema = ThemeModel::findOrFail($id);

        $form_action = site_url("theme/ubah-pengaturan/{$id}");

        return view('admin.theme.pengaturan', ['form_action' => $form_action, 'tema' => $tema]);
    }

    public function ubah_pengaturan($id = ''): void
    {
        isCan('u');

        $tema = ThemeModel::findOrFail($id);

        $opsi = $this->validateOpsi($this->input->post('opsi'), $tema);

        $tema->update(['opsi' => $opsi]);

        redirect_with('success', 'Berhasil Ubah Data', "theme/pengaturan/{$id}");
    }

    public function salin_config($id = ''): void
    {
        isCan('u');

        $tema = ThemeModel::findOrFail($id);

        if ($tema->sistem) {
            redirect_with('error', 'Tidak dapat menambahkan config pada tema sistem');
        }

        $sumber = FCPATH . 'storage/app/template/ekspor/config_tema.json';
        $tujuan = FCPATH . $tema->path . '/config.json';

        if (copy($sumber, $tujuan)) {
            redirect_with('success', 'Berhasil Salin Config', "theme/pengaturan/{$id}");
        }

        redirect_with('error', 'Gagal Salin Config', "theme/pengaturan/{$id}");
    }

    public function aktifkan($id = null): void
    {
        isCan('u');

        // rencana-refaktor-tema-siappakai.md §1.4/Fase 7: dipindah ke
        // App\Actions\Theme\ActivateTheme (padanan Premium, diporting di sini
        // krn Umum sebelumnya mengaktifkan tema inline tanpa gerbang
        // entitlement apa pun -- perlu SEKARANG krn Umum juga bisa dihost
        // SiapPakai, Opensid::UMUM, yang menjalankan Fase 1 sync yang sama).
        (new ActivateTheme())->handle($id);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    public function delete($id = ''): void
    {
        // isMultiDB();
        // isSiapPakai();
        isCan('h');

        $delete = ThemeModel::findOrFail($id);

        if ($delete->status) {
            redirect_with('error', 'Tema yang aktif tidak dapat dihapus');
        }

        if ($delete->sistem) {
            redirect_with('error', 'Tema sistem tidak dapat dihapus');
        }

        if ($delete->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function pindai(): void
    {
        isCan('u');

        theme_scan();

        redirect_with('success', 'Berhasil Memindai Tema');
    }

    protected function unggah_tema()
    {
        $this->load->library('Upload');

        $nama_tema               = mt_rand(1000, 9999) . '-tema';
        $config['upload_path']   = sys_get_temp_dir();
        $config['allowed_types'] = 'zip';
        $config['overwrite']     = true;
        $config['max_size']      = max_upload() * 5 * 1024;
        $config['file_name']     = $nama_tema . '.zip';

        $this->upload->initialize($config);

        if ($this->upload->do_upload('userfile')) {
            $upload = $this->upload->data();

            return $this->extractAndValidateTheme($upload);
        }

        return [
            'status' => false,
            'data'   => $this->upload->display_errors(),
        ];
    }

    protected function extractAndValidateTheme($upload)
    {
        $zip = new ZipArchive();

        if ($zip->open($upload['full_path']) !== true) {
            unlink($upload['full_path']);

            return [
                'status' => false,
                'data'   => 'Tema tidak valid',
            ];
        }

        $lokasi_ekstrak = FCPATH . 'desa/themes/';
        $subfolder      = $zip->getNameIndex(0);
        $zip->extractTo($lokasi_ekstrak);
        $zip->close();

        $lokasi_tema = $lokasi_ekstrak . substr($subfolder, 0, -1);

        if (! file_exists($lokasi_tema . '/resources/views/template.blade.php')) {
            delete_files($lokasi_tema, true);

            return [
                'status' => false,
                'data'   => 'Tema tidak valid',
            ];
        }

        theme_scan();

        return [
            'status' => true,
            'data'   => 'Berhasil Unggah Tema',
        ];
    }

    protected function validateOpsi($opsi, $tema)
    {
        $opsi = [];

        foreach ($tema->config as $config) {
            $key      = $config['key'];
            $postOpsi = $this->input->post('opsi')[$key] ?? null;

            if ($config['type'] == 'unggah') {
                if (request()->file($key)?->isValid()) {
                    $opsi[$key] = $this->imageUpload($tema, $key);
                } else {
                    $opsi[$key] = $tema->opsi[$key] ?? '';
                }

                $opsi['url_' . $key] = $this->input->post('opsi')['url_' . $key] ?? '';
            } else {
                $opsi[$key] = $postOpsi;
            }
        }

        return $opsi;
    }

    protected function imageUpload($tema, $key)
    {
        $namaTema = $tema->slug;

        return $this->upload(
            file: $key,
            config: [
                'upload_path'   => CONFIG_THEMES . $namaTema,
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => max_upload() * 1024,
                'overwrite'     => true,
            ],
            callback: static function ($uploadData) use ($tema, $key, $namaTema) {
                Image::load($uploadData['full_path'])
                    ->format(Manipulations::FORMAT_WEBP)
                    ->save("{$uploadData['file_path']}{$uploadData['raw_name']}.webp");

                // Hapus original file
                unlink($uploadData['full_path']);

                // Hapus file lama jika ada karena overwrite tidak berfungsi pada kasus ini?
                if (file_exists($old = FCPATH . $tema->opsi[$key])) {
                    unlink($old);
                }

                return CONFIG_THEMES . "{$namaTema}/{$uploadData['raw_name']}.webp";
            }
        );
    }
}
