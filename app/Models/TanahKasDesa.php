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

use App\Enums\AsalTanahKasEnum;
use App\Enums\PeruntukanTanahKasEnum;
use App\Traits\Author;
use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

class TanahKasDesa extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tanah_kas_desa';

    protected $with = ['ref_persil_kelas'];

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $appends = [
        'asal_tanah_kas_label',
        'peruntukan_tanah_kas_label',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'nama_pemilik_asal' => AsalTanahKasEnum::class,
        'peruntukan'        => PeruntukanTanahKasEnum::class,
    ];

    public function scopeCheckLetterC($query, $letterC_persil)
    {
        return $query->where('letter_c', $letterC_persil)->where('visible', 1)->exists();
    }

    public function scopeCheckOldLetterC($query, $value, $letterC_persil): bool
    {
        return $query->where('visible', 1)->where('id', $value)->first()->letter_c == $letterC_persil;
    }

    public function scopeVisible($query, $value = 1)
    {
        return $query->where('visible', $value);
    }

    public function getAsalTanahKasLabelAttribute(): ?string
    {
        return $this->nama_pemilik_asal?->label();
    }

    public function getPeruntukanTanahKasLabelAttribute(): ?string
    {
        return $this->peruntukan?->label();
    }

    public function ref_persil_kelas()
    {
        return $this->belongsTo(RefPersilKelas::class, 'kelas');
    }
}
