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
 * @property int $id_kontak
 * @property int $config_id
 * @property string $nama
 * @property string|null $telepon
 * @property string|null $email
 * @property string|null $telegram
 * @property string $hubung_warga
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak whereHubungWarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak whereIdKontak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak whereTelegram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DaftarKontak withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class DaftarKontak extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kontak';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_kontak';

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'telepon',
        'email',
        'telegram',
        'hubung_warga',
        'keterangan',
    ];
}
