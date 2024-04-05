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

use App\Enums\SHDKEnum;
use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\HasOne;

defined('BASEPATH') || exit('No direct script access allowed');

class PendudukHidup extends BaseModel
{
    use ConfigId;

    /**
     * {@inheritDoc}
     */
    protected $table = 'penduduk_hidup';

    /**
     * {@inheritDoc}
     */
    public $incrementing = false;

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
