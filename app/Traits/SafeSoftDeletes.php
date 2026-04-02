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

namespace App\Traits;

use App\Scopes\SafeSoftDeletingScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

/**
 * Drop-in replacement untuk trait SoftDeletes bawaan Laravel.
 *
 * Perbedaan dari SoftDeletes standar:
 * - Scope (WHERE deleted_at IS NULL) hanya aktif **setelah** kolom `deleted_at` ada di DB.
 * - Builder macro (onlyTrashed, withTrashed, withoutTrashed) **selalu** tersedia.
 * - Tambahan helper `isSoftDeleteReady()` untuk guard di controller/service.
 *
 * Penggunaan di model:
 *   use SafeSoftDeletes;          // gantikan use SoftDeletes;
 *
 * Penggunaan di controller/service:
 *   if (Model::isSoftDeleteReady()) {
 *       Model::onlyTrashed()->...
 *   }
 */
trait SafeSoftDeletes
{
    use SoftDeletes;

    /**
     * Override boot SoftDeletes agar menggunakan SafeSoftDeletingScope.
     */
    public static function bootSoftDeletes(): void
    {
        static::addGlobalScope(new SafeSoftDeletingScope());
    }

    /**
     * Kembalikan true jika kolom deleted_at sudah ada di database.
     * Gunakan ini sebagai guard sebelum memanggil onlyTrashed() / withTrashed().
     */
    public static function isSoftDeleteReady(): bool
    {
        return Schema::hasColumn((new static())->getTable(), (new static())->getDeletedAtColumn());
    }
}
