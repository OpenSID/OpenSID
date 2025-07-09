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

use App\Libraries\Checker;
use App\Libraries\LogViewer;
use App\Libraries\Sistem;
use App\Models\Area;
use App\Models\Artikel;
use App\Models\BantuanPeserta;
use App\Models\Config;
use App\Models\Dokumen;
use App\Models\DtksLampiran;
use App\Models\Galery;
use App\Models\Garis;
use App\Models\KelompokAnggota;
use App\Models\LaporanSinkronisasi;
use App\Models\LogLogin;
use App\Models\LogPenduduk;
use App\Models\Lokasi;
use App\Models\MediaSosial;
use App\Models\Pembangunan;
use App\Models\PembangunanDokumentasi;
use App\Models\Penduduk;
use App\Models\Pengaduan;
use App\Models\Point;
use App\Models\Simbol;
use App\Models\Widget;
use Modules\Analisis\Models\AnalisisResponBukti;
use Modules\Anjungan\Models\AnjunganMenu;
use Modules\BukuTamu\Models\TamuModel;
use Modules\Lapak\Models\Produk;

defined('BASEPATH') || exit('No direct script access allowed');

class Info_sistem extends Admin_Controller
{
    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'info-sistem';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->helper('directory');
    }

    public function index()
    {
        $data                      = (new LogViewer())->showLogs();
        $data['ekstensi']          = Sistem::cekEkstensi();
        $data['kebutuhan_sistem']  = Sistem::cekKebutuhanSistem();
        $data['php']               = Sistem::cekPhp();
        $data['mysql']             = Sistem::cekDatabase();
        $data['disable_functions'] = Sistem::disableFunctions();
        $data['check_permission']  = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 0 : 1;
        $data['controller']        = $this->controller;
        // $data['total_space']       = $this->convertDisk(disk_total_space('/'));
        $data['disk'] = false;

        return view('admin.setting.info_sistem.index', $data);
    }

    public function remove_log(): void
    {
        isCan('h');
        $path = config_item('log_path');
        $file = base64_decode((string) $this->input->get('f'), true);

        if ($this->input->post()) {
            $files = $this->input->post('id_cb');

            foreach ($files as $file) {
                $file = $path . basename((string) $file);
                unlink($file);
            }

            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function cache_desa(): void
    {
        isCan('u');

        cache()->flush();

        redirect_with('success', 'Berhasil Hapus Cache');
    }

    public function cache_blade(): void
    {
        isCan('u');

        kosongkanFolder('storage/framework/views/');

        redirect_with('success', 'Berhasil Hapus Cache');
    }

    public function set_permission_desa(): void
    {
        isCan('u');

        $dirs   = $_POST['folders'];
        $error  = [];
        $result = ['status' => 1, 'message' => 'Berhasil ubah permission folder desa'];

        foreach ($dirs as $dir) {
            if (! chmod($dir, DESAPATHPERMISSION)) {
                $error[] = 'Gagal mengubah hak akses folder ' . $dir;
            }
        }

        if ($error !== []) {
            $result['status']  = 0;
            $result['message'] = implode('<br />', $error);
        }

        status_sukses(true);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result, JSON_THROW_ON_ERROR));
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(LogLogin::query())
                ->addIndexColumn()
                ->editColumn('lainnya', static function ($q) {
                    if (! $q->lainnya) return '<label class="label label-danger">Tidak ada data</label>';
                    $info = [];

                    foreach ($q->lainnya as $key => $value) {
                        if ($value) {
                            $info[] = '<div><label class="label label-success">' . $key . ' : ' . $value . '</label></div>';
                        }
                    }

                    return implode('', $info);
                })
                ->editColumn('created_at', static fn ($row) => tgl_indo2($row->created_at))
                ->rawColumns(['lainnya'])
                ->make();
        }

        return show_404();
    }

    public function fileDesa()
    {
        view('admin.setting.info_sistem.file_desa', ['files' => $this->listInvalidFile()]);
    }

    private function listInvalidFile()
    {
        $appKey             = get_app_key();
        $excludeFilePattern = '/\.(php|htaccess|html|css)|app_key|favicon.ico|latar_login.jpg|latar_login_mandiri.jpg$/'; // Pattern: ends with .php, .htaccess, or .html
        $excludeDirectory   = [LOKASI_FONT_DESA];
        // Define the directory to scan
        $directoryList = [DESAPATH . 'logo', DESAPATH . 'upload', DESAPATH . 'pengaturan'];
        // Initialize an associative array to hold matching files grouped by directory
        $groupedFiles = [];

        foreach ($directoryList as $directory) {
            // Create a recursive directory iterator
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

            // Loop through each file in the directory and subdirectories
            foreach ($iterator as $file) {
                // Get the directory path
                $dirPath = $file->getPath();
                if ($excludeDirectory) {
                    // Skip if dirPath starts with any of the excluded directories
                    foreach ($excludeDirectory as $excludedDir) {
                        if (Str::contains($dirPath . '/', $excludedDir)) {
                            continue 2; // Skip to the next iteration of the outer loop
                        }
                    }
                }
                // Check if the current item is a file (not a directory)
                if ($file->isFile()) {
                    // Get the filename
                    $filename = $file->getFilename();
                    if (preg_match($excludeFilePattern, $filename)) continue;

                    if (! (new Checker($appKey, $filename))->isValid()) {
                        // Group files by directory
                        if (! isset($groupedFiles[$dirPath])) {
                            $groupedFiles[$dirPath] = []; // Initialize an array for this directory
                        }
                        $groupedFiles[$dirPath][] = $filename; // Add the matching file to the directory's array
                    }
                }
            }
        }

        return $groupedFiles;
    }

    public function perbaikiFileDesa()
    {
        if (! is_super_admin()) {
            redirect_with('error', 'Hanya super admin yang diijinkan untuk memperbaiki file yang tidak valid');
        }
        $groupedFiles = $this->listInvalidFile();
        $mapLokasi    = [
            LOKASI_LOGO_DESA      => [Config::class => ['logo', 'kantor_desa']],
            LOKASI_USER_PICT      => [Penduduk::class => ['foto']],
            LOKASI_FOTO_KELOMPOK  => [KelompokAnggota::class => ['foto']],
            LOKASI_FOTO_LEMBAGA   => [KelompokAnggota::class => ['foto']],
            LOKASI_GALERI         => [PembangunanDokumentasi::class => ['gambar'], Galery::class => ['gambar'], Pembangunan::class => ['foto']],
            LOKASI_FOTO_ARTIKEL   => [Artikel::class => ['gambar', 'gambar1', 'gambar2', 'gambar3']],
            LOKASI_FOTO_BUKU_TAMU => [TamuModel::class => ['foto']],
            LOKASI_FOTO_LOKASI    => [Lokasi::class => ['foto']],
            LOKASI_FOTO_AREA      => [Area::class => ['foto']],
            LOKASI_FOTO_GARIS     => [Garis::class => ['foto']],
            LOKASI_DOKUMEN        => [BantuanPeserta::class => ['kartu_peserta'], Dokumen::class => ['satuan'], LaporanSinkronisasi::class => ['nama_file'], LogPenduduk::class => ['file_akta_mati']],
            LOKASI_PENGESAHAN     => [AnalisisResponBukti::class => ['pengesahan']],
            LOKASI_GAMBAR_WIDGET  => [Widget::class => ['foto']],
            LOKASI_SIMBOL_LOKASI  => [Point::class => ['simbol'], Simbol::class => ['simbol']],
            // cara simpan di produk dalam bentuk array
            //LOKASI_PRODUK             => [Produk::class => ['foto']],
            LOKASI_PENGADUAN          => [Pengaduan::class => ['foto']],
            LOKASI_PENDAFTARAN        => [PendudukMandiri::class => ['scan_ktp', 'scan_kk', 'foto_selfie']],
            LOKASI_ICON_MENU_ANJUNGAN => [AnjunganMenu::class => ['icon']],
            LOKASI_FOTO_DTKS          => [DtksLampiran::class => ['foto']],
            LOKASI_ICON_SOSMED        => [MediaSosial::class => ['gambar']],
            LOKASI_SINERGI_PROGRAM    => [SinergiProgram::class => ['gambar']],
        ];
        // tabel yang menyimpan gambar dengan nama file, tapi menampilkan gambar di web dengan tambahan prefix sedang_, kecil_ dst
        $hasPrefix = [
            Artikel::class,
            Penduduk::class,
            Pembangunan::class,
            Galery::class,
            PembangunanDokumentasi::class,
        ];
        $validPrefix  = ['sedang', 'kecil'];
        $sudahDirubah = [];

        foreach ($hasPrefix as $item) {
            $sudahDirubah[$item] = [];
        }

            if ($groupedFiles) {
                $appKey = get_app_key();

                foreach ($groupedFiles as $key => $files) {
                    $key    = str_replace('\\', '/', $key);
                    $folder = $key . '/';

                    foreach ($files as $file) {
                        if (in_array($folder, [LATAR_LOGIN])) {
                            $newFile = (new Checker($appKey, $file))->encrypt();
                            rename($folder . $file, $folder . $newFile);
                            SettingAplikasi::where('value', $file)->whereIn('key', ['latar_login', 'latar_kehadiran'])->update(['value' => $newFile]);
                        }
                        $tableMap  = $mapLokasi[$folder] ?? [];
                        $adaPrefix = false;

                        foreach ($tableMap as $table => $columns) {
                            $adaPrefix = false;
                            if (in_array($table, $hasPrefix)) {
                                $adaPrefix = true;
                            }
                            $checker   = new Checker($appKey, $file);
                            $newFile   = $checker->encrypt();
                            $fileDb    = $checker->getCurrentName();
                            $newFileDb = $checker->getFileDb();

                            foreach ($columns as $column) {
                                // cek dulu di db, jika ada baru update
                                $adaGambar = (new $table())->where($column, $fileDb)->exists();
                                if ($adaGambar) {
                                    rename($folder . $file, $folder . $newFile);
                                    (new $table())->where($column, $fileDb)->update([$column => $newFileDb]);
                                    $sudahDirubah[$table][$fileDb] = $newFileDb;
                                } else {
                                    // case gambar yang mengandung prefix, di db tidak ada karena sudah diubah sebelumnya oleh gambar yang memiliki prefix lain
                                    if ($adaPrefix) {
                                        if (isset($sudahDirubah[$table][$fileDb])) {
                                            $prefixFile = explode('_', $file);
                                            if (in_array($prefixFile[0], $validPrefix)) {
                                                $newFile = $prefixFile[0] . '_' . $sudahDirubah[$table][$fileDb];
                                                rename($folder . $file, $folder . $newFile);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        cache()->flush();
        redirect_with('success', 'File tidak valid telah diperbaiki');
    }
}
