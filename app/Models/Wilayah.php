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

namespace App\Models;

use App\Casts\Path;
use App\Casts\Zoom;
use App\Enums\JenisKelaminEnum;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\EloquentSortable\SortableTrait;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string $rt
 * @property string $rw
 * @property string $dusun
 * @property int|null $id_kepala
 * @property string|null $lat
 * @property string|null $lng
 * @property int|null $zoom
 * @property mixed|null $path
 * @property string|null $map_tipe
 * @property string|null $warna
 * @property string|null $border
 * @property int|null $urut
 * @property int|null $urut_cetak
 * @property-read string $alamat
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KeluargaAktif> $keluargaAktif
 * @property-read int|null $keluarga_aktif_count
 * @property-read \App\Models\PendudukSaja|null $kepala
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PendudukHidup> $pendudukPria
 * @property-read int|null $penduduk_pria_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PendudukHidup> $pendudukWanita
 * @property-read int|null $penduduk_wanita_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Wilayah> $rts
 * @property-read int|null $rts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Wilayah> $rwAll
 * @property-read int|null $rw_all_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Wilayah> $rws
 * @property-read int|null $rws_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah dusun()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah rt($dusun = null, $rw = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah rw($dusun = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereBorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereDusun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereIdKepala($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereMapTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereUrut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereUrutCetak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereWarna($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah whereZoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wilayah withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Wilayah extends BaseModel
{
    use ConfigId;
    use SortableTrait;
    use ShortcutCache;

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    public $sortable = [
        'order_column_name'  => 'urut',
        'sort_when_creating' => false,
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tweb_wil_clusterdesa';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The casts with the model.
     *
     * @var array
     */
    protected $casts = [
        'path' => Path::class,
        'zoom' => Zoom::class,
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model): void {

            if ($model->isDusun()) {
                $max = Wilayah::dusun()->max('urut');
            } elseif ($model->isRw()) {
                $max = Wilayah::rw($model->dusun)->max('urut');
            } else {
                $max = Wilayah::rt($model->dusun, $model->rw)->max('urut');
            }

            $model->urut = ($max ?? 0) + 1;
        });
    }

    public static function updateUrutan(): void
    {
        $all  = Wilayah::dusun()->with(['rws' => static fn ($q) => $q->with('rts')])->orderBy('urut')->get();
        $urut = 1;

        foreach ($all as $dusun) {
            $dusun->update(['urut_cetak' => $urut++]);

            foreach ($dusun->rws as $rw) {
                $rw->update(['urut_cetak' => $urut++]);

                foreach ($rw->rts as $rt) {
                    $rt->update(['urut_cetak' => $urut++]);
                }
            }
        }
    }

    public static function tree()
    {
        return self::select(['id', 'dusun', 'rt', 'rw'])->get()->groupBy('dusun')->map(static fn ($item) => $item->filter(static fn ($q): bool => $q->rw !== '0')->groupBy('rw')->map(static fn ($item) => $item->filter(static fn ($q): bool => ! $q->isDusun() && ! $q->bukanRT() )));
    }

    public static function treeAccess()
    {
        $user = ci_auth();
        if ($user->batasi_wilayah) {
            $aksesWilayah = $user->akses_wilayah ?? [];

            return self::select(['id', 'dusun', 'rt', 'rw'])->whereIn('id', $aksesWilayah)->get()->groupBy('dusun')->map(static fn ($item) => $item->filter(static fn ($q): bool => $q->rw !== '0')->groupBy('rw')->map(static fn ($item) => $item->filter(static fn ($q): bool => ! $q->isDusun() && ! $q->bukanRT() )));
        }

        return self::select(['id', 'dusun', 'rt', 'rw'])->get()->groupBy('dusun')->map(static fn ($item) => $item->filter(static fn ($q): bool => $q->rw !== '0')->groupBy('rw')->map(static fn ($item) => $item->filter(static fn ($q): bool => ! $q->isDusun() && ! $q->bukanRT()  )));
    }

    /**
     * Scope query untuk dusun
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeDusun($query)
    {
        return $query->where('rt', '=', '0')->where('rw', '=', '0');
    }

    /**
     * Scope query untuk rw.
     *
     * @param Builder    $query
     * @param mixed|null $dusun
     *
     * @return Builder
     */
    public function scopeRw($query, $dusun = null)
    {
        if ($dusun) {
            $query->whereDusun($dusun);
        }

        return $query->where('rt', '=', '0')->where('rw', '!=', '0');
    }

    /**
     * Scope query untuk rt
     *
     * @param Builder    $query
     * @param mixed|null $dusun
     * @param mixed|null $rw
     *
     * @return Builder
     */
    public function scopeRt($query, $dusun = null, $rw = null)
    {
        if ($dusun) {
            $query->whereDusun($dusun);

            if ($dusun) {
                $query->whereRw($rw);
            }
        }

        return $query->where('rt', '!=', '0');
    }

    /**
     * Define a one-to-many relationship.
     */
    public function kepala(): HasOne
    {
        return $this->hasOne(PendudukSaja::class, 'id', 'id_kepala')->select('nik', 'nama', 'id');
    }

    /**
     * Get all of the rw for the Wilayah
     */
    public function rws(): HasMany
    {
        return $this->hasMany(Wilayah::class, 'dusun', 'dusun')->where('rt', '=', '-');
    }

    public function rwAll(): HasMany
    {
        return $this->hasMany(Wilayah::class, 'dusun', 'dusun')->where('rt', '=', '-');
    }

    public function rts(): HasMany
    {
        return $this->hasMany(Wilayah::class, 'dusun', 'dusun')->whereNotIn('rt', ['0', '-']);
    }

    public function pendudukPria(): HasManyThrough
    {
        return $this->hasManyThrough(PendudukHidup::class, Wilayah::class, 'dusun', 'id_cluster', 'dusun')->where('sex', JenisKelaminEnum::LAKI_LAKI);
    }

    public function pendudukWanita(): HasManyThrough
    {
        return $this->hasManyThrough(PendudukHidup::class, Wilayah::class, 'dusun', 'id_cluster', 'dusun')->where('sex', JenisKelaminEnum::PEREMPUAN);
    }

    public function keluargaAktif()
    {
        return $this->hasManyThrough(KeluargaAktif::class, Wilayah::class, 'dusun', 'id_cluster', 'dusun')
            ->whereHas('kepalaKeluarga', static function ($q): void {
                $q->whereNotNull('id_kk')
                    ->where('kk_level', 1)
                    ->where('status_dasar', 1);
            });
    }

    public function isDusun(): bool
    {
        return $this->attributes['rt'] == '0' && $this->attributes['rw'] == '0';
    }

    public function isRw(): bool
    {
        return $this->attributes['rt'] == '0' && $this->attributes['rw'] !== '0';
    }

    public function isRt(): bool
    {
        return $this->attributes['rt'] !== '0';
    }

    public function bukanRT(): bool
    {
        return $this->attributes['rt'] == '0';
    }

    protected function getAlamatAttribute(): string
    {
        return ($this->attributes['rt'] != '0' ? 'RT ' . $this->attributes['rt'] . ' / ' : '') . ($this->attributes['rw'] != '0' ? 'RW ' . $this->attributes['rw'] . ' - ' : '') . $this->attributes['dusun'];
    }
}
