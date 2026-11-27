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

namespace App\Services\FolderDesaCleaner\Rules;

use App\Services\FolderDesaCleaner\AbstractCleanupRule;

/**
 * Finds files in desa/arsip/ that are not referenced by any DB row.
 *
 * Checks columns: log_surat.(nama_surat, lampiran),
 *                 log_surat_dinas.(nama_surat, lampiran),
 *                 surat_masuk.berkas_scan,
 *                 surat_keluar.(berkas_scan, tanda_terima)
 *
 * Tables that do not exist are silently treated as having no references,
 * so the rule is safe on installations missing those modules.
 */
class OrphanArsipRule extends AbstractCleanupRule
{
    public function key(): string
    {
        return 'orphan_arsip';
    }

    protected function folderLabel(): string
    {
        return 'desa/arsip/';
    }

    protected function description(): string
    {
        return 'Arsip surat tidak dirujuk oleh database (log_surat, surat_masuk, surat_keluar)';
    }

    protected function scan(): array
    {
        $arsipDir = $this->absPath('desa/arsip');

        if (! is_dir($arsipDir)) {
            return [];
        }

        // Build set of all basenames referenced in the DB.
        $referenced = [];

        foreach ([
            ['log_surat',      'nama_surat'],
            ['log_surat',      'lampiran'],
            ['log_surat_dinas', 'nama_surat'],
            ['log_surat_dinas', 'lampiran'],
            ['surat_masuk',    'berkas_scan'],
            ['surat_keluar',   'berkas_scan'],
            ['surat_keluar',   'tanda_terima'],
        ] as [$table, $col]) {
            $referenced += $this->referencedBasenames($table, $col);
        }

        $candidates = [];

        foreach (glob($arsipDir . DIRECTORY_SEPARATOR . '*') ?: [] as $path) {
            if (is_file($path) && ! isset($referenced[basename($path)])) {
                $candidates += $this->fileEntry($path);
            }
        }

        return $candidates;
    }
}
