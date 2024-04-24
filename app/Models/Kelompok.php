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

defined('BASEPATH') || exit('No direct script access allowed');

class Kelompok extends BaseModel
{
    use ConfigId;
    use ShortcutCache;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kelompok';

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

    public function ketua()
    {
        return $this->hasOne(Penduduk::class, 'id', 'id_ketua');
    }

    public function kelompokMaster()
    {
        return $this->hasOne(KelompokMaster::class, 'id', 'id_master');
    }

    public function kelompokAnggota()
    {
        return $this->hasMany(KelompokAnggota::class, 'id_kelompok', 'id');
    }

    /**
     * Scope query untuk status kelompok
     *
     * @param mixed $query
     * @param mixed $status
     *
     * @return Builder
     */
    public function scopeStatus($query, $status = 1)
    {
        return $query->whereHas('ketua', static function ($q) use ($status): void {
            $q->status($status);
        });
    }

    /**
     * Scope query untuk tipe kelompok
     *
     * @param mixed $query
     * @param mixed $tipe
     *
     * @return Builder
     */
    public function scopeTipe($query, $tipe = 'kelompok')
    {
        return $query->where("{$this->table}.tipe", $tipe);
    }

    /**
     * Scope query untuk jenis kelalamin ketua.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param mixed                              $status
     * @param mixed                              $session
     */
    public function scopeJenisKelaminKetua($query, $session = ''): void
    {
        $query->whereHas('ketua', static function ($query) use ($session): void {
            if (! empty($session)) {
                if ($session == JUMLAH) {
                    $query->whereNotNull('sex');
                } elseif ($session == BELUM_MENGISI) {
                    $query->whereNull('sex');
                } else {
                    $query->where('sex', $session);
                }
            }
        });
    }

    /**
     * Scope query untuk penerima bantuan.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param mixed                              $status
     */
    public function scopePenerimaBantuan($query): void
    {
        // Yg berikut hanya untuk menampilkan peserta bantuan
        $penerima_bantuan = get_instance()->session->penerima_bantuan;
        if (! in_array($penerima_bantuan, [JUMLAH, BELUM_MENGISI, TOTAL])) {
            // Salin program_id
            get_instance()->session->program_bantuan = $penerima_bantuan;
        }
        if ($penerima_bantuan && $penerima_bantuan != BELUM_MENGISI && ($penerima_bantuan != JUMLAH && get_instance()->session->program_bantuan)) {
            $query
                ->join('program_peserta as bt', 'bt.peserta', '=', 'kelompok.id')
                ->join('program as rcb', 'bt.program_id', '=', 'rcb.id', 'left');
        }
        // Untuk BUKAN PESERTA program bantuan tertentu
        if ($penerima_bantuan == BELUM_MENGISI) {
            if (get_instance()->session->program_bantuan) {
                // Program bantuan tertentu
                $program_id = get_instance()->session->program_bantuan;
                $query
                    ->join('program_peserta as bt', 'bt.peserta', '=', 'kelompok.id', 'left')
                    ->where('bt.program_id', $program_id)
                    ->whereNull('bt.id');
            } else {
                // Bukan penerima bantuan apa pun
                $query
                    ->join('program_peserta as bt', 'bt.peserta', '=', 'kelompok.id', 'left')
                    ->whereNull('bt.id');
            }
        } elseif ($penerima_bantuan == JUMLAH && ! get_instance()->session->program_bantuan) {
            // Penerima bantuan mana pun
            $query->whereRaw('kelompok.id in (select peserta from program_peserta)');
        }
    }

    /**
     * Scope query untuk list penduduk.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param mixed                              $status
     * @param mixed                              $exKelompok
     * @param mixed                              $pendId
     *
     * @return @return \Illuminate\Database\Eloquent\Collection
     */
    public function scopeListPenduduk($query, $exKelompok = 0, $pendId = 0)
    {
        $sebutanDusun = ucwords(setting('sebutan_dusun'));

        $query = $this->withoutGlobalScopes()
            ->withConfigId('p')
            ->select(['p.id', 'nik', 'nama'])
            ->selectRaw("(
                case when (p.id_kk IS NULL)
                    then
                        case when (cp.dusun = '-' or cp.dusun = '')
                            then CONCAT(COALESCE(p.alamat_sekarang, ''), ' RT ', cp.rt, ' / RW ', cp.rw)
                            else CONCAT(COALESCE(p.alamat_sekarang, ''), ' {$sebutanDusun} ', cp.dusun, ' RT ', cp.rt, ' / RW ', cp.rw)
                        end
                    else
                        case when (ck.dusun = '-' or ck.dusun = '')
                            then CONCAT(COALESCE(k.alamat, ''), ' RT ', ck.rt, ' / RW ', ck.rw)
                            else CONCAT(COALESCE(k.alamat, ''), ' {$sebutanDusun} ', ck.dusun, ' RT ', ck.rt, ' / RW ', ck.rw)
                        end
                end) AS alamat")
            ->from('penduduk_hidup as p')
            ->join('tweb_wil_clusterdesa as cp', 'p.id_cluster', '=', 'cp.id', 'left')
            ->join('tweb_keluarga as k', 'p.id_kk', '=', 'k.id', 'left')
            ->join('tweb_wil_clusterdesa as ck', 'k.id_cluster', '=', 'ck.id', 'left');

        if ($exKelompok) {
            $anggota = $this->scopeInListPenduduk($query, $exKelompok, $pendId);

            if ($anggota) {
                $query = $query->whereNotIn('p.id', $anggota);
            }
        }

        return $query->get();
    }

    /**
     * Scope query untuk in list penduduk.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param mixed                              $status
     * @param mixed                              $kelompok
     * @param mixed                              $pendId
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function scopeInListPenduduk($query, $kelompok, $pendId)
    {
        $query = $this->withoutGlobalScopes()
            ->withConfigId('k')
            ->select('p.id')
            ->from('kelompok_anggota as k')
            ->join('penduduk_hidup as p', 'k.id_penduduk', '=', 'p.id', 'left')
            ->where('k.id_kelompok', $kelompok);

        if ($pendId) {
            $query = $query->whereNotIn('p.id', $pendId);
        }

        return $query->get();
    }

    public function scopeListJabatan($query, $id_kelompok = 0)
    {
        return $query->whereRaw('jabatan', 'REGEXP', '[a-zA-Z]+')->where('id_kelompok', $id_kelompok)->orderBy('jabatan')->get()->toArray();
    }

    protected static function boot()
    {
        parent::boot();
    }
}
