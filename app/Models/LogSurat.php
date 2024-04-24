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
use App\Traits\ShortcutCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class LogSurat extends BaseModel
{
    use ConfigId;
    use ShortcutCache;

    public const KONSEP  = 0;
    public const CETAK   = 1;
    public const TOLAK   = -1;
    public const PERIKSA = 0;
    public const TERIMA  = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_surat';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

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
    protected $with = ['formatSurat', 'penduduk', 'pamong', 'tolak'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    public function formatSurat()
    {
        return $this->belongsTo(FormatSurat::class, 'id_format_surat')->withoutGlobalScope(\App\Scopes\RemoveRtfScope::class);
    }

    public function formatSuratArsip()
    {
        return $this->formatSurat();
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pend');
    }

    public function pamong()
    {
        return $this->belongsTo(Pamong::class, 'id_pamong');
    }

    public function tolak()
    {
        return $this->hasMany(LogTolak::class, 'id_surat');
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
     * @param mixed $query
     * @param mixed $value
     *
     * @return Builder
     */
    public function scopeStatus($query, $value = 1)
    {
        return $query->where('status', $value);
    }

    public function getFormatPenomoranSuratAttribute()
    {
        $thn                = $this->tahun ?? date('Y');
        $bln                = $this->bulan ?? date('m');
        $format_nomor_surat = ($this->formatSurat->format_nomor_global) ? setting('format_nomor_surat') : $this->formatSurat->format_nomor;

        // $format_nomor_surat = str_replace('[nomor_surat]', "{$this->no_surat}", $format_nomor_surat);
        $format_nomor_surat = substitusiNomorSurat($this->no_surat, $format_nomor_surat);
        $array_replace      = [
            '[kode_surat]'   => $this->formatSurat->kode_surat,
            '[tahun]'        => $thn,
            '[bulan_romawi]' => bulan_romawi((int) $bln),
            '[kode_desa]'    => identitas()->kode_desa,
        ];

        return str_replace(array_keys($array_replace), array_values($array_replace), $format_nomor_surat);
    }

    public function getFileSuratAttribute()
    {
        if ($this->lampiran != null) {
            return FCPATH . LOKASI_ARSIP . pathinfo($this->nama_surat, PATHINFO_FILENAME);
        }
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

    public function rtfFile(): string
    {
        $nama_surat = pathinfo($this->nama_surat, PATHINFO_FILENAME);

        if ($nama_surat !== '' && $nama_surat !== '0') {
            $berkas_rtf = $nama_surat . '.rtf';
        } else {
            $berkas_rtf = $this->formatSurat->url_surat . '_' . $this->penduduk->nik . '_' . date('Y-m-d') . '.rtf';
        }

        return LOKASI_ARSIP . $berkas_rtf;
    }

    public function pdfFile(): string
    {
        $nama_surat = pathinfo($this->nama_surat, PATHINFO_FILENAME);

        if ($nama_surat !== '' && $nama_surat !== '0') {
            $berkas_pdf = $nama_surat . '.pdf';
        } else {
            $berkas_pdf = $this->formatSurat->url_surat . '_' . $this->penduduk->nik . '_' . date('Y-m-d') . '.pdf';
        }

        return LOKASI_ARSIP . $berkas_pdf;
    }

    public function lampiranFile(): string
    {
        $nama_surat = pathinfo($this->nama_surat, PATHINFO_FILENAME);

        if ($nama_surat !== '' && $nama_surat !== '0') {
            $berkas_lampiran = $nama_surat . '_lampiran.pdf';
        } else {
            $berkas_lampiran = $this->formatSurat->url_surat . '_' . $this->penduduk->nik . '_' . date('Y-m-d') . '._lampiran.pdf';
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

    /**
     * Cari surat dengan nomor terakhir sesuai setting aplikasi
     *
     * @param		string 	nama tabel surat
     * @param mixed      $type
     * @param mixed|null $url
     *
     * @return array surat terakhir
     */
    public static function suratTerakhir($type, $url = null)
    {
        $setting = setting('penomoran_surat');

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
        $setting || $setting = setting('penomoran_surat');

        switch ($type) {
                // no break
            case 'log_surat':
                if ($setting == 1) {
                    $surat = LogSurat::whereNull('deleted_at')
                        ->whereYear('tanggal', $thn)
                        ->whereStatus(1)
                        ->orderBy(DB::raw('CAST(no_surat as unsigned)'), 'desc')
                        ->first();
                } else {
                    $surat = LogSurat::whereNull('deleted_at')
                        ->whereYear('tanggal', $thn)
                        ->rightJoin('tweb_surat_format', 'tweb_surat_format.id', '=', 'log_surat.id_format_surat')
                        ->where(static fn ($q) => $q->where('url_surat', $url)->orWhereRaw("url_surat = REPLACE(REPLACE('{$url}', 'erangan', ''), '-', '_')"))
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
        $settingNomer = setting('penomoran_surat');
        $data         = self::suratTerakhir('log_surat', $url);
        if ($settingNomer == 2 && empty($data['nama'])) {
            $surat        = FormatSurat::find($url);
            $data['nama'] = $surat['nama'];
        }
        $ket = [
            1 => 'Terakhir untuk semua surat layanan: ',
            2 => "Terakhir untuk jenis surat {$data['nama']}: ",
            3 => 'Terakhir untuk semua surat layanan, keluar dan masuk: ',
        ];
        $data['no_surat_berikutnya'] = $data['no_surat'] + 1;
        $data['no_surat_berikutnya'] = str_pad((string) $data['no_surat_berikutnya'], (int) setting('panjang_nomor_surat'), '0', STR_PAD_LEFT);
        $data['ket_nomor']           = $ket[$settingNomer];

        return $data;
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
}
