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
use Carbon\Carbon;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property int|null $id_pengaduan
 * @property string|null $nik
 * @property string $nama
 * @property string|null $email
 * @property string|null $telepon
 * @property string|null $judul
 * @property string $isi
 * @property int $status 1 = menunggu proses, 2 = Sedang Diproses, 3 = Selesai Diproses
 * @property string|null $foto
 * @property string $ip_address
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Pengaduan> $child
 * @property-read int|null $child_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan bulanan(?mixed $status = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan filter($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan status(?mixed $status = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan tipe($id_pengaduan = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereIdPengaduan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Pengaduan extends BaseModel
{
    use ConfigId;
    use ShortcutCache;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengaduan';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'child',
    ];

    public static function boot(): void
    {
        parent::boot();
        static::deleting(static function ($model): void {
            if ($model->foto) {
                $file = FCPATH . LOKASI_PENGADUAN . $model->foto;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        });
    }

    /**
     * Scope query untuk status pengaduan
     *
     * @return Builder
     */
    public function scopeStatus(mixed $query, mixed $status = null)
    {
        if ($status) {
            $query->where('status', $status);
        }

        return $this->scopeTipe($query);
    }

    /**
     * Scope query untuk tipe pengaduan
     * Jika id_pengaduan null maka dari warga
     * Jika id_pengaduan tidak null maka balasan dari admin
     *
     * @param mixed|null $id_pengaduan
     */
    public function scopeTipe(mixed $query, $id_pengaduan = null)
    {
        if ($id_pengaduan) {
            $query->where('id_pengaduan', $id_pengaduan);
        }

        return $query->where('id_pengaduan', null);
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function child()
    {
        return $this->hasMany(Pengaduan::class, 'id_pengaduan', 'id');
    }

    /**
     * Scope query untuk status pengaduan bulanan
     *
     * @return Builder
     */
    public function scopeBulanan(mixed $query, mixed $status = null)
    {
        if ($status) {
            $query->where('status', $status);
        }

        return $query->where('id_pengaduan', null)->whereMonth('created_at', Carbon::now()->month);
    }

    public function scopeFilter($query, $status)
    {
        if (! empty($status)) {
            $query->where('status', $status);
        }

        return $query;
    }
}
