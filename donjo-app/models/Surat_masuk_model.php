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

class Surat_masuk_model extends MY_Model
{
    // Konfigurasi untuk library 'upload'
    protected $uploadConfig = [];

    public function __construct()
    {
        parent::__construct();
        // Untuk dapat menggunakan library upload
        $this->load->library('upload');
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

    private function search_sql()
    {
        if (isset($_SESSION['cari'])) {
            $cari       = $_SESSION['cari'];
            $kw         = $this->db->escape_like_str($cari);
            $kw         = '%' . $kw . '%';
            $search_sql = " AND (u.pengirim LIKE '{$kw}' OR u.isi_singkat LIKE '{$kw}')";

            return $search_sql;
        }
    }

    private function filter_sql()
    {
        if (isset($_SESSION['filter'])) {
            $kf = $_SESSION['filter'];
            if (! empty($kf)) {
                $filter_sql = " AND YEAR(u.tanggal_penerimaan) = {$kf}";
            }

            return $filter_sql;
        }
    }

    // Digunakan untuk paging dan query utama supaya jumlah data selalu sama
    private function list_data_sql()
    {
        $sql = ' FROM surat_masuk u WHERE 1 ';
        $sql .= $this->search_sql();
        $sql .= $this->filter_sql();

        return $sql;
    }

    public function paging($p = 1, $o = 0)
    {
        $sql      = 'SELECT COUNT(*) AS jml ' . $this->list_data_sql();
        $query    = $this->db->query($sql);
        $row      = $query->row_array();
        $jml_data = $row['jml'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        //Ordering SQL
        switch ($o) {
            case 1: $order_sql = ' ORDER BY YEAR(u.tanggal_penerimaan) ASC, u.nomor_urut ASC';
                break;

            case 2: $order_sql = ' ORDER BY YEAR(u.tanggal_penerimaan) DESC, u.nomor_urut DESC';
                break;

            case 3: $order_sql = ' ORDER BY u.tanggal_penerimaan';
                break;

            case 4: $order_sql = ' ORDER BY u.tanggal_penerimaan DESC';
                break;

            case 5: $order_sql = ' ORDER BY u.pengirim';
                break;

            case 6: $order_sql = ' ORDER BY u.pengirim DESC';
                break;

            default:$order_sql = ' ORDER BY u.id';
        }

        //Paging SQL
        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;

        //Main Query
        $sql = 'SELECT u.* ' . $this->list_data_sql();
        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function list_tahun_penerimaan()
    {
        return $this->db->distinct()->select('YEAR(tanggal_penerimaan) AS tahun')->order_by('YEAR(tanggal_penerimaan)', 'DESC')->get('surat_masuk')->result_array();
    }

    public function list_tahun_surat()
    {
        return $this->db->distinct()->
        select('YEAR(tanggal_penerimaan) AS tahun')->
        order_by('YEAR(tanggal_penerimaan)', 'DESC')->
        get('surat_masuk')->result_array();
    }

    /**
     * Insert data baru ke tabel surat_masuk
     *
     * @return void
     */
    public function insert()
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
        if ($adaLampiran === true) {
            // Tes tidak berisi script PHP
            if (isPHP($_FILES['foto']['tmp_name'], $_FILES['foto']['name'])) {
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

        // penerapan transcation karena insert ke 2 tabel
        $this->db->trans_start();

        $indikatorSukses = null === $uploadError && $this->db->insert('surat_masuk', $data);

        $insert_id = $this->db->insert_id();

        // insert ke tabel disposisi surat masuk
        if ($jabatan) {
            $this->insert_disposisi_surat_masuk($insert_id, $jabatan);
        }

        // transaction selesai
        $this->db->trans_complete();

        // Set session berdasarkan hasil operasi
        $_SESSION['success']   = $indikatorSukses ? 1 : -1;
        $_SESSION['error_msg'] = $_SESSION['success'] === 1 ? null : ' -> ' . $uploadError;
    }

    private function validasi_surat_masuk(&$data)
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
     *
     * @return void
     */
    public function update($idSuratMasuk)
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

        $indikatorSukses = false;

        // Hapus lampiran lama?
        $hapusLampiranLama = $data['gambar_hapus'];
        unset($data['gambar_hapus']);

        $uploadData  = null;
        $uploadError = null;

        // Adakah file baru yang akan diupload?
        $adaLampiran = ! empty($_FILES['satuan']['name']);

        // penerapan transcation karena insert ke 2 tabel
        $this->db->trans_start();

        // Ada lampiran file
        if ($adaLampiran === true) {
            // Tes tidak berisi script PHP
            if (isPHP($_FILES['foto']['tmp_name'], $_FILES['satuan']['name'])) {
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
                $_SESSION['error_msg'] = ($oldFileRemoved === true)
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
                $databaseUpdated = $this->db->update('surat_masuk', $data);

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
                $_SESSION['error_msg'] = ($oldFileRemoved === true)
                ? null : ' -> Gagal menghapus berkas lama';
            }
            $this->db->where('id', $idSuratMasuk);
            $databaseUpdated       = $this->db->update('surat_masuk', $data);
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
        return $this->db->where('id', $id)->get('surat_masuk')->row_array();
    }

    // TODO: apakah perlu diambil dari tweb_desa_pamong?
    public function get_pengolah_disposisi()
    {
        $this->load->model('wilayah_model');
        $ref_disposisi[] = 'Sekretaris ' . ucwords($this->setting->sebutan_desa);
        array_push(
            $ref_disposisi,
            'Kasi Pemerintahan',
            'Kasi Kesejahteraan',
            'Kasi Pelayanan',
            'Kaur Keuangan',
            'Kaur Tata Usaha dan Umum',
            'Kaur Perencanaan'
        );
        $list_dusun = $this->wilayah_model->list_data();

        foreach ($list_dusun as $dusun) {
            $ref_disposisi[] = ucwords($this->setting->sebutan_singkatan_kadus) . ' ' . ucwords(strtolower($dusun['dusun']));
        }

        return $ref_disposisi;
    }

    /**
     * Hapus record surat masuk beserta file lampirannya (jika ada)
     *
     * @param string $idSuratMasuk Id surat masuk
     * @param mixed  $semua
     *
     * @return void
     */
    public function delete($idSuratMasuk, $semua = false)
    {
        if (! $semua) {
            $this->session->success   = 1;
            $this->session->error_msg = '';
        }
        // Type check
        $idSuratMasuk = is_string($idSuratMasuk) ? $idSuratMasuk : (string) $idSuratMasuk;
        // Redirect ke halaman surat masuk jika Id kosong
        if (empty($idSuratMasuk)) {
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
                $_SESSION['error_msg'] = $hapusLampiranLama === true
                ? null : ' -> Gagal menghapus berkas dari disk';
            }

            if (null === $_SESSION['error_msg']) {
                $hapusRecordDb         = $this->db->where('id', $idSuratMasuk)->delete('surat_masuk');
                $_SESSION['error_msg'] = $hapusRecordDb === true
                ? null : ' -> Gagal menghapus record dari database';
            }
        } else {
            $hapusRecordDb         = $this->db->where('id', $idSuratMasuk)->delete('surat_masuk');
            $_SESSION['error_msg'] = $hapusRecordDb === true
            ? null : ' -> Gagal menghapus record dari database';
        }

        $_SESSION['success'] = null === $_SESSION['error_msg'] ? 1 : -1;
    }

    public function delete_all()
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
        $sql        = 'SELECT berkas_scan FROM surat_masuk WHERE id = ? LIMIT 1;';
        $query      = $this->db->query($sql, [$idSuratMasuk]);
        $namaBerkas = $query->row();

        return is_object($namaBerkas) ? $namaBerkas->berkas_scan : null;
    }

    public function get_pamong($jabatan)
    {
        return $this->db
            ->select('*')
            ->from('tweb_desa_pamong')
            ->where('jabatan', $jabatan)
            ->get()
            ->row();
    }

    public function insert_disposisi_surat_masuk($id_surat_masuk, array $jabatan)
    {
        foreach ($jabatan as $value) {
            $pamong = $this->get_pamong($value);

            $this->db->insert(
                'disposisi_surat_masuk',
                [
                    'id_surat_masuk' => $id_surat_masuk,
                    'id_desa_pamong' => $pamong->pamong_id,
                    'disposisi_ke'   => $value,
                ]
            );
        }
    }

    public function update_disposisi_surat_masuk($id_surat_masuk, array $jabatan)
    {
        $this->delete_disposisi_surat($id_surat_masuk);

        foreach ($jabatan as $value) {
            $pamong = $this->get_pamong($value);

            $this->db->insert(
                'disposisi_surat_masuk',
                [
                    'id_surat_masuk' => $id_surat_masuk,
                    'id_desa_pamong' => $pamong->pamong_id,
                    'disposisi_ke'   => $value,
                ]
            );
        }
    }

    public function get_disposisi_surat_masuk($id_surat_masuk)
    {
        return $this->db
            ->select('*')
            ->from('disposisi_surat_masuk')
            ->where('id_surat_masuk', $id_surat_masuk)
            ->get()
            ->result_array();
    }

    public function delete_disposisi_surat($id_surat_masuk, $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->db->where('id_surat_masuk', $id_surat_masuk)->delete('disposisi_surat_masuk');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }
}
