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
use DateTime;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string $kode
 * @property string $judul
 * @property string $jenis
 * @property string $isi
 * @property string $server
 * @property string $tgl_berikutnya
 * @property string $updated_at
 * @property int $updated_by
 * @property int $frekuensi
 * @property string $aksi
 * @property int $aktif
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereAksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereFrekuensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereServer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereTglBerikutnya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Notifikasi extends BaseModel
{
    use ConfigId;

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
    protected $table = 'notifikasi';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    public static function semua()
    {
        $hari_ini = new DateTime();
        $compare  = $hari_ini->format('Y-m-d H:i:s');

        return self::where('tgl_berikutnya', '<=', $compare)
            ->selectRaw("notifikasi.*, IF (jenis = 'persetujuan', CONCAT('A',id), CONCAT('Z',id)) AS urut")
            ->where('aktif', 1)
            ->orderBy('urut', 'ASC')
            ->get()->toArray();
    }

    public static function convert(array $notif): array
    {
        $aksi                = explode(',', (string) $notif['aksi']);
        $notif['aksi_ya']    = $aksi[0];
        $notif['aksi_tidak'] = $aksi[1];
        $notif['isi']        = str_replace(['\n', '\"SEBAGAIMANA ADANYA\"'], ['', '"SEBAGAIMANA ADANYA"'], $notif['isi']);

        return $notif;
    }
}
