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

use App\Libraries\Paging;
use App\Models\Galery;

defined('BASEPATH') || exit('No direct script access allowed');

class First_gallery_m extends MY_Model
{
    public function paging($p = 1)
    {
        // TODO: Digunakan dimana
        $row = $this->config_id()
            ->select('COUNT(id) AS id')
            ->where('enabled', 1)
            ->where('parrent', 0)
            ->get('gambar_gallery')
            ->row_array();

        $jml_data = $row['id'];

        $paging          = new Paging();
        $cfg['page']     = $p;
        $cfg['per_page'] = 10;
        $cfg['num_rows'] = $jml_data;
        $paging->init($cfg);

        return $paging;
    }

    // daftar album galeri
    public function gallery_show($offset = 0, $limit = 50)
    {
        // OPTIMIZE: benarkah butuh paging?
        $data = $this->config_id()
            ->where('enabled', 1)
            ->where('parrent', 0)
            ->order_by('urut')
            ->get('gambar_gallery', $limit, $offset)
            ->result_array();

        // Untuk album yang tidak ada gambar cover, cari gambar di sub-gallery
        $counter = count($data);

        // Untuk album yang tidak ada gambar cover, cari gambar di sub-gallery
        for ($i = 0; $i < $counter; $i++) {
            if ($data[$i]['gambar'] == '') {
                $galeri = $data[$i]['id'];
                $row    = $this->config_id()
                    ->where('enabled', 1)
                    ->group_start()
                    ->where('parrent', $galeri)
                    ->or_where('id', $galeri)
                    ->group_end()
                    ->where('gambar <>', '')
                    ->get('gambar_gallery')
                    ->row_array();
                $data[$i]['gambar'] = $row['gambar'];
            }
        }

        return $data;
    }

    public function paging2($gal = 0, $p = 1)
    {
        $row = $this->config_id()
            ->select('COUNT(id) AS id')
            ->where('enabled', 1)
            ->where('parrent', $gal)
            ->get('gambar_gallery')
            ->row_array();
        $jml_data = $row['id'];

        $paging          = new Paging();
        $cfg['page']     = $p;
        $cfg['per_page'] = 10;
        $cfg['num_rows'] = $jml_data;
        $paging->init($cfg);

        return $paging;
    }

    // daftar gambar di tiap album
    public function sub_gallery_show($gal = 0, $offset = 0, $limit = 50)
    {
        return $this->config_id()
            ->where('enabled', 1)
            ->where('parrent', $gal)
            ->order_by('urut')
            ->get('gambar_gallery', $limit, $offset)
            ->result_array();
    }

    public function get_parent($parent)
    {
        return $this->config_id()
            ->where('id', $parent)
            ->get('gambar_gallery')
            ->row_array();
    }

    // daftar album di widget
    public function gallery_widget()
    {
        $jumlah = setting('jumlah_album_galeri') ?: 4;
        $urut   = setting('urutan_gambar_galeri') ?: 'acak';

        return Galery::where('enabled', 1)
            ->where('parrent', 0)
            ->when($urut === 'acak', static fn ($query) => $query->inRandomOrder(), static fn ($query) => $query->orderBy('urut', $urut))
            ->limit($jumlah)
            ->get();
    }
}
