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

use App\Enums\StatusEnum;
use App\Traits\ConfigId;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property int|null $id_kelompok
 * @property string|null $satuan
 * @property string $nama
 * @property int $enabled
 * @property string $tgl_upload
 * @property int|null $id_pend
 * @property int $kategori
 * @property array<array-key, mixed> $attr
 * @property int|null $tipe
 * @property string|null $url
 * @property int|null $tahun
 * @property int|null $kategori_info_publik
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int $deleted
 * @property int|null $id_syarat
 * @property int|null $id_parent
 * @property \Illuminate\Support\Carbon $created_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property int|null $dok_warga
 * @property string|null $lokasi_arsip
 * @property string|null $retensi_number
 * @property string|null $retensi_unit
 * @property string|null $retensi_date
 * @property string|null $published_at
 * @property string|null $keterangan
 * @property string $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Dokumen> $children
 * @property-read int|null $children_count
 * @property-read void $download_dokumen
 * @property-read string $expired_at_formatted
 * @property-read string|null $url_file
 * @property-read \App\Models\SyaratSurat|null $jenisDokumen
 * @property-read \App\Models\RefDokumen|null $kategoriDokumen
 * @property-read \App\Models\Penduduk|null $penduduk
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen activePublish()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen aktif($status = '1')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen filters(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen hidup()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen kategori(?mixed $value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen pengguna()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen produkHukum()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen tahun()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereAttr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereDokWarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereIdKelompok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereIdParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereIdPend($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereIdSyarat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereKategoriInfoPublik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereLokasiArsip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereRetensiDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereRetensiNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereRetensiUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereTglUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Dokumen extends BaseModel
{
    use ConfigId;

    public const DOKUMEN_WARGA = 1;
    public const ENABLE        = 1;
    public const DISABLE       = 0;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dokumen';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'attr' => '[]',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'attr' => 'json',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'satuan',
        'nama',
        'enabled',
        'tgl_upload',
        'id_pend',
        'id_parent',
        'id_kelompok',
        'kategori',
        'id_syarat',
        'dok_warga',
        'tipe',
        'url',
        'attr',
        'tahun',
        'kategori_info_publik',
        'retensi_number',
        'retensi_unit',
        'retensi_date',
        'keterangan',
        'status',
        'published_at',
    ];

    /**
     * {@inheritDoc}
     */
    protected $with = [
        'kategoriDokumen',
    ];

    public static function boot(): void
    {
        parent::boot();
        static::creating(static function ($model): void {
            if ($model->id_pend == null) {
                $model->retensi_date = $model->calculateRetensiDate();
            }
        });

        static::updating(static function ($model): void {
            if ($model->id_pend == null) {
                $model->retensi_date = $model->calculateRetensiDate();
            }
            if ($model->id_parent != null) {
                return;
            }
            static::deleteFile($model, 'satuan');
        });

        static::deleting(static function ($model): void {
            if ($model->id_parent == null) {
                static::deleteFile($model, 'satuan', true);
            }
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $logo = LOKASI_DOKUMEN . $model->getOriginal($file);
            if (file_exists($logo)) {
                unlink($logo);
            }
        }
    }

    public static function validasi(array $post): array
    {
        $ci                           = &get_instance();
        $data                         = [];
        $data['nama']                 = nomor_surat_keputusan($post['nama']);
        $data['kategori']             = (int) $post['kategori'] ?: 1;
        $data['kategori_info_publik'] = (int) $post['kategori_info_publik'] ?: null;
        $data['id_syarat']            = (int) $post['id_syarat'] ?: null;
        $data['id_pend']              = (int) $post['id_pend'] ?: null;
        $data['id_kelompok']          = (int) $post['id_kelompok'] ?: null;
        $data['tipe']                 = (int) $post['tipe'];
        $data['url']                  = $ci->security->xss_clean($post['url']) ?: null;
        $data['anggota_kk']           = (array) $post['anggota_kk'] ?? [];
        $data['dok_warga']            = (int) $post['dok_warga'] ?? 0;
        $data['retensi_number']       = $post['retensi_number'] ?? null;
        $data['retensi_unit']         = $post['retensi_unit'] ?? null;
        $data['status']               = $post['status'] ?? StatusEnum::YA;
        $data['published_at']         = $post['published_at'] ? tgl_indo_in($post['published_at']) : null;
        $data['keterangan']           = $ci->security->xss_clean($post['keterangan']) ?? null;

        if ($data['tipe'] == 1) {
            $data['url'] = null;
        }

        switch ($data['kategori']) {
            case 1: //Informsi Publik
                $data['tahun'] = $post['tahun'];
                break;

            case 2: //SK Kades
                $data['tahun']                 = date('Y', strtotime((string) $post['attr']['tgl_kep_kades']));
                $data['kategori_info_publik']  = '3';
                $data['attr']['tgl_kep_kades'] = $post['attr']['tgl_kep_kades'];
                $data['attr']['uraian']        = $ci->security->xss_clean($post['attr']['uraian']);
                $data['attr']['no_kep_kades']  = nomor_surat_keputusan($post['attr']['no_kep_kades']);
                $data['attr']['no_lapor']      = nomor_surat_keputusan($post['attr']['no_lapor']);
                $data['attr']['tgl_lapor']     = $post['attr']['tgl_lapor'];
                $data['attr']['keterangan']    = $ci->security->xss_clean($post['attr']['keterangan']);
                break;

            case 3: //Perdes
                $data['tahun']                     = date('Y', strtotime((string) $post['attr']['tgl_ditetapkan']));
                $data['kategori_info_publik']      = '3';
                $data['attr']['tgl_ditetapkan']    = $post['attr']['tgl_ditetapkan'];
                $data['attr']['tgl_lapor']         = $post['attr']['tgl_lapor'];
                $data['attr']['tgl_kesepakatan']   = $post['attr']['tgl_kesepakatan'];
                $data['attr']['uraian']            = $ci->security->xss_clean($post['attr']['uraian']);
                $data['attr']['jenis_peraturan']   = htmlentities((string) $post['attr']['jenis_peraturan']);
                $data['attr']['no_ditetapkan']     = nomor_surat_keputusan($post['attr']['no_ditetapkan']);
                $data['attr']['no_lapor']          = nomor_surat_keputusan($post['attr']['no_lapor']);
                $data['attr']['no_lembaran_desa']  = nomor_surat_keputusan($post['attr']['no_lembaran_desa']);
                $data['attr']['no_berita_desa']    = nomor_surat_keputusan($post['attr']['no_berita_desa']);
                $data['attr']['tgl_lembaran_desa'] = $post['attr']['tgl_lembaran_desa'];
                $data['attr']['tgl_berita_desa']   = $post['attr']['tgl_berita_desa'];
                $data['attr']['keterangan']        = htmlentities((string) $post['attr']['keterangan']);
                break;

            default:
                $data['tahun'] = date('Y');
                break;
        }

        return $data;
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function jenisDokumen()
    {
        return $this->belongsTo(SyaratSurat::class, 'id_syarat');
    }

    /**
     * Scope a query to only users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePengguna($query): void
    {
        // return $query->where('id_pend', auth('jwt')->id());
    }

    /**
     * Get the penduduk that owns the Dokumen
     */
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'id_pend');
    }

    /**
     * Getter untuk menambahkan url file.
     */
    public function getUrlFileAttribute(): ?string
    {
        // try {
        //     return Storage::disk('ftp')->exists("desa/upload/dokumen/{$this->satuan}")
        //         ? Storage::disk('ftp')->url("desa/upload/dokumen/{$this->satuan}")
        //         : null;
        // } catch (Exception $e) {
        //     Log::error($e);
        // }
        return null;
    }

    /**
     * Getter untuk donwload file.
     */
    public function getDownloadDokumenAttribute(): void
    {
        // try {
        //     return Storage::disk('ftp')->exists("desa/upload/dokumen/{$this->satuan}")
        //         ? Storage::disk('ftp')->download("desa/upload/dokumen/{$this->satuan}")
        //         : null;
        // } catch (Exception $e) {
        //     Log::error($e);
        // }
    }

    /**
     * Scope query untuk status dokumen
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeHidup($query)
    {
        return $query->where('deleted', '!=', 1);
    }

    /**
     * Scope query untuk status aktif
     *
     * @param Builder $query
     * @param string  $status
     *
     * @return Builder
     */
    public function scopeAktif($query, $status = '1')
    {
        return $query->where('enabled', $status);
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function kategoriDokumen()
    {
        return $this->hasOne(RefDokumen::class, 'id', 'kategori');
    }

    /**
     * Scope query untuk menyaring data dokumen berdasarkan parameter yang ditentukan
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeFilters($query, array $filters = [])
    {
        foreach ($filters as $key => $value) {
            $query->when($value ?? false, static function ($query) use ($value, $key): void {
                $query->where($key, $value);
            });
        }

        return $query;
    }

    /**
     * Scope query untuk kategori dokumen
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeKategori($query, mixed $value = 1)
    {
        return $query->where('kategori', $value);
    }

    /**
     * Get all of the children for the Dokumen
     */
    public function children(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'id_parent', 'id');
    }

    public function getNamaBerkas($id, $id_pend = 0)
    {
        $query = $this->newQuery();

        if ($id_pend) {
            $query->where('id_pend', $id_pend);
        }

        return $query->select('satuan')
            ->where('id', $id)
            ->where('enabled', 1)
            ->first()
            ->satuan ?? null;
    }

    public function getDokumen($id = 0, $id_pend = null): ?array
    {
        $query = $this->newQuery();

        if ($id_pend) {
            $query->where('id_pend', $id_pend);
        }

        $data = $query->where('id', $id)->first();

        if ($data) {
            $data->attr = json_decode($data->attr, true);

            return array_filter($data->toArray());
        }

        return null;
    }

    public function getDokumenDiAnggotaLain($id_dokumen = 0)
    {
        $data = $this->newQuery()
            ->where('id_parent', $id_dokumen)
            ->get()
            ->toArray();

        foreach (array_keys($data) as $key) {
            $data[$key]['attr'] = json_decode((string) $data[$key]['attr'], true);
            $data[$key]         = array_filter($data[$key]);
        }

        return $data;
    }

    public function scopeActive($query)
    {
        return $query->where('enabled', self::ENABLE);
    }

    public function scopeActivePublish($query)
    {
        return $query->where(static function ($q): void {
            $q->whereNull('retensi_date')
                ->orWhere('retensi_date', '>=', Carbon::now());
        })->active()->whereDate('published_at', '<=', Carbon::now()->format('Y-m-d'));
    }

    public function scopeProdukHukum($query)
    {
        return $query->where('kategori', '!=', 1);
    }

    /**
     * Menghitung tanggal retensi berdasarkan nomor dan unit waktu.
     *
     * Menggunakan tanggal pembuatan dokumen sebagai referensi untuk menghitung tanggal retensi.
     * Dapat menghitung dengan satuan waktu: hari, minggu, bulan, atau tahun.
     *
     * @return \Illuminate\Support\Carbon|null
     */
    public function calculateRetensiDate()
    {
        $createdAt = $this->created_at ?? Carbon::now(); // Gunakan `now` jika `created_at` belum tersedia

        if ($this->retensi_number > 0 && $this->retensi_unit) {
            $retensiDate = Carbon::parse($createdAt);

            match ($this->retensi_unit) {
                'hari'   => $retensiDate->addDays($this->retensi_number),
                'minggu' => $retensiDate->addWeeks($this->retensi_number),
                'bulan'  => $retensiDate->addMonths($this->retensi_number),
                'tahun'  => $retensiDate->addYears($this->retensi_number),
                default  => $retensiDate,
            };

            return $retensiDate;
        }

        return null;
    }

    /**
     * Mendapatkan tanggal retensi yang diformat jika masih aktif.
     *
     * Jika tanggal retensi ada dan masih aktif, akan mengembalikan tanggal dalam format 'd F Y H:i:s'.
     * Jika dokumen sudah kadaluarsa atau tidak ada tanggal retensi, akan mengembalikan tanda '-'.
     *
     * @return string
     */
    public function getExpiredAtFormattedAttribute()
    {
        $isActive = Carbon::now()->lessThanOrEqualTo(Carbon::parse($this->retensi_date));
        if ($this->retensi_date && $isActive) {
            return Carbon::parse($this->retensi_date)->translatedFormat('d F Y H:i');
        }

        return '-';
    }

    public function scopeTahun($query)
    {
        return $query->selectRaw('YEAR(tgl_upload) as tahun')->distinct()->orderBy('tahun', 'desc')->take(10);
    }
}
