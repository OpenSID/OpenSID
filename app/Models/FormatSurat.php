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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class FormatSurat extends Model
{
    public const MANDIRI         = 1;
    public const MANDIRI_DISABLE = 0;
    public const KUNCI           = 1;
    public const KUNCI_DISABLE   = 0;

    /**
     * {@inheritDoc}
     */
    protected $table = 'tweb_surat_format';

    /**
     * Define a many-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function syaratSurat()
    {
        return $this->belongsToMany(SyaratSurat::class, 'syarat_surat', 'surat_format_id', 'ref_syarat_id');
    }

    /**
     * Scope query untuk layanan mandiri.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeMandiri($query)
    {
        return $query->where('mandiri', static::MANDIRI);
    }

    /**
     * Scope query untuk list surat yang tidak dikunci.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeKunci($query)
    {
        return $query->where('kunci', static::KUNCI_DISABLE);
    }

    /**
     * Getter list surat dan dokumen attribute.
     *
     * @return array
     */
    public function getListSyaratSuratAttribute()
    {
        return $this->syaratSurat->map(
            static function ($syarat) {
                return [
                    'label'      => $syarat->ref_syarat_nama,
                    'value'      => $syarat->ref_syarat_id,
                    'form_surat' => [
                        [
                            'type'     => 'select',
                            'required' => true,
                            'label'    => 'Dokumen Syarat',
                            'name'     => 'dokumen',
                            'multiple' => false,
                            'values'   => $syarat->dokumen->map(static function ($dokumen) {
                                return [
                                    'label' => $dokumen->nama,
                                    'value' => $dokumen->id,
                                ];
                            }),
                        ],
                    ],
                ];
            }
        );
    }

    /**
     * Getter form surat attribute.
     *
     * @return mixed
     */
    public function getFormSuratAttribute()
    {
        // try {
        //     return app('surat')->driver($this->url_surat)->form();
        // } catch (Exception $e) {
        //     Log::error($e);

        //     return null;
        // }
    }
}
