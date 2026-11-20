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

namespace Modules\Lapak\Database\Seeders;

use App\Traits\Migrator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    use Migrator;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->createSettings([
            [
                'judul'      => 'Pesan Singkat WA',
                'key'        => 'pesan_singkat_wa',
                'value'      => 'Saya ingin membeli [nama_produk] yang Anda tawarkan di Lapak Desa [link_web]',
                'keterangan' => 'Pesan Singkat WhatsApp',
                'jenis'      => 'textarea',
                'kategori'   => 'Lapak',
            ],
            [
                'judul'      => 'Icon Lapak Peta',
                'key'        => 'icon_lapak_peta',
                'value'      => 'http://opensid.test/desa/upload/gis/lokasi/point/fastfood.png',
                'keterangan' => 'Icon penanda Lapak yang ditampilkan pada Peta',
                'jenis'      => 'select-simbol',
                'option'     => json_encode([
                    'model' => 'App\Models\Simbol',
                    'value' => 'simbol',
                    'label' => 'simbol',
                ]),
                'attribute' => json_encode([
                    'class' => 'required',
                ]),
                'kategori' => 'Lapak',
            ],
            [
                'judul'      => 'Jumlah Produk Perhalaman',
                'key'        => 'jumlah_produk_perhalaman',
                'value'      => 10,
                'keterangan' => 'Jumlah produk yang ditampilkan dalam satu halaman',
                'jenis'      => 'input-number',
                'attribute'  => json_encode([
                    'class' => 'required',
                    'min'   => 1,
                    'max'   => 50,
                    'step'  => 1,
                ]),
                'kategori' => 'Lapak',
            ],
            [
                'judul'      => 'Banyak Foto Tiap Produk',
                'key'        => 'banyak_foto_tiap_produk',
                'value'      => 3,
                'keterangan' => 'Banyaknya foto tiap produk yang bisa di unggah',
                'jenis'      => 'input-number',
                'attribute'  => json_encode([
                    'class' => 'required',
                    'min'   => 1,
                    'max'   => 5,
                    'step'  => 1,
                ]),
                'kategori' => 'Lapak',
            ],
            [
                'judul'      => 'Jumlah Pengajuan Produk Oleh Warga',
                'key'        => 'jumlah_pengajuan_produk',
                'value'      => 3,
                'keterangan' => 'Jumlah pengajuan produk perhari oleh warga melalui layanan mandiri',
                'jenis'      => 'input-number',
                'attribute'  => json_encode([
                    'class' => 'required',
                    'min'   => 1,
                    'max'   => 50,
                    'step'  => 1,
                ]),
                'kategori' => 'Lapak',
            ],
        ]);
    }
}
