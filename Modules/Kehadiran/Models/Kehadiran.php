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

namespace Modules\Kehadiran\Models;

use App\Models\BaseModel;
use App\Models\Pamong;
use App\Traits\ConfigId;
use Carbon\Carbon;

defined('BASEPATH') || exit('No direct script access allowed');

class Kehadiran extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kehadiran_perangkat_desa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tanggal',
        'pamong_id',
        'jam_masuk',
        'jam_keluar',
        'status_kehadiran',
    ];

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Define a many-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function pamong()
    {
        return $this->belongsTo(Pamong::class, 'pamong_id', 'pamong_id');
    }

    public function scopeLupaAbsen($query, $tanggal)
    {
        $jam = JamKerja::where('nama_hari', Carbon::createFromFormat('Y-m-d', $tanggal)->dayName)->first('jam_keluar');

        return $query->where('tanggal', $tanggal)
            ->where('status_kehadiran', 'hadir')
            ->where('jam_keluar', null)
            ->take(1)
            ->update([
                'jam_keluar'       => $jam->jam_keluar,
                'status_kehadiran' => 'lupa melapor keluar',
            ]);
    }

    public function scopeFilter($query, array $filters)
    {
        if (! empty($filters['tanggal'])) {
            [$awal, $akhir] = explode(' - ', (string) $filters['tanggal']);
            $query->whereBetween('tanggal', [$awal, $akhir]);
        }

        if (! empty($filters['status'])) {
            $query->where('status_kehadiran', $filters['status']);
        }

        if (! empty($filters['pamong'])) {
            $query->where('pamong_id', $filters['pamong']);
        }

        return $query;
    }
}
