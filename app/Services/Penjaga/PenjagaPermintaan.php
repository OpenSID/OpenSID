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

namespace App\Services\Penjaga;

/**
 * Registry penjaga (guard) akses request & migrasi milik add-on.
 *
 * Sebelumnya core memanggil langsung `CekService::validasi()/validasiAkses()`
 * (saat request admin/publik) dan `validasiVersi()` (saat cek migrasi) untuk
 * menegakkan batas versi/langganan premium. Kini core hanya bertanya ke registry
 * ini; add-on (mis. modul Pelanggan) mendaftarkan penjaganya saat boot.
 *
 * Tiap penjaga menerima konteks `(bool $migration, bool $install)` dan
 * mengembalikan `true` untuk MEMBOLEHKAN. Semua penjaga harus setuju (AND).
 * Tanpa add-on → selalu `true` (core OSS tak membatasi apa pun).
 */
class PenjagaPermintaan
{
    /**
     * @var list<callable(bool, bool): bool>
     */
    private array $guards = [];

    /**
     * Daftarkan penjaga. `$migration` = konteks cek migrasi (vs request biasa);
     * `$install` = apakah sedang instalasi (hanya relevan saat migrasi).
     *
     * @param callable(bool, bool): bool $guard
     */
    public function daftarkan(callable $guard): void
    {
        $this->guards[] = $guard;
    }

    /**
     * Apakah request saat ini boleh diproses? Default `true` bila tak ada penjaga.
     */
    public function izinkanPermintaan(): bool
    {
        return $this->evaluate(false, false);
    }

    /**
     * Apakah migrasi boleh dijalankan? Default `true` bila tak ada penjaga.
     */
    public function izinkanMigrasi(bool $install): bool
    {
        return $this->evaluate(true, $install);
    }

    private function evaluate(bool $migration, bool $install): bool
    {
        foreach ($this->guards as $guard) {
            if (! $guard($migration, $install)) {
                return false;
            }
        }

        return true;
    }
}
