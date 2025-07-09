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

use App\Traits\Author;
use App\Traits\ConfigId;
use App\Traits\Uuid;

defined('BASEPATH') || exit('No direct script access allowed');

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
        static::saved(function (Keuangan $keuangan) {
            $keuangan->load([
                'template' => [
                    'parent.parent',
                ],
            ]);

            /**
             * Query ini untuk mengambil daftar keuangan
             * berdasarkan parent ke 2.
             *
             * Contoh: child dari parent 5.1
             * ```php
             *  $child = [
             *      "5.1.1",
             *      "5.1.2",
             *      "5.1.3",
             *      "5.1.4",
             *  ];
             */
            $child = static::where('tahun', $keuangan->tahun)
                ->whereRaw('length(template_uuid) in (5)')
                ->where('template_uuid', 'like', "{$keuangan->template->parent->uuid}%")
                ->get();

            // update jumlah anggaran dan realisasi dari data child dari parent ke 2.
            static::where('tahun', $keuangan->tahun)
                ->where('template_uuid', $keuangan->template->parent->uuid)
                ->update([
                    'anggaran'  => $child->sum('anggaran'),
                    'realisasi' => $child->sum('realisasi'),
                ]);
        });
    }

    // relasi
    public function template()
    {
        return $this->belongsTo(KeuanganTemplate::class, 'template_uuid', 'uuid');
    }

    // salin
    public static function salin($tahun)
    {
        $template = [
            [
                'kode_rekening' => '4',
                'uraian'        => 'Pendapatan',
                'anggaran'      => 0,
                'realisasi'     => 0,
            ],
        ];

        foreach ($template as $data) {
            $data['config_id'] = identitas('id');
            $data['tahun']     = $tahun;

            self::create($data);
        }
    }

    public function scopeTahunAnggaran($query)
    {
        return $query->distinct()->select('tahun')->orderBy('tahun', 'desc');
    }
}
