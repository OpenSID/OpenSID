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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * User: didikkurniawan
 * Date: 10/1/16
 * Time: 06:59
 */
class Api_inventaris_peralatan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('inventaris_peralatan_model');
    }

    public function add()
    {
        $this->redirect_hak_akses('u');
        $data = $this->inventaris_peralatan_model->add([
            'nama_barang'     => $this->input->post('nama_barang_save'),
            'kode_barang'     => $this->input->post('kode_barang'),
            'register'        => $this->input->post('register'),
            'merk'            => $this->input->post('merk'),
            'ukuran'          => $this->input->post('ukuran'),
            'bahan'           => $this->input->post('bahan'),
            'tahun_pengadaan' => $this->input->post('tahun_pengadaan'),
            'no_pabrik'       => $this->input->post('no_pabrik'),
            'no_rangka'       => $this->input->post('no_rangka'),
            'no_mesin'        => $this->input->post('no_mesin'),
            'no_polisi'       => $this->input->post('no_polisi'),
            'no_bpkb'         => $this->input->post('no_bpkb'),
            'asal'            => $this->input->post('asal'),
            'harga'           => $this->input->post('harga'),
            'keterangan'      => $this->input->post('keterangan'),
            'visible'         => 1,
            'created_by'      => $this->session->user,
            'updated_by'      => $this->session->user,
        ]);
        if ($data) {
            $_SESSION['success'] = 1;
        } else {
            $_SESSION['success'] = -1;
        }
        redirect('inventaris_peralatan');
    }

    public function add_mutasi()
    {
        $this->redirect_hak_akses('u');
        $id_asset = $this->input->post('id_inventaris_peralatan');
        $data     = $this->inventaris_peralatan_model->add_mutasi([
            'id_inventaris_peralatan' => $id_asset,
            'status_mutasi'           => $this->input->post('status_mutasi'),
            'jenis_mutasi'            => $this->input->post('mutasi'),
            'tahun_mutasi'            => $this->input->post('tahun_mutasi'),
            'harga_jual'              => $this->input->post('harga_jual'),
            'sumbangkan'              => $this->input->post('sumbangkan'),
            'keterangan'              => $this->input->post('keterangan'),
            'visible'                 => 1,
            'created_by'              => $this->session->user,
            'updated_by'              => $this->session->user,
        ]);
        if ($data) {
            $_SESSION['success'] = 1;
        } else {
            $_SESSION['success'] = -1;
        }
        redirect('inventaris_peralatan/mutasi');
    }

    public function update($id)
    {
        $this->redirect_hak_akses('u');
        $data = $this->inventaris_peralatan_model->update($id, [
            'nama_barang'     => $this->input->post('nama_barang_save'),
            'kode_barang'     => $this->input->post('kode_barang'),
            'register'        => $this->input->post('register'),
            'merk'            => $this->input->post('merk'),
            'ukuran'          => $this->input->post('ukuran'),
            'bahan'           => $this->input->post('bahan'),
            'tahun_pengadaan' => $this->input->post('tahun_pengadaan'),
            'no_pabrik'       => $this->input->post('no_pabrik'),
            'no_rangka'       => $this->input->post('no_rangka'),
            'no_mesin'        => $this->input->post('no_mesin'),
            'no_polisi'       => $this->input->post('no_polisi'),
            'no_bpkb'         => $this->input->post('no_bpkb'),
            'asal'            => $this->input->post('asal'),
            'harga'           => $this->input->post('harga'),
            'keterangan'      => $this->input->post('keterangan'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);
        if ($data) {
            $_SESSION['success'] = 1;
        } else {
            $_SESSION['success'] = -1;
        }
        redirect('inventaris_peralatan');
    }

    public function update_mutasi($id)
    {
        $this->redirect_hak_akses('u');
        $id_asset = $this->input->post('id_asset');
        $data     = $this->inventaris_peralatan_model->update_mutasi($id, [
            'jenis_mutasi'  => ($this->input->post('status_mutasi') == 'Hapus') ? $this->input->post('mutasi') : null,
            'status_mutasi' => $this->input->post('status_mutasi'),
            'tahun_mutasi'  => $this->input->post('tahun_mutasi'),
            'harga_jual'    => $this->input->post('harga_jual') || null,
            'sumbangkan'    => $this->input->post('sumbangkan') || null,
            'keterangan'    => $this->input->post('keterangan'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        if ($data) {
            $_SESSION['success'] = 1;
        } else {
            $_SESSION['success'] = -1;
        }
        redirect('inventaris_peralatan/mutasi');
    }

    public function delete($id)
    {
        $this->redirect_hak_akses('h', 'inventaris_peralatan');
        $data = $this->inventaris_peralatan_model->delete($id);
        if ($data) {
            $_SESSION['success'] = 1;
        } else {
            $_SESSION['success'] = -1;
        }
        redirect('inventaris_peralatan');
    }

    public function delete_mutasi($id)
    {
        $this->redirect_hak_akses('h', 'inventaris_peralatan/mutasi');
        $data = $this->inventaris_peralatan_model->delete_mutasi($id);
        if ($data) {
            $_SESSION['success'] = 1;
        } else {
            $_SESSION['success'] = -1;
        }
        redirect('inventaris_peralatan/mutasi');
    }
}
