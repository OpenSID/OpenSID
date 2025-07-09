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

use App\Traits\ConfigIdNull;
use App\Traits\ShortcutCache;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Bantuan extends BaseModel
{
    use ShortcutCache;
    use ConfigIdNull;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'program';

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
    protected $guarded = [];

    /**
     * {@inheritDoc}
     */
    protected $appends = ['status_masa_aktif'];

    /**
     * The casts with the model.
     *
     * @var array
     */
    protected $casts = [
        'sdate' => 'date',
        'edate' => 'date',
    ];

    public function getStatusMasaAktifAttribute()
    {
        return $this->sdate?->isFuture() || $this->edate?->isPast() ? 'Tidak Aktif' : 'Aktif';
    }

    public function scopeGetProgram($query, $program_id = null)
    {
        $query->withCount('peserta');
        if ($program_id === null) {
            return $query;
        }

        return $query->whereId($program_id);
    }

    public static function peserta_tidak_valid($sasaran)
    {
        $query = DB::table('program_peserta as pp')
            ->select('pp.id', 'p.nama', 'p.sasaran', 'pp.peserta', 'pp.kartu_nama')
            ->join('program as p', 'p.id', '=', 'pp.program_id')
            ->where('p.sasaran', $sasaran)
            ->whereNull('s.id')
            ->orderBy('p.sasaran')
            ->orderBy('pp.peserta');

        switch ($sasaran) {
            case '1':
                $query->leftJoin('tweb_penduduk as s', 's.nik', '=', 'pp.peserta');
                break;

            case '2':
                $query->leftJoin('tweb_keluarga as s', 's.no_kk', '=', 'pp.peserta');
                break;

            case '3':
                $query->leftJoin('tweb_rtm as s', 's.no_kk', '=', 'pp.peserta');
                break;

            case '4':
                $query->leftJoin('kelompok as s', 's.kode', '=', 'pp.peserta');
                break;

            default:
                break;
        }

        return $query->get()->toArray() ?? [];
    }

    public function scopelistProgram($query, $sasaran = 0)
    {
        if ($sasaran > 0) {
            $query->where('sasaran', $sasaran);
        } else {
            $query->select(DB::raw("CONCAT('50',id) as lap"));
        }

        return $query->select('id', 'nama', 'sasaran', 'ndesc', 'sdate', 'edate')->get()->toArray();
    }

    public static function peserta_duplikat(array $program)
    {
        return DB::table('program_peserta as pp')
            ->select('pp.peserta', DB::raw('COUNT(pp.peserta) as jumlah'), DB::raw('MAX(pp.id) as id'), DB::raw('MAX(p.nama) as nama'), DB::raw('MAX(p.sasaran) as sasaran'), DB::raw('MAX(pp.kartu_nama) as kartu_nama'))
            ->join('program as p', 'pp.program_id', '=', 'p.id')
            ->where('pp.program_id', $program['id'])
            ->groupBy('pp.peserta')
            ->havingRaw('COUNT(pp.peserta) > 1')
            ->get()
            ->toArray() ?? [];
    }

    public static function impor_program($program_id = null, $data_program = [], $ganti_program = 0)
    {
        if ($ganti_program == 1 && $program_id != null) {
            self::findOrFail($program_id)->update($data_program);
        } else {
            self::create($data_program);
            $program_id = self::latest()->first()->id;
        }

        return $program_id;
    }

    public static function cek_peserta($peserta = '', $sasaran = 1): false|array
    {
        if (in_array($peserta, [null, '-', ' ', '0'])) {
            return false;
        }

        switch ($sasaran) {
            case 1:
                // Penduduk
                $sasaran_peserta = 'NIK';
                $data            = PendudukHidup::select('id', 'nik')->where('nik', $peserta)->get()->toArray();
                break;

            case 2:
                // Keluarga
                $sasaran_peserta = 'No. KK';

                $data = PendudukHidup::leftJoin('keluarga_aktif', 'penduduk_hidup.id_kk', '=', 'keluarga_aktif.id')
                    ->select('keluarga_aktif.id', 'penduduk_hidup.nik')
                    ->where('keluarga_aktif.no_kk', $peserta)
                    ->get()
                    ->toArray();
                break;

            case 3:
                // RTM
                // no_rtm = no_kk
                $sasaran_peserta = 'No. RTM';

                $data = PendudukHidup::leftJoin('tweb_rtm', 'penduduk_hidup.id', '=', 'tweb_rtm.nik_kepala')
                    ->select('tweb_rtm.id', 'penduduk_hidup.nik')
                    ->where('tweb_rtm.no_kk', $peserta)
                    ->get()
                    ->toArray();

                break;

            case 4:
                // Kelompok
                $sasaran_peserta = 'Kode Kelompok';

                // perlu cek juga untuk tipe kelompok / lembaga ?
                $data = PendudukHidup::leftJoin('kelompok', 'penduduk_hidup.id', '=', 'kelompok.id_ketua')
                    ->select('kelompok.id', 'penduduk_hidup.nik')
                    ->where('kelompok.kode', $peserta)
                    ->get()
                    ->toArray();

                break;

            default:
                // Lainnya
                break;
        }

        return [
            'id'              => $data[0]['id'], // untuk nik, no_kk, no_rtm, kode konversi menjadi id issue #3417
            'sasaran_peserta' => $sasaran_peserta,
            'valid'           => str_replace("'", '', explode(', ', (string) sql_in_list(array_column($data, 'nik')))), // untuk daftar valid anggota keluarga
        ];
    }

    public static function impor_peserta($program_id = '', $data_peserta = [], $kosongkan_peserta = 0, $data_diubah = ''): bool
    {
        if ($kosongkan_peserta == 1) {
            BantuanPeserta::where('program_id', $program_id)->delete();
        }

        if ($data_diubah) {
            $peserta_ubah = explode(', ', ltrim((string) $data_diubah, ', '));
            BantuanPeserta::where('program_id', $program_id)->whereIn('peserta', $peserta_ubah)->delete();
        }
        if ($data_peserta == null) {
            return true;
        }
        if ($kosongkan_peserta == 1) {
            return true;
        }
        BantuanPeserta::insert($data_peserta);

        return true;
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function peserta()
    {
        return $this->hasMany(BantuanPeserta::class, 'program_id');
    }

    /**
     * Scope query untuk status bantuan
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeStatus($query, mixed $value = 1)
    {
        return $query->when($value == 1, static function ($query) {
            // Filter where 'edate' is in the past
            $query->where('edate', '<', now());
        }, static function ($query) {
            // Filter where 'edate' is in the future
            $query->where(static function ($query) {
                $query->where('edate', '>=', now());
            });
        });
    }

    /**
     * Scope config_id, dipisah untuk kebutuhan OpenKab.
     *
     * @return Builder
     */
    public function scopeConfigId(mixed $query)
    {
        return $query->where('config_id', identitas('id'))->orWhereNull('config_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(static function ($model): void {
            $model->config_id = identitas('id');
        });
    }

    public static function getPeserta($peserta_id, $sasaran)
    {
        switch ($sasaran) {
            case 1:
                // Data Penduduk; $peserta_id adalah NIK
                $data                   = self::get_penduduk($peserta_id);
                $data['alamat_wilayah'] = Penduduk::get_alamat_wilayah($data);
                $data['kartu_nik']      = $data['id_peserta'] = $data['nik']; /// NIK Penduduk digunakan sebagai peserta
                $data['judul_nik']      = 'NIK Penduduk';
                $data['judul']          = 'Penduduk';
                break;

            case 2:
                // Data Penduduk; $peserta_id adalah NIK
                // NIK bisa untuk anggota keluarga, belum tentu kepala KK
                $data = self::get_penduduk($peserta_id);
                // Data KK
                $kk              = self::get_kk($data['id_kk']);
                $data['no_kk']   = $data['id_peserta'] = $kk['no_kk']; // No KK digunakan sebagai peserta
                $data['nik_kk']  = $kk['nik_kk'];
                $data['nama_kk'] = $kk['nama_kk'];

                $data['alamat_wilayah'] = Penduduk::get_alamat_wilayah($kk);
                $data['kartu_nik']      = $data['nik'];
                $data['judul_nik']      = 'NIK Penduduk';
                $data['judul']          = 'Peserta';
                break;

            case 3:
                // Data Penduduk; $peserta_id adalah No RTM (kolom no_kk)
                // sesuaikan fungsi kode dari modul lain ke sini/orm
                // nanti test debug samakan data yg didapat
                $data                    = Rtm::get_kepala_rtm($peserta_id, true);
                $data['id_peserta']      = $data['no_kk']; // No RTM digunakan sebagai peserta
                $data['nama_kepala_rtm'] = $data['nama'];
                $data['kartu_nik']       = $data['nik'];
                $data['judul_nik']       = 'NIK Kepala RTM';
                $data['judul']           = 'Kepala RTM';
                break;

            case 4:
                // Data Kelompok; $peserta_id adalah id kelompok
                $data               = Kelompok::get_ketua_kelompok($peserta_id);
                $data['kartu_nik']  = $data['nik'];
                $data['id_peserta'] = $peserta_id; // Id_kelompok digunakan sebagai peserta
                $data['judul_nik']  = 'Nama Kelompok';
                $data['judul']      = 'Ketua Kelompok';
                break;

            default:
                break;
        }

        return $data;
    }

    public static function get_penduduk($peserta_id)
    {
        $data = DB::table('penduduk_hidup as p')
            ->select([
                'p.id as id',
                'p.nama',
                'p.nik',
                'p.id_kk',
                'p.id_rtm',
                'p.rtm_level',
                'x.nama as sex',
                'h.nama as hubungan',
                'p.tempatlahir',
                'p.tanggallahir',
                'a.nama as agama',
                'k.nama as pendidikan',
                'j.nama as pekerjaan',
                'w.nama as warganegara',
                'c.dusun',
                'c.rw',
                'c.rt',
            ])
            ->leftJoin('tweb_penduduk_sex as x', 'x.id', '=', 'p.sex')
            ->leftJoin('tweb_penduduk_hubungan as h', 'h.id', '=', 'p.kk_level')
            ->leftJoin('tweb_penduduk_agama as a', 'a.id', '=', 'p.agama_id')
            ->leftJoin('tweb_penduduk_pendidikan_kk as k', 'k.id', '=', 'p.pendidikan_kk_id')
            ->leftJoin('tweb_penduduk_pekerjaan as j', 'j.id', '=', 'p.pekerjaan_id')
            ->leftJoin('tweb_penduduk_warganegara as w', 'w.id', '=', 'p.warganegara_id')
            ->leftJoin('tweb_wil_clusterdesa as c', 'c.id', '=', 'p.id_cluster')
            ->where(static function ($query) use ($peserta_id): void {
                $query->where('p.nik', $peserta_id)
                    ->orWhere('p.id', $peserta_id);
            })
            ->first();

        if ($data) {
            // add umur with helper
            return collect($data)->merge([
                'umur' => umur($data->tanggallahir),
            ])->toArray();
        }

        return null;
    }

    public static function get_kk($id_kk)
    {
        $data = DB::table('keluarga_aktif as k')
            ->select([
                'k.no_kk',
                'p.nik as nik_kk',
                'p.nama as nama_kk',
                'k.alamat',
                'c.*',
            ])
            ->leftJoin('penduduk_hidup as p', 'p.id', '=', 'k.nik_kepala')
            ->leftJoin('tweb_wil_clusterdesa as c', 'c.id', '=', 'k.id_cluster')
            ->where(static function ($query) use ($id_kk): void {
                $query->where('k.no_kk', $id_kk)
                    ->orWhere('k.id', $id_kk);
            })
            ->first();

        return collect($data)->toArray();
    }

    public static function getProgramPeserta($slug): array
    {
        // Untuk program bantuan, $slug berbentuk '50<program_id>'s
        $slug    = preg_replace('/^50/', '', $slug);
        $program = self::get_program_data($slug);
        $peserta = self::get_data_peserta($program, $slug);

        $filter = array_column(is_array($peserta) ? $peserta : [], 'peserta') ?? [];

        switch ($program['sasaran']) {
            case 1:
                $penduduk = self::get_pilihan_penduduk($filter);
                break;

            case 2:
                $penduduk = self::get_pilihan_kk($filter);
                break;

            case 3:
                $penduduk = self::get_pilihan_rumah_tangga($filter);
                break;

            case 4:
                $penduduk = self::get_pilihan_kelompok($filter);
                break;

            default:
        }

        return ['detail' => $program, 'peserta' => $peserta, 'penduduk' => $penduduk];
    }

    private static function get_pilihan_kk(array $filter)
    {
        // Daftar keluarga, tidak termasuk keluarga yang sudah menjadi peserta
        $data = DB::table('penduduk_hidup as p')
            ->select([
                'k.no_kk',
                'p.nama',
                'p.nik',
                'h.nama as kk_level',
                'w.dusun',
                'w.rw',
                'w.rt',
            ])
            ->leftJoin('tweb_penduduk_hubungan as h', 'h.id', '=', 'p.kk_level')
            ->leftJoin('keluarga_aktif as k', 'k.id', '=', 'p.id_kk')
            ->leftJoin('tweb_wil_clusterdesa as w', 'w.id', '=', 'k.id_cluster')
            ->whereIn('p.kk_level', ['1', '2', '3', '4'])
            ->where('k.no_kk', '!=', 'null')
            ->orderBy('p.id_kk')
            ->get();

        if ($data) {
            return collect($data)->filter(static fn ($item): bool => ! in_array($item->no_kk, $filter))->map(static fn ($item): array => [
                'id'   => $item->nik,
                'nik'  => $item->nik,
                'nama' => strtoupper('KK[' . $item->no_kk . '] - [' . $item->kk_level . '] ' . $item->nama . ' [' . $item->nik . ']'),
                'info' => 'RT/RW ' . $item->rt . '/' . $item->rw . '  ' . self::dusun($item->dusun),
            ])->toArray();
        }

        return [];
    }

    private static function get_pilihan_penduduk(array $filter)
    {
        $data = DB::table('penduduk_hidup as p')
            ->select([
                'p.nik',
                'p.nama',
                'w.rt',
                'w.rw',
                'w.dusun',
            ])
            ->leftJoin('tweb_wil_clusterdesa as w', 'w.id', '=', 'p.id_cluster')
            ->orderBy('p.nama')
            ->get();

        if ($data) {
            return collect($data)->filter(static fn ($item): bool => ! in_array($item->no_kk, $filter))->map(static fn ($item): array => [
                'id'   => $item->nik,
                'nik'  => $item->nik,
                'nama' => strtoupper($item->nama) . ' [' . $item->nik . ']',
                'info' => 'RT/RW ' . $item->rt . '/' . $item->rw . '  ' . self::dusun($item->dusun),
            ])->toArray();
        }

        return [];
    }

    private static function get_pilihan_rumah_tangga(array $filter)
    {
        // Data RTM
        $data = DB::table('tweb_rtm as r')
            ->select([
                'r.no_kk as id',
                'o.nama',
                'w.rt',
                'w.rw',
                'w.dusun',
            ])
            ->leftJoin('tweb_penduduk as o', 'o.id', '=', 'r.nik_kepala')
            ->leftJoin('tweb_wil_clusterdesa as w', 'w.id', '=', 'o.id_cluster')
            ->get();

        if ($data) {
            return collect($data)->filter(static fn ($item): bool => ! in_array($item->id, $filter))->map(static fn ($item): array => [
                'id'   => $item->id,
                'nik'  => $item->id,
                'nama' => strtoupper($item->nama) . ' [' . $item->id . ']',
                'info' => 'RT/RW ' . $item->rt . '/' . $item->rw . '  ' . self::dusun($item->dusun),
            ])->toArray();
        }

        return [];
    }

    private static function get_pilihan_kelompok(array $filter)
    {
        // Data Kelompok
        $data = DB::table('kelompok as k')
            ->select([
                'k.id',
                'k.nama as nama_kelompok',
                'o.nama',
                'w.rt',
                'w.rw',
                'w.dusun',
            ])
            ->leftJoin('tweb_penduduk as o', 'o.id', '=', 'k.id_ketua')
            ->leftJoin('tweb_wil_clusterdesa as w', 'w.id', '=', 'o.id_cluster')
            ->get();

        if ($data) {
            return collect($data)->filter(static fn ($item): bool => ! in_array($item->id, $filter))->map(static fn ($item): array => [
                'id'   => $item->id,
                'nik'  => $item->nama_kelompok,
                'nama' => strtoupper($item->nama) . ' [' . $item->nama_kelompok . ']',
                'info' => 'RT/RW ' . $item->rt . '/' . $item->rw . '  ' . self::dusun($item->dusun),
            ])->toArray();
        }

        return [];
    }

    public static function get_program_data($slug)
    {
        $hasil0 = self::where('id', $slug)->first()?->toArray() ?? show_404();

        switch ($hasil0['sasaran']) {
            case 1:
                // Data penduduk
                $hasil0['judul_peserta']      = 'NIK';
                $hasil0['judul_peserta_plus'] = 'No. KK';
                $hasil0['judul_peserta_info'] = 'Nama Penduduk';
                $hasil0['judul_cari_peserta'] = 'NIK / Nama Penduduk';
                break;

            case 2:
                // Data KK
                $hasil0['judul_peserta']      = 'No. KK';
                $hasil0['judul_peserta_plus'] = 'NIK';
                $hasil0['judul_peserta_info'] = 'Kepala Keluarga';
                $hasil0['judul_cari_peserta'] = 'No. KK / Nama Kepala Keluarga';
                break;

            case 3:
                // Data RTM
                $hasil0['judul_peserta']      = 'No. Rumah Tangga';
                $hasil0['judul_peserta_info'] = 'Kepala Rumah Tangga';
                $hasil0['judul_cari_peserta'] = 'No. RT / Nama Kepala Rumah Tangga';
                break;

            case 4:
                // Data Kelompok
                $hasil0['judul_peserta']      = 'Nama Kelompok';
                $hasil0['judul_peserta_info'] = 'Ketua Kelompok';
                $hasil0['judul_cari_peserta'] = 'Nama Kelompok / Nama Kepala Keluarga';
        }

        return $hasil0;
    }

    public static function get_data_peserta(array $hasil0, string $slug)
    {
        $query = self::get_peserta_sql($slug, $hasil0['sasaran']);

        return match ($hasil0['sasaran']) {
            1       => self::get_data_peserta_penduduk($query),
            2       => self::get_data_peserta_kk($query),
            3       => self::get_data_peserta_rumah_tangga($query),
            4       => self::get_data_peserta_kelompok($query),
            default => null,
        };
    }

    public static function get_peserta_sql(string $slug, $sasaran, bool $jumlah = false)
    {
        $query = DB::table('program_peserta as p');

        switch ($sasaran) {
            case 1:
                // Data penduduk
                if (! $jumlah) {
                    $select_sql = [
                        'p.*',
                        'o.nama',
                        's.nama as status_dasar',
                        'x.nama as sex',
                        'w.rt',
                        'w.rw',
                        'w.dusun',
                        'k.no_kk',
                    ];
                }

                $query->select($select_sql)
                    ->rightJoin('tweb_penduduk as o', 'p.peserta', '=', 'o.nik')
                    ->leftJoin('tweb_status_dasar as s', 'o.status_dasar', '=', 's.id')
                    ->leftJoin('tweb_penduduk_sex as x', 'x.id', '=', 'o.sex')
                    ->leftJoin('tweb_keluarga as k', 'k.id', '=', 'o.id_kk')
                    ->leftJoin('tweb_wil_clusterdesa as w', 'w.id', '=', 'o.id_cluster');
                break;

            case 2:
                // Data KK
                if (! $jumlah) {
                    $select_sql = [
                        'p.*',
                        'p.peserta as nama',
                        'k.nik_kepala',
                        'k.no_kk',
                        'o.nik as nik_kk',
                        'o.nama as nama_kk',
                        'x.nama as sex',
                        'w.rt',
                        'w.rw',
                        'w.dusun',
                        's.nama as status_dasar',
                    ];
                }

                $query->select($select_sql)
                    ->join('tweb_keluarga as k', 'p.peserta', '=', 'k.no_kk')
                    ->rightJoin('tweb_penduduk as o', 'k.nik_kepala', '=', 'o.id')
                    ->leftJoin('tweb_status_dasar as s', 'o.status_dasar', '=', 's.id')
                    ->rightJoin('tweb_penduduk as kartu', 'p.kartu_id_pend', '=', 'kartu.id')
                    ->leftJoin('tweb_penduduk_sex as x', 'x.id', '=', 'kartu.sex')
                    ->leftJoin('tweb_wil_clusterdesa as w', 'w.id', '=', 'o.id_cluster');
                break;

            case 3:
                // Data RTM
                if (! $jumlah) {
                    $select_sql = [
                        'p.*',
                        'o.nama',
                        'o.nik',
                        'r.no_kk',
                        'x.nama as sex',
                        'w.rt',
                        'w.rw',
                        'w.dusun',
                        's.nama as status_dasar',
                    ];
                }

                $query->select($select_sql)
                    ->leftJoin('tweb_rtm as r', 'r.no_kk', '=', 'p.peserta')
                    ->rightJoin('tweb_penduduk as o', 'o.id', '=', 'r.nik_kepala')
                    ->leftJoin('tweb_status_dasar as s', 'o.status_dasar', '=', 's.id')
                    ->leftJoin('tweb_penduduk_sex as x', 'x.id', '=', 'o.sex')
                    ->leftJoin('tweb_wil_clusterdesa as w', 'w.id', '=', 'o.id_cluster');
                break;

            case 4:
                // Data Kelompok
                if (! $jumlah) {
                    $select_sql = [
                        'p.*',
                        'o.nama',
                        'o.nik',
                        'x.nama as sex',
                        'k.no_kk',
                        'r.nama as nama_kelompok',
                        'w.rt',
                        'w.rw',
                        'w.dusun',
                        's.nama as status_dasar',
                    ];
                }

                $query->select($select_sql)
                    ->leftJoin('kelompok as r', 'r.id', '=', 'p.peserta')
                    ->rightJoin('tweb_penduduk as o', 'o.id', '=', 'r.id_ketua')
                    ->leftJoin('tweb_status_dasar as s', 'o.status_dasar', '=', 's.id')
                    ->leftJoin('tweb_penduduk_sex as x', 'x.id', '=', 'o.sex')
                    ->leftJoin('tweb_keluarga as k', 'k.id', '=', 'o.id_kk')
                    ->leftJoin('tweb_wil_clusterdesa as w', 'w.id', '=', 'o.id_cluster');
                break;

            default:
                break;
        }
        $query->where('p.program_id', $slug);

        return $query->get() ?? [];
    }

    private static function get_data_peserta_penduduk($data)
    {
        if ($data) {
            return collect($data)->map(static function ($item) {
                $item->nik          = $item->peserta;
                $item->peserta_plus = $item->no_kk ?? '-';
                $item->peserta_nama = $item->peserta;
                $item->peserta_info = $item->nama;
                $item->nama         = strtoupper($item->nama);
                $item->info         = 'RT/RW ' . $item->rt . '/' . $item->rw . '  ' . self::dusun($item->dusun);

                return $item;
            })->toArray();
        }

        // return collection
        return [];
    }

    private static function get_data_peserta_kk($data)
    {
        // Data KK
        if ($data) {
            return collect($data)->map(static function ($item) {
                $item->nik          = $item->peserta;
                $item->peserta_plus = $item->nik_kk;
                $item->peserta_nama = $item->no_kk;
                $item->peserta_info = $item->nama_kk;
                $item->nama         = strtoupper($item->nama);
                $item->info         = 'RT/RW ' . $item->rt . '/' . $item->rw . '  ' . self::dusun($item->dusun);

                return $item;
            })->toArray();
        }

        return [];
    }

    private static function get_data_peserta_rumah_tangga($data)
    {
        // Data RTM
        if ($data) {
            return collect($data)->map(static function ($item) {
                $item->nik          = $item->peserta;
                $item->peserta_nama = $item->no_kk;
                $item->peserta_info = $item->nama_kk;
                $item->nama         = strtoupper($item->nama) . ' [' . $item->nik . ' - ' . $item->no_kk . ']';
                $item->info         = 'RT/RW ' . $item->rt . '/' . $item->rw . '  ' . self::dusun($item->dusun);

                return $item;
            })->toArray();
        }

        return [];
    }

    private static function get_data_peserta_kelompok($data)
    {
        // Data Kelompok
        if ($data) {
            return collect($data)->map(static function ($item) {
                $item->nik          = $item->nama_kelompok;
                $item->peserta_nama = $item->nama_kelompok;
                $item->peserta_info = $item->nama;
                $item->nama         = strtoupper($item->nama);
                $item->info         = 'RT/RW ' . $item->rt . '/' . $item->rw . '  ' . self::dusun($item->dusun);

                return $item;
            })->toArray();
        }

        return [];
    }

    private static function dusun(?string $nama_dusun = null): string
    {
        return (setting('sebutan_dusun') == '-') ? '' : ucwords(strtolower(setting('sebutan_dusun') . ' ' . $nama_dusun));
    }

    // relasi ke program_peserta
    public function peserta_bantuan()
    {
        return $this->hasMany(BantuanPeserta::class, 'program_id');
    }
}
