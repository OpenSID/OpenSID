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
use Carbon\Carbon;

defined('BASEPATH') || exit('No direct script access allowed');

class Pengaduan extends BaseModel
{
    use ConfigId;
    use ShortcutCache;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengaduan';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'child',
    ];

    /**
     * Scope query untuk status pengaduan
     *
     * @param mixed $query
     * @param mixed $status
     *
     * @return Builder
     */
    public function scopeStatus($query, $status = null)
    {
        if ($status) {
            $query->where('status', $status);
        }

        return $this->scopeTipe($query);
    }

    /**
     * Scope query untuk tipe pengaduan
     * Jika id_pengaduan null maka dari warga
     * Jika id_pengaduan tidak null maka balasan dari admin
     *
     * @param mixed      $query
     * @param mixed|null $id_pengaduan
     */
    public function scopeTipe($query, $id_pengaduan = null)
    {
        if ($id_pengaduan) {
            $query->where('id_pengaduan', $id_pengaduan);
        }

        return $query->where('id_pengaduan', null);
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function child()
    {
        return $this->hasMany(Pengaduan::class, 'id_pengaduan', 'id');
    }

    /**
     * Scope query untuk status pengaduan bulanan
     *
     * @param mixed $query
     * @param mixed $status
     *
     * @return Builder
     */
    public function scopeBulanan($query, $status = null)
    {
        if ($status) {
            $query->where('status', $status);
        }

        return $query->where('id_pengaduan', null)->whereMonth('created_at', Carbon::now()->month);
    }

    public function scopeFilter($query, $status)
    {
        if (! empty($status)) {
            $query->where('status', $status);
        }

        return $query;
    }

    public static function boot(): void
    {
        parent::boot();
        static::deleting(static function ($model): void {
            if ($model->foto) {
                $file = FCPATH . LOKASI_PENGADUAN . $model->foto;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        });
    }
}
