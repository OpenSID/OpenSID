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

use App\Enums\SasaranEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Keluarga extends BaseModel
{
    use ConfigId;
    use ShortcutCache;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tweb_keluarga';

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
    protected $with = [
        'wilayah',
    ];

    protected $casts = [
        'tgl_cetak_kk' => 'date:Y-m-d',
    ];

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function kepalaKeluarga()
    {
        return $this->hasOne(Penduduk::class, 'id', 'nik_kepala')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return HasMany
     */
    public function anggota()
    {
        return $this->hasMany(Penduduk::class, 'id_kk')
            ->status(1)
            ->orderBy('kk_level')
            ->orderBy('tanggallahir')
            ->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function Wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_cluster')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    public function LogKeluarga()
    {
        return $this->hasMany(LogKeluarga::class, 'id_kk', 'id')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    /**
     * Scope query untuk status keluarga
     *
     * @return Builder
     */
    public function scopeStatus()
    {
        return static::whereHas('kepalaKeluarga', static function ($query): void {
            $query->status()->where('kk_level', '1');
        });
    }

    public function scopeLogTerakhir($query, $configId, $tgl)
    {
        $tgl    = date('Y-m-d', strtotime($tgl . ' + 1 day'));
        $sqlRaw = "select max(id) id from log_keluarga where id_kk IS NOT NULL and config_id = {$configId} and tgl_peristiwa < '{$tgl}'  group by id_kk";

        return $query->join('log_keluarga', static function ($q) use ($configId): void {
            $q->on('log_keluarga.id_kk', '=', 'tweb_keluarga.id')
                ->where('log_keluarga.config_id', '=', $configId)
                ->whereNotIn('log_keluarga.id_peristiwa', [2, 3, 4]);
        })->join(DB::raw("({$sqlRaw}) as log"), 'log.id', '=', 'log_keluarga.id');
    }

    protected static function nomerKKSementara(): int
    {
        // buat jadi orm laravel
        return self::selectRaw('RIGHT(no_kk, 5) as digit')
            ->where('no_kk', 'like', '0' . identitas('kode_desa') . '%')
            ->where('no_kk', '!=', '0')
            ->orderByRaw('RIGHT(no_kk, 5) DESC')
            ->first()->digit ?? 0;
    }

    protected static function formatNomerKKSementara(): string
    {
        // buat jadi orm laravel
        $digit = self::nomerKKSementara();

        return '0' . identitas()->kode_desa . sprintf('%05d', (int) $digit + 1);
    }

    /**
     * Get all of the bantuan for the Keluarga
     */
    public function bantuan(): HasManyThrough
    {
        return $this->hasManyThrough(Bantuan::class, BantuanPeserta::class, 'peserta', 'id', 'no_kk', 'program_id')->where('sasaran', SasaranEnum::KELUARGA);
    }

    /**
     * Get all of the suplemen for the Keluarga
     */
    public function suplemen(): HasMany
    {
        return $this->hasMany(SuplemenTerdata::class, 'id_terdata', 'id')->where('sasaran', SasaranEnum::KELUARGA);
    }

    public function analisis(): HasMany
    {
        return $this->hasMany(AnalisisRespon::class, 'id_subjek', 'id')
            ->join('analisis_indikator', 'analisis_indikator.id', '=', 'analisis_respon.id_indikator')
            ->join('analisis_master', 'analisis_master.id', '=', 'analisis_indikator.id_master')
            ->where('analisis_master.subjek_tipe', SasaranEnum::KELUARGA);
    }

    public function scopeAktif($query)
    {
        return $query->where('kepalaKeluarga.status', '=', StatusDasarEnum::HIDUP);
    }

    public function bolehHapus()
    {
        if ($this->anggota->count() > 0) {
            return false;
        }
        if ($this->kepalaKeluarga && $this->kepalaKeluarga->status_dasar != StatusDasarEnum::HIDUP) {
            return false;
        }
        if ($this->bantuan->count() > 0) {
            return false;
        }
        if ($this->suplemen->count() > 0) {
            return false;
        }

        return ! ($this->analisis->count() > 0);
    }

    public static function dataCetak($id)
    {
        $result        = [];
        $ids           = is_array($id) ? $id : [$id];
        $identitasDesa = identitas();
        $keluarga      = Keluarga::with(['kepalaKeluarga', 'anggota' => static fn ($q) => $q->orderBy('kk_level')])->whereIn('id', $ids)->get()->keyBy('id');

        foreach ($ids as $id) {
            $data = $keluarga->get($id);
            $item = [
                'id_kk'     => $id,
                'main'      => $data->anggota,
                'kepala_kk' => $data->kepalaKeluarga->toArray(),
                'desa'      => $identitasDesa,
            ];
            $result[] = $item;
        }

        return $result;
    }

    public function hapusAnggota($idPend = 0, $no_kk_sebelumnya = null): void
    {
        $pend = Penduduk::find($idPend);

        if ($pend->kk_level == SHDKEnum::KEPALA_KELUARGA) {
            $temp2['updated_by'] = auth()->id;
            $temp2['nik_kepala'] = null;
            $this->update($temp2);
        }

        $pend->no_kk_sebelumnya = $no_kk_sebelumnya; // Tidak simpan no kk kalau keluar dari keluarga
        $pend->id_kk            = null;
        $pend->kk_level         = null;
        $pend->updated_at       = date('Y-m-d H:i:s');
        $pend->updated_by       = auth()->id;
        $pend->save();

        // hapus dokumen bersama dengan kepala KK sebelumnya
        Dokumen::where('id_pend', $pend->id)->where('id_parent', '>', 0)->delete();
        // catat peristiwa keluar/pecah di log_keluarga
        $log_keluarga = [
            'id_kk'           => $this->id,
            'id_peristiwa'    => LogKeluarga::ANGGOTA_KELUARGA_PECAH,
            'tgl_peristiwa'   => date('Y-m-d H:i:s'),
            'id_pend'         => $pend->id,
            'id_log_penduduk' => null,
            'updated_by'      => auth()->id,
        ];

        LogKeluarga::create($log_keluarga);
    }

    public function log_keluarga($id = null, $id_peristiwa = 1, $id_pend = null, $id_log_penduduk = null): void
    {
        $log_keluarga = [
            'id_kk'           => $id,
            'id_peristiwa'    => $id_peristiwa,
            'tgl_peristiwa'   => date('Y-m-d H:i:s'),
            'id_pend'         => $id_pend,
            'id_log_penduduk' => $id_log_penduduk,
            'updated_by'      => auth()->id,
        ];

        LogKeluarga::create($log_keluarga);
    }

    public static function tambahKeluargaDariPenduduk($data): void
    {
        $pend = Penduduk::where('id', $data['nik_kepala'])->first();

        // Gunakan alamat penduduk sebagai alamat keluarga
        $data['alamat']     = $pend->alamat_sekarang;
        $data['id_cluster'] = $pend->id_cluster;
        $data['updated_by'] = auth()->id;

        $keluarga = Keluarga::create($data);

        $pend->id_kk    = $keluarga->id;
        $pend->kk_level = SHDKEnum::KEPALA_KELUARGA;
        $pend->status   = 1; // statusnya menjadi tetap
        $pend->save();

        $log['id_pend']    = $data['nik_kepala'];
        $log['id_cluster'] = $pend->id_cluster;
        $log['tanggal']    = date('Y-m-d H:i:s');
        LogPerubahanPenduduk::create($log);

        $log_keluarga = [
            'id_kk'           => null,
            'id_peristiwa'    => LogKeluarga::KELUARGA_BARU,
            'tgl_peristiwa'   => date('Y-m-d H:i:s'),
            'id_pend'         => null,
            'id_log_penduduk' => null,
            'updated_by'      => auth()->id,
        ];
        // Untuk statistik perkembangan keluarga
        LogKeluarga::create($log_keluarga);
    }

    public static function baru($data)
    {
        $maksud_tujuan = $data['maksud_tujuan_kedatangan'];
        unset($data['maksud_tujuan_kedatangan']);

        $tgl_lapor = rev_tgl($data['tgl_lapor'], null);
        if ($data['tgl_peristiwa']) {
            $tgl_peristiwa = rev_tgl($data['tgl_peristiwa'], null);
        } else {
            $tgl_peristiwa = rev_tgl($data['tanggallahir'], null);
        }
        unset($data['tgl_lapor'], $data['tgl_peristiwa']);

        // Simpan alamat keluarga sebelum menulis penduduk
        $data2['alamat'] = $data['alamat'];
        unset($data['alamat']);

        // Tulis penduduk baru sebagai kepala keluarga
        $data['kk_level']   = SHDKEnum::KEPALA_KELUARGA;
        $data['created_by'] = auth()->id;
        $kepalaKeluarga     = Penduduk::create($data);

        // Tulis keluarga baru
        $data2['nik_kepala'] = $kepalaKeluarga->id;
        $data2['no_kk']      = $data['no_kk'];
        $data2['id_cluster'] = $data['id_cluster'];
        $data2['updated_by'] = auth()->id;
        $keluarga            = self::create($data2);

        // Update penduduk kaitkan dengan KK
        $default['updated_at'] = date('Y-m-d H:i:s');
        $default['updated_by'] = auth()->id;
        $default['id_kk']      = $keluarga->id;
        $kepalaKeluarga->update($default);

        // Jenis peristiwa didapat dari form yang berbeda
        // Jika peristiwa lahir akan mengambil data dari field tanggal lahir
        $x = [
            'tgl_peristiwa'            => $tgl_peristiwa,
            'kode_peristiwa'           => LogPenduduk::BARU_PINDAH_MASUK,
            'tgl_lapor'                => $tgl_lapor,
            'created_by'               => auth()->id,
            'maksud_tujuan_kedatangan' => $maksud_tujuan,
        ];
        $kepalaKeluarga->log()->create($x);

        $log['id_pend']    = $kepalaKeluarga->id;
        $log['id_cluster'] = $kepalaKeluarga->id_cluster;
        $log['tanggal']    = date('Y-m-d H:i:s');
        LogPerubahanPenduduk::create($log);

        // Untuk statistik perkembangan keluarga
        $keluarga->log_keluarga($keluarga->id, LogKeluarga::KELUARGA_BARU_DATANG);
    }

    public static function tambahAnggota($data)
    {

        $penduduk = Penduduk::create($data);

        if ($foto = upload_foto_penduduk(time() . '-' . $penduduk->id . '-' . random_int(10000, 999999))) {
            $penduduk->foto = $foto;
            $penduduk->save();
        }
        $maksud_tujuan = $data['maksud_tujuan_kedatangan'];
        unset($data['maksud_tujuan_kedatangan']);
        // Jika anggota yang ditambah adalah kepala keluarga untuk kk kosong
        if ($penduduk->kk_level == SHDKEnum::KEPALA_KELUARGA) {
            self::where(['id' => $data['id_kk']])->whereNull('nik_kepala')->update(['nik_kepala' => $penduduk->id]);
        }
        // Jenis peristiwa didapat dari form yang berbeda
        // Jika peristiwa lahir akan mengambil data dari field tanggal lahir
        $x = [
            'tgl_peristiwa'            => $data['tgl_peristiwa'] . ' 00:00:00',
            'kode_peristiwa'           => $data['jenis_peristiwa'],
            'tgl_lapor'                => $data['tgl_lapor'],
            'created_by'               => auth()->id,
            'maksud_tujuan_kedatangan' => $maksud_tujuan,
        ];

        $penduduk->log()->create($x);
    }

    public static function pecahKK($id, $data)
    {
        // Buat keluarga baru
        $lama             = self::find($id);
        $baru             = $lama->replicate();
        $baru->nik_kepala = bilangan($data['nik_kepala']);
        $baru->no_kk      = bilangan($data['no_kk']);
        $baru->updated_at = date('Y-m-d H:i:s');
        $baru->updated_by = auth()->id;
        $baru->save();

        // Untuk statistik perkembangan keluarga
        $log_keluarga = [
            'id_kk'           => $baru->id,
            'id_peristiwa'    => LogKeluarga::KELUARGA_BARU,
            'tgl_peristiwa'   => date('Y-m-d H:i:s'),
            'id_pend'         => null,
            'id_log_penduduk' => null,
            'updated_by'      => auth()->id,
        ];
        LogKeluarga::create($log_keluarga);

        foreach ($lama->anggota as $anggota) {
            if ($anggota->id == $data['nik_kepala']) {
                $anggota->kk_level = SHDKEnum::KEPALA_KELUARGA;
            }
            $anggota->id_kk            = $baru->id;
            $anggota->no_kk_sebelumnya = $lama->no_kk;
            $anggota->update();
        }

        // hapus dokumen bersama dengan kepala KK sebelumnya
        Dokumen::where('id_pend', $lama->nik_kepala)->where('id_parent', '>', 0)->delete();
    }

    public function delete()
    {
        if (! $this->bolehHapus()) {
            throw new Exception("Keluarga ini (id = {$this->id} ) tidak diperbolehkan dihapus");
        }
        $noKK = $this->no_kk;
        $this->anggota->each(function ($item, $key) use ($noKK) {
            $this->hapusAnggota($item->id, $noKK);
        });
        $this->anggota()->delete();

        // Hapus peserta program bantuan sasaran keluarga, kalau ada
        BantuanPeserta::whereHas('bantuanKeluarga')->where(['peserta' => $noKK])->delete();

        // Untuk statistik perkembangan keluarga
        $this->log_keluarga($this->id, LogKeluarga::KELUARGA_HAPUS);
        parent::delete();
    }

    public function judulStatistik($tipe = 0, $nomor = 1, $sex = 0)
    {
        if ($nomor == JUMLAH) {
            $judul = ['nama' => 'JUMLAH'];
        } elseif ($nomor == BELUM_MENGISI) {
            $judul = ['nama' => 'BELUM MENGISI'];
        } elseif ($nomor == TOTAL) {
            $judul = ['nama' => 'TOTAL'];
        } else {
            switch ($tipe) {
                case 'kelas_sosial':
                    $judul = KelasSosial::find($nomor)->toArray();
                    break;

                case 'bantuan_keluarga':
                    $judul = Bantuan::find($nomor)->toArray();
                    break;
            }
        }
        if ($sex == 1) {
            $judul['nama'] .= ' - LAKI-LAKI';
        } elseif ($sex == 2) {
            $judul['nama'] .= ' - PEREMPUAN';
        }

        return $judul;
    }

    public static function validasi_data_keluarga($data)
    {
        $result = ['status' => true, 'messages' => []];
        // Sterilkan data
        $data['alamat'] = strip_tags($data['alamat']);
        if (! empty($data['id'])) {
            $kkLama = self::findOrFail($data['id']);
            if ($data['no_kk'] == $kkLama->no_kk) {
                return $result;
            } // Tidak berubah
        }
        $invalid = [];
        if (isset($data['no_kk'])) {
            if (! ctype_digit($data['no_kk'])) {
                $invalid[] = 'Nomor KK hanya berisi angka';
            }
            if (strlen($data['no_kk']) != 16 && $data['no_kk'] != '0') {
                $invalid[] = 'Nomor KK panjangnya harus 16 atau 0';
            }
            if (self::where(['no_kk' => $data['no_kk']])->exists()) {
                $invalid[] = "Nomor KK {$data['no_kk']} sudah digunakan";
            }
        }

        if (! empty($invalid)) {
            $result['status']   = false;
            $result['messages'] = implode(PHP_EOL, $invalid);

            return $result;
        }

        return $result;
    }

    public function pindah($idCluster): void
    {
        $this->update(['id_cluster' => $idCluster, 'updated_by' => auth()->id]);
        $this->pindahAnggota($idCluster);
    }

    private function pindahAnggota($idCluster): void
    {
        // Ubah dusun/rw/rt untuk semua anggota keluarga
        if (! empty($idCluster)) {
            $data['id_cluster'] = $idCluster;
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = auth()->id;

            foreach ($this->anggota as $anggota) {
                $anggota->update($data);
                $log = [
                    'id_pend'        => $anggota->id,
                    'kode_peristiwa' => 6,
                    'tgl_peristiwa'  => date('Y-m-d H:i:s'),
                ];
                $anggota->log()->upsert($log, ['kode_peristiwa', 'tgl_peristiwa', 'id_pend']);
            }
        }
    }
}
