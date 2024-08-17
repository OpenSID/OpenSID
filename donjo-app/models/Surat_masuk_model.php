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

use App\Models\Pamong;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat_masuk_model extends MY_Model
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
        return $this->autocomplete_str('pengirim', 'surat_masuk');
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db
                ->group_start()
                ->like('u.pengirim', $cari)
                ->or_like('u.isi_singkat', $cari)
                ->group_end();
        }
    }

    private function filter_sql(): void
    {
        if ($filter = $this->session->filter) {
            $this->db->where('YEAR(u.tanggal_penerimaan)', $filter);
        }
    }

    // Digunakan untuk paging dan query utama supaya jumlah data selalu sama
    private function list_data_sql()
    {
        $this->config_id()->from('surat_masuk u');
        $this->search_sql();
        $this->filter_sql();

        return $this->db;
    }

    public function paging($p = 1, $o = 0)
    {
        return $this->paginasi($p, $this->list_data_sql()->count_all_results());
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $this->list_data_sql();

        //Ordering SQL
        switch ($o) {
            case 1: $order_sql = 'YEAR(u.tanggal_penerimaan) ASC, u.nomor_urut ASC';
                break;

            case 2: $order_sql = 'YEAR(u.tanggal_penerimaan) DESC, u.nomor_urut DESC';
                break;

            case 3: $order_sql = 'u.tanggal_penerimaan';
                break;

            case 4: $order_sql = 'u.tanggal_penerimaan DESC';
                break;

            case 5: $order_sql = 'u.pengirim';
                break;

            case 6: $order_sql = 'u.pengirim DESC';
                break;

            default:$order_sql = 'u.id';
        }

        return $this->db
            ->select('u.*')
            ->order_by($order_sql)
            ->limit($limit, $offset)
            ->get()
            ->result_array();
    }

    public function list_tahun_penerimaan()
    {
        return $this->config_id()->distinct()->select('YEAR(tanggal_penerimaan) AS tahun')->order_by('YEAR(tanggal_penerimaan)', 'DESC')->get('surat_masuk')->result_array();
    }

    public function list_tahun_surat()
    {
        return $this->config_id()
            ->distinct()
            ->select('YEAR(tanggal_penerimaan) AS tahun')
            ->order_by('YEAR(tanggal_penerimaan)', 'DESC')
            ->get('surat_masuk')
            ->result_array();
    }

    /**
     * Insert data baru ke tabel surat_masuk
     */
    public function insert(): void
    {
        // Ambil semua data dari var. global $_POST
        $data              = $this->input->post(null);
        $data['config_id'] = identitas('id');

        unset($data['url_remote'], $data['nomor_urut_lama']);

        // ambil disposisi ke variabel lain karena
        // tidak lagi digunakan pada tabel surat masuk
        $jabatan = $data['disposisi_kepada'];

        // hapus data disposisi dari post
        // surat masuk
        unset($data['disposisi_kepada']);
        $this->validasi_surat_masuk($data);

        // Adakah lampiran yang disertakan?
        $adaLampiran = ! empty($_FILES['satuan']['name']);

        // Cek nama berkas user boleh lebih dari 80 karakter (+20 untuk unique id) karena -
        // karakter maksimal yang bisa ditampung kolom surat_masuk.berkas_scan hanya 100 karakter
        if ($adaLampiran && ((strlen($_FILES['satuan']['name']) + 20) >= 100)) {
            $_SESSION['success']   = -1;
            $_SESSION['error_msg'] = ' -> Nama berkas yang coba Anda unggah terlalu panjang, ' .
            'batas maksimal yang diijinkan adalah 80 karakter';
            redirect('surat_masuk');
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
                session_error($uploadError);
                redirect('surat_masuk');
            }
        }
        // Berkas lampiran
        $data['berkas_scan'] = $adaLampiran && null !== $uploadData
        ? $uploadData['file_name'] : null;

        try {
            $this->db->trans_start();
            $this->db->insert('surat_masuk', $data);
            $insert_id = $this->db->insert_id();

            // insert ke tabel disposisi surat masuk
            if ($jabatan) {
                $this->disposisi_surat_masuk($insert_id, $jabatan);
            }

            // transaction selesai
            $this->db->trans_complete();
            session_success();
        } catch (Exception $e) {
            var_dump('fdsd');

            exit();
        }
    }

    private function validasi_surat_masuk(&$data): void
    {
        // Normalkan tanggal
        $data['tanggal_penerimaan'] = tgl_indo_in($data['tanggal_penerimaan']);
        $data['tanggal_surat']      = tgl_indo_in($data['tanggal_surat']);
        // Bersihkan data
        $data['nomor_surat']   = strip_tags($data['nomor_surat']);
        $data['pengirim']      = alfanumerik_spasi($data['pengirim']);
        $data['isi_singkat']   = strip_tags($data['isi_singkat']);
        $data['isi_disposisi'] = strip_tags($data['isi_disposisi']);
    }

    /**
     * Update data di tabel surat_masuk
     *
     * @param int $idSuratMasuk Id berkas untuk query ke database
     */
    public function update($idSuratMasuk): void
    {
        // Ambil semua data dari var. global $_POST
        $data = $this->input->post(null);
        unset($data['url_remote'], $data['nomor_urut_lama']);

        // ambil disposisi ke variabel lain karena
        // tidak lagi digunakan pada tabel surat masuk
        $jabatan = $data['disposisi_kepada'];
        // hapus data disposisi dari post
        // surat masuk
        unset($data['disposisi_kepada']);

        $_SESSION['error_msg'] = null;

        $this->validasi_surat_masuk($data);

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
            if (isPHP($_FILES['satuan']['tmp_name'], $_FILES['satuan']['name'])) {
                $_SESSION['error_msg'] .= ' -> Jenis file ini tidak diperbolehkan ';
                $_SESSION['success'] = -1;
                redirect('man_user');
            }
            // Cek nama berkas tidak boleh lebih dari 80 karakter (+20 untuk unique id) karena -
            // karakter maksimal yang bisa ditampung kolom surat_masuk.berkas_scan hanya 100 karakter
            if ((strlen($_FILES['satuan']['name']) + 20) >= 100) {
                $_SESSION['success']   = -1;
                $_SESSION['error_msg'] = ' -> Nama berkas yang coba Anda unggah terlalu panjang, ' .
            'batas maksimal yang diijinkan adalah 80 karakter';
                redirect('surat_masuk');
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
                // Update database dengan `berkas_scan` berisi nama unik
                $this->db->where('id', $idSuratMasuk);
                $databaseUpdated = $this->config_id()->update('surat_masuk', $data);

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
            $this->db->where('id', $idSuratMasuk);
            $databaseUpdated       = $this->config_id()->update('surat_masuk', $data);
            $_SESSION['error_msg'] = ($databaseUpdated === true)
            ? null : 'Gagal memperbarui data di database';
            $adaBerkasLamaDiDB = null !== $berkasLama;
        }

        if ($jabatan) {
            $this->disposisi_surat_masuk($idSuratMasuk, $jabatan);
        }

        $this->db->trans_complete();

        $_SESSION['success'] = null === $_SESSION['error_msg'] ? 1 : -1;
    }

    public function get_surat_masuk($id)
    {
        return $this->config_id()->where('id', $id)->get('surat_masuk')->row_array();
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
            redirect('surat_masuk');
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
                $hapusRecordDb         = $this->config_id()->where('id', $idSuratMasuk)->delete('surat_masuk');
                $_SESSION['error_msg'] = $hapusRecordDb === true
                ? null : ' -> Gagal menghapus record dari database';
            }
        } else {
            $hapusRecordDb         = $this->config_id()->where('id', $idSuratMasuk)->delete('surat_masuk');
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
     * @param string $idSuratMasuk Id pada tabel surat_masuk
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
            ->get('surat_masuk')
            ->row()
            ->berkas_scan;
    }

    public function disposisi_surat_masuk($id_surat_masuk, array $jabatan): void
    {
        $this->delete_disposisi_surat($id_surat_masuk);

        foreach ($jabatan as $value) {
            $this->db->insert(
                'disposisi_surat_masuk',
                [
                    'config_id'      => identitas('id'),
                    'id_surat_masuk' => $id_surat_masuk,
                    'id_desa_pamong' => Pamong::where('jabatan_id', $value)->first()->pamong_id,
                    'disposisi_ke'   => $value,
                ]
            );
        }
    }

    public function delete_disposisi_surat($id_surat_masuk, $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id_surat_masuk', $id_surat_masuk)->delete('disposisi_surat_masuk');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function remove_character(): void
    {
        $surat_masuk = $this->config_id()->select('*')->get('surat_masuk')->result_array();

        foreach ($surat_masuk as $data) {
            $this->config_id();
            $this->db->where('id', $data['id']);
            $this->db->update(
                'surat_masuk',
                [
                    'pengirim'      => trim(preg_replace('/\n|\r/', ' ', $data['pengirim'])),
                    'isi_singkat'   => trim(preg_replace('/\n|\r/', ' ', $data['isi_singkat'])),
                    'isi_disposisi' => trim(preg_replace('/\n|\r/', ' ', $data['isi_disposisi'])),
                ]
            );
        }
    }
}
