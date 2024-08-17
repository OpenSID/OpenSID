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

class Dokumen extends Mandiri_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['penduduk_model', 'web_dokumen_model', 'keluarga_model', 'referensi_model']);
        $this->controller = 'layanan-mandiri/dokumen';
    }

    public function index(): void
    {
        $this->render('dokumen/index', [
            'dokumen'            => $this->penduduk_model->list_dokumen($this->is_login->id_pend),
            'jenis_syarat_surat' => $this->referensi_model->list_by_id('ref_syarat_surat', 'ref_syarat_id'),
        ]);
    }

    public function form($id = ''): void
    {
        if ($this->is_login->kk_level == '1') { //Jika Kepala Keluarga
            $data['kk'] = $this->keluarga_model->list_anggota($this->is_login->id_kk);
        }

        if ($id) {
            $data['dokumen']     = $this->web_dokumen_model->get_dokumen($id, $this->is_login->id_pend) ?? show_404();
            $data['anggota']     = array_column($this->web_dokumen_model->get_dokumen_di_anggota_lain($id), 'id_pend');
            $data['aksi']        = 'Ubah';
            $data['form_action'] = site_url("{$this->controller}/ubah/{$id}");
        } else {
            $data['dokumen']     = null;
            $data['anggota']     = null;
            $data['aksi']        = 'Tambah';
            $data['form_action'] = site_url("{$this->controller}/tambah");
        }

        $data['jenis_syarat_surat'] = $this->referensi_model->list_by_id('ref_syarat_surat', 'ref_syarat_id');

        $this->render('dokumen/form', $data);
    }

    public function tambah(): void
    {
        if ($this->web_dokumen_model->insert($this->is_login->id_pend, true)) {
            redirect_with('success', 'Berhasil tambah dokumen');
        } else {
            redirect_with('error', 'Gagal tambah dokumen -> ' . $this->session->error_msg);
        }
    }

    public function ubah($id = ''): void
    {
        $this->web_dokumen_model->get_dokumen($id) ?? show_404();

        if ($this->web_dokumen_model->update($id, $this->is_login->id_pend, true)) {
            redirect_with('success', 'Berhasil ubah dokumen');
        } else {
            redirect_with('error', 'Gagal ubah dokumen -> ' . $this->session->error_msg);
        }
    }

    public function hapus($id = ''): void
    {
        $this->web_dokumen_model->get_dokumen($id, $this->session->is_login->id_pend) ?? show_404();

        if ($this->web_dokumen_model->delete($id)) {
            redirect_with('success', 'Berhasil hapus dokumen');
        } else {
            redirect_with('error', 'Gagal hapus dokumen');
        }
    }

    public function unduh($id = ''): void
    {
        // Ambil nama berkas dari database
        if ($berkas = $this->web_dokumen_model->get_nama_berkas($id, $this->is_login->id_pend)) {
            ambilBerkas($berkas, null, null, LOKASI_DOKUMEN);
        } else {
            redirect_with('error', 'Gagal ubah dokumen');
        }
    }
}
