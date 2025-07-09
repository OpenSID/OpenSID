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

namespace App\Traits;

use App\Enums\StatusEnum;

trait StatusTrait
{
    /**
     * Mendapatkan nama kolom status.
     */
    private static function getStatusColumn(): string
    {
        return defined('static::STATUS') ? static::STATUS : 'status';
    }

    /**
     * Scope untuk status tertentu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $status
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->when($status !== '', static function ($query) {
            $query->where(self::getStatusColumn(), StatusEnum::YA);
        });
    }

    /**
     * Scope untuk status aktif.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where(self::getStatusColumn(), StatusEnum::YA);
    }

    /**
     * Scope untuk status tidak aktif.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where(self::getStatusColumn(), StatusEnum::TIDAK);
    }

    /**
     * Mengubah status data.
     *
     * @param mixed $id      ID data yang akan diubah. Bisa berupa string (UUID) atau integer.
     * @param bool  $onlyOne Jika true, hanya satu data yang bisa aktif.
     *
     * @return bool Mengembalikan true jika status berhasil diubah, false jika gagal.
     */
    public static function updateStatus($id, bool $onlyOne = false): bool
    {
        $kolom = self::getStatusColumn();

        // Cari data berdasarkan ID (baik string/UUID maupun integer)
        $data = static::findOrFail($id);

        $newStatus = $data->{$kolom} === StatusEnum::YA ? StatusEnum::TIDAK : StatusEnum::YA;

        // Update status
        if ($data->update([$kolom => $newStatus])) {
            if ($onlyOne && $newStatus === StatusEnum::YA) {
                $primaryKey = $data->getKeyName(); // Mendapatkan primary key
                static::where($primaryKey, '!=', $id)->update([$kolom => StatusEnum::TIDAK]);
            }

            return true;
        }

        return false;
    }
}
