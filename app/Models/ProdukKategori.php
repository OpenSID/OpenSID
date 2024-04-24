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

class ProdukKategori extends BaseModel
{
    use ConfigId;
    use ShortcutCache;

    protected $table   = 'produk_kategori';
    protected $guarded = [];
    public $timestamps = false;

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id', 'id_produk_kategori');
    }

    public function scopelistKategori($query)
    {
        return $this->withoutGlobalScopes()
            ->withConfigId('produk_kategori')
            ->select(
                'produk_kategori.*',
                DB::raw('(SELECT COUNT(pr.id) FROM produk pr WHERE pr.id_produk_kategori = produk_kategori.id) as jumlah')
            );
    }

    public function kategoriInsert($post = []): void
    {
        $data = $this->kategoriValidasi($post);

        $this->create($data);
    }

    public function kategoriUpdate($id = 0, $post = []): void
    {
        $data = $this->kategoriValidasi($post);

        $this->where('id', $id)->update($data);
    }

    public function kategoriDelete($id = 0): void
    {
        $this->where('id', $id)->delete();
    }

    public function kategoriDeleteAll(): void
    {
        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->kategoriDelete($id);
        }
    }

    private function kategoriValidasi($post = [])
    {
        return [
            'kategori' => alfanumerik_spasi($post['kategori']),
            'slug'     => url_title($post['kategori'], 'dash', true),
        ];
    }
}
