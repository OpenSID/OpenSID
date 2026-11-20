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
 * @property int|null $pesan_id
 * @property string $text
 * @property string|null $pengirim
 * @property string|null $nama_pengirim
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $custom_date
 * @property-read \App\Models\Pesan|null $headerPesan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanDetail whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanDetail whereNamaPengirim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanDetail wherePengirim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanDetail wherePesanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanDetail whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PesanDetail withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class PesanDetail extends BaseModel
{
    use ConfigId;

    protected $table    = 'pesan_detail';
    protected $fillable = ['text', 'pesan_id', 'pesan_detail', 'nama_pengirim', 'id', 'pengirim'];

    public function headerPesan()
    {
        return $this->hasOne(Pesan::class, 'pesan_id', 'id');
    }

    public function getCustomDateAttribute()
    {
        return $this->created_at->format('d-m-Y H:i');
    }
}
