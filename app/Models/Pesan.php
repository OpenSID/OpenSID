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

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string|null $judul
 * @property string $jenis
 * @property int $sudah_dibaca
 * @property int $diarsipkan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PesanDetail> $detailPesan
 * @property-read int|null $detail_pesan_count
 * @property-read mixed $custom_date
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan status($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereDiarsipkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereSudahDibaca($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Pesan extends BaseModel
{
    use ConfigId;

    protected $table    = 'pesan';
    protected $fillable = ['id', 'judul', 'jenis', 'sudah_dibaca', 'diarsipkan'];

    public function scopeStatus($query, $status)
    {
        if ($status != null) {
            return $query->where('sudah_dibaca', (int) $status);
        }

        return null;
    }

    public function detailPesan()
    {
        return $this->hasMany(PesanDetail::class, 'pesan_id', 'id');
    }

    public function getCustomDateAttribute()
    {
        return $this->created_at->format('d-m-Y H:i');
    }
}
