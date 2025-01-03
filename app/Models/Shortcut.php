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

use App\Enums\HubunganRTMEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\SasaranEnum;
use App\Enums\StatusEnum;
use App\Libraries\ShortcutModule;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Exception;
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

    protected $appends = [
        'link',
        'akses',
    ];

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

    public function getModuleData($key)
    {
        $raw_query = $this->attributes['raw_query'];

        return static::querys()['modules'][$raw_query][$key] ?? null;
    }

    public function getLinkAttribute()
    {
        return $this->getModuleData('link');
    }

    public function getAksesAttribute()
    {
        return $this->getModuleData('akses');
    }

    public function getCountAttribute()
    {
        try {
            return $this->getModuleData('jumlah') ?? 0;
        } catch (Exception $e) {
            // Log the error for debugging
            log_message('error', "Query : {$this->attributes['raw_query']}. Error : " . $e->getMessage());

            // Return a default value on error
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

        return cache()->rememberForever('shortcut_' . ci_auth()->id, static function () use ($isAdmin): array {
            $activeShortcut = self::where('status', '=', '1')->orderBy('urut')->get();

            $shorcutModules = (new ShortcutModule())->scan();

            return [
                'data'    => $activeShortcut,
                'modules' => collect([
                    // Wilayah
                    'Dusun' => [
                        'link'   => 'wilayah',
                        'akses'  => 'wilayah-administratif',
                        'jumlah' => Wilayah::dusun()->count(),
                    ],

                    'RW' => [
                        'link'   => 'wilayah',
                        'akses'  => 'wilayah-administratif',
                        'jumlah' => Wilayah::rw()->count(),
                    ],

                    'RT' => [
                        'link'   => 'wilayah',
                        'akses'  => 'wilayah-administratif',
                        'jumlah' => Wilayah::rt()->count(),
                    ],

                    // Penduduk
                    'Penduduk' => [
                        'link'   => 'penduduk',
                        'akses'  => 'penduduk',
                        'jumlah' => PendudukSaja::status()->count(),
                    ],

                    'Penduduk Laki-laki' => [
                        'link'   => 'penduduk',
                        'akses'  => 'penduduk',
                        'jumlah' => PendudukSaja::status()->where('sex', JenisKelaminEnum::LAKI_LAKI)->count(),
                    ],

                    'Penduduk Perempuan' => [
                        'link'   => 'penduduk',
                        'akses'  => 'penduduk',
                        'jumlah' => PendudukSaja::status()->where('sex', JenisKelaminEnum::PEREMPUAN)->count(),
                    ],

                    'Penduduk TagID' => [
                        'link'   => 'penduduk',
                        'akses'  => 'penduduk',
                        'jumlah' => PendudukSaja::status()->whereNotNull('tag_id_card')->count(),
                    ],

                    'Dokumen Penduduk' => [
                        'link'   => 'penduduk',
                        'akses'  => 'penduduk',
                        'jumlah' => Dokumen::whereHas('penduduk', static fn ($q) => $q->withOnly([])->status())->hidup()->count(),
                    ],

                    // Keluarga
                    'Keluarga' => [
                        'link'   => 'keluarga',
                        'akses'  => 'keluarga',
                        'jumlah' => Keluarga::statusAktif()->count(),
                    ],

                    'Kepala Keluarga' => [
                        'link'   => 'keluarga',
                        'akses'  => 'keluarga',
                        'jumlah' => Keluarga::whereHas('kepalaKeluarga', static function ($query): void {
                            $query->status()->kepalaKeluarga();
                        })->count(),
                    ],

                    'Kepala Keluarga Laki-laki' => [
                        'link'   => 'keluarga',
                        'akses'  => 'keluarga',
                        'jumlah' => Keluarga::whereHas('kepalaKeluarga', static function ($query): void {
                            $query->status()->kepalaKeluarga()->where('sex', JenisKelaminEnum::LAKI_LAKI);
                        })->count(),
                    ],

                    'Kepala Keluarga Perempuan' => [
                        'link'   => 'keluarga',
                        'akses'  => 'keluarga',
                        'jumlah' => Keluarga::whereHas('kepalaKeluarga', static function ($query): void {
                            $query->status()->kepalaKeluarga()->where('sex', JenisKelaminEnum::PEREMPUAN);
                        })->count(),
                    ],

                    // RTM
                    'RTM' => [
                        'link'   => 'rtm',
                        'akses'  => 'rumah-tangga',
                        'jumlah' => Rtm::status()->count(),
                    ],

                    'Kepala RTM' => [
                        'link'   => 'rtm',
                        'akses'  => 'rumah-tangga',
                        'jumlah' => Rtm::whereHas('kepalaKeluarga', static function ($query): void {
                            $query->status()->where('rtm_level', HubunganRTMEnum::KEPALA_RUMAH_TANGGA);
                        })->count(),
                    ],

                    'Kepala RTM Laki-laki' => [
                        'link'   => 'rtm',
                        'akses'  => 'rumah-tangga',
                        'jumlah' => Rtm::with(['kepalaKeluarga' => static function ($query): void {
                            $query->status()->where('rtm_level', HubunganRTMEnum::KEPALA_RUMAH_TANGGA)->where('sex', JenisKelaminEnum::LAKI_LAKI);
                        }])->count(),
                    ],

                    'Kepala RTM Perempuan' => [
                        'link'   => 'rtm',
                        'akses'  => 'rumah-tangga',
                        'jumlah' => Rtm::with(['kepalaKeluarga' => static function ($query): void {
                            $query->status()->where('rtm_level', HubunganRTMEnum::KEPALA_RUMAH_TANGGA)->where('sex', JenisKelaminEnum::PEREMPUAN);
                        }])->count(),
                    ],

                    // Kelompok
                    'Kelompok' => [
                        'link'   => 'kelompok',
                        'akses'  => 'kelompok',
                        'jumlah' => Kelompok::status()->tipe()->count(),
                    ],

                    // Lembaga
                    'Lembaga' => [
                        'link'   => 'lembaga',
                        'akses'  => 'kelompok',
                        'jumlah' => Kelompok::status()->tipe('lembaga')->count(),
                    ],

                    // Pembangunan
                    'Pembangunan' => [
                        'link'   => 'admin_pembangunan',
                        'akses'  => 'pembangunan',
                        'jumlah' => Pembangunan::count(),
                    ],

                    // Pengaduan
                    'Pengaduan' => [
                        'link'   => 'pengaduan_admin',
                        'akses'  => 'pengaduan',
                        'jumlah' => Pengaduan::count(),
                    ],

                    'Pengaduan Menunggu Diproses' => [
                        'link'   => 'pengaduan_admin',
                        'akses'  => 'pengaduan',
                        'jumlah' => Pengaduan::where('status', 1)->count(),
                    ],

                    'Pengaduan Sedang Diproses' => [
                        'link'   => 'pengaduan_admin',
                        'akses'  => 'pengaduan',
                        'jumlah' => Pengaduan::where('status', 2)->count(),
                    ],

                    'Pengaduan Selesai Diproses' => [
                        'link'   => 'pengaduan_admin',
                        'akses'  => 'pengaduan',
                        'jumlah' => Pengaduan::where('status', 3)->count(),
                    ],

                    // Pengguna
                    'Pengguna' => [
                        'link'   => 'pengguna',
                        'akses'  => 'man_user',
                        'jumlah' => User::count(),
                    ],

                    'Grup Pengguna' => [
                        'link'   => 'pengguna',
                        'akses'  => 'man_user',
                        'jumlah' => UserGrup::count(),
                    ],

                    // Surat
                    'Surat' => [
                        'link'   => 'surat_master',
                        'akses'  => 'pengaturan-surat',
                        'jumlah' => LogSurat::whereNull('deleted_at')->count(),
                    ],

                    'Surat Tercetak' => [
                        'link'   => 'keluar',
                        'akses'  => 'arsip-layanan',
                        'jumlah' => LogSurat::whereNull('deleted_at')
                            ->when($isAdmin->jabatan_id == kades()->id, static fn ($q) => $q->when(setting('tte') == 1, static fn ($tte) => $tte->where('tte', '=', 1))
                                ->when(setting('tte') == 0, static fn ($tte) => $tte->where('verifikasi_kades', '=', '1'))
                                ->orWhere(static function ($verifikasi): void {
                                    $verifikasi->whereNull('verifikasi_operator');
                                }))
                            ->when($isAdmin->jabatan_id == sekdes()->id, static fn ($q) => $q->where('verifikasi_sekdes', '=', '1')->orWhereNull('verifikasi_operator'))
                            ->when($isAdmin == null || ! in_array($isAdmin->jabatan_id, RefJabatan::getKadesSekdes()), static fn ($q) => $q->where('verifikasi_operator', '=', '1')
                                ->orWhereNull('verifikasi_operator'))
                            ->count(),
                    ],

                    // Layanan Mandiri
                    'Verifikasi Layanan Mandiri' => [
                        'link'   => 'mandiri',
                        'akses'  => 'pendaftar-layanan-mandiri',
                        'jumlah' => PendudukMandiri::status()->count(),
                    ],

                    // Bantuan
                    'Bantuan' => [
                        'link'   => 'program_bantuan',
                        'akses'  => 'program-bantuan',
                        'jumlah' => Bantuan::count(),
                    ],

                    'Bantuan Penduduk' => [
                        'link'   => 'program_bantuan',
                        'akses'  => 'program-bantuan',
                        'jumlah' => Bantuan::whereSasaran(SasaranEnum::PENDUDUK)->count(),
                    ],

                    'Bantuan Keluarga' => [
                        'link'   => 'program_bantuan',
                        'akses'  => 'program-bantuan',
                        'jumlah' => Bantuan::whereSasaran(SasaranEnum::KELUARGA)->count(),
                    ],

                    'Bantuan Rumah Tangga' => [
                        'link'   => 'program_bantuan',
                        'akses'  => 'program-bantuan',
                        'jumlah' => Bantuan::whereSasaran(SasaranEnum::RUMAH_TANGGA)->count(),
                    ],

                    'Bantuan Kelompok/Lembaga' => [
                        'link'   => 'program_bantuan',
                        'akses'  => 'program-bantuan',
                        'jumlah' => Bantuan::whereSasaran(SasaranEnum::KELOMPOK)->count(),
                    ],
                    'Buku Peraturan di Desa (Semua)' => [
                        'link'   => 'dokumen_sekretariat/perdes/3',
                        'akses'  => 'administrasi-umum',
                        'jumlah' => DokumenHidup::peraturanDesa(3)->count(),
                    ],
                    'Buku Peraturan di Desa (Aktif)' => [
                        'link'   => 'dokumen_sekretariat/perdes/3?active=' . StatusEnum::YA,
                        'akses'  => 'administrasi-umum',
                        'jumlah' => DokumenHidup::peraturanDesa(3)->active()->count(),
                    ],
                    'Buku Peraturan di Desa (Tidak Aktif)' => [
                        'link'   => 'dokumen_sekretariat/perdes/3?active=2',
                        'akses'  => 'administrasi-umum',
                        'jumlah' => DokumenHidup::peraturanDesa(3)->nonActive()->count(),
                    ],
                    'Buku Keputusan Kepala Desa (Semua)' => [
                        'link'   => 'dokumen_sekretariat/perdes/2',
                        'akses'  => 'administrasi-umum',
                        'jumlah' => DokumenHidup::peraturanDesa(2)->count(),
                    ],
                    'Buku Keputusan Kepala Desa (Aktif)' => [
                        'link'   => 'dokumen_sekretariat/perdes/2?active=' . StatusEnum::YA,
                        'akses'  => 'administrasi-umum',
                        'jumlah' => DokumenHidup::peraturanDesa(2)->active()->count(),
                    ],
                    'Buku Keputusan Kepala Desa (Tidak Aktif)' => [
                        'link'   => 'dokumen_sekretariat/perdes/2?active=2',
                        'akses'  => 'administrasi-umum',
                        'jumlah' => DokumenHidup::peraturanDesa(2)->nonActive()->count(),
                    ],
                    'Buku Inventaris dan Kekayaan Desa (Semua)' => [
                        'link'   => 'bumindes_inventaris_kekayaan',
                        'akses'  => 'administrasi-umum',
                        'jumlah' => count(MasterInventaris::permen47(date('Y'))) ?? 0,
                    ],
                    'Buku Pemerintah Desa (Semua)' => [
                        'link'   => 'pengurus',
                        'akses'  => 'pemerintah-desa',
                        'jumlah' => Pamong::withOnly([])->count(),
                    ],
                    'Buku Pemerintah Desa (Aktif)' => [
                        'link'   => 'pengurus?status=' . Pamong::LOCK,
                        'akses'  => 'pemerintah-desa',
                        'jumlah' => Pamong::withOnly([])->status(StatusEnum::YA)->count(),
                    ],
                    'Buku Pemerintah Desa (Tidak Aktif)' => [
                        'link'   => 'pengurus?status=' . Pamong::UNLOCK,
                        'akses'  => 'pemerintah-desa',
                        'jumlah' => Pamong::withOnly([])->status(StatusEnum::TIDAK)->count(),
                    ],
                    'Buku Tanah Kas Desa (Semua)' => [
                        'link'   => 'bumindes_tanah_kas_desa',
                        'akses'  => 'administrasi-umum',
                        'jumlah' => TanahKasDesa::withOnly([])->visible()->count(),
                    ],
                    'Buku Tanah Di Desa (Semua)' => [
                        'link'   => 'bumindes_tanah_desa',
                        'akses'  => 'administrasi-umum',
                        'jumlah' => TanahDesa::withOnly([])->visible()->count(),
                    ],
                    'Buku Agenda - Surat Keluar (Semua)' => [
                        'link'   => 'surat_keluar',
                        'akses'  => 'administrasi-umum',
                        'jumlah' => SuratKeluar::count(),
                    ],
                    'Buku Agenda - Surat Masuk (Semua)' => [
                        'link'   => 'surat_masuk',
                        'akses'  => 'administrasi-umum',
                        'jumlah' => SuratMasuk::count(),
                    ],
                    'Buku Ekspedisi (Semua)' => [
                        'link'   => 'ekspedisi',
                        'akses'  => 'buku-eskpedisi',
                        'jumlah' => Ekspedisi::count(),
                    ],
                    'Buku Lembaran Desa Dan Berita Desa (Semua)' => [
                        'link'   => 'lembaran_desa',
                        'akses'  => 'administrasi-umum',
                        'jumlah' => DokumenHidup::peraturanDesa(3)->count(),
                    ],
                    'Buku Lembaran Desa Dan Berita Desa (Aktif)' => [
                        'link'   => 'lembaran_desa?status=1',
                        'akses'  => 'administrasi-umum',
                        'jumlah' => DokumenHidup::peraturanDesa(3)->active()->count(),
                    ],
                    'Buku Lembaran Desa Dan Berita Desa (Tidak Aktif)' => [
                        'link'   => 'lembaran_desa?status=2',
                        'akses'  => 'administrasi-umum',
                        'jumlah' => DokumenHidup::peraturanDesa(3)->nonActive()->count(),
                    ],
                ])->merge($shorcutModules),
            ];
        });
    }
}
