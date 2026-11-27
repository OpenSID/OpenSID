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

use App\Traits\Author;
use App\Traits\ConfigId;

/**
 * @property int $id
 * @property int $config_id
 * @property string|null $slug
 * @property string $nama
 * @property int $jenis
 * @property string|null $template
 * @property string|null $template_desa
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat jenis(?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat whereTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat whereTemplateDesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LampiranSurat withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class LampiranSurat extends BaseModel
{
    use Author;
    use ConfigId;

    public const LAMPIRAN_SISTEM = 1;
    public const LAMPIRAN_DESA   = 2;
    public const JENIS_LAMPIRAN  = [
        self::LAMPIRAN_SISTEM => 'Lampiran Sistem',
        self::LAMPIRAN_DESA   => 'Lampiran [Desa]',
    ];

    /**
     * Static data margin lampiran.
     *
     * @var array
     */
    public const MARGINS = [
        'kiri'  => 1.78,
        'atas'  => 0.63,
        'kanan' => 1.78,
        'bawah' => 1.37,
    ];

    public const KOTAK = [
        'jarak' => 2,
        'lebar' => 5,
    ];

    /**
     * {@inheritDoc}
     */
    protected $table = 'lampiran_surat';

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'slug',
        'nama',
        'jenis',
        'template',
        'template_desa',
        'status',
        'margin',
        'margin_global',
        'ukuran',
        'orientasi',
        'created_by',
        'updated_by',
    ];

    /**
     * Scope query untuk Jenis Surat
     *
     * @return Builder
     */
    public function scopeJenis(mixed $query, mixed $value)
    {
        if (empty($value)) {
            return $query->whereNotNull('jenis');
        }

        if (is_array($value)) {
            return $query->whereIn('jenis', $value);
        }

        return $query->where('jenis', $value);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
