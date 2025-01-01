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

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\ConfigId;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

defined('BASEPATH') || exit('No direct script access allowed');

class PesanMandiri extends BaseModel
{
    use ConfigId;
    use Uuid;

    public const READ   = 1;
    public const UNREAD = 2;
    public const MASUK  = 1;
    public const KELUAR = 2;

    protected $table      = 'pesan_mandiri';
    protected $primaryKey = 'uuid';
    protected $fillable   = ['uuid', 'config_id', 'owner', 'penduduk_id', 'subjek', 'komentar', 'status', 'tipe', 'is_archived'];

    public function scopeBelumDibaca($query, $pendudukId)
    {
        return $query->wherePendudukId($pendudukId)->whereStatus(self::UNREAD)->whereTipe(self::KELUAR);
    }

    public function isRead(): bool
    {
        return $this->attributes['status'] == self::READ;
    }

    public function isArchive(): bool
    {
        return $this->attributes['is_archived'] == StatusEnum::YA;
    }

    public static function hasDelay($penduduk_id = '', $tipe = 1)
    {
        return self::where('penduduk_id', $penduduk_id)
            ->where('tipe', $tipe)
            ->where('tgl_upload', '>', Carbon::now()->subSeconds(config_item('rentang_kirim_pesan')))
            ->exists();
    }

    /**
     * Get the penduduk that owns the PesanMandiri
     */
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }
}
