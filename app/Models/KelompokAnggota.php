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

use App\Enums\JabatanKelompokEnum;
use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

class KelompokAnggota extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kelompok_anggota';

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

    protected $appends = [
        'nama_jabatan',
        'alamat_lengkap',
        'nama_penduduk',
    ];

    /**
     * Scope query untuk tipe kelompok
     *
     * @return Builder
     */
    public function scopeTipe(mixed $query, mixed $tipe = 'kelompok')
    {
        return $query->where("{$this->table}.tipe", $tipe);
    }

    public function scopePengurus($query)
    {
        return $query->where('jabatan', '!=', JabatanKelompokEnum::ANGGOTA)->orderBy('jabatan');
    }

    public function scopeAnggota($query)
    {
        return $query->where('jabatan', '=', JabatanKelompokEnum::ANGGOTA);
    }

    public function scopeSlugKelompok($query, $slug)
    {
        return $query->whereHas('kelompok', static function ($query) use ($slug) {
            $query->where('slug', $slug);
        });
    }

    public function getAlamatLengkapAttribute(): string
    {
        $sebutanDusun = ucwords((string) setting('sebutan_dusun'));
        $alamat       = "{$this->anggota->wilayah->dusun} RW {$this->anggota->wilayah->rw} RT {$this->anggota->wilayah->rt}";

        return $alamat == ' RW  RT ' ? '' : "{$sebutanDusun} {$alamat}";
    }

    public function getNamaPendudukAttribute(): string
    {
        return ucwords((string) $this->anggota->nama);
    }

    /**
     * Scope query untuk list penduduk.
     *
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder $query
     */
    public function scopeListAnggota($query, mixed $kelompokId = 0)
    {
        $sebutanDusun = ucwords((string) setting('sebutan_dusun'));

        return $this->withoutGlobalScopes()
            ->withConfigId('ka')
            ->selectRaw('ka.*, tp.nik, tp.nama, tp.tempatlahir, tp.tanggallahir, tp.sex AS id_sex, tpx.nama AS sex, tp.foto, tpp.nama as pendidikan, tpa.nama as agama')
            ->selectRaw("(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = tp.id) AS umur")
            ->selectRaw('a.dusun,a.rw,a.rt')
            ->selectRaw("CONCAT('{$sebutanDusun} ', a.dusun, ' RW ', a.rw, ' RT ', a.rt) AS alamat")
            ->selectRaw("
                (case
                    when jabatan = 1 then 'KETUA'
                    when jabatan = 2 then 'WAKIL KETUA'
                    when jabatan = 3 then 'SEKRETARIS'
                    when jabatan = 4 then 'BENDAHARA'
                    else 'ANGGOTA'
                end) as jabatan
            ")
            ->from('kelompok_anggota as ka')
            ->join('tweb_penduduk as tp', 'ka.id_penduduk', '=', 'tp.id', 'left')
            ->join('tweb_penduduk_sex as tpx', 'tp.sex', '=', 'tpx.id', 'left')
            ->join('tweb_penduduk_pendidikan_kk as tpp', 'tp.pendidikan_kk_id', '=', 'tpp.id', 'left')
            ->join('tweb_penduduk_agama as tpa', 'tp.agama_id', '=', 'tpa.id', 'left')
            ->join('tweb_wil_clusterdesa as a', 'tp.id_cluster', '=', 'a.id', 'left')
            ->where('ka.id_kelompok', $kelompokId)
            ->orderByRaw('CAST(jabatan AS UNSIGNED) + 30 - jabatan, CAST(no_anggota AS UNSIGNED)');
    }

    public function getNamaJabatanAttribute(): string
    {
        // check if jabatan is string, aware of "1"
        return is_string($this->jabatan) && ! is_numeric($this->jabatan)
            ? strtoupper($this->jabatan)
            : strtoupper((string) JabatanKelompokEnum::valueOf($this->jabatan));
    }

    public function scopeUbahJabatan($query, $id_kelompok, $id_penduduk, $jabatan, $jabatan_lama): bool
    {
        $query->where('id_kelompok', $id_kelompok);
        $kelompok = Kelompok::find($id_kelompok);

        if ($jabatan == JabatanKelompokEnum::KETUA) {
            $query->where('jabatan', '1')->update(['jabatan' => '90', 'no_sk_jabatan' => '']); // Anggota
            $kelompok->update(['id_ketua' => $id_penduduk]);
        } elseif ($jabatan_lama == JabatanKelompokEnum::KETUA) {
            // jika yang diubah adalah jabatan KETUA maka kosongkan id_ketua kelompok di tabel kelompok
            // kolom id_ketua di tabel kelompok tidak bisa NULL
            $kelompok->update(['id_ketua' => -9999]);
        }

        return true;
    }

    public function scopeCekAnggotaTerdaftar($query, $validasi = null, $data = null, $id_kelompok = 0)
    {
        return $query->where($validasi, '=', $data)->where('id_kelompok', '=', $id_kelompok)->get();
    }

    public function anggota()
    {
        return $this->hasOne(Penduduk::class, 'id', 'id_penduduk');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id');
    }

    public static function boot(): void
    {
        parent::boot();

        static::updating(static function ($model): void {
            static::deleteFile($model, 'foto');
        });

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'foto', true);
        });
    }

    public static function deleteFile($model, ?string $file, bool $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $lokasi   = $model->tipe === 'kelompok' ? LOKASI_FOTO_KELOMPOK : LOKASI_FOTO_LEMBAGA;
            $pathFile = $lokasi . $model->getOriginal($file);

            if (file_exists($pathFile)) {
                unlink($pathFile);
            }
        }
    }
}
