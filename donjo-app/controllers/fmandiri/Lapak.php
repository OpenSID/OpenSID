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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

use Modules\Lapak\Models\Produk;
use Modules\Lapak\Models\ProdukKategori;

class Lapak extends Mandiri_Controller
{
    public function index($p = 1)
    {
        $keyword     = $this->input->get('keyword', true);
        $id_kategori = $this->input->get('id_kategori', true);

        $kategori = ProdukKategori::get();

        $produk = Produk::listProduk()
            ->when($id_kategori, static function ($query, $kategori): void {
                $query->where('id_produk_kategori', $kategori);
            })
            ->when($keyword, static function ($query, $keyword): void {
                $query->where(static function ($query) use ($keyword): void {
                    $query
                        ->where('p.nama', 'like', "%{$keyword}%")
                        ->orWhere('produk.nama', 'like', "%{$keyword}%")
                        ->orWhere('pk.kategori', 'like', "%{$keyword}%")
                        ->orWhere('produk.harga', 'like', "%{$keyword}%")
                        ->orWhere('produk.satuan', 'like', "%{$keyword}%")
                        ->orWhere('produk.potongan', 'like', "%{$keyword}%")
                        ->orWhere('produk.deskripsi', 'like', "%{$keyword}%");
                });
            })
            ->where('produk.status', 1)
            ->paginate();

        return view('layanan_mandiri.lapak.index', ['id_kategori' => $id_kategori, 'keyword' => $keyword, 'kategori' => $kategori, 'produk' => $produk]);
    }
}
