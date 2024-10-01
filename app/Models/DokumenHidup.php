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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Traits\Author;
use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

class DokumenHidup extends BaseModel
{
    use ConfigId;
    use Author;

    public const WIDGET_SISTEM  = 1;
    public const WIDGET_STATIS  = 2;
    public const WIDGET_DINAMIS = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dokumen_hidup';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The casts with the model.
     *
     * @var array
     */
    protected $casts = [
    ];

    public static function boot(): void
    {
        parent::boot();

        static::updating(static function ($model): void {
            if ($model->id_parent != null) {
                return;
            }
            static::deleteFile($model, 'satuan');
        });

        static::deleting(static function ($model): void {
            if ($model->id_parent == null) {
                static::deleteFile($model, 'satuan', true);
            }
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $logo = LOKASI_DOKUMEN . $model->getOriginal($file);
            if (file_exists($logo)) {
                unlink($logo);
            }
        }
    }

    public function scopePeraturanDesa($query, $kat)
    {
        $data = $query->where('kategori', $kat);
        if ($kat == 3 && ($jenis = session('jenis_peraturan'))) {
            $attr = '"jenis_peraturan":"' . $jenis . '"';
            $data->where('attr', 'like', '%' . $attr . '%');
        }

        return $data;
    }

    public function scopeDataCetak($query, $kat = 1, ?string $tahun = '', ?string $jenis_peraturan = '')
    {
        $data = $query->where('id_pend', '0')
            ->where('enabled', '1');

        if ($tahun !== null && $tahun !== '' && $tahun !== '0') {
            switch ($kat) {
                case '1':
                    // Informasi publik
                    $data->where('tahun', $tahun);
                    break;

                case '2':
                    // SK KADES
                    $regex = '"tgl_kep_kades":"[[:digit:]]{2}-[[:digit:]]{2}-' . $tahun;
                    $data->whereRaw("attr REGEXP '" . $regex . "'");
                    break;

                case '3':
                    // PERDES
                    $regex = '"tgl_ditetapkan":"[[:digit:]]{2}-[[:digit:]]{2}-' . $tahun;
                    $data->whereRaw("attr REGEXP '" . $regex . "'");
                    break;
            }
        }

        if ($kat == 3 && $jenis_peraturan) {
            $like = '"jenis_peraturan":"' . $jenis_peraturan . '"';
            $data->where('attr', 'LIKE', "%{$like}%");
        }

        // Informasi publik termasuk kategori lainnya
        if ($kat != '1') {
            // $this->db->where('kategori', $kat);
            $data->where('kategori', $kat);
        }

        return $data->where('id_pend', '0')->where('enabled', '1')->get()->map(static function ($item) {
            $item->attr = json_decode($item->attr, true);

            return $item;
        });
    }

    public function scopeGetDokumen($query, $id = 0, $id_pend = null): ?array
    {
        if ($id_pend) {
            $query->where('id_pend', $id_pend);
        }

        $data = $query->where('id', $id)->first()->toArray();

        if ($data) {
            $data['attr'] = json_decode($data['attr'], true);

            return array_filter($data);
        }

        return null;
    }

    public function scopeGetTahun($query, $kat)
    {
        switch ($kat) {
            case '1':
                // Informasi publik, termasuk kategori lainnya
                return $query
                    ->distinct()
                    ->select('tahun')
                    ->orderByDesc('tahun')
                    ->get()
                    ->toArray();

            case '2':
                // SK KADES
                $attr_str = '"tgl_kep_kades":';

                return $query
                    ->distinct()
                    ->selectRaw("SUBSTRING_INDEX(JSON_UNQUOTE(JSON_EXTRACT(attr, '$.tgl_kep_kades')), '-', -1) as tahun")
                    ->where('kategori', $kat)
                    ->orderByDesc('tahun')
                    ->get()
                    ->toArray();

            case '3':
                // PERDES
                $attr_str = '"tgl_ditetapkan":';

                return $query
                    ->distinct()
                    ->selectRaw("SUBSTRING_INDEX(JSON_UNQUOTE(JSON_EXTRACT(attr, '$.tgl_ditetapkan')), '-', -1) as tahun")
                    ->where('kategori', $kat)
                    ->orderByDesc('tahun')
                    ->get()
                    ->toArray();
        }
    }
}
