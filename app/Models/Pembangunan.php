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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property int|null $id_lokasi
 * @property array<array-key, mixed>|null $sumber_dana
 * @property string|null $judul
 * @property string|null $slug
 * @property string|null $keterangan
 * @property string|null $lokasi
 * @property string|null $lat
 * @property string|null $lng
 * @property string|null $volume
 * @property string|null $tahun_anggaran
 * @property string|null $pelaksana_kegiatan
 * @property int|null $pamong_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $foto
 * @property int|null $anggaran
 * @property int|null $perubahan_anggaran
 * @property int|null $sumber_biaya_pemerintah
 * @property int|null $sumber_biaya_provinsi
 * @property int|null $sumber_biaya_kab_kota
 * @property int|null $sumber_biaya_swadaya
 * @property int|null $sumber_biaya_jumlah
 * @property string|null $manfaat
 * @property int|null $waktu
 * @property int $satuan_waktu 1 = Hari, 2 = Minggu, 3 = Bulan, 4 = Tahun
 * @property string|null $sifat_proyek
 * @property int|null $realisasi_anggaran
 * @property int|null $silpa
 * @property-read mixed $alamat
 * @property-read string|null $lokasi_lengkap
 * @property-read mixed $max_persentase
 * @property-read string $lokasi_pemb
 * @property-read \App\Models\Pamong|null $pamong
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PembangunanDokumentasi> $pembangunanDokumentasi
 * @property-read int|null $pembangunan_dokumentasi_count
 * @property-read \App\Models\Wilayah|null $wilayah
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan slug($slug)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan status($value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan tipe($tipe = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereIdLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereManfaat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan wherePamongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan wherePelaksanaKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan wherePerubahanAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereRealisasiAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereSatuanWaktu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereSifatProyek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereSilpa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereSumberBiayaJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereSumberBiayaKabKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereSumberBiayaPemerintah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereSumberBiayaProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereSumberBiayaSwadaya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereSumberDana($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereTahunAnggaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan whereWaktu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembangunan withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Pembangunan extends BaseModel
{
    use ConfigId;
    use ShortcutCache;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pembangunan';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'id_lokasi',
        'sumber_dana',
        'judul',
        'slug',
        'keterangan',
        'lokasi',
        'lat',
        'lng',
        'volume',
        'tahun_anggaran',
        'pelaksana_kegiatan',
        'pamong_id',
        'status',
        'created_at',
        'updated_at',
        'foto',
        'anggaran',
        'perubahan_anggaran',
        'sumber_biaya_pemerintah',
        'sumber_biaya_provinsi',
        'sumber_biaya_kab_kota',
        'sumber_biaya_swadaya',
        'sumber_biaya_jumlah',
        'manfaat',
        'waktu',
        'satuan_waktu',
        'sifat_proyek',
        'realisasi_anggaran',
        'silpa',
    ];

    protected $casts = [
        'sumber_dana' => 'array',
    ];

    /**
     * The appends with the model.
     *
     * @var array
     */
    protected $appends = [
        'alamat',
        'lokasi_lengkap',
    ];

    public static function activePembangunanMap()
    {
        return self::with(['wilayah'])->get()->map(static function ($item): \Illuminate\Database\Eloquent\Model {
            $item->alamat = '=== Lokasi Tidak Ditemukan ===';
            if ($item->wilayah) {
                $alamat = $item->wilayah->rt != '0' ? 'RT ' . $item->wilayah->rt . '/' : '';
                $alamat .= $item->wilayah->rw != '0' ? 'RW ' . $item->wilayah->rw . '-' : '';
                $alamat .= $item->wilayah->dusun ?? '';

                $item->alamat = $alamat;
            }
            $item->anggaran = (string) ($item->anggaran);

            return $item;
        })->toArray();
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

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $gambar = LOKASI_GALERI . $model->getOriginal($file);
            if (file_exists($gambar)) {
                unlink($gambar);
            }
        }
    }

    public function pembangunanDokumentasi()
    {
        return $this->hasMany(PembangunanDokumentasi::class, 'id_pembangunan')->orderByRaw('CAST(persentase as UNSIGNED INTEGER)');
    }

    public function getAlamatAttribute()
    {
        return $this->lokasi ?? $this->wilayah?->dusun;
    }

    public function getLokasiLengkapAttribute(): ?string
    {
        if ($this->alamat == null) {
            return 'Lokasi tidak diketahui';
        }
        if ($this->wilayah && $this->id_lokasi == $this->wilayah->id) {
            $alamat = '';
            if ($this->wilayah->rt != '0') {
                $alamat .= 'RT ' . $this->wilayah->rt . ' / ';
            }
            if ($this->wilayah->rw != '0') {
                $alamat .= 'RW ' . $this->wilayah->rw . ' - ';
            }

            return $alamat . $this->wilayah->dusun;
        }

        return null;
    }

    public function wilayah()
    {
        return $this->hasOne(Wilayah::class, 'id', 'id_lokasi');
    }

    /**
     * Get the lokasi.
     *
     * @return string
     */
    public function getlokasiPembAttribute()
    {
        if ($this->lokasi == null) {
            return 'Dusun ' . $this->wilayah->dusun . (($this->wilayah->rw != 0) ? " - Rw {$this->wilayah->rw}" : '') . (($this->wilayah->rt != 0) ? "/RT {$this->wilayah->rw}" : '');
        }

        return $this->lokasi;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getMaxPersentaseAttribute()
    {
        if (count($this->pembangunanDokumentasi) <= 0) {
            return 'belum ada progres';
        }

        $max = $this->pembangunanDokumentasi
            ->map(static fn ($item): int => is_numeric($item->persentase) ? (int) $item->persentase : (int) str_replace('%', '', $item->persentase))
            ->max();

        if (Str::endsWith($max, '%') == false) {
            $max .= '%';
        }

        return $max;
    }

    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    public function scopeStatus($query, $value = 1)
    {
        return $query->where('status', $value);
    }

    public function scopeTipe($query, $tipe = null)
    {
        if ($tipe == 'kegiatan') {
            $query = $query->orWhereHas('pembangunanDokumentasi', static function ($query): void {
                $query->whereRaw('CAST(REPLACE(persentase, "%", "") AS SIGNED) < 100');
            });
        }

        if ($tipe == 'rencana') {
            $query = $query->whereDoesntHave('pembangunanDokumentasi');
        }

        if ($tipe == 'hasil') {
            return $query->orWhereHas('pembangunanDokumentasi', static function ($query): void {
                $query->whereRaw('CAST(REPLACE(persentase, "%", "") AS SIGNED) = 100');
            });
        }

        return $query;
    }

    public function pamong()
    {
        return $this->belongsTo(Pamong::class, 'pamong_id');
    }

    public function getPelaksanaKegiatanAttribute($value)
    {
        // Jika pamong_id kosong, kembalikan nilai asli
        if (! $this->pamong_id) {
            return $value;
        }

        // Jika relasi pamong tidak ditemukan, kembalikan nilai asli
        if (! $this->pamong) {
            return $value;
        }

        // Prioritas: pamong_nama > nama penduduk > nilai asli
        return $this->pamong->pamong_nama
            ?? $this->pamong->penduduk?->nama
            ?? $value;
    }
}
