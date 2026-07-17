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

use App\Services\Module\ModuleManager;
use Illuminate\Support\Facades\File;

defined('BASEPATH') || exit('No direct script access allowed');

class Install_modul extends CI_Controller
{
    private readonly int|string $modulesDirectory;

    public function __construct()
    {
        parent::__construct();
        $this->modulesDirectory = array_keys(config_item('modules_locations') ?? [])[0] ?? '';
    }

    /**
     * $namaModulVersi berisi namaModul__urlDownload__versiModul
     * digunakan untuk proses development dan instalasi modul pada siappakai
     * asumsinya folder module tersebut sudah ada, tinggal jalankan proses migrasi saja
     * contoh
     * php index.php modul pasang Prodeskel___dowload
     */
    public function pasang(string $namaModulVersi): void
    {
        [$name, $url, $version] = explode('___', $namaModulVersi);

        // Folder modul diasumsikan sudah ada; instalasi baru bila belum pernah ada.
        $pasangBaru = ! File::exists($this->modulesDirectory . $name);

        $manager = app(ModuleManager::class);

        // Tegakkan min_core + migrasi via implementasi tunggal ModuleManager.
        try {
            $manager->install($name);
        } catch (RuntimeException $e) {
            log_message('error', "Paket {$name} tidak dipasang: {$e->getMessage()}");

            return;
        }

        if ($pasangBaru) {
            $manager->reportInstall($name, $version);
        }

        log_message('notice', 'Paket ' . $name . ' berhasil dipasang');
    }

    /**
     * $namaModulVersi berisi namaModul__urlDownload__versiModul
     * digunakan untuk proses development dan instalasi modul pada siappakai
     * asumsinya folder module tersebut sudah ada, tinggal jalankan proses migrasi saja
     * contoh
     * php index.php hapus pasang Prodeskel
     */
    public function hapus(string $namaModulVersi): void
    {
        try {
            $name = $namaModulVersi;
            if ($name === '' || $name === '0') {
                log_message('error', 'Nama paket tidak boleh kosong');
            }
            app(ModuleManager::class)->uninstall($name);
            log_message('notice', 'Paket ' . $name . ' berhasil dihapus');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
        }
    }
}
