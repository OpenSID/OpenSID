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
 * @property int|null $id_user_mandiri
 * @property int $config_id
 * @property string $judul Judul notifikasi
 * @property string $isi Isi notifikasi
 * @property string|null $token
 * @property string|null $device
 * @property string|null $image gambar notifikasi, jika ada
 * @property string $payload Tujuan navicasi saat notifikasi di klik
 * @property int $read menandatakan notifikasi sudah terbaca atau belum, 1 artinya sudah dibaca, 0 artinya belum dibaca
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri whereIdUserMandiri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri whereRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogNotifikasiMandiri withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class LogNotifikasiMandiri extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_notifikasi_mandiri';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $fillable = [
        'id_user_mandiri',
        'config_id',
        'judul',
        'isi',
        'token',
        'device',
        'image',
        'read',
        'payload',
    ];
}
