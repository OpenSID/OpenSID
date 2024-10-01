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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Artikel extends BaseModel
{
    use ConfigId;

    public const ENABLE              = 1;
    public const HEADLINE            = 1;
    public const TIPE_NOT_IN_ARTIKEL = ['statis', 'agenda', 'keuangan'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'artikel';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gambar',
        'isi',
        'enabled',
        'tgl_upload',
        'judul',
        'headline',
        'gambar1',
        'gambar2',
        'gambar3',
        'dokumen',
        'link_dokumen',
        'boleh_komentar',
        'slug',
        'hit',
        'slider',
        'tipe',
        'id_kategori',
        'id_user',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'author',
        'category',
        'comments',
    ];

    /**
     * The attributes that should be appended to model.
     *
     * @var array
     */
    protected $appends = [
        'url_slug',
    ];

    protected $casts = [
        'tgl_upload' => 'datetime:d-m-Y H:i:s',
    ];

    /**
     * Scope a query to only include article.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOnlyArticle($query): \Illuminate\Database\Query\Builder
    {
        return $query->whereNotIn('tipe', static::TIPE_NOT_IN_ARTIKEL);
    }

    /**
     * Scope a query to only enable article.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeEnable($query)
    {
        return $query->where('enabled', static::ENABLE);
    }

    /**
     * Scope a query to only headline article.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeHeadline($query)
    {
        return $query->where('headline', static::HEADLINE);
    }

    public function scopeStatis($query)
    {
        return $query->where('tipe', 'statis');
    }

    public function scopeDinamis($query)
    {
        return $query->where('tipe', 'dinamis');
    }

    public function scopeKeuangan($query)
    {
        return $query->where('tipe', 'keuangan');
    }

    /**
     * Scope a query to only archive article.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeArsip($query)
    {
        $kategori = json_decode(preg_replace('/\\\\/', '', setting('anjungan_artikel')), null);

        $artikel = $query->select(Artikel::raw('*, YEAR(tgl_upload) AS thn, MONTH(tgl_upload) AS bln, DAY(tgl_upload) AS hri'))
            ->where([['enabled', 1], ['tgl_upload', '<', date('Y-m-d H:i:s')]]);

        if (null !== $kategori) {
            return $artikel->whereIn('id_kategori', $kategori);
        }

        return $artikel;
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany(Komentar::class, 'id_artikel');
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return HasMany
     */
    public function agenda()
    {
        return $this->hasOne(Agenda::class, 'id_artikel');
    }

    public function getPerkiraanMembacaAttribute()
    {
        return Str::perkiraanMembaca($this->isi);
    }

    /**
     * Getter untuk menambahkan url gambar.
     */
    public function getUrlGambarAttribute(): void
    {
        // return $this->gambar
        //     ? config('filesystems.disks.ftp.url') . "/desa/upload/artikel/sedang_{$this->gambar}"
        //     : '';
    }

    /**
     * Getter untuk menambahkan url gambar.
     */
    public function getUrlGambar1Attribute(): void
    {
        // return $this->gambar1
        //     ? config('filesystems.disks.ftp.url') . "/desa/upload/artikel/sedang_{$this->gambar1}"
        //     : '';
    }

    /**
     * Getter untuk menambahkan url gambar.
     */
    public function getUrlGambar2Attribute(): void
    {
        // return $this->gambar2
        //     ? config('filesystems.disks.ftp.url') . "/desa/upload/artikel/sedang_{$this->gambar2}"
        //     : '';
    }

    /**
     * Getter untuk menambahkan url gambar.
     */
    public function getUrlGambar3Attribute(): void
    {
        // return $this->gambar3
        //     ? config('filesystems.disks.ftp.url') . "/desa/upload/artikel/sedang_{$this->gambar3}"
        //     : '';
    }

    /**
     * Getter untuk menambahkan url slug.
     */
    public function getUrlSlugAttribute(): string
    {
        return site_url('artikel/' . Carbon::parse($this->tgl_upload)->format('Y/m/d') . '/' . $this->slug);
    }

    public function bolehUbah(): bool
    {
        return auth()->id == $this->id_user || auth()->id_grup != 4;
    }

    public function getKategoriAttribute()
    {
        return $this->tipe == 'dinamis' ? $this->id_kategori : $this->tipe;
    }
}
