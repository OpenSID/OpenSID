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

use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\EloquentSortable\SortableTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class Shortcut extends BaseModel
{
    use ConfigId;
    use SortableTrait;
    use ShortcutCache;

    public const ACTIVE   = 1;
    public const INACTIVE = 0;
    // public const is_shortcut = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shortcut';

    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes appended to the model.
     *
     * @var array
     */
    protected $appends = ['count'];

    /**
     * {@inheritDoc}
     */
    public $sortable = [
        'order_column_name'  => 'urut',
        'sort_when_creating' => true,
    ];

    public function scopeStatus($query, $status = null)
    {
        if ($status) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public static function listIcon()
    {
        $list_icon = [];

        $file = FCPATH . 'assets/fonts/fontawesome.txt';

        if (file_exists($file)) {
            $list_icon = file_get_contents($file);
            $list_icon = explode('.', $list_icon);

            return array_map(static fn ($a) => explode(':', $a)[0], $list_icon);
        }

        return null;
    }

    public function getCountAttribute()
    {
        $raw_query   = $this->attributes['raw_query'];
        $jenis_query = $this->attributes['jenis_query'];
        $config_id   = identitas('id');

        try {
            if ($jenis_query == 0) {
                return $this->querys()['jumlah'][$raw_query];
            }

            if (preg_match('/^DB::table/i', $raw_query) && preg_match('/->count\(\)/i', $raw_query)) {
                if (! preg_match('/->where\(\'config_id\',\s*config_id\(\)\)/i', $raw_query)) {
                    $raw_query = preg_replace('/^DB::table/i', 'DB::table', $raw_query);
                    $raw_query = preg_replace('/->count\(\)/i', "->where('config_id', {$config_id})->count()", $raw_query);
                }

                return eval("return {$raw_query};");
            }

            if (preg_match('/^select/i', $raw_query)) {
                if (! preg_match('/where\s+config_id\s*=\s*config_id\(\)/i', $raw_query)) {
                    $raw_query = preg_replace('/^select/i', 'select', $raw_query);
                    $raw_query = preg_replace('/from/i', 'from', $raw_query);
                    $raw_query = preg_replace('/where/i', "where config_id = {$config_id} and", $raw_query);
                }

                return DB::statement($raw_query);
            }

            if (! class_exists($raw_query)) {
                throw new Exception("Class '{$raw_query}' not found");
            }

            return eval("return {$raw_query};");
        } catch (Exception $e) {
            log_message('error', "Query : {$raw_query}. Error : " . $e->getMessage());

            return 0;
        }
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(static function ($model): void {
            $model->urut = self::max('urut') + 1;
        });
    }

    public static function querys()
    {
        $isAdmin = get_instance()->session->isAdmin->pamong->jabatan_id;

        return cache()->rememberForever('shortcut_' . auth()->id, static function () use ($isAdmin) {
            $querys           = [];
            $querys['data']   = self::where('status', '=', '1')->orderBy('urut')->get();
            $querys['jumlah'] = [
                'Dusun' => Wilayah::dusun()->count(),
                'RW'    => Wilayah::rw()->count(),
                'RT'    => Wilayah::rt()->count(),

                // Penduduk
                'Penduduk'           => Penduduk::status()->count(),
                'Penduduk Laki-laki' => Penduduk::status()->where('sex', 1)->count(),
                'Penduduk Perempuan' => Penduduk::status()->where('sex', 2)->count(),
                'Penduduk TagID'     => Penduduk::status()->whereNotNull('tag_id_card')->count(),
                'Dokumen Penduduk'   => Penduduk::status()->withCount('dokumen')->get()->sum('dokumen_count'),

                // Keluarga
                'Keluarga'        => Keluarga::with('kepalaKeluarga')->status()->count(),
                'Kepala Keluarga' => Keluarga::with(['kepalaKeluarga' => static function ($query): void {
                    $query->status()->where('kk_level', 1);
                }])->count(),
                'Kepala Keluarga Laki-laki' => Keluarga::with(['kepalaKeluarga' => static function ($query): void {
                    $query->status()->where('kk_level', 1)->where('sex', 1);
                }])->count(),
                'Kepala Keluarga Perempuan' => Keluarga::with(['kepalaKeluarga' => static function ($query): void {
                    $query->status()->where('kk_level', 1)->where('sex', 2);
                }])->count(),

                // RTM
                'RTM'        => Rtm::status()->count(),
                'Kepala RTM' => Rtm::with(['kepalaKeluarga' => static function ($query): void {
                    $query->status()->where('rtm_level', 1);
                }])->count(),
                'Kepala RTM Laki-laki' => Rtm::with(['kepalaKeluarga' => static function ($query): void {
                    $query->status()->where('rtm_level', 1)->where('sex', 1);
                }])->count(),
                'Kepala RTM Perempuan' => Rtm::with(['kepalaKeluarga' => static function ($query): void {
                    $query->status()->where('rtm_level', 1)->where('sex', 2);
                }])->count(),

                // Kelompok
                'Kelompok' => Kelompok::status()->tipe()->count(),

                // Lembaga
                'Lembaga' => Kelompok::status()->tipe('lembaga')->count(),

                // Pembangunan
                'Pembangunan' => Pembangunan::count(),

                // Pengaduan
                'Pengaduan'                   => Pengaduan::count(),
                'Pengaduan Menunggu Diproses' => Pengaduan::where('status', 1)->count(),
                'Pengaduan Sedang Diproses'   => Pengaduan::where('status', 2)->count(),
                'Pengaduan Selesai Diproses'  => Pengaduan::where('status', 3)->count(),

                // Pengguna
                'Pengguna'      => User::count(),
                'Grup Pengguna' => UserGrup::count(),

                // Surat
                'Surat'          => LogSurat::whereNull('deleted_at')->count(),
                'Surat Tercetak' => LogSurat::whereNull('deleted_at')
                    ->when($isAdmin->jabatan_id == kades()->id, static function ($q) {
                        return $q->when(setting('tte') == 1, static fn ($tte) => $tte->where('tte', '=', 1))
                            ->when(setting('tte') == 0, static fn ($tte) => $tte->where('verifikasi_kades', '=', '1'))
                            ->orWhere(static function ($verifikasi) {
                                $verifikasi->whereNull('verifikasi_operator');
                            });
                    })
                    ->when($isAdmin->jabatan_id == sekdes()->id, static fn ($q) => $q->where('verifikasi_sekdes', '=', '1')->orWhereNull('verifikasi_operator'))
                    ->when($isAdmin == null || ! in_array($isAdmin->jabatan_id, RefJabatan::getKadesSekdes()), static fn ($q) => $q->where('verifikasi_operator', '=', '1')->orWhereNull('verifikasi_operator'))->count(),

                // Layanan Mandiri
                'Verifikasi Layanan Mandiri' => PendudukMandiri::status()->count(),

                // Lapak
                'Produk'          => Produk::count(),
                'Pelapak'         => Pelapak::count(),
                'Kategori Produk' => ProdukKategori::count(),

                // Bantuan
                'Bantuan'                  => Bantuan::count(),
                'Bantuan Penduduk'         => Bantuan::whereSasaran(1)->count(),
                'Bantuan Keluarga'         => Bantuan::whereSasaran(2)->count(),
                'Bantuan Rumah Tangga'     => Bantuan::whereSasaran(3)->count(),
                'Bantuan Kelompok/Lembaga' => Bantuan::whereSasaran(4)->count(),
            ];

            $bantuan = Bantuan::withCount('peserta')->get()->mapWithKeys(static function ($bantuan) {
                return [
                    'Bantuan ' . $bantuan->nama => $bantuan->peserta_count,
                ];
            })->toArray();

            $querys['jumlah'] = array_merge($querys['jumlah'], $bantuan);

            return $querys;
        });
    }
}
