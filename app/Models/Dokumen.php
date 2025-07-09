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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

defined('BASEPATH') || exit('No direct script access allowed');

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
        'kategori',
        'id_syarat',
        'dok_warga',
        'tipe',
        'url',
        'attr',
        'tahun',
        'kategori_info_publik',
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

        static::updating(static function ($model): void {
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
    public function getUrlFileAttribute(): void
    {
        // try {
        //     return Storage::disk('ftp')->exists("desa/upload/dokumen/{$this->satuan}")
        //         ? Storage::disk('ftp')->url("desa/upload/dokumen/{$this->satuan}")
        //         : null;
        // } catch (Exception $e) {
        //     Log::error($e);
        // }
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
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
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

    public static function validasi(array $post): array
    {
        $ci                           = &get_instance();
        $data                         = [];
        $data['nama']                 = nomor_surat_keputusan($post['nama']);
        $data['kategori']             = (int) $post['kategori'] ?: 1;
        $data['kategori_info_publik'] = (int) $post['kategori_info_publik'] ?: null;
        $data['id_syarat']            = (int) $post['id_syarat'] ?: null;
        $data['id_pend']              = (int) $post['id_pend'] ?: 0;
        $data['tipe']                 = (int) $post['tipe'];
        $data['url']                  = $ci->security->xss_clean($post['url']) ?: null;
        $data['anggota_kk']           = (array) $post['anggota_kk'] ?? [];
        $data['dok_warga']            = (int) $post['dok_warga'] ?? 0;

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

    public function scopeProdukHukum($query)
    {
        return $query->where('kategori', '!=', 1);
    }
}
