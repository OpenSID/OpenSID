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

namespace Modules\Lapak\Models;

use App\Enums\StatusEnum;
use App\Models\BaseModel;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Illuminate\Support\Facades\DB;

class ProdukKategori extends BaseModel
{
    use ConfigId;
    use ShortcutCache;

    public $timestamps = false;
    protected $table   = 'produk_kategori';
    protected $guarded = [];

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

    public function kategoriInsert($post = []): bool
    {
        return (bool) $this->create($post);
    }

    public function kategoriUpdate($id = 0, $post = []): bool
    {
        $validator = $this->kategoriValidasi($post, $id);

        if ($validator->fails()) {
            return false;
        }

        return (bool) $this->where('id', $id)->update($validator->validated());
    }

    public function kategoriDelete($id = 0): void
    {
        $this->where('id', $id)->delete();
    }

    public function kategoriDeleteAll(): void
    {
        $id_cb = request('id_cb', []);

        foreach ($id_cb as $id) {
            $this->kategoriDelete($id);
        }
    }

    public function kategoriValidasi(array $post = [], $id = null)
    {
        // Sanitasi input sebelum validasi
        $post['kategori'] = alfanumerik_spasi($post['kategori']);
        $post['slug']     = url_title($post['slug'] ?: $post['kategori'], 'dash', true);

        return validator($post, [
            'kategori' => ['required', 'string', 'max:100'],
            'slug'     => ['required', 'string', 'max:100', 'unique:produk_kategori,slug,' . $id . ',id,config_id,' . identitas('id')],
        ], [
            'kategori.required' => 'Kategori produk wajib diisi.',
            'slug.required'     => 'Slug produk wajib diisi.',
            'slug.unique'       => 'Slug sudah digunakan, silakan gunakan slug lain.',
        ]);
    }

    protected function scopeActive($query)
    {
        return $query->whereStatus(StatusEnum::YA);
    }
}
