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

use App\Models\Theme as ThemeModel;
use App\Traits\Upload;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Modules\Pelanggan\Services\PelangganService;
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

        $themeList = $themeModel = ThemeModel::query()
            ->when($kategori, static fn ($query) => $query->where('sistem', $kategori))
            ->orderBy('sistem', 'desc')
            ->paginate($perPage);

        $themeOrder = collect(PelangganService::apiPelangganPemesanan()?->body?->pemesanan ?? [])
            ->flatMap(static fn ($item) => collect($item?->layanan ?? [])
                ->map(static fn ($layanan) => (array) $layanan))
            ->filter(static fn ($layanan) => ($layanan['nama_kategori'] ?? null) === 'Tema');

        try {
            $response = Http::withToken(setting('layanan_opendesa_token'))
                ->acceptJson()
                ->get(config_item('server_layanan') . '/api/v1/themes', [
                    'kategori' => $kategori,
                    'page'     => $currentPage,
                    'per_page' => $perPage,
                ])
                ->throw()
                ->json();

            $themeApi = collect($response['data'])->map(static fn ($theme) => new ThemeModel([
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
            ]));

            $mergedThemes = collect($themeModel->items())->merge($themeApi->toArray())->unique('slug');

            $themeList = new LengthAwarePaginator(
                $mergedThemes,
                $themeModel->total() + $response['meta']['total'],
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } catch (Throwable $e) {
            logger()->error($e);
        }

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
        $data = $this->validated(request(), [
            'url'  => 'required|url',
            'nama' => [
                'required',
                'string',
                static function ($attribute, $value, $fail) {
                    $response = Http::withToken(setting('layanan_opendesa_token'))
                        ->acceptJson()
                        ->post(config_item('server_layanan') . '/api/v1/themes', [$attribute => $value]);

                    if ($response->failed()) {
                        $errorMessage = $response->json('message', 'Data pemesanan tidak terdaftar / salah');
                        $fail($errorMessage);
                    }
                },
            ],
        ]);

        try {
            Http::withToken(setting('layanan_opendesa_token'))
                ->acceptJson()
                ->withOptions(['sink' => $path = sys_get_temp_dir() . '/' . mt_rand(1000, 9999) . '-tema.zip'])
                ->throw()
                ->get($data['url']);

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

        $status = ThemeModel::findOrFail($id);
        $status->update(['status' => 1]);

        ThemeModel::where('id', '!=', $id)->update(['status' => 0]);

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

    public function pindai(): void
    {
        isCan('u');

        theme_scan();

        redirect_with('success', 'Berhasil Memindai Tema');
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
