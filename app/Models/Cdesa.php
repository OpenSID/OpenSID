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

use App\Scopes\AccessWilayahScope;
use App\Traits\Author;
use App\Traits\ConfigId;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Cdesa extends BaseModel
{
    use ConfigId;
    use Author;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cdesa';

    protected $guarded = [];
    protected $appends = ['nama_pemilik', 'nik_pemilik', 'id_pemilik', 'alamat'];

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    public function cdesaPenduduk()
    {
        return $this->hasOne(CdesaPenduduk::class, 'id_cdesa', 'id');
    }

    public function penduduk()
    {
        return $this->hasOneThrough(PendudukSaja::class, CdesaPenduduk::class, 'id_cdesa', 'id', 'id', 'id_pend')->withoutGlobalScope(AccessWilayahScope::class);
    }

    public function cdesaMutasi()
    {
        return $this->hasMany(CdesaMutasi::class, 'id_cdesa_masuk', 'id');
    }

    public function scopeJumlahPersil($query, string $alias = 'jumlah')
    {
        $sql = <<<SQL
                    (select count(distinct persil.id)
                    from mutasi_cdesa
                    left join persil on  persil.id = mutasi_cdesa.id_persil
                    where (mutasi_cdesa.id_cdesa_masuk = cdesa.id or mutasi_cdesa.cdesa_keluar = cdesa.id or persil.cdesa_awal = cdesa.id)
                    )
                    as {$alias}
            SQL;

        return $query->selectRaw($sql);
    }

    public function scopeListCdesa($query, $kecuali = [])
    {
        $query->with(['penduduk']);

        if ($kecuali) {
            $query->whereNotIn('cdesa.id', $kecuali);
        }

        return $query->get()->map(function ($item) {
            // Mengisi nilai luas persil untuk setiap data
            $luas_persil  = $this->jumlah_luas($item->id);
            $item->basah  = $luas_persil['BASAH'];
            $item->kering = $luas_persil['KERING'];

            return $item;
        })->toArray();
    }

    // Untuk cetak daftar C-Desa, menghitung jumlah luas per kelas persil
    // Perhitungkan kasus suatu C-Desa adalah pemilik awal keseluruhan persil
    /**
     * @param mixed $id_cdesa
     *
     * @return float[]|int[]
     */
    public function jumlah_luas($id_cdesa): array
    {
        // Mengambil data persil awal
        $persil_awal = DB::table('persil as p')
            ->select('p.id', 'p.luas_persil', 'k.tipe')
            ->join('ref_persil_kelas as k', 'p.kelas', '=', 'k.id')
            ->where('p.cdesa_awal', $id_cdesa)
            ->get();

        // Membuat array untuk menyimpan luas persil berdasarkan tipe
        $persil_awal->groupBy('tipe')->mapWithKeys(static fn ($items): array => [$items->first()->tipe => $items->pluck('luas_persil', 'id')->toArray()])->toArray();

        // Mengambil data mutasi persil
        $list_mutasi = DB::table('mutasi_cdesa as m')
            ->select('m.id_persil', 'm.luas', 'm.cdesa_keluar', 'k.tipe')
            ->join('persil as p', 'p.id', '=', 'm.id_persil')
            ->join('ref_persil_kelas as k', 'p.kelas', '=', 'k.id')
            ->where('m.id_cdesa_masuk', $id_cdesa)
            ->orWhere('m.cdesa_keluar', $id_cdesa)
            ->get();

        // Menghitung luas persil dari mutasi
        $luas_persil_mutasi = [];

        foreach ($list_mutasi as $mutasi) {
            if ($mutasi->cdesa_keluar == $id_cdesa) {
                $luas_persil_mutasi[$mutasi->tipe][$mutasi->id_persil] = ($luas_persil_mutasi[$mutasi->tipe][$mutasi->id_persil] ?? 0) - $mutasi->luas;
            } else {
                $luas_persil_mutasi[$mutasi->tipe][$mutasi->id_persil] = ($luas_persil_mutasi[$mutasi->tipe][$mutasi->id_persil] ?? 0) + $mutasi->luas;
            }
        }

        // Menjumlahkan luas total per tipe persil
        $luas_total = [];

        foreach ($luas_persil_mutasi as $tipe => $luas) {
            $luas_total[$tipe] = array_sum($luas);
        }

        return $luas_total;
    }

    public function scopeListPersil($query, $id_cdesa)
    {
        return DB::table('persil as p')
            ->select('p.*', 'rk.kode as kelas_tanah')
            ->selectRaw("(CASE WHEN p.id_wilayah = w.id THEN CONCAT(
        (CASE WHEN w.rt != '0' THEN CONCAT('RT ', w.rt, ' / ') ELSE '' END),
        (CASE WHEN w.rw != '0' THEN CONCAT('RW ', w.rw, ' - ') ELSE '' END),
        w.dusun
    ) ELSE CASE WHEN p.lokasi IS NOT NULL THEN p.lokasi ELSE '=== Lokasi Tidak Ditemukan ===' END END) AS alamat")
            ->selectRaw('COUNT(m.id) as jml_mutasi')
            ->leftJoin('mutasi_cdesa as m', 'p.id', '=', 'm.id_persil')
            ->leftJoin('ref_persil_kelas as rk', 'p.kelas', '=', 'rk.id')
            ->leftJoin('tweb_wil_clusterdesa as w', 'w.id', '=', 'p.id_wilayah')
            ->where(static function ($query) use ($id_cdesa): void {
                $query->where('m.id_cdesa_masuk', $id_cdesa)
                    ->orWhere('m.cdesa_keluar', $id_cdesa)
                    ->orWhere('p.cdesa_awal', $id_cdesa);
            })
            ->groupBy('p.id')
            ->orderByRaw('CAST(p.nomor AS UNSIGNED), nomor_urut_bidang');
    }

    public static function cetakMutasi($id_cdesa, $tipe = '')
    {
        // Mutasi masuk
        $sql_masuk = DB::table('mutasi_cdesa as m')
            ->selectRaw('m.tanggal_mutasi, m.luas, m.cdesa_keluar as id_cdesa_keluar, p.id as id_persil, p.nomor as nopersil, p.nomor_urut_bidang, 0 as cdesa_awal, p.luas_persil, c.nomor as cdesa_masuk, 0 as cdesa_keluar, rk.kode as kelas_tanah, rm.nama as sebabmutasi')
            ->leftJoin('persil as p', 'p.id', '=', 'm.id_persil')
            ->leftJoin('ref_persil_kelas as rk', 'p.kelas', '=', 'rk.id')
            ->leftJoin('ref_persil_mutasi as rm', 'm.jenis_mutasi', '=', 'rm.id')
            ->leftJoin('cdesa as c', 'c.id', '=', 'm.cdesa_keluar')
            ->where('m.id_cdesa_masuk', $id_cdesa)
            ->where('m.jenis_mutasi', '<>', 9)
            ->where('rk.tipe', $tipe)
            ->toRawSql();

        // Mutasi keluar
        $sql_keluar = DB::table('mutasi_cdesa as m')
            ->selectRaw('m.tanggal_mutasi, m.luas, m.cdesa_keluar as id_cdesa_keluar, p.id as id_persil, p.nomor as nopersil, p.nomor_urut_bidang, 0 as cdesa_awal, p.luas_persil, 0 as cdesa_masuk, c.nomor as cdesa_keluar, rk.kode as kelas_tanah, rm.nama as sebabmutasi')
            ->leftJoin('persil as p', 'p.id', '=', 'm.id_persil')
            ->leftJoin('ref_persil_kelas as rk', 'p.kelas', '=', 'rk.id')
            ->leftJoin('ref_persil_mutasi as rm', 'm.jenis_mutasi', '=', 'rm.id')
            ->leftJoin('cdesa as c', 'c.id', '=', 'm.id_cdesa_masuk')
            ->where('m.cdesa_keluar', $id_cdesa)
            ->where('rk.tipe', $tipe)
            ->toRawSql();

        // Persil milik awal
        $sql_cdesa_awal = DB::table('persil as p')
            ->selectRaw('"" as tanggal_mutasi, 0 as luas, 0 as id_cdesa_keluar, p.id as id_persil, p.nomor as nopersil, p.nomor_urut_bidang, p.cdesa_awal, p.luas_persil, 0 as cdesa_masuk, 0 as cdesa_keluar, rk.kode as kelas_tanah, "" as sebabmutasi')
            ->leftJoin('ref_persil_kelas as rk', 'p.kelas', '=', 'rk.id')
            ->where('p.cdesa_awal', $id_cdesa)
            ->where('rk.tipe', $tipe)
            ->toRawSql();

        // Gabungkan semua query menjadi satu string SQL
        $sql = '(' . $sql_masuk . ') UNION (' . $sql_keluar . ') UNION (' . $sql_cdesa_awal . ') ORDER BY nopersil, nomor_urut_bidang, cdesa_awal DESC, tanggal_mutasi';

        // Eksekusi query UNION
        $data = DB::select($sql);

        foreach ($data as $key => $mutasi) {
            if ($id_cdesa == $mutasi->cdesa_awal && ! isset($processed[$mutasi->id_persil])) {
                // Cek kalau memiliki keseluruhan persil sekali saja untuk setiap persil
                $data[$key]->luas   = $mutasi->luas_persil;
                $data[$key]->mutasi = '<p>Memiliki keseluruhan persil sejak awal</p>';
                // Tandai persil ini sebagai sudah diproses
                $processed[$mutasi->id_persil] = true;
            } else {
                if (isset($processed[$mutasi->id_persil])) {
                    // Tidak ulangi info persil
                    $data[$key]->nopersil    = '';
                    $data[$key]->kelas_tanah = '';
                }
                $data[$key]->mutasi = self::format_mutasi($id_cdesa, (array) $mutasi);
            }
        }

        return $data;
    }

    private static function format_mutasi($id_cdesa, array $mutasi): string
    {
        $keluar = $mutasi['id_cdesa_keluar'] == $id_cdesa;
        $div    = $keluar ? 'class="out"' : null;
        $hasil  = "<p {$div}>";
        $hasil .= $mutasi['sebabmutasi'];
        $hasil .= $keluar ? ' ke C No ' . str_pad((string) $mutasi['cdesa_keluar'], 4, '0', STR_PAD_LEFT) : ' dari C No ' . str_pad((string) $mutasi['cdesa_masuk'], 4, '0', STR_PAD_LEFT);
        $hasil .= empty($mutasi['luas']) ? null : ', Seluas ' . number_format($mutasi['luas']) . ' m<sup>2</sup>, ';
        $hasil .= empty($mutasi['tanggal_mutasi']) ? null : tgl_indo_out($mutasi['tanggal_mutasi']) . '<br />';
        $hasil .= empty($mutasi['keterangan']) ? null : $mutasi['keterangan'];

        return $hasil . '</p>';
    }

    protected function getNamaPemilikAttribute()
    {
        return $this->jenis_pemilik == 1 ? ($this->penduduk?->nama ?? '-' ) : $this->nama_pemilik_luar;
    }

    protected function getNikPemilikAttribute()
    {
        return $this->jenis_pemilik == 1 ? ($this->penduduk?->nik ?? '-' ) : $this->nik_pemilik_luar;
    }

    protected function getIdPemilikAttribute()
    {
        return $this->jenis_pemilik == 1 ? ($this->penduduk?->id ?? '-' ) : '-';
    }

    protected function getAlamatAttribute()
    {
        return $this->jenis_pemilik == 1 ? ($this->penduduk?->alamatWilayah ?? '-' ) : $this->alamat_pemilik_luar;
    }
}
