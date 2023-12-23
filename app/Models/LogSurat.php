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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

defined('BASEPATH') || exit('No direct script access allowed');

class LogSurat extends BaseModel
{
    use ConfigId;

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
        return $this->belongsTo(FormatSurat::class, 'id_format_surat');
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
        $format_nomor_surat = ($this->formatSurat->format_nomor == '') ? setting('format_nomor_surat') : $this->formatSurat->format_nomor;

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
                if ($this->tte == null) {
                    $statusPeriksa = $this->verifikasi_kades;
                } else {
                    $statusPeriksa = 2;
                }
            }
        } elseif ($jabatanId == $idJabatanSekdes && setting('verifikasi_sekdes') == 1) {
            if ($this->verifikasi_sekdes == 1) {
                if ($this->tte == null) {
                    if ($this->verifikasi_kades == null) {
                        $statusPeriksa = 1;
                    } else {
                        $statusPeriksa = $this->verifikasi_kades;
                    }
                } else {
                    $statusPeriksa = $this->tte;
                }
            }
        } else {
            if ($this->verifikasi_operator == 1) {
                if ($this->tte == null) {
                    if ($this->verifikasi_kades === null) {
                        if ($this->verifikasi_sekdes === null) {
                            $statusPeriksa = 1;
                        } else {
                            $statusPeriksa = $this->verifikasi_sekdes;
                        }
                    } else {
                        $statusPeriksa = $this->verifikasi_kades;
                    }
                } else {
                    $statusPeriksa = $this->tte;
                }
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

    public function scopeMasuk($query, $isAdmin, $listJabatan = [])
    {
        $jabatanId       = $listJabatan['jabatan_id'];
        $jabatanKadesId  = $listJabatan['jabatan_kades_id'];
        $jabatanSekdesId = $listJabatan['jabatan_sekdes_id'];

        return $query->when($jabatanId == $jabatanKadesId, static fn ($q) => $q->when(setting('tte') == 1, static fn ($tte) => $tte->where(static fn ($r) => $r->where('verifikasi_kades', '=', 0)->orWhere('tte', '=', 0)))
            ->when(setting('tte') == 0, static fn ($tte) => $tte->where('verifikasi_kades', '=', '0')))
            ->when($jabatanId == $jabatanSekdesId, static fn ($q) => $q->where('verifikasi_sekdes', '=', '0'))
            ->when($isAdmin == null || ! in_array($jabatanId, [$jabatanKadesId, $jabatanSekdesId]), static fn ($q) => $q->where('verifikasi_operator', '=', '0'));
    }

    public function scopeArsip($query, $isAdmin, $listJabatan = [])
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
}
