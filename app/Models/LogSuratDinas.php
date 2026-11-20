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

use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property int $id_format_surat
 * @property int $id_pamong
 * @property string|null $nama_pamong Nama pamong agar tidak berubah saat ada perubahan di master pamong
 * @property string|null $nama_jabatan
 * @property int|null $id_user
 * @property string $tanggal
 * @property string|null $bulan
 * @property string|null $tahun
 * @property string|null $no_surat
 * @property string|null $nama_surat
 * @property string|null $lampiran
 * @property string|null $keterangan
 * @property string|null $lokasi_arsip
 * @property int|null $urls_id
 * @property bool $status 0. Konsep, 1. Cetak
 * @property string|null $log_verifikasi
 * @property int|null $tte
 * @property int|null $verifikasi_operator
 * @property int|null $verifikasi_kades
 * @property int|null $verifikasi_sekdes
 * @property string|null $isi_surat
 * @property string|null $input
 * @property int|null $karakter 1:biasa, 2:terbatas, 3:rahasia
 * @property int|null $derajat 1:biasa, 2:segera, 3:sangat segera
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property string|null $deleted_at
 * @property-read string|null $file_surat
 * @property-read array|string $format_penomoran_surat
 * @property-read \App\Models\Pamong|null $pamong
 * @property-read \App\Models\Penduduk|null $penduduk
 * @property-read \App\Models\SuratDinas $suratDinas
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LogTolak> $tolak
 * @property-read int|null $tolak_count
 * @property-read \App\Models\Urls|null $urlId
 * @property-read \App\Models\User|null $user
 * @method static Builder<static>|LogSuratDinas arsip($isAdmin, array $listJabatan = [])
 * @method static Builder<static>|LogSuratDinas ditolak()
 * @method static Builder<static>|LogSuratDinas masuk($isAdmin, array $listJabatan = [])
 * @method static Builder<static>|LogSuratDinas newModelQuery()
 * @method static Builder<static>|LogSuratDinas newQuery()
 * @method static Builder<static>|LogSuratDinas pengguna()
 * @method static Builder<static>|LogSuratDinas query()
 * @method static Builder<static>|LogSuratDinas status(?mixed $value = 1)
 * @method static Builder<static>|LogSuratDinas whereBulan($value)
 * @method static Builder<static>|LogSuratDinas whereConfigId($value)
 * @method static Builder<static>|LogSuratDinas whereCreatedAt($value)
 * @method static Builder<static>|LogSuratDinas whereCreatedBy($value)
 * @method static Builder<static>|LogSuratDinas whereDeletedAt($value)
 * @method static Builder<static>|LogSuratDinas whereDerajat($value)
 * @method static Builder<static>|LogSuratDinas whereId($value)
 * @method static Builder<static>|LogSuratDinas whereIdFormatSurat($value)
 * @method static Builder<static>|LogSuratDinas whereIdPamong($value)
 * @method static Builder<static>|LogSuratDinas whereIdUser($value)
 * @method static Builder<static>|LogSuratDinas whereInput($value)
 * @method static Builder<static>|LogSuratDinas whereIsiSurat($value)
 * @method static Builder<static>|LogSuratDinas whereKarakter($value)
 * @method static Builder<static>|LogSuratDinas whereKeterangan($value)
 * @method static Builder<static>|LogSuratDinas whereLampiran($value)
 * @method static Builder<static>|LogSuratDinas whereLogVerifikasi($value)
 * @method static Builder<static>|LogSuratDinas whereLokasiArsip($value)
 * @method static Builder<static>|LogSuratDinas whereNamaJabatan($value)
 * @method static Builder<static>|LogSuratDinas whereNamaPamong($value)
 * @method static Builder<static>|LogSuratDinas whereNamaSurat($value)
 * @method static Builder<static>|LogSuratDinas whereNoSurat($value)
 * @method static Builder<static>|LogSuratDinas whereStatus($value)
 * @method static Builder<static>|LogSuratDinas whereTahun($value)
 * @method static Builder<static>|LogSuratDinas whereTanggal($value)
 * @method static Builder<static>|LogSuratDinas whereTte($value)
 * @method static Builder<static>|LogSuratDinas whereUpdatedAt($value)
 * @method static Builder<static>|LogSuratDinas whereUpdatedBy($value)
 * @method static Builder<static>|LogSuratDinas whereUrlsId($value)
 * @method static Builder<static>|LogSuratDinas whereVerifikasiKades($value)
 * @method static Builder<static>|LogSuratDinas whereVerifikasiOperator($value)
 * @method static Builder<static>|LogSuratDinas whereVerifikasiSekdes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSuratDinas withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class LogSuratDinas extends BaseModel
{
    use ConfigId;

    public const KONSEP  = 0;
    public const CETAK   = 1;
    public const TOLAK   = -1;
    public const PERIKSA = 0;
    public const TERIMA  = 1;

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_surat_dinas';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['suratDinas', 'penduduk', 'pamong', 'tolak'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Cari surat dengan nomor terakhir sesuai setting aplikasi
     *
     * @param		string 	nama tabel surat
     * @param mixed|null $url
     *
     * @return array surat terakhir
     */
    public static function suratTerakhir(mixed $type, $url = null)
    {
        $setting = setting('penomoran_surat_dinas');

        if ($setting == 3) {
            $last_sl = self::suratTerakhirType('log_surat', null, 1);
            $last_sm = self::suratTerakhirType('surat_masuk', null, 1);
            $last_sk = self::suratTerakhirType('surat_keluar', null, 1);

            $surat[$last_sl['no_surat']]   = $last_sl;
            $surat[$last_sm['nomor_urut']] = $last_sm;
            $surat[$last_sk['nomor_urut']] = $last_sk;
            krsort($surat);

            return current($surat);
        }

        return self::suratTerakhirType($type, $url);
    }

    public static function suratTerakhirType($type, $url = null, $setting = null)
    {
        $thn                 = date('Y');
        $setting || $setting = setting('penomoran_surat_dinas');

        switch ($type) {
            // no break
            case 'log_surat':
                if ($setting == 1) {
                    $surat = LogSuratDinas::whereNull('deleted_at')
                        ->where('no_surat', '!=', '')
                        ->whereYear('tanggal', $thn)
                        ->whereStatus(1)
                        ->orderBy(DB::raw('CAST(no_surat as unsigned)'), 'desc')
                        ->first();
                } elseif ($setting == 4) {
                    $surat = LogSuratDinas::whereNull('deleted_at')
                        ->where('no_surat', '!=', '')
                        ->whereYear('tanggal', $thn)
                        ->rightJoin('surat_dinas', 'surat_dinas.id', '=', 'log_surat_dinas.id_format_surat')
                        ->where('kode_surat', static function ($q) use ($url): void {
                            $q->select('kode_surat')
                                ->from('surat_dinas')
                                ->where('url_surat', $url);
                        })
                        ->orderBy(DB::raw('CAST(no_surat as unsigned)'), 'desc')
                        ->first();
                } else {
                    $surat = LogSuratDinas::whereNull('deleted_at')
                        ->where('no_surat', '!=', '')
                        ->whereYear('tanggal', $thn)
                        ->rightJoin('surat_dinas', 'surat_dinas.id', '=', 'log_surat_dinas.id_format_surat')
                        ->where(static fn ($q) => $q->where('url_surat', $url))
                        ->orderBy(DB::raw('CAST(no_surat as unsigned)'), 'desc')
                        ->first();
                }
                break;

            case 'surat_masuk':
                $surat = SuratMasuk::whereYear('tanggal_surat', $thn)
                    ->orderBy(DB::raw('CAST(nomor_urut as unsigned)'), 'desc')
                    ->first();
                break;

            case 'surat_keluar':
                $surat = SuratKeluar::whereYear('tanggal_surat', $thn)
                    ->orderBy(DB::raw('CAST(nomor_urut as unsigned)'), 'desc')
                    ->first();
        }
        $surat                                             = $surat ? $surat->toArray() : ['no_surat' => 0];
        $surat['nomor_urut']    || $surat['nomor_urut']    = $surat['no_surat'];
        $surat['no_surat']      || $surat['no_surat']      = $surat['nomor_urut'];
        $surat['tanggal_surat'] || $surat['tanggal_surat'] = $surat['tanggal'];
        $surat['tanggal']       || $surat['tanggal']       = $surat['tanggal_surat'];
        $surat['tanggal']                                  = tgl_indo2($surat['tanggal']);

        return $surat;
    }

    public static function lastNomerSurat($url)
    {
        $settingNomer = setting('penomoran_surat_dinas');
        $data         = self::suratTerakhir('log_surat', $url);
        if ($settingNomer == 2 && empty($data['nama'])) {
            $surat        = SuratDinas::find($url);
            $data['nama'] = $surat['nama'];
        } elseif ($settingNomer == 4) {
            $data['kode_surat'] = SuratDinas::where('url_surat', $url)->first()->kode_surat;
        }

        $no_surat = $data['no_surat'] + 1;

        $ket = [
            1 => 'Terakhir untuk semua surat layanan: ',
            2 => "Terakhir untuk jenis surat {$data['nama']}: ",
            3 => 'Terakhir untuk semua surat layanan, keluar dan masuk: ',
            4 => "Terakhir untuk klasifikasi surat: {$data['kode_surat']}: ",
        ];

        $data['no_surat_berikutnya'] = $no_surat;
        $data['no_surat_berikutnya'] = str_pad((string) $data['no_surat_berikutnya'], (int) setting('panjang_nomor_surat_dinas'), '0', STR_PAD_LEFT);
        $data['ket_nomor']           = $ket[$settingNomer];

        return $data;
    }

    public static function ambilNomorBerikutnya($url, $nomorAwal = null, $maxRetry = 10)
    {
        if ($nomorAwal === null) {
            $data = self::lastNomerSurat($url);
            $nomor = (int)$data['no_surat_berikutnya'];
        } else {
            $nomor = (int)$nomorAwal;
        }

        $panjang = (int) setting('panjang_nomor_surat_dinas');
        for ($i = 0; $i < $maxRetry; $i++) {
            $formatted = str_pad((string) $nomor, $panjang, '0', STR_PAD_LEFT);
            if (!self::isDuplikat($formatted, $url)) {
                return $formatted;
            }
            $nomor++;
        }

        return null;
    }

    public static function boot(): void
    {
        parent::boot();

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'nama_surat', true);
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $surat = LOKASI_ARSIP . $model->getOriginal($file);
            if (file_exists($surat)) {
                unlink($surat);
            }
        }
    }

    public static function isDuplikat($nomor_surat, $url = null)
    {
        $thn     = date('Y');
        $setting = setting('penomoran_surat_dinas');
        if ($setting == 3) {
            // Nomor urut gabungan surat layanan, surat masuk dan surat keluar
            $suratMasuk    = SuratMasuk::where(['nomor_urut' => $nomor_surat])->whereYear('tanggal_surat', $thn)->exists();
            $suratKeluar   = SuratKeluar::where(['nomor_urut' => $nomor_surat])->whereYear('tanggal_surat', $thn)->exists();
            $logSuratDinas = self::whereNull('deleted_at')->where(['no_surat' => $nomor_surat])->whereYear('tanggal', $thn)->exists();

            $result = $logSuratDinas || $suratMasuk || $suratKeluar;
        } elseif ($setting == 1) {
            $result = self::whereNull('deleted_at')->where(['no_surat' => $nomor_surat])->whereYear('tanggal', $thn)->exists();
        } elseif ($setting == 4) {
            $kode_surat = SuratDinas::where('url_surat', $url)->first()->kode_surat;
            $result     = self::whereNull('deleted_at')
                ->whereYear('tanggal', $thn)
                ->whereNoSurat($nomor_surat)
                ->rightJoin('surat_dinas', 'surat_dinas.id', '=', 'log_surat_dinas.id_format_surat')
                ->where(static fn ($q) => $q->where('kode_surat', $kode_surat))
                ->exists();
        } else {
            $result = self::whereHas('suratDinas', static fn ($q) => $q->where(['url_surat' => $url]))->whereNull('deleted_at')->where(['no_surat' => $nomor_surat])->whereYear('tanggal', $thn)->exists();
        }

        return $result;
    }

    /**
     * Generate or retrieve QR code for Surat Dinas using urlPendekDinas
     *
     * @param string      $namaSurat Nama file surat
     * @param string|null $logo      Path to logo
     *
     * @return array Array dengan keys: viewqr (file path), urls_id (Urls record ID), isiqr (short URL)
     */
    public static function buatQrCode($namaSurat, ?string $logo): array
    {
        // Ambil data surat dinas
        $log_surat = self::select(['id', 'urls_id'])
            ->where('nama_surat', $namaSurat)
            ->first();

        // Pastikan hasil selalu array (meskipun tidak ada data)
        if ($log_surat) {
            // Gunakan urlPendekDinas untuk Surat Dinas (bukan urlPendek untuk surat biasa)
            $urls = Urls::urlPendekDinas($log_surat->toArray());

            $qrCode = [
                'isiqr'   => $urls['isiqr'],
                'urls_id' => $urls['urls_id'],
                'logoqr'  => gambar_desa($logo, false, true),
                'sizeqr'  => 6,
                'foreqr'  => '#000000',
            ];

            $qrCode['viewqr'] = qrcode_generate($qrCode);
        } else {
            $qrCode['viewqr'] = null;
        }

        return $qrCode;
    }

    public function suratDinas()
    {
        return $this->belongsTo(SuratDinas::class, 'id_format_surat');
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pend');
    }

    public function pamong()
    {
        return $this->belongsTo(Pamong::class, 'id_pamong');
    }

    /**
     * Get the urlId associated with the LogSuratDinas
     */
    public function urlId(): HasOne
    {
        return $this->hasOne(Urls::class, 'id', 'urls_id');
    }

    public function tolak()
    {
        return $this->hasMany(LogTolak::class, 'id_surat_dinas');
    }

    /**
     * Get the user that owns the LogSurat
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Scope query untuk pengguna.
     *
     * @param Builder $query
     */
    public function scopePengguna($query): void
    {
        // return $query->where('id_pend', auth('jwt')->user()->penduduk->id);
    }

    /**
     * Scope query untuk Status LogSurat
     *
     * @return Builder
     */
    public function scopeStatus(mixed $query, mixed $value = 1)
    {
        return $query->where('status', $value);
    }

    public function getFormatPenomoranSuratAttribute(): array|string
    {
        $thn                = $this->tahun ?? date('Y');
        $bln                = $this->bulan ?? date('m');
        $format_nomor_surat = format_penomoran_surat($this->suratDinas->format_nomor_global, setting('format_nomor_surat'), $this->suratDinas->format_nomor);

        // $format_nomor_surat = str_replace('[nomor_surat]', "{$this->no_surat}", $format_nomor_surat);
        $format_nomor_surat = substitusiNomorSurat($this->no_surat, $format_nomor_surat);
        $array_replace      = [
            '[kode_surat]'   => $this->suratDinas->kode_surat,
            '[tahun]'        => $thn,
            '[bulan_romawi]' => bulan_romawi((int) $bln),
            '[kode_desa]'    => identitas()->kode_desa,
        ];

        return str_ireplace(array_keys($array_replace), array_values($array_replace), $format_nomor_surat);
    }

    public function getFileSuratAttribute(): ?string
    {
        if ($this->lampiran != null) {
            return FCPATH . LOKASI_ARSIP . pathinfo($this->nama_surat, PATHINFO_FILENAME);
        }

        return null;
    }

    public function statusPeriksa($jabatanId, $idJabatanKades, $idJabatanSekdes): int
    {
        $statusPeriksa = 0;
        if ($jabatanId == $idJabatanKades && setting('verifikasi_kades') == 1) {
            if ($this->verifikasi_kades == 1) {
                $statusPeriksa = null === $this->tte ? $this->verifikasi_kades : 2;
            }
        } elseif ($jabatanId == $idJabatanSekdes && setting('verifikasi_sekdes') == 1) {
            if ($this->verifikasi_sekdes == 1) {
                if (null === $this->tte) {
                    $statusPeriksa = $this->verifikasi_kades == null ? 1 : $this->verifikasi_kades;
                } else {
                    $statusPeriksa = $this->tte;
                }
            }
        } elseif ($this->verifikasi_operator == 1) {
            // $statusPeriksa = $this->tte == null ? $this->verifikasi_kades ?? $this->verifikasi_sekdes ?? 1 : $this->tte
            if (null === $this->tte) {
                if ($this->verifikasi_kades == null) {
                    $statusPeriksa = $this->verifikasi_sekdes == null ? 1 : $this->verifikasi_sekdes;
                } else {
                    $statusPeriksa = $this->verifikasi_kades;
                }
            } else {
                $statusPeriksa = $this->tte;
            }
        }

        return $statusPeriksa;
    }

    public function pdfFile(): string
    {
        $nama_surat = pathinfo($this->nama_surat, PATHINFO_FILENAME);

        if ($nama_surat !== '' && $nama_surat !== '0') {
            $berkas_pdf = $nama_surat . '.pdf';
        } else {
            $berkas_pdf = $this->suratDinas->url_surat . '_' . $this->penduduk->nik . '_' . date('Y-m-d') . '.pdf';
        }

        return LOKASI_ARSIP . $berkas_pdf;
    }

    public function lampiranFile(): string
    {
        $nama_surat = pathinfo($this->nama_surat, PATHINFO_FILENAME);

        if ($nama_surat !== '' && $nama_surat !== '0') {
            $berkas_lampiran = $nama_surat . '_lampiran.pdf';
        } else {
            $berkas_lampiran = $this->suratDinas->url_surat . '_' . $this->penduduk->nik . '_' . date('Y-m-d') . '._lampiran.pdf';
        }

        return LOKASI_ARSIP . $berkas_lampiran;
    }

    public function scopeMasuk($query, $isAdmin, array $listJabatan = [])
    {
        $jabatanId       = $listJabatan['jabatan_id'];
        $jabatanKadesId  = $listJabatan['jabatan_kades_id'];
        $jabatanSekdesId = $listJabatan['jabatan_sekdes_id'];

        return $query->when($jabatanId == $jabatanKadesId, static fn ($q) => $q->when(setting('tte') == 1, static fn ($tte) => $tte->where(static fn ($r) => $r->where('verifikasi_kades', '=', 0)->orWhere('tte', '=', 0)))
            ->when(setting('tte') == 0, static fn ($tte) => $tte->where('verifikasi_kades', '=', '0')))
            ->when($jabatanId == $jabatanSekdesId, static fn ($q) => $q->where('verifikasi_sekdes', '=', '0'))
            ->when($isAdmin == null || ! in_array($jabatanId, [$jabatanKadesId, $jabatanSekdesId]), static fn ($q) => $q->where('verifikasi_operator', '=', '0'));
    }

    public function scopeArsip($query, $isAdmin, array $listJabatan = [])
    {
        $jabatanId       = $listJabatan['jabatan_id'];
        $jabatanKadesId  = $listJabatan['jabatan_kades_id'];
        $jabatanSekdesId = $listJabatan['jabatan_sekdes_id'];

        return $query->when($jabatanId == $jabatanKadesId, static fn ($q) => $q->when(setting('tte') == 1, static fn ($tte) => $tte->where('verifikasi_kades', '=', '1'))
            ->when(setting('tte') == 0, static fn ($tte) => $tte->where('verifikasi_kades', '=', '1'))
            ->orWhere(static function ($verifikasi): void {
                $verifikasi->whereNull('verifikasi_operator');
            }))
            ->when($jabatanId == $jabatanSekdesId, static fn ($q) => $q->where('verifikasi_sekdes', '=', '1')->orWhereNull('verifikasi_operator'))
            ->when($isAdmin == null || ! in_array($jabatanId, [$jabatanKadesId, $jabatanSekdesId]), static fn ($q) => $q->where('verifikasi_operator', '=', '1')->orWhereNull('verifikasi_operator'));
    }

    public function scopeDitolak($query)
    {
        return $query->where('verifikasi_operator', '=', '-1');
    }

    public function setKeteranganAttribute(): void
    {
        $this->attributes['keterangan'] = null;
    }
}
