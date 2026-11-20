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
 * @property string|null $no_kk
 * @property int|null $nik_kepala
 * @property string|null $tgl_daftar
 * @property int|null $kelas_sosial
 * @property string|null $tgl_cetak_kk
 * @property string|null $alamat
 * @property int|null $id_cluster
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\Penduduk|null $kepalaKeluarga
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif whereIdCluster($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif whereKelasSosial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif whereNikKepala($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif whereNoKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif whereTglCetakKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif whereTglDaftar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KeluargaAktif withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class KeluargaAktif extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'keluarga_aktif';

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function kepalaKeluarga()
    {
        return $this->hasOne(Penduduk::class, 'id_kk')->kepalaKeluarga();
    }
}
