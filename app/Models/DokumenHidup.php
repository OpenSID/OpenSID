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
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class DokumenHidup extends BaseModel
{
    use ConfigId;
    use Author;

    public const ENABLE = 1;

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

    public function scopePeraturanDesa($query, $kat, $tahun = '')
    {
        $query->where('kategori', $kat);
        if ($kat == 3 && $tahun != '') {
            $query->whereRaw("JSON_EXTRACT(attr, '$.tgl_ditetapkan') LIKE ?", ["%{$tahun}%"]);
        }

        return $query;
    }

    public function isActive(): bool
    {
        return $this->attributes['enabled'] == self::ENABLE;
    }

    public function scopeInformasiPublik($query)
    {
        return $query->where('id_pend', 0);
    }

    public function scopeActive($query)
    {
        return $query->where(['enabled' => self::ENABLE]);
    }

    public function scopeNonActive($query)
    {
        return $query->where('enabled', '!=', self::ENABLE);
    }

    public function scopeDataCetak($query, $kat = 1, ?string $tahun = '', ?string $jenis_peraturan = '')
    {
        $query = $query->where('id_pend', '0')->where('enabled', '1');

        if ($tahun !== null && $tahun !== '' && $tahun !== '0') {
            switch ($kat) {
                case '1':
                    // Informasi publik
                    $query->where('tahun', $tahun);
                    break;

                case '2':
                    // SK KADES
                    $regex = '"tgl_kep_kades":"[[:digit:]]{2}-[[:digit:]]{2}-' . $tahun;
                    $query->whereRaw("attr REGEXP '" . $regex . "'");
                    break;

                case '3':
                    // PERDES
                    $regex = '"tgl_ditetapkan":"[[:digit:]]{2}-[[:digit:]]{2}-' . $tahun;
                    $query->whereRaw("attr REGEXP '" . $regex . "'");
                    break;
            }
        }

        if ($kat == 3 && $jenis_peraturan) {
            $like = '"jenis_peraturan":"' . $jenis_peraturan . '"';
            $query->where('attr', 'LIKE', "%{$like}%");
        }

        // Informasi publik termasuk kategori lainnya
        if ($kat != '1') {
            $query->where('kategori', $kat);
        }

        $this->casts = [
            'attr' => 'json',
        ];

        return $query;
    }

    public function scopeGetDokumen($query, $id = 0, $id_pend = null): ?array
    {
        if ($id_pend) {
            $query->where('id_pend', $id_pend);
        }

        $data = $query->where('id', $id)->first()->toArray();

        if ($data) {
            $data['attr'] = json_decode((string) $data['attr'], true);

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

        return null;
    }

    /**
     * Scope daftar arsip fisik dokumen desa.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArsipFisikDokumenDesa(mixed $query)
    {
        return $query
            ->select([
                'id',
                DB::raw("IF(kategori = 3,
                    REPLACE(TRIM(BOTH '\"' FROM JSON_EXTRACT(attr, '$.no_ditetapkan')), '\"', ''),
                    IF(JSON_UNQUOTE(JSON_EXTRACT(attr, '$.no_kep_kades')) IS NOT NULL AND JSON_UNQUOTE(JSON_EXTRACT(attr, '$.no_kep_kades')) != '',
                        REPLACE(TRIM(BOTH '\"' FROM JSON_EXTRACT(attr, '$.no_kep_kades')), '\"', ''),
                        '-')
                ) AS nomor_dokumen"),
                DB::raw("IF(kategori = 2, STR_TO_DATE(TRIM(BOTH '' FROM JSON_EXTRACT(`attr`, '$.tgl_kep_kades')), '%d-%m-%Y'), IF(kategori = 3, STR_TO_DATE(TRIM(BOTH '' FROM JSON_EXTRACT(`attr`, '$.tgl_ditetapkan')), '%d-%m-%Y'), DATE(`updated_at`))) AS tanggal_dokumen"),
                DB::raw('nama as nama_dokumen'),
                DB::raw("IF(kategori=3, '1-3', IF(kategori=2, '1-2', '1-1')) as jenis"),
                DB::raw("IF(kategori=3, 'perdes', IF(kategori=2, 'sk_kades', 'informasi_desa_lain')) as nama_jenis"),
                'lokasi_arsip',
                DB::raw("IF(kategori=3, 'dokumen_sekretariat/perdes/3', IF(kategori=2, 'dokumen_sekretariat/perdes/2', 'dokumen')) as modul_asli"),
                'tahun',
                DB::raw("'dokumen_desa' as kategori"),
                DB::raw('NULL as lampiran'),
            ])
            ->where('id_pend', 0)
            ->whereNotNull('satuan');
    }

    /**
     * Scope daftar arsip fisik kependudukan.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArsipFisikKependudukan(mixed $query)
    {
        return $query
            ->select([
                DB::raw('dokumen_hidup.id'),
                DB::raw("'' as nomor_dokumen"),
                DB::raw('DATE(dokumen_hidup.updated_at) as tanggal_dokumen'),
                DB::raw('tweb_penduduk.nama as nama_dokumen'),
                DB::raw("CONCAT('4-', ref_syarat_surat.ref_syarat_id) as jenis"),
                DB::raw('ref_syarat_surat.ref_syarat_nama as nama_jenis'),
                DB::raw('dokumen_hidup.lokasi_arsip'),
                DB::raw("CONCAT('penduduk/dokumen/', dokumen_hidup.id_pend) as modul_asli"),
                DB::raw('EXTRACT(YEAR FROM dokumen_hidup.updated_at) as tahun'),
                DB::raw("'kependudukan' as kategori"),
                DB::raw('NULL as lampiran'),
            ])
            ->join('tweb_penduduk', 'dokumen_hidup.id_pend', '=', 'tweb_penduduk.id')
            ->join('ref_syarat_surat', 'dokumen_hidup.id_syarat', '=', 'ref_syarat_surat.ref_syarat_id')
            ->where('dokumen_hidup.id_pend', '!=', 0)
            ->whereNotNull('dokumen_hidup.satuan');
    }
}
