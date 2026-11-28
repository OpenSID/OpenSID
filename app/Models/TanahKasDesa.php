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

use App\Enums\AsalTanahKasEnum;
use App\Enums\PeruntukanTanahKasEnum;
use App\Traits\Author;
use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property AsalTanahKasEnum $nama_pemilik_asal
 * @property string $letter_c
 * @property string $kelas
 * @property int $luas
 * @property int|null $asli_milik_desa
 * @property int|null $pemerintah
 * @property int|null $provinsi
 * @property int|null $kabupaten_kota
 * @property int|null $lain_lain
 * @property int|null $sawah
 * @property int|null $tegal
 * @property int|null $kebun
 * @property int|null $tambak_kolam
 * @property int|null $tanah_kering_darat
 * @property int|null $ada_patok
 * @property int|null $tidak_ada_patok
 * @property int|null $ada_papan_nama
 * @property int|null $tidak_ada_papan_nama
 * @property string|null $tanggal_perolehan
 * @property string $lokasi
 * @property PeruntukanTanahKasEnum $peruntukan
 * @property string $mutasi
 * @property string $keterangan
 * @property int $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property-read string|null $asal_tanah_kas_label
 * @property-read string|null $peruntukan_tanah_kas_label
 * @property-read \App\Models\RefPersilKelas|null $ref_persil_kelas
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa checkLetterC($letterC_persil)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa checkOldLetterC($value, $letterC_persil)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa visible($value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereAdaPapanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereAdaPatok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereAsliMilikDesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereKabupatenKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereKebun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereLainLain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereLetterC($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereLuas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereNamaPemilikAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa wherePemerintah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa wherePeruntukan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereSawah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereTambakKolam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereTanahKeringDarat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereTanggalPerolehan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereTegal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereTidakAdaPapanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereTidakAdaPatok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa whereVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TanahKasDesa withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class TanahKasDesa extends BaseModel
{
    use Author;
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tanah_kas_desa';

    protected $with = ['ref_persil_kelas'];

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $appends = [
        'asal_tanah_kas_label',
        'peruntukan_tanah_kas_label',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'nama_pemilik_asal' => AsalTanahKasEnum::class,
        'peruntukan'        => PeruntukanTanahKasEnum::class,
    ];

    public function scopeCheckLetterC($query, $letterC_persil)
    {
        return $query->where('letter_c', $letterC_persil)->where('visible', 1)->exists();
    }

    public function scopeCheckOldLetterC($query, $value, $letterC_persil): bool
    {
        return $query->where('visible', 1)->where('id', $value)->first()->letter_c == $letterC_persil;
    }

    public function scopeVisible($query, $value = 1)
    {
        return $query->where('visible', $value);
    }

    public function getAsalTanahKasLabelAttribute(): ?string
    {
        return $this->nama_pemilik_asal?->label();
    }

    public function getPeruntukanTanahKasLabelAttribute(): ?string
    {
        return $this->peruntukan?->label();
    }

    public function ref_persil_kelas()
    {
        return $this->belongsTo(RefPersilKelas::class, 'kelas');
    }
}
