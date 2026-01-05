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

namespace Modules\Kehadiran\Models;

use App\Models\BaseModel;
use App\Models\Pamong;
use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

defined('BASEPATH') || exit('No direct script access allowed');

class PengajuanIzinDetail extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kehadiran_pengajuan_izin_detail';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pengajuan_izin_id',
        'tanggal',
        'jenis_izin',
        'id_pamong',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relationship to pengajuan izin header
     */
    public function pengajuanIzin(): BelongsTo
    {
        return $this->belongsTo(PengajuanIzin::class, 'pengajuan_izin_id');
    }

    /**
     * Relationship to pamong
     */
    public function pamong(): BelongsTo
    {
        return $this->belongsTo(Pamong::class, 'pamong_id', 'id_pamong');
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $tanggal
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTanggal($query, $tanggal)
    {
        return $query->where('tanggal', $tanggal);
    }

    /**
     * Scope untuk filter berdasarkan rentang tanggal
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $tanggalAwal
     * @param string                                $tanggalAkhir
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRentangTanggal($query, $tanggalAwal, $tanggalAkhir)
    {
        return $query->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
    }

    /**
     * Scope untuk filter berdasarkan jenis izin
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $jenisIzin
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByJenisIzin($query, $jenisIzin)
    {
        return $query->where('jenis_izin', $jenisIzin);
    }

    /**
     * Scope untuk filter berdasarkan status
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $status
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan pamong
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $pamongId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPamong($query, $pamongId)
    {
        return $query->where('id_pamong', $pamongId);
    }

    /**
     * Scope untuk rekapitulasi per bulan
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $tahun
     * @param int                                   $bulan
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRekapBulanan($query, $tahun, $bulan)
    {
        return $query->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan);
    }

    /**
     * Scope untuk rekapitulasi per tahun
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $tahun
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRekapTahunan($query, $tahun)
    {
        return $query->whereYear('tanggal', $tahun);
    }
}
