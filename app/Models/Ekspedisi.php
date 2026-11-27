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

use App\Traits\Author;
use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property int|null $nomor_urut
 * @property string|null $nomor_surat
 * @property string|null $kode_surat
 * @property string $tanggal_surat
 * @property string $tanggal_catat
 * @property string|null $tujuan
 * @property string|null $isi_singkat
 * @property string|null $berkas_scan
 * @property int|null $ekspedisi
 * @property string|null $tanggal_pengiriman
 * @property string|null $tanda_terima
 * @property string|null $keterangan
 * @property string|null $lokasi_arsip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property int|null $arsip_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi getTahun()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi getTandaTerima($id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi untukEkspedisi($id, $masuk = 0)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereArsipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereBerkasScan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereEkspedisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereIsiSingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereKodeSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereLokasiArsip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereNomorSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereNomorUrut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereTandaTerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereTanggalCatat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereTanggalPengiriman($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereTanggalSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereTujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ekspedisi withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Ekspedisi extends BaseModel
{
    use ConfigId;
    use Author;

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'surat_keluar';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The casts with the model.
     *
     * @var array
     */
    protected $casts = [];

    protected static function booted()
    {
        static::addGlobalScope('custom_where', static function ($builder): void {
            $builder->where('ekspedisi', 1);
        });
    }

    public function scopeGetTahun($query)
    {
        return $query
            ->distinct()
            ->selectRaw('YEAR(tanggal_surat) AS tahun')
            ->orderByDesc('tahun')
            ->get()
            ->toArray();
    }

    public function scopeUntukEkspedisi($query, $id, $masuk = 0)
    {
        return $query->where('id', $id)->update([
            'ekspedisi'          => $masuk,
            'tanggal_pengiriman' => null,
            'tanda_terima'       => null,
            'keterangan'         => null,
        ]);
    }

    public function scopeGetTandaTerima($query, $id)
    {
        return $query
            ->select('tanda_terima')
            ->where('id', $id)
            ->first();
    }
}
