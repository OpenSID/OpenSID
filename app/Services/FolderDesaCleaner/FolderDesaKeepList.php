<?php

declare(strict_types=1);

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

namespace App\Services\FolderDesaCleaner;

use Illuminate\Support\Facades\DB;

/**
 * Menyimpan daftar file yang DIPERTAHANKAN (di-deselect pengguna) pada fitur
 * Bersihkan Folder Desa, agar pilihan itu:
 *  - tetap berlaku saat kembali ke tab (tidak reset tiap kunjungan), dan
 *  - dihormati oleh proses Acak (pembersihan folder memakai daftar tersimpan ini).
 *
 * Disimpan sebagai JSON di setting_aplikasi (kolom mediumtext) per config_id,
 * sehingga tidak butuh migrasi/tabel baru.
 */
class FolderDesaKeepList
{
    private const KEY = 'bersih_folder_keep';

    /**
     * Path relatif yang dipertahankan (tidak dihapus).
     *
     * @return list<string>
     */
    public function get(): array
    {
        $value = DB::table('setting_aplikasi')
            ->where('config_id', $this->configId())
            ->where('key', self::KEY)
            ->value('value');

        $decoded = ($value !== null && $value !== '') ? json_decode((string) $value, true) : [];

        return is_array($decoded) ? array_values(array_filter($decoded, 'is_string')) : [];
    }

    /**
     * @param list<string> $paths
     */
    public function set(array $paths): void
    {
        $paths = array_values(array_unique(array_filter($paths, 'is_string')));

        DB::table('setting_aplikasi')->updateOrInsert(
            ['config_id' => $this->configId(), 'key' => self::KEY],
            [
                'value'    => json_encode($paths),
                'judul'    => 'File dipertahankan (Bersih Folder Desa)',
                'kategori' => 'sistem',
            ]
        );
    }

    public function clear(): void
    {
        DB::table('setting_aplikasi')
            ->where('config_id', $this->configId())
            ->where('key', self::KEY)
            ->delete();
    }

    private function configId(): int
    {
        return (int) (identitas('id') ?: DB::table('config')->value('id'));
    }
}
