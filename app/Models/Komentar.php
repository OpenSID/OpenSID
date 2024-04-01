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
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

defined('BASEPATH') || exit('No direct script access allowed');

class Komentar extends BaseModel
{
    use ConfigId;

    public const ACTIVE      = 1;
    public const NONACTIVE   = 2;
    public const TIPE_MASUK  = 2;
    public const TIPE_KELUAR = 1;
    public const LOCK        = 1;
    public const UNLOCK      = 2;
    public const ROOT        = 0;
    public const CHILD       = 2;

    /**
     * The name of the "created at" column.
     *
     * @var string|null
     */
    public const CREATED_AT = 'tgl_upload';

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    public const UPDATED_AT = 'updated_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'komentar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'owner', 'subjek', 'komentar', 'tipe', 'status', 'id_artikel', 'parent_id'];

    protected $appends = ['foto', 'pengguna', 'url_artikel'];

    /**
     * Scope a query to only enable category.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeEnable($query)
    {
        return $query->where('status', static::ACTIVE);
    }

    /**
     * Scope query untuk tipe pesan masuk.
     *
     * @param Builder $query
     * @param string  $tipe
     *
     * @return Builder
     */
    public function scopeTipePesan($query, string $type)
    {
        $tipePesan = $type === 'masuk'
            ? self::TIPE_MASUK
            : self::TIPE_KELUAR;

        return $query->where('tipe', $tipePesan);
    }

    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'id_artikel');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'tipe');
    }

    public function getFotoAttribute()
    {
        if ($this->parent_id) {
            $foto = User::find($this->owner)->foto;
        }

        return cache()->rememberForever('foto_komentar_' . $this->id, static fn () => AmbilFoto($foto, 'kecil_', mt_rand(1, 2)));
    }

    public function children(): HasMany
    {
        return $this->hasMany(Komentar::class, 'parent_id', 'id');
    }

    public function getPenggunaAttribute()
    {
        $parent = $this->parent_id;
        $owner  = $this->owner;

        return cache()->rememberForever('pengguna_komentar_' . $this->id, static function () use ($parent, $owner) {
            if ($parent) {
                $user = User::with('userGrup')->find($owner);

                $owner = [
                    'nama'  => ucwords($user->nama),
                    'level' => ucwords($user->userGrup->nama),
                ];
            } else {
                $owner = [
                    'nama'  => ucwords($owner),
                    'level' => 'Pengunjung',
                ];
            }

            return $owner;
        });
    }

    public function getUrlArtikelAttribute()
    {
        $artikel = Artikel::find($this->id_artikel);
        if ($artikel) {
            $tgl_upload = Carbon::createFromFormat('Y-m-d H:i:s', $artikel->tgl_upload)->format('Y/m/d');

            return site_url("artikel/{$tgl_upload}/{$artikel->slug}");
        }

        return null;
    }

    protected static function booted()
    {
        self::boot();
        static::addGlobalScope('isKomentar', static function (Builder $builder) {
            $builder->whereNotIn('id_artikel', ['null', '775']);
        });
        static::deleting(static function ($komentar) {
            $komentar->children()->delete();
        });
    }
}
