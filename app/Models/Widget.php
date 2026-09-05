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

use App\Casts\Sebutan;
use App\Enums\AktifEnum;
use App\Traits\ConfigId;
use Spatie\EloquentSortable\SortableTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class Widget extends BaseModel
{
    use ConfigId;
    use SortableTrait;

    public const WIDGET_SISTEM = 1;
    public const WIDGET_STATIS = 2;

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    public $sortable = [
        'order_column_name'  => 'urut',
        'sort_when_creating' => true,
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'widget';

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
        'judul' => Sebutan::class,
    ];

    /**
     * The attributes with the model.
     *
     * @var array
     */
    protected $attributes = [
        'enabled' => AktifEnum::TIDAK_AKTIF,
    ];

    public static function updateUrutan(): void
    {
        $all  = Widget::orderBy('urut')->get();
        $urut = 1;

        foreach ($all as $w) {
            $w->update(['urut' => $urut++]);
        }
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $foto = LOKASI_GAMBAR_WIDGET . $model->getOriginal($file);
            if (file_exists($foto)) {
                unlink($foto);
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(static function ($model): void {
            if (empty($model->urut)) {
                $model->urut = self::urutMax();
            }
        });

        static::updating(static function ($model): void {
            static::deleteFile($model, 'foto');
        });

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'foto', true);
        });
    }

    public function scopeGetWidget($query, $id)
    {
        $data = $query->where('id', $id)->get()->map(static function ($item) {
            $item->judul = e($item->judul);
            $item->isi   = htmlentities($item->isi);

            return $item;
        })->toArray();

        return $data[0];
    }

    // widget statis di ambil dari folder storage/app/themes/nama_tema/widgets dan desa/themes/nama_tema/resorces/views/widgets
    /**
     * @return mixed[]
     */
    public function scopeListWidgetBaru(): array
    {
        ci()->load->helper('theme');

        $allTheme    = theme()->orderBy('sistem', 'desc')->get();
        $list_widget = [];

        foreach ($allTheme as $tema) {
            $list_widget = array_merge($list_widget, $this->widget($tema->view_path . '/widgets/*.blade.php', $tema->nama));
        }

        return $list_widget;
    }

    /**
     * @param mixed|null $tema
     *
     * @return string[]
     */
    public function widget(mixed $lokasi, $tema = null): array
    {
        $list_widget = glob($lokasi);
        $l_widget    = [];

        foreach ($list_widget as $widget) {
            if ($tema) {
                $l_widget[$tema][] = $widget;
            } else {
                $l_widget[] = $widget;
            }
        }

        return $l_widget;
    }

    public function scopeGetSetting($query, string $widget, $opsi = '')
    {
        // Data di kolom setting dalam format json
        $data    = $query->where('isi', $widget)->first('setting');
        $setting = json_decode((string) $data['setting'], true);
        if (empty($setting)) {
            return [];
        }

        return empty($opsi) ? $setting : $setting[$opsi];
    }

    public function listWidgetStatis()
    {
        return static::where('jenis_widget', 2)
            ->pluck('isi')
            ->toArray();
    }

    public function scopeJenis($query, $value)
    {
        if (empty($value)) {
            return $query->whereNotNull('jenis_widget');
        }

        if (is_array($value)) {
            return $query->whereIn('jenis_widget', $value);
        }

        return $query->where('jenis_widget', $value);
    }

    public function scopeStatus($query, $value = 1)
    {
        return $query->where('enabled', $value);
    }

    public function scopeNomorUrut($query, $id, $direction)
    {
        $data = $this->findOrFail($id);

        $currentNo = $data->urut;
        $targetNo  = ($direction == 2) ? $currentNo - 1 : $currentNo + 1;

        $query->where('urut', $targetNo)->update(['urut' => $currentNo]);

        $data->update(['urut' => $targetNo]);

        return $query;
    }

    public function scopeUrutMax($query): int|float
    {
        return $query->orderByDesc('urut')->first()->urut + 1;
    }

    public function getIsiAttribute($value): string
    {
        if ($this->jenis_widget == 2 && str_contains((string) $value, '/widgets/')) {
            $value = str_replace('/widgets/', '/resources/views/widgets/', $value);
        }

        if (str_contains((string) $value, '.php') && ! str_contains((string) $value, 'blade')) {
            $value = preg_replace('/(?<!blade)\.php$/', '.blade.php', (string) $value);
        }

        return str_replace('/resources/views/resources/views/', '/resources/views/', $value);
    }
}
