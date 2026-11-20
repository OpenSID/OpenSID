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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string|null $satuan
 * @property string $nama
 * @property int $enabled
 * @property string $tgl_upload
 * @property int|null $id_pend
 * @property int $kategori
 * @property string $attr
 * @property int|null $tipe
 * @property string|null $url
 * @property int|null $tahun
 * @property int|null $kategori_info_publik
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int $deleted
 * @property int|null $id_syarat
 * @property int|null $id_parent
 * @property \Illuminate\Support\Carbon $created_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property int|null $dok_warga
 * @property string|null $lokasi_arsip
 * @property string|null $keterangan
 * @property string $status
 * @property string|null $retensi_date
 * @property string|null $retensi_number
 * @property string|null $retensi_unit
 * @property string|null $published_at
 * @property int|null $id_kelompok
 * @property-read string $expired_at_formatted
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup arsipFisikDokumenDesa()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup arsipFisikKependudukan()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup dataCetak($kat = 1, ?string $tahun = '', ?string $jenis_peraturan = '')
 * @method static ?array getDokumen($id = 0, $id_pend = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup getTahun($kat)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup informasiPublik()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup nonActive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup peraturanDesa($kat, $tahun = '')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup status($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereAttr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereDokWarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereIdKelompok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereIdParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereIdPend($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereIdSyarat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereKategoriInfoPublik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereLokasiArsip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereRetensiDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereRetensiNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereRetensiUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereTglUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenHidup withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class DokumenHidup extends BaseModel
{
    use ConfigId;
    use Author;

    public const ENABLE = 1;

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dokumen_hidup';

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

    public static function listDokumen($idPenduduk)
    {
        $data    = self::where('id_pend', $idPenduduk)->where('deleted', 0)->get()->toArray();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']     = $i + 1;
            $data[$i]['hidden'] = false;

            // jika dokumen berelasi dengan dokumen kepala kk
            if (isset($data[$i]['id_parent'])) {
                $data[$i]['hidden'] = true;
            }
        }

        return $data;
    }

    public function scopePeraturanDesa($query, $kat, $tahun = '')
    {
        $query->where('kategori', $kat);

        if ($tahun != '') {
            if ($kat == 2) {
                // Filter SK Kades berdasarkan tahun dari tgl_kep_kades
                $query->whereRaw("JSON_EXTRACT(attr, '$.tgl_kep_kades') LIKE ?", ["%{$tahun}%"]);
            } elseif ($kat == 3) {
                // Filter Peraturan Desa berdasarkan tahun dari tgl_ditetapkan
                $query->whereRaw("JSON_EXTRACT(attr, '$.tgl_ditetapkan') LIKE ?", ["%{$tahun}%"]);
            }
        }

        return $query;
    }

    public function scopeStatus($query, $status)
    {
        return $query->when(! empty($status), static function ($q) use ($status): void {
            $q->where('enabled', $status);
        });
    }

    public function isActive(): bool
    {
        return $this->attributes['enabled'] == self::ENABLE;
    }

    public function scopeInformasiPublik($query)
    {
        return $query->whereNull('id_pend');
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
        $query = $query->whereNull('id_pend')->where('enabled', '1');

        if ($tahun !== null && $tahun !== '' && $tahun !== '0') {
            switch ($kat) {
                case '1':
                    // Informasi publik
                    $query->where('tahun', $tahun);
                    break;

                case '2':
                    // Filter SK Kades berdasarkan tahun dari tgl_kep_kades
                    $query->whereRaw("JSON_EXTRACT(attr, '$.tgl_kep_kades') LIKE ?", ["%{$tahun}%"]);
                    break;

                case '3':
                    // Filter Peraturan Desa berdasarkan tahun dari tgl_ditetapkan
                    $query->whereRaw("JSON_EXTRACT(attr, '$.tgl_ditetapkan') LIKE ?", ["%{$tahun}%"]);
                    break;
            }
        }

        if ($kat == 3 && $jenis_peraturan) {
            $query->whereRaw("JSON_EXTRACT(attr, '$.jenis_peraturan') = ?", [$jenis_peraturan]);
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

    public static function getDokumen($id = 0, $id_pend = null): ?array
    {
        $query = self::query();

        if ($id_pend) {
            $query->where('id_pend', $id_pend);
        }

        $row = $query->where('id', $id)->first();

        if (! $row) {
            return null;
        }

        $data = $row->toArray();

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
                DB::raw("IF(kategori=3, 'dokumen_sekretariat/peraturan', IF(kategori=2, 'dokumen_sekretariat/keputusan', 'dokumen')) as modul_asli"),
                'tahun',
                DB::raw("'dokumen_desa' as kategori"),
                DB::raw('NULL as lampiran'),
            ])
            ->whereNull('id_pend')
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
            ->leftJoin('ref_syarat_surat', 'dokumen_hidup.id_syarat', '=', 'ref_syarat_surat.ref_syarat_id')
            ->whereNotNull('dokumen_hidup.id_pend')
            ->whereNotNull('dokumen_hidup.satuan');
    }

    /**
     * Mendapatkan tanggal retensi yang diformat jika masih aktif.
     *
     * Jika tanggal retensi ada dan masih aktif, akan mengembalikan tanggal dalam format 'd F Y H:i:s'.
     * Jika dokumen sudah kadaluarsa atau tidak ada tanggal retensi, akan mengembalikan tanda '-'.
     *
     * @return string
     */
    public function getExpiredAtFormattedAttribute()
    {
        $isActive = Carbon::now()->lessThanOrEqualTo(Carbon::parse($this->retensi_date));
        if ($this->retensi_date && $isActive) {
            return Carbon::parse($this->retensi_date)->translatedFormat('d F Y H:i');
        }

        return '-';
    }
}
