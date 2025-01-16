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

use App\Enums\SasaranEnum;
use App\Traits\ConfigId;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class BantuanPeserta extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'program_peserta';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['bantuan'];

    public function bantuan()
    {
        return $this->belongsTo(Bantuan::class, 'program_id');
    }

    public function bantuanKeluarga()
    {
        return $this->belongsTo(Bantuan::class, 'program_id')->where(['sasaran' => SasaranEnum::KELUARGA]);
    }

    public function bantuanPenduduk()
    {
        return $this->belongsTo(Bantuan::class, 'program_id')->where(['sasaran' => SasaranEnum::PENDUDUK]);
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'peserta', 'nik');
    }

    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'peserta', 'no_kk');
    }

    public function rtm()
    {
        return $this->belongsTo(Rtm::class, 'peserta', 'no_kk');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'peserta', 'kode');
    }

    /**
     * Scope query untuk peserta.
     *
     * @param Builder $query
     */
    public function scopePeserta($query): void
    {
        // return $query->where('peserta', auth('jwt')->user()->penduduk->nik);
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

    /*
     * Fungsi untuk menampilkan program bantuan yang sedang diterima peserta.
     * $id => id_peserta tergantung sasaran
     * $cat => sasaran program bantuan.
     *
     * */
    // ubah ke laravel gunakan DB::
    public static function getPesertaProgram($cat, $id)
    {
        $data_program = DB::table('program_peserta as o')
            ->select(
                'p.id as id',
                'o.peserta as nik',
                'o.id as peserta_id',
                'p.nama as nama',
                'p.sdate',
                'p.edate',
                'p.ndesc',
                'p.sasaran',
                DB::raw(
                    "p.edate >= CURDATE() as status"
                )
            )
            ->join('program as p', 'p.id', '=', 'o.program_id')
            ->where('o.peserta', $id)
            ->where('p.sasaran', $cat)
            ->get();

        if (empty($data_program)) {
            return null;
        }

        $data_profil = match ($cat) {
            // Rincian Penduduk
            1 => tap(DB::table('tweb_penduduk as o')
                ->select('o.nama', 'o.foto', 'o.nik', 'w.rt', 'w.rw', 'w.dusun')
                ->join('tweb_wil_clusterdesa as w', 'w.id', '=', 'o.id_cluster')
                ->where('o.nik', $id)
                ->first(), static fn ($row) => [
                    'id'    => $id,
                    'nama'  => $row->nama . ' - ' . $row->nik,
                    'ndesc' => 'Alamat: RT ' . strtoupper($row->rt) . ' / RW ' . strtoupper($row->rw) . ' ' . strtoupper($row->dusun),
                    'foto'  => $row->foto,
                ]),
            // KK
            2 => tap(DB::table('tweb_keluarga as o')
                ->select('o.nik_kepala', 'o.no_kk', 'p.nama', 'w.rt', 'w.rw', 'w.dusun')
                ->join('tweb_penduduk as p', 'o.nik_kepala', '=', 'p.id')
                ->join('tweb_wil_clusterdesa as w', 'w.id', '=', 'p.id_cluster')
                ->where('o.no_kk', $id)
                ->first(), static fn ($row) => [
                    'id'    => $id,
                    'nama'  => 'Kepala KK : ' . $row->nama . ', NO KK: ' . $row->no_kk,
                    'ndesc' => 'Alamat: RT ' . strtoupper($row->rt) . ' / RW ' . strtoupper($row->rw) . ' ' . strtoupper($row->dusun),
                    'foto'  => '',
                ]),
            // RTM
            3 => tap(DB::table('tweb_rtm as r')
                ->select('r.id', 'r.no_kk', 'o.nama', 'o.nik', 'w.rt', 'w.rw', 'w.dusun')
                ->join('tweb_penduduk as o', 'o.id', '=', 'r.nik_kepala')
                ->join('tweb_wil_clusterdesa as w', 'w.id', '=', 'o.id_cluster')
                ->where('r.no_kk', $id)
                ->first(), static fn ($row) => [
                    'id'    => $id,
                    'nama'  => 'Kepala RTM : ' . $row->nama . ', NIK: ' . $row->nik,
                    'ndesc' => 'Alamat: RT ' . strtoupper($row->rt) . ' / RW ' . strtoupper($row->rw) . ' ' . strtoupper($row->dusun),
                    'foto'  => '',
                ]),
            // Kelompok
            4 => tap(DB::table('kelompok as k')
                ->select('k.id as id', 'k.nama as nama', 'p.nama as ketua', 'p.nik as nik', 'w.rt', 'w.rw', 'w.dusun')
                ->join('tweb_penduduk as p', 'p.id', '=', 'k.id_ketua')
                ->join('tweb_wil_clusterdesa as w', 'w.id', '=', 'p.id_cluster')
                ->where('k.id', $id)
                ->first(), static fn ($row) => [
                    'id'    => $id,
                    'nama'  => $row->nama,
                    'ndesc' => 'Ketua: ' . $row->ketua . ' [' . $row->nik . ']<br />Alamat: RT ' . strtoupper($row->rt) . ' / RW ' . strtoupper($row->rw) . ' ' . strtoupper($row->dusun),
                    'foto'  => '',
                ]),

            default => null,
        };

        return ['programkerja' => $data_program, 'profil' => $data_profil] ?? null;
    }

    public static function boot(): void
    {
        static::updating(static function ($model): void {
            static::deleteFile($model, 'kartu_peserta');
        });

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'kartu_peserta', true);
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $foto = LOKASI_DOKUMEN . $model->getOriginal($file);
            if (file_exists($foto)) {
                unlink($foto);
            }
        }
    }
}
