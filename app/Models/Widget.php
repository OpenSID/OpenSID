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

use App\Casts\Sebutan;
use App\Enums\StatusEnum;
use App\Traits\ConfigId;
use Spatie\EloquentSortable\SortableTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class Widget extends BaseModel
{
    use ConfigId;
    use SortableTrait;

    public const WIDGET_SISTEM  = 1;
    public const WIDGET_STATIS  = 2;
    public const WIDGET_DINAMIS = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'widget';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

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
        'enabled' => StatusEnum::TIDAK,
    ];

    /**
     * {@inheritDoc}
     */
    public $sortable = [
        'order_column_name'  => 'urut',
        'sort_when_creating' => true,
    ];

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
            $list        = $this->widget($tema->view_path . '/widgets/*.blade.php');
            $list_widget = array_merge($list_widget, $list);
        }

        return $list_widget;
    }

    /**
     * @return string[]
     */
    public function widget(mixed $lokasi): array
    {
        $this->listWidgetStatis();
        $list_widget = glob($lokasi);

        $l_widget = [];

        foreach ($list_widget as $widget) {
            $l_widget[] = $widget;
        }

        return $l_widget;
    }

    public function scopeGetSetting($query, string $widget, $opsi = '')
    {
        // Data di kolom setting dalam format json
        $data    = $query->where('isi', $widget . '.php')->first('setting');
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

    public static function updateUrutan(): void
    {
        $all  = Widget::orderBy('urut')->get();
        $urut = 1;

        foreach ($all as $w) {
            $w->update(['urut' => $urut++]);
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(static function ($model): void {
            static::deleteFile($model, 'foto');
        });

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'foto', true);
        });
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

    public function getIsiAttribute($value): string
    {
        if ($this->jenis_widget == 2 && strpos($value, '/widgets/') !== false) {
            $value = str_replace('/widgets/', '/resources/views/widgets/', $value);
        }

        if (strpos($value, '.php') !== false && strpos($value, 'blade') === false) {
            $value = preg_replace('/(?<!blade)\.php$/', '.blade.php', $value);
        }

        return str_replace('/resources/views/resources/views/', '/resources/views/', $value);
    }
}
