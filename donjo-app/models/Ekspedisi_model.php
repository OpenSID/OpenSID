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

class Ekspedisi_model extends Surat_keluar_model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function autocomplete()
    {
        $this->db->where('ekspedisi', 1);

        return parent::autocomplete();
    }

    public function paging($o = 0, $offset = 0)
    {
        $this->db->where('ekspedisi', 1);

        return parent::paging($o, $offset);
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $this->db->where('ekspedisi', 1);

        return parent::list_data($o, $offset, $limit);
    }

    /**
     * Update data di tabel surat_keluar untuk ekspedisi
     *
     * @param int $id Id surat_keluar untuk query ke database
     *
     * @return void
     */
    public function update($id)
    {
        // Ambil semua data dari var. global $_POST
        $post = $this->input->post();
        $data = $this->validasi($post);

        $this->session->error_msg = null;

        // Ambil nama berkas scan lama dari database
        $berkas_lama = $this->get_tanda_terima($id);

        // Lokasi berkas scan lama (absolut)
        $lokasi_berkas_lama = $this->uploadConfig['upload_path'] . $berkas_lama;
        $lokasi_berkas_lama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH . $lokasi_berkas_lama);

        // Hapus lampiran lama?
        $hapus_lampiran_lama = $post['gambar_hapus'];

        $upload_data = null;

        // Adakah file baru yang akan diupload?
        $ada_berkas = ! empty($_FILES['tanda_terima']['name']);

        // penerapan transaction karena insert ke 2 tabel
        $this->db->trans_start();

        // Ada lampiran file
        if ($ada_berkas === true) {
            // Tes tidak berisi script PHP
            if (isPHP($_FILES['foto']['tmp_name'], $_FILES['tanda_terima']['name'])) {
                $this->session->error_msg .= ' -> Jenis file ini tidak diperbolehkan ';
                $this->session->success = -1;
                redirect('ekspedisi');
            }
            // Cek nama berkas tidak boleh lebih dari 80 karakter (+20 untuk unique id) karena -
            // karakter maksimal yang bisa ditampung kolom surat_keluar.berkas_scan hanya 100 karakter
            if ((strlen($_FILES['tanda_terima']['name']) + 20) >= 100) {
                $this->session->success   = -1;
                $this->session->error_msg = ' -> Nama berkas yang coba Anda unggah terlalu panjang, ' .
                    'batas maksimal yang diijinkan adalah 80 karakter';
                redirect('ekspedisi');
            }
            // Inisialisasi library 'upload'
            $this->upload->initialize($this->uploadConfig);
            // Upload sukses
            if ($this->upload->do_upload('tanda_terima')) {
                $upload_data = $this->upload->data();
                // Hapus berkas dari disk
                // Perhatian: operator 'or' di sini error menggantikan '||'
                $berkas_dihapus = empty($berkas_lama) || (file_exists($lokasi_berkas_lama) && unlink($lokasi_berkas_lama));
                if (! $berkas_dihapus) {
                    $this->session->error_msg .= ' -> Gagal menghapus berkas lama';
                }
                // Buat nama file unik untuk nama file upload
                $nama_file_unik = tambahSuffixUniqueKeNamaFile($upload_data['file_name']);
                // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
                $berkas_direname = rename(
                    $this->uploadConfig['upload_path'] . $upload_data['file_name'],
                    $this->uploadConfig['upload_path'] . $nama_file_unik
                );

                $data['tanda_terima'] = $berkas_direname ? $nama_file_unik : $upload_data['file_name'];
                // Update database dengan `tanda_terima` berisi nama unik
                $this->db->where('id', $id);
                $hasil = $this->db->update('surat_keluar', $data);
                if (! $hasil) {
                    $this->session->error_msg .= ' -> Gagal memperbarui data di database';
                }
            }
            // Upload gagal
            else {
                $this->session->error_msg .= $this->upload->display_errors(null, null);
            }
        }
        // Tidak ada file upload
        else {
            if ($hapus_lampiran_lama) {
                $data['tanda_terima'] = null;
                $hasil                = file_exists($lokasi_berkas_lama) && unlink($lokasi_berkas_lama);
                if (! $hasil) {
                    $this->session->error_msg .= ' -> Gagal menghapus berkas lama';
                }
            }
            $this->db->where('id', $id);
            $hasil = $this->db->update('surat_keluar', $data);
            if (! $hasil) {
                $this->session->error_msg .= ' -> Gagal memperbarui data di database';
            }
        }

        $this->db->trans_complete();

        $this->session->success = null === $this->session->error_msg ? 1 : -1;
    }

    private function validasi($post)
    {
        $data['tanggal_pengiriman'] = tgl_indo_in($post['tanggal_pengiriman']);
        $data['keterangan']         = htmlentities($post['keterangan']);

        return $data;
    }

    public function get_tanda_terima($id)
    {
        return $this->db
            ->select('tanda_terima')
            ->where('id', $id)
            ->get('surat_keluar')
            ->row()->tanda_terima;
    }

    public function list_tahun_surat()
    {
        $this->db->where('ekspedisi', 1);

        return parent::list_tahun_surat();
    }
}
