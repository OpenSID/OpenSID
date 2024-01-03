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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Traits\ConfigId;

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
        'kategori',
        'id_syarat',
        'dok_warga',
    ];

    /**
     * {@inheritDoc}
     */
    protected $with = [
        'kategoriDokumen',
    ];

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
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePengguna($query)
    {
        // return $query->where('id_pend', auth('jwt')->id());
    }

    /**
     * Getter untuk menambahkan url file.
     *
     * @return string
     */
    public function getUrlFileAttribute()
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
     *
     * @return string
     */
    public function getDownloadDokumenAttribute()
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
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeFilters($query, array $filters = [])
    {
        foreach ($filters as $key => $value) {
            $query->when($value ?? false, static function ($query) use ($value, $key) {
                $query->where($key, $value);
            });
        }

        return $query;
    }

    /**
     * Scope query untuk kategori dokumen
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function scopeKategori($query, $value = 1)
    {
        return $query->where('kategori', $value);
    }
}
