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

use App\Enums\BahasaEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\PekerjaanEnum;
use App\Enums\SakitMenahunEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\StatusKTPEnum;
use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Wilayah;

defined('BASEPATH') || exit('No direct script access allowed');

class PendudukHidup extends BaseModel
{
    use ConfigId;

    /**
     * {@inheritDoc}
     */
    public $incrementing = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'penduduk_hidup';

    /**
     * {@inheritDoc}
     */
    protected $appends = [
        'namaTempatDilahirkan',
        'namaJenisKelahiran',
        'namaPenolongKelahiran',
        'wajibKTP',
        'elKTP',
        'statusPerkawinan',
        'statusHamil',
        'namaAsuransi',
        'umur',
        'tanggalLahirId',
        'sakit_menahun',
        'status_rekam_ktp',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'tanggallahir'      => 'datetime:d-m-Y',
        'tanggal_peristiwa' => 'datetime:d-m-Y',
        'created_at'        => 'datetime:d-m-Y',
    ];

    /**
     * Get the mandiri associated with the PendudukHidup
     */
    public function mandiri(): HasOne
    {
        return $this->hasOne(PendudukMandiri::class, 'id_pend', 'id');
    }

    public function map()
    {
        return $this->belongsTo(PendudukMap::class, 'id', 'id');
    }

    /**
     * Get the phone associated with the config.
     */
    public function config()
    {
        return $this->hasOne(Config::class, 'id', 'config_id');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'id_kk')->withDefault();
    }

    public function rtm()
    {
        return $this->belongsTo(Rtm::class, 'id_rtm', 'no_kk');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function clusterDesa()
    {
        return $this->belongsTo(Wilayah::class, 'id_cluster')->withDefault();
    }

    public function logPenduduk()
    {
        return $this->hasMany(LogPenduduk::class, 'id_pend')->selectRaw('max(id) as id');
    }

    public function logPendudukAsli()
    {
        return $this->hasMany(LogPenduduk::class, 'id_pend');
    }

    public function logPerubahanPenduduk()
    {
        return $this->hasMany(LogPerubahanPenduduk::class, 'id_pend');
    }

    public function dokumenHidup()
    {
        return $this->hasMany(DokumenHidup::class, 'id_pend');
    }

    /**
     * Getter tempat dilahirkan attribute.
     */
    public function getNamaTempatDilahirkanAttribute(): ?string
    {
        return match ($this->tempat_dilahirkan) {
            1       => 'RS/RB',
            2       => 'Puskesmas',
            3       => 'Polindes',
            4       => 'Rumah',
            5       => 'Lainnya',
            default => null
        };
    }

    /**
     * Getter tempat dilahirkan attribute.
     */
    public function getNamaJenisKelahiranAttribute(): ?string
    {
        return match ($this->jenis_kelahiran) {
            1       => 'Tunggal',
            2       => 'Kembar 2',
            3       => 'Kembar 3',
            4       => 'Kembar 4',
            default => null,
        };
    }

    public function getSakitMenahunAttribute()
    {
        return SakitMenahunEnum::valueOf($this->sakit_menahun_id);
    }

    /**
     * Getter tempat dilahirkan attribute.
     */
    public function getNamaPenolongKelahiranAttribute(): ?string
    {
        return match ($this->penolong_kelahiran) {
            1       => 'Dokter',
            2       => 'Bidan Perawat',
            3       => 'Dukun',
            4       => 'Lainnya',
            default => null,
        };
    }

    public function getElKTPAttribute(): ?string
    {
        return match ($this->ktp_el) {
            1       => 'BELUM',
            2       => 'KTP-EL',
            3       => 'KIA',
            default => null,
        };
    }

    /**
     * Getter wajib ktp attribute.
     */
    public function getWajibKTPAttribute(): string
    {
        return (($this->tanggallahir && $this->tanggallahir->age > 16) || (! empty($this->status_kawin) && $this->status_kawin != 1))
            ? 'WAJIB KTP'
            : 'BELUM';
    }

    /**
     * Getter status perkawinan attribute.
     *
     * @return string
     */
    public function getStatusPerkawinanAttribute()
    {
        return ! empty($this->status_kawin) && $this->status_kawin != StatusKawinEnum::KAWIN
            ? StatusKawinEnum::valueToUpper($this->status_kawin)
            : (
                empty($this->akta_perkawinan) && empty($this->tanggalperkawinan)
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
        return ! empty($this->id_asuransi) && $this->id_asuransi != 1
            ? (($this->id_asuransi == 99)
                ? "Nama/No Asuransi : {$this->no_asuransi}"
                : "No Asuransi : {$this->no_asuransi}")
            : '';
    }

    /**
     * Getter umur attribute.
     *
     * @return string|null
     */
    public function getUmurAttribute()
    {
        return $this->tanggallahir?->age;
    }

    /**
     * Getter tanggal lahir indonesia attribute.
     *
     * @return string|null
     */
    public function getTanggalLahirIdAttribute()
    {
        return $this->tanggallahir?->format('d F Y');
    }

    public function getPekerjaanAttribute(): string
    {
        return PekerjaanEnum::valueOf($this->pekerjaan_id) ?: '';
    }

    public function getStatusRekamKtpAttribute()
    {
        return StatusKTPEnum::valueOf($this->status_rekam) ?: '';
    }

    public function getJenisKelaminAttribute(): string
    {
        return JenisKelaminEnum::valueOf($this->sex) ?: '';
    }

    public function getBahasaAttribute(): string
    {
        return BahasaEnum::valueOf($this->bahasa_id) ?: '';
    }

    public function getPendudukStatusDasarAttribute(): string
    {
        return StatusDasarEnum::valueOf($this->status_dasar) ?: '';
    }

    /**
     * Scope query untuk status penduduk.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeStatus($query, mixed $value = 1)
    {
        return $query->where('status_dasar', $value);
    }

    /**
     * Scope query untuk jenis kelamin penduduk.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeJenisKelamin($query, mixed $value = null)
    {
        if (null === $value) {
            return $query;
        }

        return $query->where('sex', $value);
    }

    /**
     * Scope untuk Statistik.
     */
    public function scopeCountStatistik(mixed $query)
    {
        $this->appends = [];
        $this->with    = [];

        return $this->scopeConfigId($query)
            ->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 1 THEN tweb_penduduk.id END) AS laki_laki')
            ->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 2 THEN tweb_penduduk.id END) AS perempuan')
            ->where('tweb_penduduk.status_dasar', 1);
    }

    public function scopeCountStatistikSuku($query)
    {
        return $this->scopeConfigId($query)
            ->select(['suku AS id', 'suku AS nama'])
            ->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 1 THEN tweb_penduduk.id END) AS laki_laki')
            ->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 2 THEN tweb_penduduk.id END) AS perempuan')
            ->where('tweb_penduduk.status_dasar', 1)
            ->groupBy('suku')
            ->whereNotNull('suku')
            ->where('suku', '!=', '');
    }

    /**
     * Scope untuk memanggil relasi tabel referensi.
     */
    public function scopeWithRef(mixed $query)
    {
        return $query->with([
            'config',
            'keluarga',
            'rtm',
            'clusterDesa',
            'logPenduduk',
            'logPerubahanPenduduk',
        ]);
    }

    protected function scopeLepas($query, $shdk = false)
    {
        $query->whereNull('id_kk')->where('status', 1);

        if ($shdk) {
            $query->where(static fn ($q) => $q->where('kk_level', '!=', SHDKEnum::KEPALA_KELUARGA)->orWhereNull('kk_level'));
        } else {
            $query->where(static fn ($q) => $q->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->orWhereNull('kk_level'));
        }

        return $query;
    }
}
