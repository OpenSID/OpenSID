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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

defined('BASEPATH') || exit('No direct script access allowed');

class FormatSurat extends Model
{
    public const MANDIRI         = 1;
    public const MANDIRI_DISABLE = 0;
    public const KUNCI           = 1;
    public const KUNCI_DISABLE   = 0;

    /**
     * Static data masa berlaku surat.
     *
     * @var array
     */
    public const MASA_BERLAKU = [
        'd' => 'Hari',
        'w' => 'Minggu',
        'M' => 'Bulan',
        'y' => 'Tahun',
    ];

    /**
     * Static data jenis surat.
     *
     * @var array
     */
    public const JENIS_SURAT = [
        '1' => 'Surat Sistem (lama/rtf)',
        '2' => 'Surat [Desa] (lama/rtf)',
        '3' => 'Surat Sistem (baru/tinymce)',
        '4' => 'Surat [Desa] (baru/tinymce)',
    ];

    /**
     * Static data margin surat.
     *
     * @var array
     */
    public const MARGINS = [
        'kiri'  => 1.78,
        'atas'  => 0.63,
        'kanan' => 1.78,
        'bawah' => 1.37,
    ];

    /**
     * Static data orientation surat.
     *
     * @var array
     */
    public const ORIENTATAIONS = [
        'Potrait',
        'Lanscape',
    ];

    /**
     * Static data Size surat.
     *
     * @var array
     */
    public const SIZES = [
        'A1',
        'A2',
        'A3',
        'A4',
        'A5',
        'A6',
        'F4',
    ];

    /**
     * {@inheritDoc}
     */
    protected $table = 'tweb_surat_format';

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'url_surat',
        'kode_surat',
        'lampiran',
        'kunci',
        'favorit',
        'jenis',
        'mandiri',
        'masa_berlaku',
        'satuan_masa_berlaku',
        'qr_code',
        'logo_garuda',
        'syarat_surat',
        'template',
        'template_desa',
        'kode_isian',
        'orientasi',
        'ukuran',
        'margin',
        'created_by',
        'updated_by',
    ];

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $appends = [
        'judul_surat',
        'margin_cm_to_mm',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'masa_berlaku' => 'integer',
        'kunci'        => 'boolean',
        'favorit'      => 'boolean',
        'mandiri'      => 'boolean',
        'qr_code'      => 'boolean',
        'logo_garuda'  => 'boolean',
        // 'syarat_surat' => 'json',
        // 'kode_isian'   => 'json',
        // 'margin'       => 'json',
    ];

    /**
     * Define a many-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function syaratSurat()
    {
        return $this->belongsToMany(SyaratSurat::class, 'syarat_surat', 'surat_format_id', 'ref_syarat_id');
    }

    /**
     * Scope query untuk layanan mandiri.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeMandiri($query)
    {
        return $query->where('mandiri', static::MANDIRI);
    }

    /**
     * Scope query untuk list surat yang tidak dikunci.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    // public function scopeKunci($query)
    // {
    //     return $query->where('kunci', static::KUNCI_DISABLE);
    // }

    /**
     * Getter list surat dan dokumen attribute.
     *
     * @return array
     */
    public function getListSyaratSuratAttribute()
    {
        return $this->syaratSurat->map(
            static function ($syarat) {
                return [
                    'label'      => $syarat->ref_syarat_nama,
                    'value'      => $syarat->ref_syarat_id,
                    'form_surat' => [
                        [
                            'type'     => 'select',
                            'required' => true,
                            'label'    => 'Dokumen Syarat',
                            'name'     => 'dokumen',
                            'multiple' => false,
                            'values'   => $syarat->dokumen->map(static function ($dokumen) {
                                return [
                                    'label' => $dokumen->nama,
                                    'value' => $dokumen->id,
                                ];
                            }),
                        ],
                    ],
                ];
            }
        );
    }

    /**
     * Getter form surat attribute.
     *
     * @return mixed
     */
    public function getFormSuratAttribute()
    {
        // try {
        //     return app('surat')->driver($this->url_surat)->form();
        // } catch (Exception $e) {
        //     Log::error($e);

        //     return null;
        // }
    }

    /**
     * Setter untuk url_surat.
     *
     * @return void
     */
    // public function setUrlSuratAttribute()
    // {
    //     $this->attributes['url_surat'] = 'surat_' . strtolower(str_replace([' ', '-'], '_', $this->attributes['nama']));
    // }

    /**
     * Getter untuk lokasi_surat
     *
     * @return string
     */
    public function getLokasiSuratAttribute()
    {
        return LOKASI_SURAT_DESA . $this->url_surat;
    }

    /**
     * Getter untuk judul_surat
     *
     * @return string
     */
    public function getJudulSuratAttribute()
    {
        return 'Surat ' . $this->nama;
    }

    /**
     * Getter untuk judul_surat
     *
     * @return string
     */
    public function getMarginCmToMmAttribute()
    {
        $margin = json_decode($this->margin);

        return [
            $margin->kiri * 10,
            $margin->atas * 10,
            $margin->kanan * 10,
            $margin->bawah * 10,
        ];
    }

    /**
     * Scope query untuk IsExist
     *
     * @param mixed $query
     * @param mixed $value
     *
     * @return Builder
     */
    public function scopeIsExist($query, $value)
    {
        return $query->where('url_surat', $value)->exists();
    }

    /**
     * Scope query untuk Kunci Surat
     *
     * @param mixed $query
     * @param mixed $value
     *
     * @return Builder
     */
    public function scopeKunci($query, $value = 1)
    {
        return $query->where('kunci', $value);
    }

    /**
     * Scope query untuk Favorit Surat
     *
     * @param mixed $query
     * @param mixed $value
     *
     * @return Builder
     */
    public function scopeFavorit($query, $value = 1)
    {
        return $query->where('favorit', $value);
    }

    /**
     * Scope query untuk Jenis Surat
     *
     * @param mixed $query
     * @param mixed $value
     *
     * @return Builder
     */
    public function scopeJenis($query, $value)
    {
        if (empty($value)) {
            return $query->whereNotNull('jenis');
        }

        if (is_array($value)) {
            return $query->whereIn('jenis', $value);
        }

        return $query->where('jenis', $value);
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(static function ($model) {
    //         $model->created_by = auth()->id;
    //         $model->updated_by = auth()->id;
    //     });

    //     static::updating(static function ($model) {
    //         $model->updated_by = auth()->id;
    //     });
    // }
}
