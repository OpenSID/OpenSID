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

use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

defined('BASEPATH') || exit('No direct script access allowed');

class PermohonanSurat extends BaseModel
{
    use ConfigId;

    public const BELUM_LENGKAP         = 0;
    public const SEDANG_DIPERIKSA      = 1;
    public const MENUNGGU_TANDA_TANGAN = 2;
    public const SIAP_DIAMBIL          = 3;
    public const SUDAH_DIAMBIL         = 4;
    public const DIBATALKAN            = 5;
    public const STATUS_PERMOHONAN     = [
        self::BELUM_LENGKAP         => 'Belum Lengkap',
        self::SEDANG_DIPERIKSA      => 'Sedang Diperiksa',
        self::MENUNGGU_TANDA_TANGAN => 'Menunggu Tandatangan',
        self::SIAP_DIAMBIL          => 'Siap Diambil',
        self::SUDAH_DIAMBIL         => 'Sudah Diambil',
        self::DIBATALKAN            => 'Dibatalkan',
    ];

    /**
     * {@inheritDoc}
     */
    protected $table = 'permohonan_surat';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'id_pemohon',
        'id_surat',
        'isian_form',
        'alasan',
        'keterangan',
        'status',
        'no_hp_aktif',
        'syarat',
        'alasan',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'isian_form' => 'json',
        'syarat'     => 'json',
    ];

    /**
     * {@inheritDoc}
     */
    protected $with = ['surat', 'penduduk'];

    /**
     * Getter untuk mapping status permohonan.
     *
     * @return string
     */
    public function getStatusPermohonanAttribute()
    {
        return static::STATUS_PERMOHONAN[$this->status];
    }

    /**
     * Getter untuk mapping syartsurat permohonan.
     *
     * @return string
     */
    public function getSyaratSuratAttribute()
    {
        if ($this->syarat == null) {
            return null;
        }

        $dokumen = Dokumen::where('id_pend', $this->id_pemohon)->whereIn('id', $this->syarat)->get();

        return $dokumen->map(static function ($syarat) {
            $syarat->nama_syarat = $syarat->jenisDokumen->ref_syarat_nama;

            return $syarat;
        });
    }

    /**
     * Scope query untuk pengguna.
     *
     * @param Builder $query
     */
    public function scopePengguna($query): void
    {
        // return $query->where('id_pemohon', auth('jwt')->user()->penduduk->id);
    }

    /**
     * Scope query untuk status.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeStatus($query, string $status = '')
    {
        if ($status == '') {
            return $query;
        }

        return $query->where('status', $status);
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pemohon');
    }

    public function surat()
    {
        return $this->belongsTo(FormatSurat::class, 'id_surat')->withoutGlobalScope(\App\Scopes\RemoveRtfScope::class);
    }

    public function scopeBelumDiambil($query)
    {
        return $query->where('status', '!=', self::SUDAH_DIAMBIL);
    }

    /**
     * Get all of the logSurat for the PermohonanSurat
     */
    public function logSurat(): HasMany
    {
        return $this->hasMany(LogSurat::class, 'id_format_surat', 'id_surat')->withoutGlobalScope(\App\Scopes\RemoveRtfScope::class);
    }

    public function mapSyaratSurat()
    {
        return collect($this->syarat)->map(static function ($item, $key): array {
            $syaratSurat        = SyaratSurat::select(['ref_syarat_nama'])->find($key);
            $dokumenKelengkapan = Dokumen::select(['nama'])->find($item);

            return [
                'ref_syarat_id'   => $key,
                'ref_syarat_nama' => $syaratSurat->ref_syarat_nama,
                'dok_id'          => $item,
                'dok_nama'        => ($item == '-1') ? 'Bawa bukti fisik ke Kantor Desa' : $dokumenKelengkapan->nama,
            ];
        })->values();
    }

    public function proses($status): void
    {
        if ($status == PermohonanSurat::BELUM_LENGKAP) {
            // Belum Lengkap
            $this->db->where('status', PermohonanSurat::SEDANG_DIPERIKSA);
        } elseif ($status == PermohonanSurat::DIBATALKAN) {
            // Batalkan hanya jika status = 0 (belum lengkap) atau 1 (sedang diproses)
            $this->db->where_in('status', [PermohonanSurat::BELUM_LENGKAP, PermohonanSurat::SEDANG_DIPERIKSA]);
        } else {
            // Lainnya
            $this->db->where('status', ($status - 1));
        }

        $this->update(['status' => $status]);
    }
}
