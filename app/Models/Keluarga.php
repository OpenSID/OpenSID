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

namespace App\Models;

use App\Traits\ConfigId;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Keluarga extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tweb_keluarga';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * {@inheritDoc}
     */
    protected $with = [
        'wilayah',
    ];

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function kepalaKeluarga()
    {
        return $this->hasOne(Penduduk::class, 'id', 'nik_kepala')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return HasMany
     */
    public function anggota()
    {
        return $this->hasMany(Penduduk::class, 'id_kk')
            ->status(1)
            ->orderBy('kk_level')
            ->orderBy('tanggallahir')
            ->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function Wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_cluster')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    public function LogKeluarga()
    {
        return $this->hasMany(LogKeluarga::class, 'id_kk', 'id')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * Scope query untuk status keluarga
     *
     * @return Builder
     */
    public function scopeStatus()
    {
        return static::whereHas('kepalaKeluarga', static function ($query): void {
            $query->status()->where('kk_level', '1');
        });
    }

    public function scopeLogTerakhir($query, $configId, $tgl)
    {
        $tgl    = date('Y-m-d', strtotime($tgl . ' + 1 day'));
        $sqlRaw = "select max(id) id from log_keluarga where id_kk IS NOT NULL and config_id = {$configId} and tgl_peristiwa < '{$tgl}'  group by id_kk";

        return $query->join('log_keluarga', static function ($q) use ($configId): void {
            $q->on('log_keluarga.id_kk', '=', 'tweb_keluarga.id')
                ->where('log_keluarga.config_id', '=', $configId)
                ->whereNotIn('log_keluarga.id_peristiwa', [2, 3, 4]);
        })->join(DB::raw("({$sqlRaw}) as log"), 'log.id', '=', 'log_keluarga.id');
    }

    protected static function nomerKKSementara(): int
    {
        // buat jadi orm laravel
        $digit = self::selectRaw('RIGHT(no_kk, 5) as digit')
            ->where('no_kk', 'like', '0' . identitas('kode_desa') . '%')
            ->where('no_kk', '!=', '0')
            ->orderByRaw('RIGHT(no_kk, 5) DESC')
            ->first()->digit ?? 0;

        return (int) $digit + 1;
    }
}
