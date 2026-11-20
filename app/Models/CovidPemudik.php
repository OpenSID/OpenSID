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

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property int|null $id_terdata
 * @property int $pantau
 * @property string|null $tanggal_datang
 * @property string|null $asal_mudik
 * @property string|null $durasi_mudik
 * @property string|null $tujuan_mudik
 * @property string|null $keluhan_kesehatan
 * @property string|null $status_covid
 * @property string|null $no_hp
 * @property string|null $email
 * @property string|null $keterangan
 * @property string|null $is_wajib_pantau
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereAsalMudik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereDurasiMudik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereIdTerdata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereIsWajibPantau($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereKeluhanKesehatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik wherePantau($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereStatusCovid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereTanggalDatang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik whereTujuanMudik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidPemudik withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class CovidPemudik extends BaseModel
{
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
    protected $table = 'covid19_pemudik';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];
}
