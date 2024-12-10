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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Traits;

use App\Models\Modul;
use Illuminate\Support\Facades\File;

trait Migrator
{
    /**
     * Tambah atau perbarui data ke tabel modul.
     *
     * @return void
     */
    protected function createModul(array $data)
    {
        $modul = new Modul();
        $modul = $modul->withoutGlobalScope('config_id');

        $data['ikon_kecil'] ??= $data['ikon'];
        // Tetapkan nilai urut jika belum disediakan
        if (! isset($data['urut'])) {
            $data['urut'] = $data['parent'] == Modul::PARENT
                ? $modul->max('urut') + 1
                : $modul->where('parent', $data['parent'])->max('urut') + 1;
        }

        // Simpan atau perbarui data modul
        $modul->upsert($data, ['config_id', 'modul'], ['url', 'slug', 'parent', 'urut']);

        cache()->flush();
    }

    /**
     * Hapus data dari tabel modul berdasarkan config_id dan slug.
     *
     * @return void
     */
    protected function deleteModul(array $where)
    {
        $modul = new Modul();
        $modul = $modul->withoutGlobalScope('config_id');

        $modul = $modul->where($where)->first();

        if ($modul) {
            // Hapus modul anak jika ini adalah parent
            if ($modul->parent == Modul::PARENT) {
                $modul->whereParent($modul->id)->delete();
            }

            // Hapus modul itu sendiri
            $modul->delete();
        }

        cache()->flush();
    }

    /**
     * Jalankan migrasi modul.
     *
     * @param string $name
     * @param string $action
     * @return void
     */
    private function jalankanMigrasiModule(string $name, string $action = 'up'): void
    {
        $modulesDirectory = array_keys(config_item('modules_locations') ?? [])[0] ?? '';
        $directoryTable   = $modulesDirectory . '/' . $name . '/Database/Migrations';

        // Mendapatkan daftar file migrasi
        $migrations = File::files($directoryTable);

        if ($action === 'up') {
            // Mengurutkan berdasarkan nama file
            usort($migrations, static fn ($a, $b): int => strcmp($a->getFilename(), $b->getFilename()));
        }

        foreach ($migrations as $migrate) {
            $migrateFile = require $migrate->getPathname();

            match ($action) {
                'down'  => $migrateFile->down(),
                default => $migrateFile->up(),
            };
        }

        cache()->flush();
    }
}
