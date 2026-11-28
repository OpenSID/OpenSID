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

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kelompok> $kelompok
 * @property string $deskripsi
 * @property string|null $tipe
 * @property-read int|null $kelompok_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KelompokMaster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KelompokMaster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KelompokMaster query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KelompokMaster tipe(?mixed $tipe = 'kelompok')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KelompokMaster whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KelompokMaster whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KelompokMaster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KelompokMaster whereKelompok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KelompokMaster whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KelompokMaster withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class KelompokMaster extends BaseModel
{
    use ConfigId;

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $table = 'kelompok_master';

    /**
     * {@inheritDoc}
     */
    protected $guarded = [];

    public function kelompok()
    {
        return $this->hasMany(Kelompok::class, 'id_master', 'id');
    }

    /**
     * Scope query untuk tipe kelompok
     *
     * @return Builder
     */
    public function scopeTipe(mixed $query, mixed $tipe = 'kelompok')
    {
        return $query->where('tipe', $tipe);
    }
}
