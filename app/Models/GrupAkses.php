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
 * @property int $id_grup
 * @property int $id_modul
 * @property int|null $akses
 * @property-read \App\Models\UserGrup $grup
 * @property-read \App\Models\Modul $modul
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupAkses newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupAkses newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupAkses query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupAkses whereAkses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupAkses whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupAkses whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupAkses whereIdGrup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupAkses whereIdModul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupAkses withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class GrupAkses extends BaseModel
{
    use ConfigId;

    public const BACA  = 1;
    public const UBAH  = 3;
    public const HAPUS = 7;

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
    protected $table = 'grup_akses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'id_grup',
        'id_modul',
        'akses',
    ];

    public function modul()
    {
        return $this->belongsTo(Modul::class, 'id_modul', 'id');
    }

    public function grup()
    {
        return $this->belongsTo(UserGrup::class, 'id_grup', 'id');
    }
}
