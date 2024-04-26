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

use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Illuminate\Support\Facades\DB;

class Pelapak extends BaseModel
{
    use ConfigId;
    use ShortcutCache;

    protected $table   = 'pelapak';
    protected $guarded = [];
    public $timestamps = false;

    public function penduduk()
    {
        return $this->belongsTo(PendudukHidup::class, 'id_pend', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id', 'id_pelapak');
    }

    public function scopelistPelapak($query)
    {
        return $this->withoutGlobalScopes()
            ->withConfigId('pelapak')
            ->select(
                'pelapak.*',
                'p.nama as pelapak',
                'p.nik',
                DB::raw('(SELECT COUNT(pr.id) FROM produk pr WHERE pr.id_pelapak = pelapak.id) as jumlah')
            )
            ->leftJoin('penduduk_hidup as p', 'pelapak.id_pend', '=', 'p.id');
    }

    public function listPenduduk($id = null)
    {
        return DB::table('penduduk_hidup as p')
            ->select('p.id', 'p.nik', 'p.nama', 'p.telepon')
            ->where('p.nik', '<>', '')
            ->where('p.nik', '<>', 0)
            ->where('p.config_id', identitas('id'))
            ->whereNotIn('p.id', static function ($query) use ($id): void {
                $query->select('id_pend')
                    ->from('pelapak')
                    ->where('id_pend', '!=', $id)
                    ->whereColumn('config_id', 'p.config_id');
            })
            ->get();
    }

    public function pelapakInsert(): void
    {
        $data = $this->pelapakValidasi();

        $this->create($data);

        // Tambahkan no telpon ke tweb_penduduk jika kosong
        DB::table('tweb_penduduk')
            ->where('config_id', identitas('id'))
            ->where('id', $data['id_pend'])
            ->update(['telepon' => $data['telepon']]);
    }

    public function pelapakUpdate($id = 0): void
    {
        $data = $this->pelapakValidasi();

        $this->where('id', $id)->update($data);
    }

    public function pelapakUpdateMaps($id = 0): void
    {
        $post = ci()->input->post();

        $data = [
            'lat'  => $post['lat'],
            'lng'  => $post['lng'],
            'zoom' => $post['zoom'],
        ];
        $this->where('id', $id)->update($data);
    }

    private function pelapakValidasi(): array
    {
        $post = ci()->input->post();

        return [
            'id_pend' => bilangan($post['id_pend']),
            'telepon' => bilangan($post['telepon']),
        ];
    }

    public function pelapakDelete($id = 0): void
    {
        $this->where('id', $id)->delete();
    }

    public function pelapakDeleteAll(): void
    {
        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->pelapakDelete($id);
        }
    }

    protected static function boot()
    {
        parent::boot();
    }
}
