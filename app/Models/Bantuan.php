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
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Bantuan extends BaseModel
{
    use ShortcutCache;
    // use ConfigId;

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
     * The casts with the model.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

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

    public function peserta_tidak_valid2($sasaran)
    {
        $query = $this->config_id('pp')
            ->select('pp.id, p.nama, p.sasaran, pp.peserta, pp.kartu_nama')
            ->from('program_peserta pp')
            ->join('program p', 'p.id = pp.program_id')
            ->where('p.sasaran', $sasaran)
            ->where('s.id is NULL')
            ->order_by('p.sasaran', 'pp.peserta');

        switch ($sasaran) {
            case '1':
                $query->join('tweb_penduduk s', 's.nik = pp.peserta', 'left');
                break;

            case '2':
                $query->join('tweb_keluarga s', 's.no_kk = pp.peserta', 'left');
                break;

            case '3':
                $query->join('tweb_rtm s', 's.no_kk = pp.peserta', 'left');
                break;

            case '4':
                $query->join('kelompok s', 's.kode = pp.peserta', 'left');
                break;

            default:
                break;
        }

        return $query->get()->result_array() ?? [];
    }

    public function scopelistProgram($query, $sasaran = 0)
    {
        if ($sasaran > 0) {
            $query->where('sasaran', $sasaran);
        } else {
            $query->select(DB::raw("CONCAT('50',id) as lap"));
        }

        return $query->select('id', 'nama', 'sasaran', 'ndesc', 'sdate', 'edate', 'status')->get()->toArray();
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
        $sekarang      = $data_program['sdate'] ?? date('Y m d');
        $data_tambahan = [
            'status' => ($data_program['edate'] < $sekarang) ? 0 : 1,
            // 'config_id' => $this->config_id,
        ];

        $data_program = array_merge($data_program, $data_tambahan);

        if ($ganti_program == 1 && $program_id != null) {
            // $this->db->where('id', $program_id)->update('program', $data_program);
            // self::where('id', $program_id)->update($data_program);
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
                // $data = PendudukHidup::with(['keluarga_aktif' => static function ($query): void {
                //     $query->select('id', 'no_kk');
                // }])->where('nik', $peserta)->get()->toArray();
                // $tes = str_replace("'", '', explode(', ', sql_in_list(array_column($data, 'nik')))); // untuk daftar valid anggota keluarga
                // dd($data);
                break;

            case 2:
                // Keluarga
                $sasaran_peserta = 'No. KK';

                $data = PendudukHidup::leftJoin('keluarga_aktif', 'penduduk_hidup.id_kk', '=', 'keluarga_aktif.id')
                    ->select('keluarga_aktif.id', 'penduduk_hidup.nik')
                    ->where('keluarga_aktif.no_kk', $peserta)
                    ->get()
                    ->toArray();
                // dd($data);
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
        return $query->where('status', $value);
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
}
