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

use App\Enums\AgamaEnum;
use App\Enums\CaraKBEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\PendidikanSedangEnum;
use App\Enums\SasaranEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\StatusKawinSpesifikEnum;
use App\Enums\StatusPendudukEnum;
use App\Scopes\AccessWilayahScope;
use App\Traits\Author;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Penduduk extends BaseModel
{
    use Author;
    use ConfigId;
    use ShortcutCache;

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
        'nama',
        'nik',
        'id_kk',
        'kk_level',
        'id_rtm',
        'rtm_level',
        'sex',
        'tempatlahir',
        'tanggallahir',
        'agama_id',
        'pendidikan_kk_id',
        'pendidikan_sedang_id',
        'pekerjaan_id',
        'status_kawin',
        'warganegara_id',
        'dokumen_pasport',
        'dokumen_kitas',
        'ayah_nik',
        'ibu_nik',
        'nama_ayah',
        'nama_ibu',
        'foto',
        'golongan_darah_id',
        'id_cluster',
        'status',
        'alamat_sebelumnya',
        'alamat_sekarang',
        'status_dasar',
        'hamil',
        'cacat_id',
        'sakit_menahun_id',
        'akta_lahir',
        'akta_perkawinan',
        'tanggalperkawinan',
        'akta_perceraian',
        'tanggalperceraian',
        'cara_kb_id',
        'telepon',
        'tanggal_akhir_paspor',
        'no_kk_sebelumnya',
        'ktp_el',
        'status_rekam',
        'waktu_lahir',
        'tempat_dilahirkan',
        'jenis_kelahiran',
        'kelahiran_anak_ke',
        'penolong_kelahiran',
        'berat_lahir',
        'panjang_lahir',
        'tag_id_card',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'id_asuransi',
        'no_asuransi',
        'status_asuransi',
        'email',
        'email_token',
        'email_tgl_kadaluarsa',
        'email_tgl_verifikasi',
        'telegram',
        'telegram_token',
        'telegram_tgl_kadaluarsa',
        'telegram_tgl_verifikasi',
        'bahasa_id',
        'ket',
        'negara_asal',
        'tempat_cetak_ktp',
        'tanggal_cetak_ktp',
        'suku',
        'bpjs_ketenagakerjaan',
        'hubung_warga',
    ];

    /**
     * {@inheritDoc}
     */
    protected $appends = [
        'pendidikan',
        'usia',
        'alamat_wilayah',
        'alamat_wilayah_kartu_keluarga',
        'nama_asuransi',
        'jml_anak',
        'lokasi',
        'status_perkawinan',
    ];

    /**
     * {@inheritDoc}
     */
    protected $with = [
        'jenisKelamin',
        'agama',
        'pendidikanKK',
        'pekerjaan',
        'wargaNegara',
        'golonganDarah',
        'cacat',
        'statusKawin',
        'pendudukStatus',
        'wilayah',
        'keluarga',
        'rtm',
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

    private $wilayahColumn = 'id_cluster';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new AccessWilayahScope());
    }

    public function getWilayahColumn()
    {
        return $this->wilayahColumn;
    }

    public function getJmlAnakAttribute(): string
    {
        return $this->where('id_kk', $this->id_kk)->where('kk_level', SHDKEnum::ANAK)->count();
    }

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

    public function getPendidikanAttribute()
    {
        return PendidikanSedangEnum::valueOf($this->pendidikan_sedang_id);
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

    public function scopeUrut($query)
    {
        return $query
            ->select('tweb_penduduk.*')
            ->leftJoin('tweb_keluarga', 'tweb_keluarga.id', '=', 'tweb_penduduk.id_kk')
            // ->orderBy(DB::raw('CONCAT(tweb_keluarga.no_kk, tweb_penduduk.id_kk, tweb_penduduk.kk_level)'), 'asc')
            ->orderBy(DB::raw("CASE
                WHEN CHAR_LENGTH(tweb_penduduk.nik) < 16 THEN 1
                WHEN tweb_penduduk.nik LIKE '0%' AND CHAR_LENGTH(tweb_penduduk.nik) = 16 THEN 2
                ELSE 3
                END"));
    }

    public function scopeOrderKeluarga($query)
    {
        return $query->orderBy('kk_level')->orderBy('tanggallahir');
    }

    public function scopeEksporData($query)
    {
        return $query->select([
            'tweb_keluarga.alamat',
            'tweb_wil_clusterdesa.dusun',
            'tweb_wil_clusterdesa.rw',
            'tweb_wil_clusterdesa.rt',
            'tweb_penduduk.nama AS nama',
            'tweb_keluarga.no_kk AS nomor_kk',
            'tweb_penduduk.nik AS nomor_nik',
            'tweb_penduduk.sex as gender',
            'tweb_penduduk.tempatlahir AS tempat_lahir',
            'tweb_penduduk.tanggallahir AS tanggal_lahir',
            'tweb_penduduk.agama_id',
            'tweb_penduduk.pendidikan_kk_id AS pendidikan_dlm_kk',
            'tweb_penduduk.pendidikan_sedang_id AS pendidikan_sdg_ditempuh',
            // dapatkan data pekerjaan etc dari relasi ? lakukan di method map?
            // cari cara lain? db select manual tanpa model?
            // di cara export using fast excel itu semuanya yang terexport, sehingga harus di select manual agar sama seperti export sevbelumnya
            'tweb_penduduk.pekerjaan_id',
            'tweb_penduduk.status_kawin AS status_kawin',
            'tweb_penduduk.kk_level AS hubungan_keluarga',
            'tweb_penduduk.warganegara_id AS kewarganegaraan',
            'tweb_penduduk.nama_ayah AS nama_ayah',
            'tweb_penduduk.nama_ibu AS nama_ibu',
            'tweb_penduduk.golongan_darah_id AS gol_darah',
            'tweb_penduduk.akta_lahir AS akta_lahir',
            'tweb_penduduk.dokumen_pasport AS nomor_dokumen_pasport',
            'tweb_penduduk.tanggal_akhir_paspor AS tanggal_akhir_paspor',
            'tweb_penduduk.dokumen_kitas AS nomor_dokumen_kitas',
            'tweb_penduduk.ayah_nik AS nik_ayah',
            'tweb_penduduk.ibu_nik AS nik_ibu',
            'tweb_penduduk.akta_perkawinan AS nomor_akta_perkawinan',
            'tweb_penduduk.tanggalperkawinan AS tanggal_perkawinan',
            'tweb_penduduk.akta_perceraian AS nomor_akta_perceraian',
            'tweb_penduduk.tanggalperceraian AS tanggal_perceraian',
            'tweb_penduduk.cacat_id AS cacat',
            'tweb_penduduk.cara_kb_id AS cara_kb',
            'tweb_penduduk.hamil AS hamil',
            'tweb_penduduk.ktp_el AS ktp_el',
            'tweb_penduduk.status_rekam AS status_rekam',
            'tweb_penduduk.alamat_sekarang AS alamat_sekarang',
            'tweb_penduduk.id',
            'tweb_penduduk.foto',
            'tweb_penduduk.status_dasar',
            'tweb_penduduk.created_at',
            'tweb_penduduk.updated_at',
            // Kolom tambahan khusus OpenDK dimana?
        ])
            ->leftJoin('tweb_keluarga', 'tweb_keluarga.id', '=', 'tweb_penduduk.id_kk')
            ->leftJoin('tweb_wil_clusterdesa', 'tweb_penduduk.id_cluster', '=', 'tweb_wil_clusterdesa.id')
            ->orderBy('tweb_keluarga.no_kk', 'asc')
            ->orderBy('tweb_penduduk.kk_level', 'asc')->get();
    }

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
     * Define a one-to-many relationship.
     *
     * @return HasOne
     */
    public function log_latest()
    {
        return $this->hasOne(LogPenduduk::class, 'id_pend')->latest();
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return HasMany
     */
    public function log()
    {
        return $this->hasMany(LogPenduduk::class, 'id_pend');
    }

    /**
     * Getter wajib ktp attribute.
     */
    public function getWajibKTPAttribute(): string
    {
        return (($this->tanggallahir->age > 16) || (! empty($this->status_kawin) && $this->status_kawin != 1))
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
        $status = match ($this->status_kawin) {
            StatusKawinSpesifikEnum::KAWIN_TERCATAT => $this->isBelumTercatat($this->akta_perkawinan, $this->tanggalperkawinan)
                    ? StatusKawinSpesifikEnum::KAWIN_BELUM_TERCATAT
                    : StatusKawinSpesifikEnum::KAWIN_TERCATAT,

            StatusKawinSpesifikEnum::CERAIHIDUP_TERCATAT => $this->isBelumTercatat($this->akta_perceraian, $this->tanggalperceraian)
                    ? StatusKawinSpesifikEnum::CERAIHIDUP_BELUM_TERCATAT
                    : StatusKawinSpesifikEnum::CERAIHIDUP_TERCATAT,

            default => $this->status_kawin,
        };

        return StatusKawinSpesifikEnum::valueOf($status);
    }

    private function isBelumTercatat($akta, $tanggal): bool
    {
        return empty($akta) && empty($tanggal);
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
        return ! empty($this->id_asuransi) && $this->id_asuransi != 1 ? (($this->id_asuransi == 99) ? "Nama/No Asuransi : {$this->no_asuransi}" : "No Asuransi : {$this->no_asuransi}") : '';
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

    /**
     * Scope query untuk status dasar penduduk
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeStatusDasar($query, array $value)
    {
        return $query->whereIn('status_dasar', $value);
    }

    /**
     * Scope query untuk mendapatkan penduduk hidup
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeHidup($query, int $value)
    {
        return $query->where('status_dasar', $value);
    }

    /**
     * Scope query untuk status penduduk
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeStatusPenduduk($query, $value = 1)
    {
        return $query->where('status', $value);
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
            if (! in_array($key, $allowedFilters)) {
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

    public function getAlamatWilayahKartuKeluargaAttribute(): string
    {
        if ($this->id_kk != null) {
            return $this->keluarga->alamat . ' ' . ucwords(setting('sebutan_dusun') . ' ' . $this->keluarga->wilayah->dusun);
        }

        return $this->alamat_sekarang . ' ' . ucwords(setting('sebutan_dusun') . ' ' . $this->wilayah->dusun);
    }

    public function scopeKepalaKeluarga($query)
    {
        return $query->where(['kk_level' => SHDKEnum::KEPALA_KELUARGA]);
    }

    public static function orangTua($idKk)
    {
        return [
            'ayah' => self::ayah($idKk)->first(['nama', 'nik']),
            'ibu'  => self::ibu($idKk)->first(['nama', 'nik']),
        ];
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
        $individu['pendidikan']  = $individu['pendidikan_k_k']['nama'] ?? ($individu['pendidikan'] ?? '');
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

    protected function scopeDusun($query, $dusun = null)
    {
        if (! $dusun) {
            return $query;
        }
        $listRt = Wilayah::whereDusun($dusun)->pluck('id');

        return $query->whereIn('id_cluster', $listRt);
    }

    protected function scopeBatasiUmur($query, $tglPemilihan, $umurObj = [])
    {
        if (empty($umurObj) || ! isset($umurObj['min']) || ! isset($umurObj['max'])) {
            return $query;
        }

        if (isset($umurObj['min'], $umurObj['max'])  ) {
            if ($umurObj['min'] == '' && $umurObj['max'] == '') {
                return $query;
            }
        }

        $satuan  = $umurObj['satuan'] == 'tahun' ? 'YEAR' : 'MONTH';
        $umurMin = empty($umurObj['min']) ? 0 : $umurObj['min'];
        $umurMax = empty($umurObj['max']) && $umurObj['max'] != 0 ? 1000 : $umurObj['max'];

        if ($umurMax == '') {
            $umurMax = 1000;
        }

        return $query->whereRaw(DB::raw("TIMESTAMPDIFF({$satuan}, tanggallahir, STR_TO_DATE('{$tglPemilihan}','%d-%m-%Y')) between {$umurMin} and {$umurMax}"));
    }

    public function scopeFilterLog($query, array $filters)
    {
        $tahun = $filters['tahun'];
        $bulan = $filters['bulan'];

        switch (true) {
            case $tahun && $bulan:
                $tahun_bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

                return $query->whereHas('log_latest', static function ($query) use ($tahun, $tahun_bulan) {
                    $query->whereRaw("date_format(tgl_lapor, '%Y-%m') <= '{$tahun}-{$tahun_bulan}'");
                });
                break;

            case $tahun:
                return $query->whereHas('log_latest', static function ($query) use ($tahun) {
                    $query->whereYear('tgl_lapor', '<=', $tahun);
                });
                break;

            case $bulan:
                return $query->whereHas('log_latest', static function ($query) use ($bulan) {
                    $query->whereMonth('tgl_lapor', '<=', $bulan);
                });
                break;

            default:
                return $query;
        }
    }

    /**
     * Get all of the pesan for the Penduduk
     */
    public function pesan(): HasMany
    {
        return $this->hasMany(PesanMandiri::class, 'identitas', 'nik');
    }

    public function bantuan(): HasManyThrough
    {
        return $this->hasManyThrough(Bantuan::class, BantuanPeserta::class, 'peserta', 'id', 'nik', 'program_id')->where(['sasaran' => SasaranEnum::PENDUDUK]);
    }

    public function pesertaBantuan(): HasMany
    {
        return $this->hasMany(BantuanPeserta::class, 'peserta', 'nik')->whereHas('bantuanPenduduk');
    }

    public function asuransi(): BelongsTo
    {
        return $this->belongsTo(PendudukAsuransi::class, 'id_asuransi');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pengubah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function pamong(): HasOne
    {
        return $this->hasOne(Pamong::class, 'id_pend');
    }

    public function pamongUser(): HasOne
    {
        return $this->hasOne(Pamong::class, 'id_pend')->whereHas('user');
    }

    public function bahasa()
    {
        return $this->belongsTo(Bahasa::class, 'bahasa_id');
    }

    public function logSurat(): HasMany
    {
        return $this->hasMany(LogSurat::class, 'id_pend');
    }

    /**
     * Get the map associated with the Penduduk
     */
    public function map(): HasOne
    {
        return $this->hasOne(PendudukMap::class, 'id', 'id');
    }

    public static function activeMap($filter = [])
    {
        if ($filter['layer_keluarga']) {
            $groupType = 'keluarga';
        } elseif ($filter['layer_rtm']) {
            $groupType = 'rtm';
        } else {
            $groupType = 'penduduk';
        }

        $sex            = $filter['sex'];
        $dusun          = $filter['dusun'];
        $rw             = $filter['rw'];
        $rt             = $filter['rt'];
        $agama          = $filter['agama'];
        $cari           = $filter['cari'];
        $statusPenduduk = $filter['status_penduduk'];
        $statusKawin    = $filter['status_kawin'];
        $pekerjaan      = $filter['pekerjaan_id'];
        $pendidikan     = $filter['pendidikan_kk_id'];
        $umurMin        = $filter['umur_min'];
        $umurMax        = $filter['umur_max'];
        $umurSatuan     = $filter['umur'] ?? 'tahun'; // tahun or bulan
        $idCluster      = [];

        if (empty($idCluster) && ! empty($rt)) {
            $rts       = Wilayah::whereDusun($dusun)->whereRw($rw)->whereRt($rt)->first();
            $idCluster = [$rts->id];
        }

        if (empty($idCluster) && ! empty($rw)) {
            $rws       = Wilayah::with(['rts' => static fn ($q) => $q->select(['id'])])->whereDusun($dusun)->whereRw($rw)->first();
            $idCluster = array_merge([$rws->id], $rws->rts->pluck('id')->toArray());
        }

        if (empty($idCluster) && ! empty($dusun)) {
            $idCluster = Wilayah::whereDusun($dusun)->select(['id'])->get()->pluck('id')->toArray();
        }

        return self::whereHas('map')->withOnly([
            'wilayah',
            'keluarga',
            'rtm',
        ])->with(['map'])->hidup(StatusDasarEnum::HIDUP)->selectRaw('*')->when($groupType, static function ($r) use ($groupType) {
            if ($groupType == 'rtm') {
                return $r->whereNotNull('id_rtm')->where('id_rtm', '!=', 0)->where(['rtm_level' => 1])->selectRaw(DB::raw('(SELECT COUNT(*) FROM tweb_penduduk p WHERE p.id_rtm != 0 and p.id_rtm = tweb_penduduk.id_rtm) as jumlah_anggota'));
            }
            if ($groupType == 'keluarga') {
                return $r->whereNotNull('id_kk')->where(['kk_level' => 1])->selectRaw(DB::raw('(SELECT COUNT(*) FROM tweb_penduduk p WHERE p.id_kk = tweb_penduduk.id_kk) as jumlah_anggota'));
            }

            return $r->selectRaw(DB::raw('(SELECT COUNT(*) FROM tweb_penduduk p WHERE p.id_kk = tweb_penduduk.id_kk) as jumlah_anggota'));
        })->when(! empty($idCluster), static fn ($q) => $q->whereIn('id_cluster', $idCluster))
            ->when($sex, static fn ($q) => $q->whereSex($sex))
            ->when($agama, static fn ($q) => $q->whereAgamaId($agama))
            ->when($umurMin && $umurMax, static fn ($q) => $q->batasiUmur(['max' => $umurMax, 'min' => $umurMin, 'satuan' => $umurSatuan], date('d-m-Y')))
            ->when($pendidikan, static fn ($q) => $q->wherePendidikanKkId($pendidikan))
            ->when($pekerjaan, static fn ($q) => $q->wherePekerjaanId($pekerjaan))
            ->when($statusPenduduk, static fn ($q) => $q->whereStatus($statusPenduduk))
            ->when($statusKawin, static fn ($q) => $q->whereStatusKawin($statusKawin))
            ->when($cari, static fn ($q) => $q->where(static function ($r) use ($cari) {
                $r->where('nama', 'like', "%{$cari}%")->orWhere('nik', 'like', "%{$cari}%")->orWhere('tag_id_card', 'like', "%{$cari}%");
            }))
            ->get()->map(static function ($item) {
                $item->id_sex = $item->sex;
                $item->sex    = JenisKelaminEnum::valueOf($item->sex) ?: '';
                $item->foto   = $item->foto;
                $item->agama  = AgamaEnum::valueOf($item->agama_id) ?: '';
                $item->alamat = $item->alamat_wilayah;
                $item->lat    = $item->map->lat;
                $item->lng    = $item->map->lng;
                $item->umur   = $item->umur;
                unset($item->map);

                return $item;
            })->toArray();
    }

    public static function nikSementara()
    {
        $digit = self::selectRaw('RIGHT(nik, 5) as digit')
            ->orderBy(DB::raw('RIGHT(nik, 5)'), 'desc')
            ->where('nik', 'like', '0%')
            ->where('nik', '!=', '0')
            ->first()
            ->digit ?? 0;

        // NIK Sementara menggunakan format 0[kode-desa][nomor-urut]
        return '0' . identitas()->kode_desa . sprintf('%05d', $digit + 1);
    }

    public static function validasi(&$data, $id = null)
    {
        $result = ['status' => true, 'messages' => ''];
        // validasi jika NIK sementara dengan data yang sama sudah ada
        if (strpos($data['nik'], '0') === 0) {
            $tanggal_lahir = date('Y-m-d', strtotime($data['tanggallahir']));

            $existingData = Penduduk::where('nama', $data['nama'])
                ->where('tanggallahir', $tanggal_lahir)
                ->where('tempatlahir', $data['tempatlahir'])
                ->where('sex', $data['sex'])
                ->where('id', '!=', $id)
                ->exists();

            if ($existingData) {
                $result['status']   = false;
                $result['messages'] = "Data Penduduk dengan NIK Sementara {$data['nik']} sudah ada";

                return $result;
            }
        }

        $data['tanggallahir']         = empty($data['tanggallahir']) ? null : tgl_indo_in($data['tanggallahir']);
        $data['tanggal_akhir_paspor'] = empty($data['tanggal_akhir_paspor']) ? null : tgl_indo_in($data['tanggal_akhir_paspor']);
        $data['tanggalperkawinan']    = empty($data['tanggalperkawinan']) ? null : tgl_indo_in($data['tanggalperkawinan']);
        $data['tanggalperceraian']    = empty($data['tanggalperceraian']) ? null : tgl_indo_in($data['tanggalperceraian']);
        $data['tanggal_cetak_ktp']    = empty($data['tanggal_cetak_ktp']) ? null : tgl_indo_in($data['tanggal_cetak_ktp']);

        $data['pendidikan_kk_id']     = $data['pendidikan_kk_id'] ?: null;
        $data['pendidikan_sedang_id'] = $data['pendidikan_sedang_id'] ?: null;
        $data['pekerjaan_id']         = $data['pekerjaan_id'] ?: null;
        $data['status_kawin']         = $data['status_kawin'] ?: null;
        $data['id_asuransi']          = $data['id_asuransi'] ?: null;
        $data['hamil']                = $data['hamil'] ?: null;

        $data['ktp_el']             = $data['ktp_el'] ?: null;
        $data['tag_id_card']        = $data['tag_id_card'] ?: null;
        $data['status_rekam']       = $data['status_rekam'] ?: null;
        $data['berat_lahir']        = $data['berat_lahir'] ?: null;
        $data['tempat_dilahirkan']  = $data['tempat_dilahirkan'] ?: null;
        $data['jenis_kelahiran']    = $data['jenis_kelahiran'] ?: null;
        $data['penolong_kelahiran'] = $data['penolong_kelahiran'] ?: null;
        $data['panjang_lahir']      = $data['panjang_lahir'] ?: null;
        $data['cacat_id']           = $data['cacat_id'] ?: null;
        $data['sakit_menahun_id']   = $data['sakit_menahun_id'] ?: null;
        $data['ket']                = htmlentities($data['ket']);
        if (empty($data['id_asuransi']) || $data['id_asuransi'] == 1) {
            $data['no_asuransi'] = null;
        }
        if (empty($data['warganegara_id'])) {
            $data['warganegara_id'] = 1;
        } //default WNI

        // Hanya status 'kawin' yang boleh jadi akseptor kb
        if ($data['status_kawin'] != 2 || ! in_array($data['cara_kb_id'], CaraKBEnum::keys())) {
            $data['cara_kb_id'] = null;
        }
        // Status hamil tidak berlaku bagi laki-laki
        if ($data['sex'] == 1) {
            $data['hamil'] = null;
        }
        if (empty($data['kelahiran_anak_ke'])) {
            $data['kelahiran_anak_ke'] = null;
        }
        if ($data['warganegara_id'] == 1 || empty($data['dokumen_kitas'])) {
            $data['dokumen_kitas'] = null;
        }
        // Tanggal cetak ktp harus <= tanggal input
        if ($data['tanggal_cetak_ktp'] > date('Y-m-d')) {
            $data['tanggal_cetak_ktp'] = date('Y-m-d');
        }

        switch ($data['status_kawin']) {
            case 1:
                // Status 'belum kawin' tidak berlaku akta perkawinan dan perceraian
                $data['akta_perkawinan']   = '';
                $data['akta_perceraian']   = '';
                $data['tanggalperkawinan'] = null;
                $data['tanggalperceraian'] = null;
                break;

            case 2:
                // Status 'kawin' tidak berlaku akta perceraian
                $data['akta_perceraian']   = '';
                $data['tanggalperceraian'] = null;
                break;

            case 3:
            case 4:
                break;
        }

        // Sterilkan data
        $data['no_kk_sebelumnya']     = preg_replace('/[^0-9\.]/', '', strip_tags($data['no_kk_sebelumnya']));
        $data['akta_lahir']           = nomor_surat_keputusan($data['akta_lahir']);
        $data['tempatlahir']          = strip_tags($data['tempatlahir']);
        $data['dokumen_pasport']      = nomor_surat_keputusan($data['dokumen_pasport']);
        $data['nama_ayah']            = nama($data['nama_ayah']);
        $data['nama_ibu']             = nama($data['nama_ibu']);
        $data['alamat_sebelumnya']    = strip_tags($data['alamat_sebelumnya']);
        $data['alamat_sekarang']      = strip_tags($data['alamat_sekarang']);
        $data['akta_perkawinan']      = nomor_surat_keputusan($data['akta_perkawinan']);
        $data['akta_perceraian']      = nomor_surat_keputusan($data['akta_perceraian']);
        $data['bpjs_ketenagakerjaan'] = nomor_surat_keputusan($data['bpjs_ketenagakerjaan']);
        $data['suku']                 = nama_terbatas($data['suku']);

        $data['telepon']  = empty($data['telepon']) ? null : bilangan($data['telepon']);
        $data['email']    = empty($data['email']) ? null : email($data['email']);
        $data['telegram'] = empty($data['telegram']) ? null : bilangan($data['telegram']);

        $data['status_asuransi'] = empty($data['status_asuransi']) ? null : $data['status_asuransi'];

        $valid = [];
        if (preg_match("/[^a-zA-Z '\\.,\\-]/", $data['nama'])) {
            $valid[] = 'Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip';
        }
        if (isset($data['nik'])) {
            $errorNik = self::nik_error($data['nik'], 'NIK');
            if ($errorNik) {
                $result['status']   = false;
                $result['messages'] = $errorNik;

                return $result;
            }
            //Tidak termasuk penduduk yg diupdate
            $existingData = Penduduk::select(['nik', 'status_dasar'])
                ->when($id, static fn ($q) => $q->where('id', '!=', $id))
                ->where('nik', $data['nik'])
                ->where('nik', '!=', 0)
                ->first();

            if ($existingData) {
                if ($existingData->status_dasar != StatusDasarEnum::PERGI) {
                    $result['messages'] = "NIK {$data['nik']} sudah digunakan";
                } else {
                    $result['messages'] = "NIK {$data['nik']} terdaftar Penduduk PERGI. Ubah Status di Menu Log Penduduk";
                }
                $result['status'] = false;

                return $result;
            }
        }
        $errorNikAyah = self::nik_error($data['ayah_nik'], 'NIK Ayah');
        if ($errorNikAyah) {
            $result['status']   = false;
            $result['messages'] = $errorNikAyah;

            return $result;
        }
        $errorNikIbu = self::nik_error($data['ibu_nik'], 'NIK Ibu');
        if ($errorNikIbu) {
            $result['status']   = false;
            $result['messages'] = $errorNikIbu;

            return $result;
        }

        //cek email duplikat
        if (isset($data['email'])) {
            $existingData = Penduduk::where('email', $data['email'])
                ->where('id', '!=', $id)
                ->exists();

            if ($existingData) {
                $result['status']   = false;
                $result['messages'] = "Email {$data['email']} sudah digunakan";

                return $result;
            }
        }

        //cek telegram duplikat
        if (isset($data['telegram'])) {
            $existingData = Penduduk::where('telegram', $data['telegram'])
                ->where('id', '!=', $id)
                ->exists();

            if ($existingData) {
                $result['status']   = false;
                $result['messages'] = "Email {$data['telegram']} sudah digunakan";

                return $result;
            }
        }

        // Cek duplikasi Tag ID Card
        if (isset($data['tag_id_card'])) {
            $existingData = Penduduk::where('tag_id_card', $data['tag_id_card'])
                ->where('id', '!=', $id)
                ->exists();

            if ($existingData) {
                $result['status']   = false;
                $result['messages'] = "Tag ID Card {$data['tag_id_card']} sudah digunakan";

                return $result;
            }
        }

        return $result;
    }

    public static function nik_error($nilai, string $judul)
    {
        if (empty($nilai)) {
            return false;
        }
        if (! ctype_digit($nilai)) {
            return $judul . ' hanya berisi angka';
        }
        if (strlen($nilai) == 16) {
            return false;
        }
        if ($nilai == '0') {
            return false;
        }

        return $judul . ' panjangnya harus 16 atau bernilai 0';
    }

    public static function baru($data)
    {
        $penduduk = self::create($data);

        if ($foto = upload_foto_penduduk(time() . '-' . $penduduk->id . '-' . random_int(10000, 999999))) {
                $penduduk->foto = $foto;
                $penduduk->save();
        }
        $maksud_tujuan = $data['maksud_tujuan_kedatangan'];
        unset($data['maksud_tujuan_kedatangan']);

        // Jenis peristiwa didapat dari form yang berbeda
        // Jika peristiwa lahir akan mengambil data dari field tanggal lahir
        $logPenduduk = [
            'id_pend'                  => $penduduk->id,
            'tgl_peristiwa'            => $data['tgl_peristiwa'] . ' 00:00:00',
            'kode_peristiwa'           => $data['jenis_peristiwa'],
            'tgl_lapor'                => $data['tgl_lapor'],
            'maksud_tujuan_kedatangan' => $maksud_tujuan,
        ];

        LogPenduduk::create($logPenduduk);

        return $penduduk;
    }

    public function ubah($data): void
    {
        // Reset data terkait kewarganegaarn dari WNA / Dua Kewarganegaraan menjadi WNI
        if ($data['warganegara_id'] == 1) {
            $data['negara_asal'] = null;
        }

        // Reset data terkait kepemilikan KTP dari Memiliki KTP-EL menjadi Belum Memiliki KTP-EL
        if ($data['ktp_el'] == 1) {
            $data['tempat_cetak_ktp']  = null;
            $data['tanggal_cetak_ktp'] = null;
        }
        $clusterLama = $this->id_cluster;
        $alamat      = $data['alamat'];
        if (($data['kk_level'] == SHDKEnum::KEPALA_KELUARGA) && $this->id_kk) {
            // Kalau ada penduduk lain yg juga Kepala Keluarga, ubah menjadi hubungan Lainnya
            $lvl['kk_level']   = SHDKEnum::LAINNYA;
            $lvl['updated_at'] = Carbon::now();
            $lvl['updated_by'] = ci_auth()->id;
            Penduduk::where('id_kk', $this->id_kk)->where('id', '!=', $this->id)
                ->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                ->update($lvl);
            Keluarga::where('id', $this->id_kk)->update(['nik_kepala' => $this->id]);
        }

        // Untuk anggota keluarga
        if ($this->id_kk) {
            // Ganti alamat KK
            $keluarga = Keluarga::find($this->id_kk);
            $keluarga->update(['alamat' => $alamat]);
            if ($clusterLama != $data['id_cluster']) {
                $keluarga->pindah($data['id_cluster']);
            }
        }

        if ($foto = upload_foto_penduduk(time() . '-' . $this->id . '-' . random_int(10000, 999999))) {
            $data['foto'] = $foto;
        } else {
            unset($data['foto']);
        }

        unset($data['no_kk'], $data['dusun'], $data['rw'], $data['file_foto'], $data['old_foto']);

        $tgl_lapor = rev_tgl($data['tgl_lapor']);
        if ($data['tgl_peristiwa']) {
            $tgl_peristiwa = rev_tgl($data['tgl_peristiwa']);
        }
        unset($data['tgl_lapor'], $data['tgl_peristiwa']);

        // Reset data terkait penduduk TIDAK TETAP saat status berubah menjadi TETAP
        $maksud_tujuan = $data['maksud_tujuan_kedatangan'];
        if ($data['status'] == 1) {
            $data['maksud_tujuan_kedatangan'] = null;
        }
        unset($data['maksud_tujuan_kedatangan']);
        $this->update($data);

        // Perbarui data log, mengecek status dasar dari penduduk, jika status dasar adalah hidup
        // maka akan menupdate data dengan kode_peristiwa 1/5
        $log = [
            'tgl_peristiwa'            => $tgl_peristiwa,
            'updated_at'               => date('Y-m-d H:i:s'),
            'updated_by'               => $this->session->user,
            'maksud_tujuan_kedatangan' => $maksud_tujuan,
        ];

        if ($data['tgl_lapor']) {
            $log['tgl_lapor'] = $tgl_lapor;
        }

        if ($data['tgl_peristiwa']) {
            if ($this->status_dasar == StatusDasarEnum::HIDUP) {
                LogPenduduk::where('id_pend', $this->id)->whereIn('kode_peristiwa', [LogPenduduk::BARU_LAHIR, LogPenduduk::BARU_PINDAH_MASUK])->update($log);
            } else {
                LogPenduduk::where('id_pend', $this->id)->whereIn('kode_peristiwa', $this->status_dasar)->update($log);
            }
        }
    }

    public function delete()
    {
        if ($this->foto) {
            // Hapus file foto penduduk yg di hapus di folder desa/upload/user_pict
            $file_foto = LOKASI_USER_PICT . $this->foto;
            if (is_file($file_foto)) {
                unlink($file_foto);
            }

            // Hapus file foto kecil penduduk yg di hapus di folder desa/upload/user_pict
            $file_foto_kecil = LOKASI_USER_PICT . 'kecil_' . $this->foto;
            if (is_file($file_foto_kecil)) {
                unlink($file_foto_kecil);
            }
        }
        $log = [
            'id_pend'    => $this->id,
            'nik'        => $this->nik,
            'foto'       => $this->foto,
            'deleted_by' => ci_auth()->id,
            'deleted_at' => date('Y-m-d H:i:s'),
        ];
        LogHapusPenduduk::create($log);
        // hapus bantuan penduduk tersebut
        $this->pesertaBantuan()->delete();

        return parent::delete();
    }

    public static function awalBulan($tahun, $bulan)
    {
        $akhirBulanKemarin = Carbon::createFromDate($tahun, $bulan)->subMonth()->endOfMonth()->format('Y-m-d');
        // penduduk yang masih hidup sampai dengan akhir bulan kemarin
        $listKodePeristiwa = array_diff(array_keys(LogPenduduk::kodePeristiwa()), [LogPenduduk::MATI, LogPenduduk::PINDAH_KELUAR, LogPenduduk::HILANG]);

        return Penduduk::select(['status', 'nama', 'nik', 'tanggallahir', 'tempatlahir', 'nama_ayah', 'nama_ibu', 'id_kk', 'kk_level', 'sex', 'warganegara_id'])->withOnly([])->whereHas('log', static function ($q) use ($akhirBulanKemarin, $listKodePeristiwa) {
            $q->peristiwaSampaiDengan($akhirBulanKemarin)->whereIn('kode_peristiwa', $listKodePeristiwa);
        });
        // ->whereStatus(StatusPendudukEnum::TETAP)->get();
    }

    public function getLokasiAttribute()
    {
        if ($this->rtm->nik_kepala != null) {
            $id = $this->rtm->nik_kepala;
        } elseif ($this->keluarga != '[]' && $this->keluarga != null) {
            $id = $this->keluarga->nik_kepala;
        } else {
            $id = $this->id;
        }

        return PendudukMap::find($id);
    }

    protected function scopeWajibKtp($query)
    {
        return $query->batasiUmur(date('d-m-Y'), ['satuan' => 'tahun', 'min' => 17, 'max' => 9999])->orwhereIn('status_kawin', [StatusKawinEnum::KAWIN, StatusKawinEnum::CERAIHIDUP, StatusKawinEnum::CERAIMATI]);
    }

    public static function get_alamat_wilayah($data)
    {
        $dusun          = (setting('sebutan_dusun') == '-') ? '' : ucwords(strtolower(setting('sebutan_dusun'))) . ' ' . ucwords(strtolower($data['dusun']));
        $alamat_wilayah = "{$data['alamat']} RT {$data['rt']} / RW {$data['rw']} " . $dusun;

        return trim($alamat_wilayah);
    }
}
