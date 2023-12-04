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

defined('BASEPATH') || exit('No direct script access allowed');

class DtksLampiran extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dtks_lampiran';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'judul',
        'keterangan',
        'foto',
        'id_rtm',
    ];

    public function getFotoKecilAttribute(): string
    {
        $path = LOKASI_FOTO_DTKS . 'kecil_' . $this->attributes['foto'];
        if (! file_exists(FCPATH . $path)) {
            return '';
        }

        return base_url($path);
    }

    public function dtks()
    {
        return $this->belongsToMany(Dtks::class, 'dtks_ref_lampiran', 'id_lampiran', 'id_dtks')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(static function ($model) {
            static::deleteFile($model->getOriginal('foto'));
        });
    }

    private static function deleteFile(?string $file): void
    {
        if ($file) {
            $path = FCPATH . LOKASI_FOTO_DTKS . $file;
            if (file_exists($path)) {
                unlink($path);
            }

            $path_kecil = FCPATH . LOKASI_FOTO_DTKS . 'kecil_' . $file;
            if (file_exists($path_kecil)) {
                unlink($path_kecil);
            }
        }
    }
}
