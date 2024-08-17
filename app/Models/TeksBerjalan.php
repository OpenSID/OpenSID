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

use App\Traits\Author;
use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

class TeksBerjalan extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'teks_berjalan';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The casts with the model.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    public function scopeList($query, $tipe = '', $status = '')
    {
        if ($tipe != '') {
            $query->where('tipe', $status);
        }
        if ($status != '') {
            $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Scope query untuk status
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    // TODO :: ganti jadi YA (1) dan TIDAK (0)
    public function scopeStatus($query, $value = 1)
    {
        return $query->where('status', $value);
    }

    /**
     * Scope query untuk tipe
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeTipe($query, $value = 1)
    {
        return $query->where('tipe', $value);
    }

    public function scopeNomorUrut($query, $id, $direction)
    {
        $data = $this->findOrFail($id);

        $currentNo = $data->urut;
        $targetNo  = ($direction == 2) ? $currentNo - 1 : $currentNo + 1;

        $query->where('urut', $targetNo)->update(['urut' => $currentNo]);

        $data->update(['urut' => $targetNo]);

        return $query;
    }

    public function scopeUrutMax($query)
    {
        return $query->orderByDesc('urut')->first()->urut + 1;
    }

    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'tautan', 'id');
    }
}
