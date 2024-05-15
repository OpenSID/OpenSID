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

use App\Enums\HubunganRTMEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\SasaranEnum;
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

    public static function listIcon(): ?array
    {
        $list_icon = [];

        $file = FCPATH . 'assets/fonts/fontawesome.txt';

        if (file_exists($file)) {
            $list_icon = file_get_contents($file);
            $list_icon = explode('.', $list_icon);

            return array_map(static fn ($a): string => explode(':', $a)[0], $list_icon);
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
                return static::querys()['jumlah'][$raw_query];
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

        return cache()->rememberForever('shortcut_' . auth()->id, static function () use ($isAdmin): array {
            $activeShortcut    = self::where('status', '=', '1')->orderBy('urut')->get();
            $querys            = [];
            $querys['data']    = $activeShortcut;
            $querys['jumlah']  = [];
            $querys['mapping'] = [];
            $mapping           = collect([
                'Dusun' => Wilayah::dusun(),
                'RW'    => Wilayah::rw(),
                'RT'    => Wilayah::rt(),

                // Penduduk
                'Penduduk'           => PendudukSaja::status(),
                'Penduduk Laki-laki' => PendudukSaja::status()->where('sex', JenisKelaminEnum::LAKI_LAKI),
                'Penduduk Perempuan' => PendudukSaja::status()->where('sex', JenisKelaminEnum::PEREMPUAN),
                'Penduduk TagID'     => PendudukSaja::status()->whereNotNull('tag_id_card'),
                'Dokumen Penduduk'   => Dokumen::whereHas('penduduk', static fn ($q) => $q->withOnly([])->status())->hidup(),

                // Keluarga
                'Keluarga'        => Keluarga::status(),
                'Kepala Keluarga' => Keluarga::whereHas('kepalaKeluarga', static function ($query): void {
                    $query->status()->kepalaKeluarga();
                }),
                'Kepala Keluarga Laki-laki' => Keluarga::whereHas('kepalaKeluarga', static function ($query): void {
                    $query->status()->kepalaKeluarga()->where('sex', JenisKelaminEnum::LAKI_LAKI);
                }),
                'Kepala Keluarga Perempuan' => Keluarga::whereHas('kepalaKeluarga', static function ($query): void {
                    $query->status()->kepalaKeluarga()->where('sex', JenisKelaminEnum::PEREMPUAN);
                }),

                // RTM
                'RTM'        => Rtm::status(),
                'Kepala RTM' => Rtm::whereHas('kepalaKeluarga', static function ($query): void {
                    $query->status()->where('rtm_level', HubunganRTMEnum::KEPALA_RUMAH_TANGGA);
                }),
                'Kepala RTM Laki-laki' => Rtm::with(['kepalaKeluarga' => static function ($query): void {
                    $query->status()->where('rtm_level', HubunganRTMEnum::KEPALA_RUMAH_TANGGA)->where('sex', JenisKelaminEnum::LAKI_LAKI);
                }]),
                'Kepala RTM Perempuan' => Rtm::with(['kepalaKeluarga' => static function ($query): void {
                    $query->status()->where('rtm_level', HubunganRTMEnum::KEPALA_RUMAH_TANGGA)->where('sex', JenisKelaminEnum::PEREMPUAN);
                }]),

                // Kelompok
                'Kelompok' => Kelompok::status()->tipe(),

                // Lembaga
                'Lembaga' => Kelompok::status()->tipe('lembaga'),

                // Pembangunan
                'Pembangunan' => Pembangunan::whereNotNull('id'),

                // Pengaduan
                'Pengaduan'                   => Pengaduan::whereNotNull('id'),
                'Pengaduan Menunggu Diproses' => Pengaduan::where('status', 1),
                'Pengaduan Sedang Diproses'   => Pengaduan::where('status', 2),
                'Pengaduan Selesai Diproses'  => Pengaduan::where('status', 3),

                // Pengguna
                'Pengguna'      => User::whereNotNull('id'),
                'Grup Pengguna' => UserGrup::whereNotNull('id'),

                // Surat
                'Surat'          => LogSurat::whereNull('deleted_at'),
                'Surat Tercetak' => LogSurat::whereNull('deleted_at')
                    ->when($isAdmin->jabatan_id == kades()->id, static fn ($q) => $q->when(setting('tte') == 1, static fn ($tte) => $tte->where('tte', '=', 1))
                        ->when(setting('tte') == 0, static fn ($tte) => $tte->where('verifikasi_kades', '=', '1'))
                        ->orWhere(static function ($verifikasi): void {
                            $verifikasi->whereNull('verifikasi_operator');
                        }))
                    ->when($isAdmin->jabatan_id == sekdes()->id, static fn ($q) => $q->where('verifikasi_sekdes', '=', '1')->orWhereNull('verifikasi_operator'))
                    ->when($isAdmin == null || ! in_array($isAdmin->jabatan_id, RefJabatan::getKadesSekdes()), static fn ($q) => $q->where('verifikasi_operator', '=', '1')->orWhereNull('verifikasi_operator')),

                // Layanan Mandiri
                'Verifikasi Layanan Mandiri' => PendudukMandiri::status(),

                // Lapak
                'Produk'          => Produk::whereNotNull('id'),
                'Pelapak'         => Pelapak::whereNotNull('id'),
                'Kategori Produk' => ProdukKategori::whereNotNull('id'),

                // Bantuan
                'Bantuan'                  => Bantuan::whereNotNull('id'),
                'Bantuan Penduduk'         => Bantuan::whereSasaran(SasaranEnum::PENDUDUK),
                'Bantuan Keluarga'         => Bantuan::whereSasaran(SasaranEnum::KELUARGA),
                'Bantuan Rumah Tangga'     => Bantuan::whereSasaran(SasaranEnum::RUMAH_TANGGA),
                'Bantuan Kelompok/Lembaga' => Bantuan::whereSasaran(SasaranEnum::KELOMPOK),
            ]);

            $bantuan = Bantuan::get();
            if ($bantuan) {
                $pesertaBantuan = $bantuan->filter(static fn ($item) => $activeShortcut->where('raw_query', 'Bantuan ' . $item->nama)->count())->mapWithKeys(static fn ($item): array => [
                    'Bantuan ' . $item->nama => BantuanPeserta::where('program_id', $item->id),
                ]);

                $mapping = $mapping->merge($pesertaBantuan);
            }
            $querys['mapping'] = $mapping->keys();
            if ($activeShortcut) {
                $resultJumlah     = $activeShortcut->mapWithKeys(static fn ($item): array => [$item->raw_query => $mapping->get($item->raw_query)->count()])->toArray();
                $querys['jumlah'] = $resultJumlah;
            }

            return $querys;
        });
    }
}
