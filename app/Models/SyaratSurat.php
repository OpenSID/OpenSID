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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Traits\ConfigId;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class SyaratSurat extends BaseModel
{
    use ConfigId;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ref_syarat_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ref_syarat_surat';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'ref_syarat_nama',
    ];

    /**
     * The hidden with the model.
     *
     * @var array
     */
    protected $hidden = [
        'config_id',
    ];

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dokumen()
    {
        // https://github.com/OpenSID/opensid-laravel/blob/main/app/Models/SyaratSurat.php
        // return $this->hasMany(Dokumen::class, 'id_syarat')->where('id_pend', auth('jwt')->id());

        return $this->hasMany(Dokumen::class, 'id_syarat');
    }

    /**
     * Scope Format surat exist.
     *
     * @param \Illuminate\Database\Query\Builder $query
     */
    public function scopeFormatSuratExist($query): void
    {
        $sql = <<<'EOD'
                json_contains(tweb_surat_format.syarat_surat, concat('"', ref_syarat_surat.ref_syarat_id, '"'), '$' )
            EOD;

        $query->select(['ref_syarat_id', 'ref_syarat_nama', DB::raw('count(syarat_surat) as jumlah_format_surat')])
            ->leftJoin('tweb_surat_format', DB::raw($sql), '=', DB::raw('1'))
            ->groupBy(['ref_syarat_id', 'ref_syarat_nama']);
    }

    /**
     * Scope Format surat exist.
     *
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeDeleteFormatSuratExist($query, mixed $id)
    {
        return $this->formatSuratExist()
            ->where('ref_syarat_surat.ref_syarat_id', $id)
            ->whereNull('tweb_surat_format.id')
            ->delete();
    }
}
