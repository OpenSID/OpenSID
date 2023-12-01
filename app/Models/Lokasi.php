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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\HasOne;

defined('BASEPATH') || exit('No direct script access allowed');

class Lokasi extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lokasi';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'path',
        'enabled',
        'ref_polygon',
        'foto',
        'id_cluster',
        'desk',
    ];

    /**
     * The appends with the model.
     *
     * @var array
     */
    protected $appends = [
        'foto_kecil',
        'foto_sedang',
    ];

    /**
     * Getter untuk foto kecil.
     */
    public function getFotoKecilAttribute(): ?string
    {
        $foto = LOKASI_FOTO_LOKASI . 'kecil_' . $this->attributes['foto'];

        if (file_exists(FCPATH . $foto)) {
            return $foto;
        }

        return null;
    }

    /**
     * Getter untuk foto sedang.
     */
    public function getFotoSedangAttribute(): ?string
    {
        $foto = LOKASI_FOTO_LOKASI . 'sedang_' . $this->attributes['foto'];

        if (file_exists(FCPATH . $foto)) {
            return $foto;
        }

        return null;
    }

    protected function scopeActive($query)
    {
        return $query->whereEnabled(1);
    }

    /**
     * Get the point associated with the Lokasi
     */
    public function point(): HasOne
    {
        return $this->hasOne(Point::class, 'id', 'ref_point');
    }

    public static function activeLocationMap()
    {
        return self::active()->with(['point' => static fn ($q) => $q->select(['id', 'nama', 'parrent', 'simbol'])->with(['parent' => static fn ($r) => $r->select(['id', 'nama', 'parrent', 'simbol'])]),
        ])->get()->map(function ($item) {
            $item->jenis    = $item->point->parent->nama ?? '';
            $item->kategori = $item->point->nama ?? '';
            $item->simbol   = $item->point->simbol ?? '';
            unset($item->point);

            return $item;
        })->toArray();
    }
}
