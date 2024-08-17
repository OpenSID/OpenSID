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

use App\Enums\JenisKelaminEnum;
use App\Traits\ConfigId;
use Illuminate\Support\Facades\DB;

class BukuTamu extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'buku_tamu';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The appends with the model.
     *
     * @var array
     */
    protected $appends = [
        'url_foto',
    ];

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function jk()
    {
        return $this->hasOne(Sex::class, 'id', 'jenis_kelamin');
    }

    /**
     * Getter untuk url_foto
     *
     * @return string
     */
    public function getUrlFotoAttribute()
    {
        $lokasi = LOKASI_FOTO_BUKU_TAMU . $this->foto;

        if (null === $this->foto || ! file_exists(FCPATH . $lokasi)) {
            return $this->jenis_kelamin == JenisKelaminEnum::LAKI_LAKI ? FOTO_DEFAULT_PRIA : FOTO_DEFAULT_WANITA;
        }

        return base_url($lokasi);
    }

    public function scopeFilters($query, array $filters)
    {
        if (! empty($filters['tanggal'])) {
            [$awal, $akhir] = explode(' - ', $filters['tanggal']);
            $query->whereBetween(DB::raw('DATE(created_at)'), [$awal, $akhir]);
        }

        return $query;
    }

    /**
     * Setter untuk bidang
     *
     * @param mixed $value
     */
    public function setBidangAttribute($value): void
    {
        $this->attributes['bidang'] = RefJabatan::find($value)->nama ?? null;
    }

    /**
     * Setter untuk keperluan
     *
     * @param mixed $value
     */
    public function setKeperluanAttribute($value): void
    {
        $this->attributes['keperluan'] = BukuKeperluan::find($value)->keperluan ?? null;
    }
}
