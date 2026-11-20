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

namespace Modules\Kehadiran\Models;

use App\Models\BaseModel;
use App\Models\Pamong;
use App\Traits\ConfigId;
use App\Traits\Uuid;
use Modules\Kehadiran\Enums\StatusCatatan;

defined('BASEPATH') || exit('No direct script access allowed');

class CatatanHarian extends BaseModel
{
    use ConfigId; use Uuid;

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kehadiran_catatan_harian';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'pamong_id',
        'tanggal',
        'uraian_kegiatan',
        'lokasi_kegiatan',
        'hasil_diharapkan',
        'status',
        'approved_by',
        'approved_at',
        'alasan_penolakan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal'     => 'date',
        'approved_at' => 'datetime',
        'status'      => StatusCatatan::class,
    ];

    /**
     * The accessors to append to model's array form.
     * Include computed attributes in JSON responses.
     *
     * @var array
     */
    protected $appends = ['hari'];

    /**
     * Get minimum year from catatan_harian tanggal field
     */
    public static function getMinYear(): int
    {
        return (int) (self::selectRaw('EXTRACT(YEAR FROM tanggal) as year')
            ->orderBy('year', 'asc')
            ->first()?->year ?? date('Y'));
    }

    /**
     * Get the hari (day name) from the tanggal attribute.
     * Dynamically computed from the date.
     */
    public function getHariAttribute(): string
    {
        if (empty($this->attributes['tanggal'] ?? null)) {
            return '';
        }

        $date = $this->getAttribute('tanggal');
        if ($date instanceof \Carbon\Carbon) {
            return $date->locale('id')->dayName;
        }

        return \Carbon\Carbon::createFromFormat('Y-m-d', $this->attributes['tanggal'])->locale('id')->dayName;
    }

    /**
     * Define a many-to-one relationship dengan Pamong.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pamong()
    {
        return $this->belongsTo(Pamong::class, 'pamong_id', 'pamong_id');
    }

    /**
     * Define a many-to-one relationship dengan Pamong (approver).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approver()
    {
        return $this->belongsTo(Pamong::class, 'approved_by', 'pamong_id');
    }

    /**
     * Define a one-to-many relationship dengan CatatanHarianFoto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fotos()
    {
        return $this->hasMany(CatatanHarianFoto::class, 'catatan_harian_id', 'uuid');
    }

    /**
     * Scope to get only catatan untuk pamong yang login.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $pamongId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPamong($query, $pamongId)
    {
        return $query->where('pamong_id', $pamongId);
    }

    /**
     * Scope to get catatan yang perlu approval.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendingApproval($query)
    {
        return $query->where('status', StatusCatatan::MENUNGGU->value);
    }

    /**
     * Scope to get catatan yang sudah disetujui.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', StatusCatatan::DISETUJUI->value);
    }

    /**
     * Scope to get catatan berdasarkan bulan dan tahun.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $bulan
     * @param int                                   $tahun
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByMonth($query, $bulan, $tahun)
    {
        return $query->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);
    }

    /**
     * Scope to get catatan berdasarkan tahun.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string                            $tahun
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByYear($query, $tahun)
    {
        return $query->whereYear('tanggal', (int) $tahun);
    }
}
