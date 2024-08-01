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

use App\Enums\JenisKelaminEnum;
use App\Enums\SHDKEnum;
use App\Traits\Author;
use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Penduduk extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * Static data tempat lahir.
     *
     * @var array
     */
    public const TEMPAT_LAHIR = [
        1 => 'RS/RB',
        2 => 'Puskesmas',
        3 => 'Polindes',
        4 => 'Rumah',
        5 => 'Lainnya',
    ];

    /**
     * Static data jenis kelahiran.
     *
     * @var array
     */
    public const JENIS_KELAHIRAN = [
        1 => 'Tunggal',
        2 => 'Kembar 2',
        3 => 'Kembar 3',
        4 => 'Kembar 4',
    ];

    /**
     * Static data penolong kelahiran.
     *
     * @var array
     */
    public const PENOLONG_KELAHIRAN = [
        1 => 'Dokter',
        2 => 'Bidan Perawat',
        3 => 'Dukun',
        4 => 'Lainnya',
    ];

    /**
     * {@inheritDoc}
     */
    protected $table = 'tweb_penduduk';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'email',
        'telepon',
        'telegram',
        'hubung_warga',
    ];

    /**
     * {@inheritDoc}
     */
    protected $appends = [
        'usia',
        'alamat_wilayah',
    ];

    /**
     * {@inheritDoc}
     */
    protected $with = [
        'jenisKelamin',
        'agama',
        'pendidikan',
        'pendidikanKK',
        'pekerjaan',
        'wargaNegara',
        'golonganDarah',
        'cacat',
        'statusKawin',
        'pendudukStatus',
        'wilayah',
        'keluarga',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'tanggallahir' => 'datetime:Y-m-d',
    ];

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define a one-to-one relationship.
     *
     * @return HasOne
     */
    public function mandiri()
    {
        return $this->hasOne(PendudukMandiri::class, 'id_pend')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return HasOne
     */
    public function kia_ibu()
    {
        return $this->hasOne(KIA::class, 'ibu_id')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return HasOne
     */
    public function kia_anak()
    {
        return $this->hasOne(KIA::class, 'anak_id')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function jenisKelamin()
    {
        return $this->belongsTo(Sex::class, 'sex')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function agama()
    {
        return $this->belongsTo(Agama::class, 'agama_id')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_sedang_id')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function pendidikanKK()
    {
        return $this->belongsTo(PendidikanKK::class, 'pendidikan_kk_id')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_id')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function wargaNegara()
    {
        return $this->belongsTo(WargaNegara::class, 'warganegara_id')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function golonganDarah()
    {
        return $this->belongsTo(GolonganDarah::class, 'golongan_darah_id')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function cacat()
    {
        return $this->belongsTo(Cacat::class, 'cacat_id')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function sakitMenahun()
    {
        return $this->belongsTo(SakitMenahun::class, 'sakit_menahun_id')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function kb()
    {
        return $this->belongsTo(KB::class, 'cara_kb_id')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function statusKawin()
    {
        return $this->belongsTo(StatusKawin::class, 'status_kawin')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function pendudukHubungan()
    {
        return $this->belongsTo(PendudukHubungan::class, 'kk_level')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function pendudukStatus()
    {
        return $this->belongsTo(PendudukStatus::class, 'status')->withDefault();
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'id_kk')->withDefault()->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function rtm()
    {
        return $this->belongsTo(Rtm::class, 'id_rtm', 'no_kk')->withDefault()->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
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

    /**
     * Define a one-to-many relationship.
     *
     * @return HasMany
     */
    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_pend')->select('id', 'id_pend', 'nama', 'id_syarat', 'tgl_upload', 'dok_warga')->with(['jenisDokumen'])->hidup();
    }

    /**
     * Getter wajib ktp attribute.
     */
    public function getWajibKTPAttribute(): string
    {
        return (($this->tanggallahir->age > 16) || (!empty($this->status_kawin) && $this->status_kawin != 1))
            ? 'WAJIB KTP'
            : 'BELUM';
    }

    /**
     * Getter tempat dilahirkan attribute.
     *
     * @return string
     */
    public function getDiLahirkanAttribute()
    {
        return static::TEMPAT_LAHIR[$this->tempat_dilahirkan]
            ?? '';
    }

    /**
     * Getter jenis lahir attribute.
     *
     * @return string
     */
    public function getJenisLahirAttribute()
    {
        return static::JENIS_KELAHIRAN[$this->jenis_kelahiran]
            ?? '';
    }

    /**
     * Getter jenis lahir attribute.
     *
     * @return string
     */
    public function getPenolongLahirAttribute()
    {
        return static::PENOLONG_KELAHIRAN[$this->penolong_kelahiran]
            ?? '';
    }

    /**
     * Getter status perkawinan attribute.
     *
     * @return string
     */
    public function getStatusPerkawinanAttribute()
    {
        return !empty($this->status_kawin) && $this->status_kawin != 2
            ? $this->statusKawin->nama
            : (
                empty($this->akta_perkawinan)
                ? 'KAWIN BELUM TERCATAT'
                : 'KAWIN TERCATAT'
            );
    }

    /**
     * Getter status hamil attribute.
     */
    public function getStatusHamilAttribute(): string
    {
        return empty($this->hamil) ? 'TIDAK HAMIL' : 'HAMIL';
    }

    /**
     * Getter nama asuransi attribute.
     */
    public function getNamaAsuransiAttribute(): string
    {
        return !empty($this->id_asuransi) && $this->id_asuransi != 1
            ? (($this->id_asuransi == 99)
                ? "Nama/No Asuransi : {$this->no_asuransi}"
                : "No Asuransi : {$this->no_asuransi}")
            : '';
    }

    /**
     * Getter url foto attribute.
     *
     * @return string
     */
    public function getUrlFotoAttribute(): void
    {
        // try {
        //     return Storage::disk('ftp')->exists("desa/upload/user_pict/{$this->foto}")
        //         ? Storage::disk('ftp')->url("desa/upload/user_pict/{$this->foto}")
        //         : null;
        // } catch (Exception $e) {
        //     Log::error($e);
        // }
    }

    /**
     * Scope query untuk status penduduk
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeStatus($query, $value = 1)
    {
        return $query->where('status_dasar', $value);
    }

    public function scopeHubungWarga($query)
    {
        return $query->select(['id', 'nama', 'telepon', 'email', 'telegram', 'hubung_warga'])
            ->whereNotNull('telepon')
            ->orWhereNotNull('email')
            ->orWhereNotNull('telegram')
            ->status();
    }

    /**
     * Scope query untuk menyaring data penduduk berdasarkan parameter yang ditentukan
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopefilters($query, array $filters = [], array $allowedFilters = ['sex', 'status_dasar', 'kk_level'])
    {
        foreach ($filters as $key => $value) {
            if (!in_array($key, $allowedFilters)) {
                continue;
            }

            $query->when($value ?? false, static function ($query) use ($value, $key) {
                if (is_array($value)) {
                    return $query->whereIn($key, $value);
                }

                return $query->where($key, $value);
            });
        }

        return $query;
    }

    public function getUsiaAttribute(): string
    {
        return $this->getUmurAttribute() . ' Tahun';
    }

    public function getUmurAttribute()
    {
        return usia($this->tanggallahir, null, '%y');
    }

    public function getAlamatWilayahAttribute(): string
    {
        if ($this->id_kk != null) {
            return $this->keluarga->alamat . ' RT ' . $this->keluarga->wilayah->rt . ' / RW ' . $this->keluarga->wilayah->rw . ' ' . ucwords(setting('sebutan_dusun') . ' ' . $this->keluarga->wilayah->dusun);
        }

        return $this->alamat_sekarang . ' RT ' . $this->wilayah->rt . ' / RW ' . $this->wilayah->rw . ' ' . ucwords(setting('sebutan_dusun') . ' ' . $this->wilayah->dusun);
    }

    public function scopeKepalaKeluarga($query)
    {
        return $query->where(['kk_level' => SHDKEnum::KEPALA_KELUARGA]);
    }

    public function scopeAyah($query, $idKk)
    {
        return $query->where('id_kk', $idKk)->whereIn('kk_level', [SHDKEnum::KEPALA_KELUARGA, SHDKEnum::SUAMI])->where('sex', JenisKelaminEnum::LAKI_LAKI);
    }

    public function scopeIbu($query, $idKk)
    {
        return $query->where('id_kk', $idKk)->whereIn('kk_level', [SHDKEnum::KEPALA_KELUARGA, SHDKEnum::ISTRI])->where('sex', JenisKelaminEnum::PEREMPUAN);
    }

    public function isKepalaKeluarga()
    {
        return $this->attributes['kk_level'] == SHDKEnum::KEPALA_KELUARGA;
    }

    public function formIndividu()
    {
        $individu                = $this->toArray();
        $individu['pendidikan']  = $individu['pendidikan_k_k']['nama'] ?? ($individu['pendidikan']['nama'] ?? '');
        $individu['warganegara'] = $individu['warga_negara']['nama'] ?? '';
        $individu['agama']       = $this->agama->nama ?? '';
        $individu['umur']        = $this->umur;

        return $individu;
    }

    protected function scopeDpt($query, $tglPemilihan = null)
    {
        $tglPemilihan ??= date('d-m-Y');

        return $query->where(['status_dasar' => 1, 'status' => 1, 'warganegara_id' => 1])
            ->where(static function ($q) use ($tglPemilihan) {
                return $q->whereRaw(DB::raw("(DATE_FORMAT(FROM_DAYS(TO_DAYS(STR_TO_DATE('{$tglPemilihan}','%d-%m-%Y'))-TO_DAYS(`tanggallahir`)), '%Y')+0 ) >= 17"))
                    ->orWhereIn('status_kawin', [2, 3, 4]);
            })->whereNotIn('pekerjaan_id', ['6', '7']);
    }

    protected function scopeBatasiUmur($query, $tglPemilihan, $umurObj = [])
    {
        if (empty($umurObj['max']) && empty($umurObj['min'])) {
            return $query;
        }
        $satuan  = $umurObj['satuan'] == 'tahun' ? 'YEAR' : 'MONTH';
        $umurMin = empty($umurObj['min']) ? 0 : $umurObj['min'];
        $umurMax = empty($umurObj['max']) ? 1000 : $umurObj['max'];

        return $query->whereRaw(DB::raw("TIMESTAMPDIFF({$satuan}, tanggallahir, STR_TO_DATE('{$tglPemilihan}','%d-%m-%Y')) between {$umurMin} and {$umurMax}"));
    }

    /**
     * Get all of the pesan for the Penduduk
     */
    public function pesan(): HasMany
    {
        return $this->hasMany(PesanMandiri::class, 'identitas', 'nik');
    }
}
