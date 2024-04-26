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

use App\Casts\Path;
use App\Traits\Author;

defined('BASEPATH') || exit('No direct script access allowed');

class Config extends BaseModel
{
    use Author;

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
    ];

    /**
     * The appends with the model.
     *
     * @var array
     */
    protected $appends = [
        'nip_kepala_desa',
        'nama_kepala_desa',
        'path_logo',
        'path_kantor_desa',
    ];

    /**
     * The hidden with the model.
     *
     * @var array
     */
    protected $hidden = [
        'app_key',
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

    public function pamong()
    {
        return Pamong::select('pamong_nip', 'pamong_nama')->kepalaDesa()->first();
    }

    /**
     * Getter untuk path + logo desa
     *
     * @return string
     */
    public function getPathLogoAttribute()
    {
        $logo = LOKASI_LOGO_DESA . $this->attributes['logo'];

        if (empty($this->attributes['logo']) || ! file_exists(FCPATH . $logo)) {
            return 'assets/files/logo/opensid_logo.png';
        }

        return $this->attributes['logo'];
    }

    /**
     * Getter untuk path + kantor desa
     *
     * @return string
     */
    public function getPathKantorDesaAttribute()
    {
        $kantor_desa = LOKASI_LOGO_DESA . $this->attributes['kantor_desa'];

        if (empty($this->attributes['kantor_desa']) || ! file_exists(FCPATH . $kantor_desa)) {
            return 'assets/files/logo/opensid_kantor.jpg';
        }

        return $this->attributes['kantor_desa'];
    }

    public function scopeAppKey($query)
    {
        return $query->where('app_key', get_app_key());
    }

    /**
     * The "booted" method of the model.
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model): void {
            $model->app_key = get_app_key();
        });

        static::updating(static function ($model): void {
            static::deleteFile($model, 'logo');
            static::deleteFile($model, 'kantor_desa');
            static::clearCache();
        });
    }

    // Hapus cache config dan modul
    public static function clearCache(): void
    {
        cache()->forget('identitas_desa');
        hapus_cache('status_langganan');
        cache()->forget('siappakai');
        hapus_cache('_cache_modul');
    }

    public static function deleteFile($model, ?string $file): void
    {
        if ($model->isDirty($file)) {
            $logo = LOKASI_LOGO_DESA . $model->getOriginal($file);
            if (file_exists($logo)) {
                unlink($logo);
            }
        }
    }
}
