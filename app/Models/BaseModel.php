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
use App\Scopes\ConfigIdScope;
use App\Scopes\WilayahNullScope;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    protected $perPage = 10;

    /**
     * Fungsi untuk mengganti status
     *
     * @param int    $id      ID data
     * @param string $kolom   Kolom yang akan diubah
     * @param bool   $onlyOne Hanya satu data yang aktif
     */
    public static function gantiStatus($id, $kolom = 'status', $onlyOne = false): bool
    {
        $data = self::findOrFail($id);

        if ($data->update([$kolom => ($data->{$kolom} == StatusEnum::YA) ? StatusEnum::TIDAK : StatusEnum::YA])) {
            if ($onlyOne) {
                self::where('id', '!=', $id)->update([$kolom => StatusEnum::TIDAK]);
            }

            return true;
        }

        return false;
    }

    public static function scopeWithoutConfigId($query, $configId = '')
    {
        return $query->withoutGlobalScope(ConfigIdScope::class)
            ->withoutGlobalScope(WilayahNullScope::class)
            ->when($configId !== '', static fn ($query) => $query->where('config_id', $configId));
    }
}
