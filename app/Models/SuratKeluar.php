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

class SuratKeluar extends BaseModel
{
    use ConfigId;
    use Author;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'surat_keluar';

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'nomor_urut',
        'nomor_surat',
        'kode_surat',
        'tanggal_surat',
        'tanggal_catat',
        'tujuan',
        'isi_singkat',
        'berkas_scan',
        'tanggal_pengiriman',
        'tanda_terima',
        'keterangan',
        'ekspedisi',
        'created_by',
        'updated_by',
    ];

    public function scopeTahun($query)
    {
        return $query->selectRaw('YEAR(tanggal_surat) as tahun')->distinct()->orderBy('tahun', 'desc');
    }

    public function scopeAutocomplete($query)
    {
        $query->select('tujuan')->distinct()->orderBy('tujuan');

        return $query->limit(15)->pluck('tujuan')->toArray();
    }

    public static function boot(): void
    {
        parent::boot();

        static::updating(static function ($model): void {
            static::deleteFile($model, 'berkas_scan');
        });

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'berkas_scan', true);
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $gambar = LOKASI_ARSIP . $model->getOriginal($file);
            if (file_exists($gambar)) {
                unlink($gambar);
            }
        }
    }
}
