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

use App\Casts\Path;
use App\Traits\Author;
use Rennokki\QueryCache\Traits\QueryCacheable;

defined('BASEPATH') || exit('No direct script access allowed');

class Config extends BaseModel
{
    use Author;
    use QueryCacheable;

    /**
     * Path upload file config
     */
    public const UPLOAD_PATH = LOKASI_LOGO_DESA;

    // forever remember cache
    public $cacheFor = -1;

    /**
     * Digunakan untuk menyimpan instance pamong
     * yang digunakan pada getter.
     */
    protected $pamong;

    /**
     * Invalidate the cache automatically
     * upon update in the database.
     *
     * @var bool
     */
    protected static $flushCacheOnUpdate = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'config';

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'app_key',
        'nama_desa',
        'kode_desa',
        'kode_desa_bps',
        'kode_pos',
        'nama_kecamatan',
        'kode_kecamatan',
        'nama_kepala_camat',
        'nip_kepala_camat',
        'nama_kabupaten',
        'kode_kabupaten',
        'nama_propinsi',
        'kode_propinsi',
        'logo',
        'lat',
        'lng',
        'zoom',
        'map_tipe',
        'path',
        'alamat_kantor',
        'email_desa',
        'telepon',
        'nomor_operator',
        'website',
        'kantor_desa',
        'warna',
        'border',
        'created_by',
        'updated_by',
        'nama_kontak',
        'hp_kontak',
        'jabatan_kontak',
    ];

    /**
     * The appends with the model.
     *
     * @var array
     */
    protected $appends = [
        'nip_kepala_desa',
        'nama_kepala_desa',
        'url_logo',
        'url_kantor_desa',
    ];

    /**
     * The hidden with the model.
     *
     * @var array
     */
    protected $hidden = [
        'app_key',
        'nama_kontak',
        'hp_kontak',
        'jabatan_kontak',
    ];

    /**
     * The casts with the model.
     *
     * @var array
     */
    protected $casts = [
        'path' => Path::class,
    ];

    /**
     * The "booted" method of the model.
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model): void {
            $model->app_key = get_app_key();
            static::updateOtomatisKelurahan($model);
        });

        static::updating(static function ($model): void {
            static::updateOtomatisKelurahan($model);
            static::deleteFile($model, 'logo');
            static::deleteFile($model, 'kantor_desa');
            static::clearCache();
        });
    }

    // Hapus cache config dan modul
    public static function clearCache(): void
    {
        // cache()->forget('identitas_desa');
        // // hapus_cache('status_langganan');
        // cache()->forget('siappakai');
        // // hapus_cache('_cache_modul');
        // cache()->forget('anjungan_aktif');
        cache()->flush();
    }

    public static function deleteFile($model, ?string $file): void
    {
        if ($model->isDirty($file)) {
            $logo = FCPATH . static::UPLOAD_PATH . $model->getOriginal($file);
            if (file_exists($logo)) {
                unlink($logo);
            }
        }
    }

    private static function updateOtomatisKelurahan($model = null): void
    {
        $sebutanDesa  = 'Desa';
        $sebutanKades = 'Kepala Desa';

        if (isKelurahan($model ? $model->kode_desa : null)) {
            $sebutanDesa  = 'Kelurahan';
            $sebutanKades = 'Lurah';
        }

        SettingAplikasi::where('key', 'sebutan_desa')->update(['value' => $sebutanDesa]);
        RefJabatan::whereJenis(RefJabatan::KADES)->update(['nama' => $sebutanKades]);
        RefJabatan::whereJenis(RefJabatan::SEKDES)->update(['nama' => 'Sekretaris ' . $sebutanKades]);
    }

    /**
     * Getter untuk nip kepala desa dari pengurus
     *
     * @return string
     */
    public function getNipKepalaDesaAttribute()
    {
        return $this->pamong()->pamong_nip;
    }

    /**
     * Getter untuk nama kepala desa dari pengurus
     *
     * @return string
     */
    public function getNamaKepalaDesaAttribute()
    {
        return $this->pamong()->pamong_nama;
    }

    /**
     * Getter untuk pamong kepala desa.
     */
    public function pamong()
    {
        if (! $this->pamong) {
            $this->pamong = Pamong::select('pamong_nip', 'pamong_nama')->kepalaDesa()->with('penduduk')->first();
        }

        return $this->pamong;
    }

    /**
     * Getter untuk url logo desa
     *
     * @return string
     */
    public function getUrlLogoAttribute()
    {
        $logo = static::UPLOAD_PATH . $this->attributes['logo'];

        if (empty($this->attributes['logo']) || ! file_exists(FCPATH . $logo)) {
            return base_url('assets/files/logo/opensid_logo.png');
        }

        return base_url($logo);
    }

    /**
     * Getter untuk url kantor desa
     *
     * @return string
     */
    public function getUrlKantorDesaAttribute()
    {
        $kantor_desa = static::UPLOAD_PATH . $this->attributes['kantor_desa'];

        if (empty($this->attributes['kantor_desa']) || ! file_exists(FCPATH . $kantor_desa)) {
            return base_url('assets/files/logo/opensid_kantor.jpg');
        }

        return base_url($kantor_desa);
    }

    public function scopeAppKey($query, $appKey = null)
    {
        return $query->where('app_key', $appKey ?? get_app_key());
    }

    // Tidak digunakan, tapi untuk mencegah error saat transisi dari path ke url
    public function getPathLogoAttribute()
    {
        return null;
    }

    public function getPathKantorDesaAttribute()
    {
        return null;
    }
}
