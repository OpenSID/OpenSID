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

use App\Enums\AktifEnum;

trait StatusTrait
{
    /**
     * Menambahkan status_label ke appends saat model di-inisialisasi.
     */
    public function initializeStatusTrait()
    {
        if (! in_array('status_label', $this->appends)) {
            $this->appends[] = 'status_label';
        }
    }

    /**
     * Ambil nama kolom status.
     */
    public function getStatusColumn(): string
    {
        return $this->statusColumName ?? 'status';
    }

    /**
     * Scope untuk filter berdasarkan status tertentu.
     *
     * @param mixed    $query
     * @param int|null $status
     */
    public function scopeStatus($query, $status = null)
    {
        return $query->when(
            in_array($status, AktifEnum::keys()),
            fn ($q) => $q->where($this->getStatusColumn(), $status)
        );
    }

    /**
     * Scope untuk data dengan status aktif.
     *
     * @param mixed $query
     */
    public function scopeActive($query)
    {
        return $query->where($this->getStatusColumn(), AktifEnum::AKTIF);
    }

    /**
     * Scope untuk data dengan status tidak aktif.
     *
     * @param mixed $query
     */
    public function scopeInactive($query)
    {
        return $query->where($this->getStatusColumn(), AktifEnum::TIDAK_AKTIF);
    }

    public function getStatusLabelAttribute()
    {
        return AktifEnum::getLabel($this->{$this->getStatusColumn()});
    }

    /**
     * Ubah status data berdasarkan ID.
     *
     * @param mixed $id
     * @param bool  $onlyOne Jika true, hanya satu data boleh aktif.
     */
    public static function updateStatus($id, bool $onlyOne = false): bool
    {
        $model = static::findOrFail($id);
        $kolom = (new static())->getStatusColumn();

        $newStatus = $model->{$kolom} === AktifEnum::AKTIF ? AktifEnum::TIDAK_AKTIF : AktifEnum::AKTIF;

        if ($model->update([$kolom => $newStatus])) {
            if ($onlyOne && $newStatus === AktifEnum::AKTIF) {
                static::where($model->getKeyName(), '!=', $id)->update([$kolom => AktifEnum::TIDAK_AKTIF]);
            }

            return true;
        }

        return false;
    }
}
