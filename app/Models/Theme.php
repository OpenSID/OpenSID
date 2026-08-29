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

use App\Traits\ConfigId;
use Illuminate\Support\Str;
use Rennokki\QueryCache\Traits\QueryCacheable;

defined('BASEPATH') || exit('No direct script access allowed');

class Theme extends BaseModel
{
    use ConfigId;
    use QueryCacheable;

    public const DEFAULT_THEME = 'esensi';
    public const PATH_SISTEM   = 'storage/app/themes/';
    public const PATH_DESA     = 'desa/themes/';

    /**
     * Nilai kolom `kategori` -- tab katalog admin `/theme` ("Umum"/"Tema Pro").
     * BEDA dari `sistem` (lokasi folder, menentukan proteksi hapus/edit +
     * urutan) -- `kategori` murni label distribusi, dibaca dari `theme.json`
     * milik paket tema (bila ada) saat `theme_scan()`, bukan dari lokasi
     * folder. Tanpa `theme.json`/field `kategori`, fallback ke default lama
     * (`sistem` ? UMUM : PREMIUM) -- lihat theme_scan() di
     * donjo-app/helpers/theme_helper.php. Padanan Premium.
     *
     * KATEGORI_PREMIUM_EKSKLUSIF: SAMA tab filter "Tema Pro" dgn
     * KATEGORI_PREMIUM -- beda HANYA di teks ribbon kartu (box.blade.php):
     * "Premium" (bukan "Tema Pro"), krn tema ini eksklusif bonus langganan
     * OpenSID Premium (mis. Wira), tak bisa dibeli satuan spt tema
     * KATEGORI_PREMIUM lain (mis. Lestari).
     */
    public const KATEGORI_UMUM              = 'umum';
    public const KATEGORI_PREMIUM           = 'premium';
    public const KATEGORI_PREMIUM_EKSKLUSIF = 'premium-eksklusif';

    /**
     * KATEGORI_MITRA: tema hasil kerja sama OpenDesa dengan pemerintah
     * kabupaten/kota (mis. Tema Tabanan, Tema Bima). Untuk gerbang akses,
     * ribbon, dan tab filter, tema ini diperlakukan SAMA seperti
     * KATEGORI_UMUM -- gratis dipasang & diaktifkan siapa pun. Bedanya HANYA:
     * (1) teks ribbon kartu ("Tema Mitra") supaya asal kemitraan terlihat,
     * (2) untuk desa yang wilayahnya cocok dengan wilayah mitra, tema ini
     *     dipasang otomatis & dijadikan default oleh
     *     App\Services\Theme\PenyelesaiTemaBawaan (mengalahkan tema default
     *     rilis). Lihat dokumentasi/MANAJEMEN_TEMA.md §7a (repo Premium) dan
     *     premium#6991 / Layanan_OpenDESA#1382. Padanan Premium.
     */
    public const KATEGORI_MITRA = 'mitra';

    public $cacheFor = -1;

    /**
     * @var mixed[]|string
     */
    public $tema;

    /**
     * @var 'desa/themes'|'vendor/themes'
     */
    public $folder;

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
    protected $table = 'theme';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends = [
        'full_path',
        'view_path',
        'asset_path',
    ];

    /**
     * The casts with the model.
     *
     * @var array
     */
    protected $casts = [
        'sistem' => 'integer',
        'status' => 'boolean',
        'opsi'   => 'json',
    ];

    private string $templateFile = 'resources/views/template.blade.php';

    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model): void {
            $model->slug = Str::slug('desa-' . $model->nama);
        });

        static::deleting(static function ($model): void {
            deleteDir($model->full_path);

            cache()->forget('theme_active');
        });
    }

    public function getFullPathAttribute()
    {
        return $this->path;
    }

    public function getViewPathAttribute(): string
    {
        return $this->getFullPathAttribute() . '/resources/views';
    }

    public function getAssetPathAttribute(): string
    {
        return $this->getFullPathAttribute() . '/assets';
    }

    public function getConfigAttribute()
    {
        if (file_exists($path = $this->full_path . '/config.json')) {
            return json_decode(file_get_contents($path), true);
        }

        return [];
    }

    public function scopeStatus($query, $status = '1')
    {
        return $query->where('status', $status);
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeIsNotActive($query)
    {
        return $query->where('status', 0);
    }

    public function scopeSistem($query, $status = '1')
    {
        return $query->where('sistem', $status);
    }

    public function setVersiAttribute($value): void
    {
        if (empty($value)) {
            $value = VERSION;
        }

        $this->attributes['versi'] = $value;
    }

    public function getVersiAttribute(string $value): string
    {
        return 'v' . $value;
    }

    public function aktif()
    {
        $aktif = self::isActive()->first();

        // Jika ada tema aktif dan file tema valid, kembalikan tema tersebut.
        if ($aktif && file_exists($aktif->full_path . '/composer.json')) {
            return $aktif;
        }

        // Jika tidak ada tema aktif yang valid, fallback ke DEFAULT_THEME
        // Nonaktifkan semua tema terlebih dahulu.
        self::whereIn('sistem', [0, 1])->get()->each(static function ($theme): void {
            $theme->update(['status' => 0]);
        });

        // Aktifkan DEFAULT_THEME (tema sistem)
        $defaultTheme = self::sistem(1)->where('slug', self::DEFAULT_THEME)->first();
        if ($defaultTheme) {
            $defaultTheme->update(['status' => 1]);
        }

        return self::isActive()->first();
    }

    // Mengambil latar belakang website ubahan
    public function latarWebsite()
    {
        $ubahan_tema   = "desa/pengaturan/{$this->tema}/images/";
        $bawaan_tema   = "{$this->folder}/{$this->tema}/assets/css/images/latar_website.jpg";
        $latar_website = is_file($ubahan_tema) ? $ubahan_tema : $bawaan_tema;

        return is_file($latar_website) ? $latar_website : null;
    }

    public function lokasiLatarWebsite(): string
    {
        $folder = "desa/pengaturan/{$this->tema}/images/";
        if (! file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        return $folder;
    }

    // Mengambil latar belakang login mandiri ubahan
    public function latarLoginMandiri()
    {
        return file_exists(FCPATH . LATAR_KEHADIRAN) ? LATAR_KEHADIRAN : DEFAULT_LATAR_KEHADIRAN;
    }
}
