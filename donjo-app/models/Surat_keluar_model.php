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

class Surat_keluar_model extends MY_Model
{
    // Konfigurasi untuk library 'upload'
    protected array $uploadConfig;

    public function __construct()
    {
        parent::__construct();
        // Untuk dapat menggunakan library upload
        $this->load->library('MY_Upload', null, 'upload');
        // Untuk dapat menggunakan fungsi generator()
        $this->load->helper('donjolib');
        // Helper upload file
        $this->load->helper('pict_helper');
        $this->uploadConfig = [
            'upload_path'   => LOKASI_ARSIP,
            'allowed_types' => 'gif|jpg|jpeg|png|pdf',
            'max_size'      => max_upload() * 1024,
        ];
    }

    public function autocomplete()
    {
        // TODO: tambahkan kata2 dari isi_singkat
        return $this->autocomplete_str('tujuan', 'surat_keluar');
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db
                ->group_start()
                ->like('u.tujuan', $cari)
                ->or_like('u.isi_singkat', $cari)
                ->group_end();
        }
    }

    private function filter_sql(): void
    {
        if ($filter = $this->session->filter) {
            $this->db->where('YEAR(u.tanggal_surat)', $filter);
        }
    }

    // Digunakan untuk paging dan query utama supaya jumlah data selalu sama
    private function list_data_sql(): void
    {
        $this->config_id('u')->from('surat_keluar u');
        $this->search_sql();
        $this->filter_sql();
    }

    public function paging($p = 1, $o = 0)
    {
        $this->list_data_sql();
        $jml_data = $this->db
            ->select('COUNT(id) AS jml')
            ->get()
            ->row()
            ->jml;

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $this->list_data_sql();

        //Ordering
        switch ($o) {
            case 1: $order = ' YEAR(u.tanggal_surat) ASC, u.nomor_urut ASC';
                break;

            case 2: $order = ' YEAR(u.tanggal_surat) DESC, u.nomor_urut DESC';
                break;

            case 3: $order = ' u.tanggal_surat';
                break;

            case 4: $order = ' u.tanggal_surat DESC';
                break;

            case 5: $order = ' u.tujuan';
                break;

            case 6: $order = ' u.tujuan DESC';
                break;

            case 7: $order = ' u.tanggal_pengiriman';
                break;

            case 8: $order = ' u.tanggal_pengiriman DESC';
                break;

            default:$order = ' u.id';
        }

        return $this->db
            ->select('u.*')
            ->order_by($order)
            ->limit($limit, $offset)
            ->get()
            ->result_array();
    }

    public function list_tahun_surat()
    {
        return $this->config_id()
            ->distinct()
            ->select('YEAR(tanggal_surat) AS tahun')
            ->order_by('YEAR(tanggal_surat)', 'DESC')
            ->get('surat_keluar')
            ->result_array();
    }

    /**
     * Insert data baru ke tabel surat_keluar
     */
    public function insert(): void
    {
        // Ambil semua data dari var. global $_POST
        $data              = $this->input->post(null);
        $data['config_id'] = identitas('id');

        unset($data['url_remote'], $data['nomor_urut_lama']);

        $this->validasi($data);
        $data['created_by'] = $data['updated_by'] = $this->session->user;

        // Adakah lampiran yang disertakan?
        $adaLampiran = ! empty($_FILES['satuan']['name']);

        // Cek nama berkas user boleh lebih dari 80 karakter (+20 untuk unique id) karena -
        // karakter maksimal yang bisa ditampung kolom surat_keluar.berkas_scan hanya 100 karakter
        if ($adaLampiran && ((strlen($_FILES['satuan']['name']) + 20) >= 100)) {
            $_SESSION['success']   = -1;
            $_SESSION['error_msg'] = ' -> Nama berkas yang coba Anda unggah terlalu panjang, ' .
                'batas maksimal yang diijinkan adalah 80 karakter';
            redirect('surat_keluar');
        }

        $uploadData  = null;
        $uploadError = null;
        // Ada lampiran file
        if ($adaLampiran) {
            // Tes tidak berisi script PHP
            if (isPHP($_FILES['satuan']['tmp_name'], $_FILES['satuan']['name'])) {
                $_SESSION['error_msg'] .= ' -> Jenis file ini tidak diperbolehkan ';
                $_SESSION['success'] = -1;
                redirect('man_user');
            }
            // Inisialisasi library 'upload'
            $this->upload->initialize($this->uploadConfig);
            // Upload sukses
            if ($this->upload->do_upload('satuan')) {
                $uploadData = $this->upload->data();
                // Buat nama file unik agar url file susah ditebak dari browser
                $namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
                // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
                $fileRenamed = rename(
                    $this->uploadConfig['upload_path'] . $uploadData['file_name'],
                    $this->uploadConfig['upload_path'] . $namaFileUnik
                );
                // Ganti nama di array upload jika file berhasil di-rename --
                // jika rename gagal, fallback ke nama asli
                $uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];
            }
            // Upload gagal
            else {
                $uploadError = $this->upload->display_errors(null, null);
            }
        }
        // Berkas lampiran
        $data['berkas_scan'] = $adaLampiran && null !== $uploadData
            ? $uploadData['file_name'] : null;
        $data['created_by'] = $this->session->user;
        $data['updated_by'] = $this->session->user;
        // penerapan transcation karena insert ke 2 tabel
        $this->db->trans_start();

        $indikatorSukses = null === $uploadError && $this->db->insert('surat_keluar', $data);

        $this->db->insert_id();

        // transaction selesai
        $this->db->trans_complete();

        // Set session berdasarkan hasil operasi
        status_sukses($indikatorSukses); //Tampilkan Pesan
        $_SESSION['error_msg'] = $_SESSION['success'] === 1 ? null : ' -> ' . $uploadError;
    }

    private function validasi(&$data): void
    {
        // Normalkan tanggal
        $data['tanggal_surat'] = tgl_indo_in($data['tanggal_surat']);
        // Bersihkan data
        $data['nomor_surat'] = nomor_surat_keputusan(strip_tags($data['nomor_surat']));
        $data['tujuan']      = strip_tags($data['tujuan']);
        $data['isi_singkat'] = strip_tags($data['isi_singkat']);
    }

    /**
     * Update data di tabel surat_keluar
     *
     * @param int $idSuratMasuk Id berkas untuk query ke database
     */
    public function update($idSuratMasuk): void
    {
        // Ambil semua data dari var. global $_POST
        $data = $this->input->post(null);
        unset($data['url_remote'], $data['nomor_urut_lama']);

        $this->validasi($data);
        $data['updated_by'] = $this->session->user;

        $_SESSION['error_msg'] = null;

        // Ambil nama berkas scan lama dari database
        $berkasLama = $this->getNamaBerkasScan($idSuratMasuk);

        // Lokasi berkas scan lama (absolut)
        $lokasiBerkasLama = $this->uploadConfig['upload_path'] . $berkasLama;
        $lokasiBerkasLama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH . $lokasiBerkasLama);

        // Hapus lampiran lama?
        $hapusLampiranLama = $data['gambar_hapus'];
        unset($data['gambar_hapus']);

        $uploadData = null;

        // Adakah file baru yang akan diupload?
        $adaLampiran = ! empty($_FILES['satuan']['name']);

        // penerapan transcation karena insert ke 2 tabel
        $this->db->trans_start();

        // Ada lampiran file
        if ($adaLampiran) {
            // Tes tidak berisi script PHP
            if (isPHP($_FILES['foto']['tmp_name'], $_FILES['satuan']['name'])) {
                $_SESSION['error_msg'] .= ' -> Jenis file ini tidak diperbolehkan ';
                $_SESSION['success'] = -1;
                redirect('surat_keluar');
            }
            // Cek nama berkas tidak boleh lebih dari 80 karakter (+20 untuk unique id) karena -
            // karakter maksimal yang bisa ditampung kolom surat_keluar.berkas_scan hanya 100 karakter
            if ((strlen($_FILES['satuan']['name']) + 20) >= 100) {
                $_SESSION['success']   = -1;
                $_SESSION['error_msg'] = ' -> Nama berkas yang coba Anda unggah terlalu panjang, ' .
                'batas maksimal yang diijinkan adalah 80 karakter';
                redirect('surat_keluar');
            }
            // Inisialisasi library 'upload'
            $this->upload->initialize($this->uploadConfig);
            // Upload sukses
            if ($this->upload->do_upload('satuan')) {
                $uploadData = $this->upload->data();
                // Hapus berkas dari disk
                $oldFileRemoved        = unlink($lokasiBerkasLama) && ! file_exists($lokasiBerkasLama);
                $_SESSION['error_msg'] = ($oldFileRemoved)
                    ? null : ' -> Gagal menghapus berkas lama';
                // Buat nama file unik untuk nama file upload
                $namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
                // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
                $uploadedFileRenamed = rename(
                    $this->uploadConfig['upload_path'] . $uploadData['file_name'],
                    $this->uploadConfig['upload_path'] . $namaFileUnik
                );

                $uploadData['file_name'] = ($uploadedFileRenamed === false) ?: $namaFileUnik;

                $data['berkas_scan'] = $uploadData['file_name'];
                $data['updated_by']  = $this->session->user;
                $data['updated_at']  = date('Y-m-d H:i:s');
                // Update database dengan `berkas_scan` berisi nama unik
                $this->db->where('id', $idSuratMasuk);
                $databaseUpdated = $this->config_id()->update('surat_keluar', $data);

                $_SESSION['error_msg'] = ($databaseUpdated === true)
                    ? null : 'Gagal memperbarui data di database';
            }
            // Upload gagal
            else {
                $_SESSION['error_msg'] = $this->upload->display_errors(null, null);
            }
        }
        // Tidak ada file upload
        else {
            unset($data['berkas_scan']);
            if ($hapusLampiranLama) {
                $data['berkas_scan']   = null;
                $adaBerkasLamaDiDisk   = file_exists($lokasiBerkasLama);
                $oldFileRemoved        = $adaBerkasLamaDiDisk && unlink($lokasiBerkasLama);
                $_SESSION['error_msg'] = ($oldFileRemoved)
                    ? null : ' -> Gagal menghapus berkas lama';
            }
            $databaseUpdated       = $this->config_id()->where('id', $idSuratMasuk)->update('surat_keluar', $data);
            $_SESSION['error_msg'] = ($databaseUpdated === true)
                ? null : 'Gagal memperbarui data di database';
            $adaBerkasLamaDiDB = null !== $berkasLama;
        }

        $this->db->trans_complete();

        $_SESSION['success'] = null === $_SESSION['error_msg'] ? 1 : -1;
    }

    public function get_surat_keluar($id)
    {
        return $this->config_id()->where('id', $id)->get('surat_keluar')->row_array();
    }

    /**
     * Hapus record surat masuk beserta file lampirannya (jika ada)
     *
     * @param string $idSuratMasuk Id surat masuk
     * @param mixed  $semua
     */
    public function delete($idSuratMasuk, $semua = false): void
    {
        if (! $semua) {
            $this->session->success   = 1;
            $this->session->error_msg = '';
        }
        // Type check
        $idSuratMasuk = is_string($idSuratMasuk) ? $idSuratMasuk : (string) $idSuratMasuk;
        // Redirect ke halaman surat masuk jika Id kosong
        if ($idSuratMasuk === '') {
            $_SESSION['success']   = -1;
            $_SESSION['error_msg'] = ' -> Data yang anda minta tidak ditemukan';
            redirect('surat_keluar');
        }

        $_SESSION['error_msg'] = null;

        $namaBerkas = $this->getNamaBerkasScan($idSuratMasuk);

        if (null !== $namaBerkas) {
            $lokasiBerkasLama = $this->uploadConfig['upload_path'] . $namaBerkas;
            $lokasiBerkasLama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH . $lokasiBerkasLama);

            if (file_exists($lokasiBerkasLama)) {
                $hapusLampiranLama     = unlink($lokasiBerkasLama);
                $hapusLampiranLama     = ! file_exists($lokasiBerkasLama);
                $_SESSION['error_msg'] = $hapusLampiranLama
                    ? null : ' -> Gagal menghapus berkas dari disk';
            }

            if (null === $_SESSION['error_msg']) {
                $hapusRecordDb         = $this->config_id()->where('id', $idSuratMasuk)->delete('surat_keluar');
                $_SESSION['error_msg'] = $hapusRecordDb === true
                    ? null : ' -> Gagal menghapus record dari database';
            }
        } else {
            $hapusRecordDb         = $this->config_id()->where('id', $idSuratMasuk)->delete('surat_keluar');
            $_SESSION['error_msg'] = $hapusRecordDb === true
                ? null : ' -> Gagal menghapus record dari database';
        }

        $_SESSION['success'] = null === $_SESSION['error_msg'] ? 1 : -1;
    }

    public function delete_all(): void
    {
        $this->session->success   = 1;
        $this->session->error_msg = '';

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    //! ==============================================================
    //! Helper Methods
    //! ==============================================================
    /**
     * Ambil nama berkas scan dari database berdasarkan Id surat masuk
     *
     * @param string $idSuratMasuk Id pada tabel surat_keluar
     * @param string $kolom        Kolom yang akan diambil datanya
     *
     * @return string|null
     */
    public function getNamaBerkasScan($idSuratMasuk)
    {
        // Ambil nama berkas dari database
        return $this->config_id()
            ->select('berkas_scan')
            ->where('id', $idSuratMasuk)
            ->get('surat_keluar')
            ->row()
            ->berkas_scan;
    }

    public function untuk_ekspedisi($id, $masuk = 0): void
    {
        $this->config_id()
            ->where('id', $id)
            ->set('ekspedisi', $masuk)
            ->update('surat_keluar');
    }
}
