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
 * @property int $id_penduduk
 * @property int $config_id
 * @property int|null $vaksin_1
 * @property string|null $tgl_vaksin_1
 * @property string|null $dokumen_vaksin_1
 * @property string|null $jenis_vaksin_1
 * @property int|null $vaksin_2
 * @property string|null $tgl_vaksin_2
 * @property string|null $dokumen_vaksin_2
 * @property string|null $jenis_vaksin_2
 * @property int|null $vaksin_3
 * @property string|null $tgl_vaksin_3
 * @property string|null $dokumen_vaksin_3
 * @property string|null $jenis_vaksin_3
 * @property int|null $tunda
 * @property string|null $keterangan
 * @property string|null $surat_dokter
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereDokumenVaksin1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereDokumenVaksin2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereDokumenVaksin3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereIdPenduduk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereJenisVaksin1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereJenisVaksin2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereJenisVaksin3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereSuratDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereTglVaksin1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereTglVaksin2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereTglVaksin3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereTunda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereVaksin1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereVaksin2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin whereVaksin3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CovidVaksin withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class CovidVaksin extends BaseModel
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
    protected $table = 'covid19_vaksin';

    protected $primaryKey = 'id_penduduk';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];
}
