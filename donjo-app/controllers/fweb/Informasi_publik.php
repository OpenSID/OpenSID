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

class Informasi_publik extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['referensi_model', 'web_dokumen_model']);
    }

    public function index(): void
    {
        if (! $this->web_menu_model->menu_aktif('informasi_publik')) {
            show_404();
        }

        $data = $this->includes;

        $data['kategori']       = $this->referensi_model->list_data('ref_dokumen', 1);
        $data['tahun']          = $this->web_dokumen_model->tahun_dokumen();
        $data['heading']        = 'Informasi Publik';
        $data['title']          = $data['heading'];
        $data['halaman_statis'] = 'informasi_publik/index';
        $this->_get_common_data($data);

        $this->set_template('layouts/halaman_statis.tpl.php');
        theme_view($this->template, $data);
    }

    public function ajax_informasi_publik(): void
    {
        $informasi_publik = $this->web_dokumen_model->get_informasi_publik();
        $data             = [];
        $no               = $_POST['start'];

        foreach ($informasi_publik as $baris) {
            $no++;
            $row   = [];
            $row[] = $no;
            if ($baris['tipe'] == 1) {
                $row[] = "<a href='" . site_url('dokumen_web/unduh_berkas/') . $baris['id'] . "' target='_blank'>" . $baris['nama'] . '</a>';
            } else {
                $row[] = "<a href='" . $baris['url'] . "' target='_blank'>" . $baris['nama'] . '</a>';
            }
            $row[] = $baris['tahun'];
            // Ambil judul kategori
            $row[] = $this->referensi_model->list_ref_flip(KATEGORI_PUBLIK)[$baris['kategori_info_publik']];
            $row[] = $baris['tgl_upload'];
            if ($baris['tipe'] == 1) {
                $row[] = "<a href='" . site_url('informasi-publik/tampilkan/') . $baris['id'] . "' class='btn btn-primary btn-block pdf'>Lihat Dokumen </a>";
            } else {
                $row[] = "<a href='" . $baris['url'] . "' class='btn btn-primary btn-block'>Lihat Dokumen </a>";
            }
            $data[] = $row;
        }

        $output = [
            'recordsTotal'    => $this->web_dokumen_model->count_informasi_publik_all(),
            'recordsFiltered' => $this->web_dokumen_model->count_informasi_publik_filtered(),
            'data'            => $data,
        ];
        echo json_encode($output, JSON_THROW_ON_ERROR);
    }

    public function tampilkan($id_dokumen, $id_pend = 0): void
    {
        $berkas = $this->web_dokumen_model->get_nama_berkas($id_dokumen, $id_pend);

        if (! $id_dokumen || ! $berkas || ! file_exists(LOKASI_DOKUMEN . $berkas)) {
            $data['link_berkas'] = null;
        } else {
            $data = [
                'link_berkas' => site_url("dokumen/tampilkan_berkas/{$id_dokumen}/{$id_pend}"),
                'tipe'        => get_extension($berkas),
                'link_unduh'  => site_url("dokumen/unduh_berkas/{$id_dokumen}/{$id_pend}"),
            ];
        }
        $this->load->view('global/tampilkan', $data);
    }
}
