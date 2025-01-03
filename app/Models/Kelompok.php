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

use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Kelompok extends BaseModel
{
    use ConfigId;
    use ShortcutCache;
    use Sluggable;

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
    protected $guarded = ['id'];

    protected $append = ['kategori', 'nama_ketua'];

    public function getKategoriAttribute()
    {
        return $this->kelompokMaster->kelompok;
    }

    public function getNamaKetuaAttribute()
    {
        return $this->ketua->nama;
    }

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

    public function pengurus()
    {
        return $this->hasMany(KelompokAnggota::class, 'id_kelompok', 'id')->pengurus();
    }

    /**
     * Scope query untuk status kelompok
     *
     * @return Builder
     */
    public function scopeStatus(mixed $query, mixed $status = 1)
    {
        return $query->whereHas('ketua', static function ($q) use ($status): void {
            $q->status($status);
        });
    }

    public function scopeSlug(mixed $query, mixed $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Scope query untuk tipe kelompok
     *
     * @return Builder
     */
    public function scopeTipe(mixed $query, mixed $tipe = 'kelompok')
    {
        return $query->where("{$this->table}.tipe", $tipe);
    }

    /**
     * Scope query untuk jenis kelalamin ketua.
     *
     * @param \Illuminate\Database\Query\Builder $query
     */
    public function scopeJenisKelaminKetua($query, mixed $session = ''): void
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
     *
     * @return @return \Illuminate\Database\Eloquent\Collection
     */
    public function scopeListPenduduk($query, mixed $exKelompok = 0, mixed $pendId = 0)
    {
        $sebutanDusun = ucwords((string) setting('sebutan_dusun'));

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
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function scopeInListPenduduk($query, mixed $kelompok, mixed $pendId)
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

    public static function boot(): void
    {
        parent::boot();

        static::updating(static function ($model): void {
            static::deleteFile($model, 'logo');
        });

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'logo', true);
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $logo = LOKASI_LOGO_DESA . $model->getOriginal($file);
            if (file_exists($logo)) {
                unlink($logo);
            }
        }
    }

    public static function get_ketua_kelompok($id)
    {
        $data = DB::table('kelompok as k')
            ->select([
                'u.id',
                'u.nik',
                'u.nama',
                'k.id as id_kelompok',
                'k.nama as nama_kelompok',
                'u.tempatlahir',
                'u.tanggallahir',
                DB::raw("DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(`u.tanggallahir`)), '%Y') + 0 AS umur"),
                'd.nama as pendidikan',
                'f.nama as warganegara',
                'a.nama as agama',
                's.nama as sex',
                'wil.rt',
                'wil.rw',
                'wil.dusun',
            ])
            ->leftJoin('tweb_penduduk as u', 'u.id', '=', 'k.id_ketua')
            ->leftJoin('tweb_penduduk_pendidikan_kk as d', 'u.pendidikan_kk_id', '=', 'd.id')
            ->leftJoin('tweb_penduduk_warganegara as f', 'u.warganegara_id', '=', 'f.id')
            ->leftJoin('tweb_penduduk_agama as a', 'u.agama_id', '=', 'a.id')
            ->leftJoin('tweb_penduduk_sex as s', 's.id', '=', 'u.sex')
            ->leftJoin('tweb_wil_clusterdesa as wil', 'wil.id', '=', 'u.id_cluster')
            ->where('k.id', $id)
            ->first()->toArray();

            if ($data) {
                $data['alamat_wilayah'] = Penduduk::get_alamat_wilayah($data['id']);
            }

        return $data ?? null;
    }

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama',
                'unique' => false,
            ],
        ];
    }

    public static function slugCheck($nama, $type)
    {
        return self::whereSlug($nama)->whereTipe($type)->exists();
    }
}
