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

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string $template_uuid
 * @property string $tahun
 * @property numeric $anggaran
 * @property numeric $realisasi
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KeuanganTemplate $template
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan tahunAnggaran()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan whereAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan whereRealisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan whereTemplateUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keuangan withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Keuangan extends BaseModel
{
    use ConfigId;
    use Author;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'keuangan';

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * {@inheritDoc}
     */
    protected static function booted()
    {
        static::saved(function (Keuangan $keuangan): void {
            $keuangan->load([
                'template.parent.parent',
            ]);

            // Helper closure untuk update berdasarkan parent level
            $updateParent = static function ($parent, string $length) use ($keuangan): void {
                $child = static::where('tahun', $keuangan->tahun)
                    ->whereRaw('length(template_uuid) in (' . $length . ')')
                    ->where('template_uuid', 'like', "{$parent->uuid}%")
                    ->get();

                static::where('tahun', $keuangan->tahun)
                    ->where('template_uuid', $parent->uuid)
                    ->update([
                        'anggaran'  => $child->sum('anggaran'),
                        'realisasi' => $child->sum('realisasi'),
                    ]);
            };

            // Update parent ke-3 (contoh: 5.1.1)
            $updateParent($keuangan->template->parent, '8,11');

            // Update parent ke-2 (contoh: 5.1)
            $updateParent($keuangan->template->parent->parent, '5');

            // Update parent ke-1 (contoh: 5)
            // $updateParent($keuangan->template->parent->parent->parent, '3');
    });
}

    public function template()
    {
        return $this->belongsTo(KeuanganTemplate::class, 'template_uuid', 'uuid');
    }

    public function scopeTahunAnggaran($query)
    {
        return $query->distinct()->select('tahun')->orderBy('tahun', 'desc');
    }
}
