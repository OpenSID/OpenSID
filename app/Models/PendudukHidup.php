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

/**
 * @property int $id
 * @property int $config_id
 * @property string $nama
 * @property string $nik
 * @property int|null $id_kk
 * @property int $kk_level
 * @property string|null $id_rtm
 * @property int|null $rtm_level
 * @property int $sex
 * @property string $tempatlahir
 * @property \Illuminate\Support\Carbon $tanggallahir
 * @property int $agama_id
 * @property int $pendidikan_kk_id
 * @property int|null $pendidikan_sedang_id
 * @property int $pekerjaan_id
 * @property int $status_kawin
 * @property int $warganegara_id
 * @property string $dokumen_pasport
 * @property string $dokumen_kitas
 * @property string|null $ayah_nik
 * @property string|null $ibu_nik
 * @property string $nama_ayah
 * @property string $nama_ibu
 * @property string|null $foto
 * @property string $golongan_darah_id
 * @property int|null $id_cluster
 * @property int|null $status
 * @property string|null $alamat_sebelumnya
 * @property string|null $alamat_sekarang
 * @property int $status_dasar
 * @property int|null $hamil
 * @property int|null $cacat_id
 * @property int|null $sakit_menahun_id
 * @property string|null $akta_lahir
 * @property string|null $akta_perkawinan
 * @property string|null $tanggalperkawinan
 * @property string|null $akta_perceraian
 * @property string|null $tanggalperceraian
 * @property int|null $cara_kb_id
 * @property string|null $telepon
 * @property string|null $tanggal_akhir_paspor
 * @property string|null $no_kk_sebelumnya
 * @property int|null $ktp_el
 * @property int|null $status_rekam
 * @property string|null $waktu_lahir
 * @property int|null $tempat_dilahirkan
 * @property int|null $jenis_kelahiran
 * @property int|null $kelahiran_anak_ke
 * @property int|null $penolong_kelahiran
 * @property int|null $berat_lahir
 * @property string|null $panjang_lahir
 * @property string|null $tag_id_card
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property int|null $id_asuransi
 * @property string|null $no_asuransi
 * @property string|null $email
 * @property string|null $email_token
 * @property string|null $email_tgl_kadaluarsa
 * @property string|null $email_tgl_verifikasi
 * @property string|null $telegram
 * @property string|null $telegram_token
 * @property string|null $telegram_tgl_kadaluarsa
 * @property string|null $telegram_tgl_verifikasi
 * @property int|null $bahasa_id
 * @property string|null $ket
 * @property string|null $negara_asal
 * @property string|null $tempat_cetak_ktp
 * @property string|null $tanggal_cetak_ktp
 * @property string|null $pekerja_migran
 * @property string|null $suku
 * @property string|null $marga
 * @property string|null $adat
 * @property string|null $bpjs_ketenagakerjaan
 * @property string|null $hubung_warga
 * @property-read \App\Models\Config|null $config
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DokumenHidup> $dokumenHidup
 * @property-read int|null $dokumen_hidup_count
 * @property-read string $bahasa
 * @property-read string|null $el_k_t_p
 * @property-read string $jenis_kelamin
 * @property-read string $nama_asuransi
 * @property-read string|null $nama_jenis_kelahiran
 * @property-read string|null $nama_penolong_kelahiran
 * @property-read string|null $nama_tempat_dilahirkan
 * @property-read string $pekerjaan
 * @property-read string $penduduk_status_dasar
 * @property-read mixed $sakit_menahun
 * @property-read string $status_hamil
 * @property-read string $status_perkawinan
 * @property-read mixed $status_rekam_ktp
 * @property-read string|null $tanggal_lahir_id
 * @property-read string|null $umur
 * @property-read string $wajib_k_t_p
 * @property-read \App\Models\Keluarga|null $keluarga
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LogPenduduk> $logPenduduk
 * @property-read int|null $log_penduduk_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LogPenduduk> $logPendudukAsli
 * @property-read int|null $log_penduduk_asli_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LogPerubahanPenduduk> $logPerubahanPenduduk
 * @property-read int|null $log_perubahan_penduduk_count
 * @property-read \App\Models\PendudukMandiri|null $mandiri
 * @property-read \App\Models\PendudukMap|null $map
 * @property-read \App\Models\Rtm|null $rtm
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup countStatistik()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup countStatistikSuku()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup jenisKelamin(?mixed $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup lepas($shdk = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup lepasSemua()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup status(?mixed $value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereAdat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereAgamaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereAktaLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereAktaPerceraian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereAktaPerkawinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereAlamatSebelumnya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereAlamatSekarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereAyahNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereBahasaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereBeratLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereBpjsKetenagakerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereCacatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereCaraKbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereDokumenKitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereDokumenPasport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereEmailTglKadaluarsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereEmailTglVerifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereEmailToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereGolonganDarahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereHamil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereHubungWarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereIbuNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereIdAsuransi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereIdCluster($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereIdKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereIdRtm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereJenisKelahiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereKelahiranAnakKe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereKet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereKkLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereKtpEl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereMarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereNamaAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereNamaIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereNegaraAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereNoAsuransi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereNoKkSebelumnya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup wherePanjangLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup wherePekerjaMigran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup wherePekerjaanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup wherePendidikanKkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup wherePendidikanSedangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup wherePenolongKelahiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereRtmLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereSakitMenahunId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereStatusDasar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereStatusKawin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereStatusRekam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereSuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTagIdCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTanggalAkhirPaspor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTanggalCetakKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTanggallahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTanggalperceraian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTanggalperkawinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTelegram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTelegramTglKadaluarsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTelegramTglVerifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTelegramToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTempatCetakKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTempatDilahirkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereTempatlahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereWaktuLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup whereWarganegaraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup withRef()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukHidup withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
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

    protected function scopeLepasSemua($query)
    {
        return $query->whereNull('id_kk')
            ->where('status', 1);
    }
}
