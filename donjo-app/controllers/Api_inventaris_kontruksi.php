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

defined('BASEPATH') || exit('No direct script access allowed');

class Api_inventaris_kontruksi extends Admin_Controller
{
    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'inventaris';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('inventaris_kontruksi_model');
    }

    public function add(): void
    {
        isCan('u');
        $data = $this->inventaris_kontruksi_model->add([
            'nama_barang'          => $this->input->post('nama_barang'),
            'kondisi_bangunan'     => $this->input->post('fisik_bangunan'),
            'kontruksi_bertingkat' => $this->input->post('tingkat'),
            'kontruksi_beton'      => $this->input->post('bahan'),
            'luas_bangunan'        => $this->input->post('luas_bangunan'),
            'letak'                => $this->input->post('alamat'),
            'no_dokument'          => $this->input->post('no_bangunan'),
            'tanggal_dokument'     => $this->input->post('tanggal_bangunan'),
            'tanggal'              => $this->input->post('tanggal_mulai'),
            'status_tanah'         => $this->input->post('status_tanah'),
            'kode_tanah'           => $this->input->post('kode_tanah'),
            'asal'                 => $this->input->post('asal'),
            'harga'                => $this->input->post('harga'),
            'keterangan'           => $this->input->post('keterangan'),
            'visible'              => 1,
            'created_by'           => $this->session->user,
            'updated_by'           => $this->session->user,
        ]);
        $_SESSION['success'] = $data ? 1 : -1;
        redirect('inventaris_kontruksi');
    }

    public function update($id): void
    {
        isCan('u');
        $data = $this->inventaris_kontruksi_model->update($id, [
            'nama_barang'          => $this->input->post('nama_barang'),
            'kondisi_bangunan'     => $this->input->post('fisik_bangunan'),
            'kontruksi_bertingkat' => $this->input->post('tingkat'),
            'kontruksi_beton'      => $this->input->post('bahan'),
            'luas_bangunan'        => $this->input->post('luas_bangunan'),
            'letak'                => $this->input->post('alamat'),
            'no_dokument'          => $this->input->post('no_bangunan'),
            'tanggal_dokument'     => $this->input->post('tanggal_bangunan'),
            'tanggal'              => $this->input->post('tanggal_mulai'),
            'status_tanah'         => $this->input->post('status_tanah'),
            'kode_tanah'           => $this->input->post('kode_tanah'),
            'asal'                 => $this->input->post('asal'),
            'harga'                => $this->input->post('harga'),
            'keterangan'           => $this->input->post('keterangan'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);
        $_SESSION['success'] = $data ? 1 : -1;
        redirect('inventaris_kontruksi');
    }

    public function delete($id): void
    {
        isCan('h');
        $data                = $this->inventaris_kontruksi_model->delete($id);
        $_SESSION['success'] = $data ? 1 : -1;
        redirect('inventaris_kontruksi');
    }
}
