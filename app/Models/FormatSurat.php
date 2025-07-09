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

use App\Scopes\RemoveRtfScope;
use App\Traits\Author;
use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

defined('BASEPATH') || exit('No direct script access allowed');

class FormatSurat extends BaseModel
{
    use Author;
    use ConfigId;

    public const MANDIRI               = 1;
    public const MANDIRI_DISABLE       = 0;
    public const KUNCI                 = 1;
    public const KUNCI_DISABLE         = 0;
    public const FAVORIT               = 1;
    public const FAVORIT_DISABLE       = 0;
    public const TINYMCE_SISTEM        = 3;
    public const TINYMCE_DESA          = 4;
    public const RTF                   = [1, 2];
    public const TINYMCE               = [3, 4];
    public const SISTEM                = [3];
    public const DESA                  = [4];
    public const DEFAULT_ORIENTATAIONS = 'Potrait';
    public const DEFAULT_SIZES         = 'F4';

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
        self::TINYMCE_SISTEM => 'Surat Sistem',
        self::TINYMCE_DESA   => 'Surat [Desa]',
    ];

    /**
     * Static data margin surat.
     *
     * @var array
     */
    public const MARGINS = [
        'kiri'  => 3,
        'atas'  => 2.5,
        'kanan' => 2,
        'bawah' => 2.5,
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
     * Static data atribut surat.
     *
     * @var array
     */
    public const ATTRIBUTES = [
        'text'            => 'Input Teks',
        'number'          => 'Input Angka',
        'email'           => 'Input Email',
        'url'             => 'Input Url',
        'date'            => 'Input Tanggal',
        'time'            => 'Input Jam',
        'textarea'        => 'Text Area',
        'select-manual'   => 'Pilihan (Kustom)',
        'select-otomatis' => 'Pilihan (Referensi)',
        'hari'            => 'Input Hari',
        'hari-tanggal'    => 'Input Hari dan Tanggal',
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
        'config_id',
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
        'kecamatan',
        'syarat_surat',
        'template',
        'template_desa',
        'form_isian',
        'kode_isian',
        'orientasi',
        'ukuran',
        'margin',
        'margin_global',
        'header',
        'footer',
        'format_nomor',
        'format_nomor_global',
        'sumber_penduduk_berulang',
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
        'header'       => 'integer',
        'jenis'        => 'integer',
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
            static fn ($syarat): array => [
                'label'      => $syarat->ref_syarat_nama,
                'value'      => $syarat->ref_syarat_id,
                'form_surat' => [
                    [
                        'type'     => 'select',
                        'required' => true,
                        'label'    => 'Dokumen Syarat',
                        'name'     => 'dokumen',
                        'multiple' => false,
                        'values'   => $syarat->dokumen->map(static fn ($dokumen): array => [
                            'label' => $dokumen->nama,
                            'value' => $dokumen->id,
                        ]),
                    ],
                ],
            ]
        );
    }

    /**
     * Getter form surat attribute.
     */
    public function getFormSuratAttribute(): void
    {
        // try {
        //     return app('surat')->driver($this->url_surat)->form();
        // } catch (Exception $e) {
        //     Log::error($e);

        //     return null;
        // }
    }

    /**
     * Getter untuk judul_surat
     */
    public function getJudulSuratAttribute(): string
    {
        return 'Surat ' . $this->nama;
    }

    /**
     * Getter untuk kode_isian
     *
     * @return string
     */
    public function getKodeIsianAttribute()
    {
        return json_decode((string) $this->attributes['kode_isian'], null);
    }

    /**
     * Getter untuk form_isian
     *
     * @return mixed
     */
    public function getFormIsianAttribute()
    {
        return json_decode((string) $this->attributes['form_isian'], null);
    }

    /**
     * Getter untuk judul_surat
     *
     * @return string
     */
    public function getMarginCmToMmAttribute(): array
    {
        $margin = json_decode($this->margin, null);

        return [
            $margin->kiri * 10,
            $margin->atas * 10,
            $margin->kanan * 10,
            $margin->bawah * 10,
        ];
    }

    /**
     * Getter untuk url surat sistem
     */
    public function getUrlSuratSistemAttribute(): ?string
    {
        return null;
    }

    /**
     * Scope query untuk IsExist
     *
     * @return Builder
     */
    public function scopeIsExist(mixed $query, mixed $value)
    {
        return $query->where('url_surat', $value)->exists();
    }

    /**
     * Scope query untuk Kunci Surat
     *
     * @return Builder
     */
    public function scopeKunci(mixed $query, mixed $value = self::KUNCI)
    {
        if ($value == '') {
            return $query;
        }

        return $query->where('kunci', $value);
    }

    /**
     * Scope query untuk Favorit Surat
     *
     * @return Builder
     */
    public function scopeFavorit(mixed $query, mixed $value = self::FAVORIT)
    {
        return $query->where('favorit', $value);
    }

    /**
     * Scope query untuk Jenis Surat
     *
     * @return Builder
     */
    public function scopeJenis(mixed $query, mixed $value)
    {
        if (empty($value)) {
            return $query->whereNotNull('jenis');
        }

        if (is_array($value)) {
            return $query->whereIn('jenis', $value);
        }

        return $query->where('jenis', $value);
    }

    /**
     * Scope query untuk layanan mandiri.
     *
     * @param Builder    $query
     * @param mixed|null $url
     *
     * @return Builder
     */
    public function scopeCetak($query, $url = null)
    {
        return $this->scopeKunci($query, self::KUNCI_DISABLE)->where('url_surat', $url);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new RemoveRtfScope());
    }

    public static function format_penomoran_surat(array $data): array|string
    {
        $thn     = $data['surat']['cek_thn'] ?? date('Y');
        $bln     = $data['surat']['cek_bln'] ?? date('m');
        $setting = format_penomoran_surat($data['surat']['format_nomor_global'], setting('format_nomor_surat'), $data['surat']['format_nomor']);
        self::substitusi_nomor_surat($data['input']['nomor'], $setting);
        $array_replace = [
            '[kode_surat]'   => $data['surat']['kode_surat'],
            '[tahun]'        => $thn,
            '[bulan_romawi]' => bulan_romawi((int) $bln),
            '[kode_desa]'    => identitas()->kode_desa,
        ];

        return str_ireplace(array_keys($array_replace), array_values($array_replace), $setting);
    }

    public static function substitusi_nomor_surat($nomor, &$buffer): void
    {
        $buffer = str_replace('[nomor_surat]', "{$nomor}", $buffer);
        if (preg_match_all('/\[nomor_surat,\s*\d+\]/', $buffer, $matches)) {
            foreach ($matches[0] as $match) {
                $parts         = explode(',', $match);
                $panjang       = (int) trim(rtrim($parts[1], ']'));
                $nomor_panjang = str_pad("{$nomor}", $panjang, '0', STR_PAD_LEFT);
                $buffer        = str_replace($match, $nomor_panjang, $buffer);
            }
        }
    }

    /**
     * Get the logSurat that owns the FormatSurat
     */
    public function logSurat(): BelongsTo
    {
        return $this->belongsTo(LogSurat::class, 'id', 'id_format_surat');
    }

    /**
     * Get the formatNomorSuratAttribute
     */
    public function getFormatNomorSuratAttribute()
    {
        return $this->format_nomor_global === false && empty($this->format_nomor) ? setting('format_nomor_surat') : $this->format_nomor;
    }

    public function isBawaan(): bool
    {
        return $this->jenis == self::TINYMCE_SISTEM;
    }

    protected function scopeSistem(Builder $query)
    {
        return $query->whereIn('jenis', self::SISTEM);
    }
}
