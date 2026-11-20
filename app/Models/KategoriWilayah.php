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

use Illuminate\Database\Eloquent\Relations\BelongsTo;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $kategori_id
 * @property string $tingkat_wilayah
 * @property string $kode_wilayah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Kategori $kategori
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriWilayah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriWilayah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriWilayah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriWilayah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriWilayah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriWilayah whereKategoriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriWilayah whereKodeWilayah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriWilayah whereTingkatWilayah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriWilayah whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriWilayah withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class KategoriWilayah extends BaseModel
{
    public const TINGKAT_KABUPATEN = 'kabupaten';
    public const TINGKAT_PROVINSI  = 'provinsi';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kategori_wilayah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kategori_id',
        'tingkat_wilayah',
        'kode_wilayah',
    ];

    /**
     * Get the kategori that this wilayah mapping belongs to.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
