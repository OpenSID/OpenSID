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

use App\Models\FormatSurat;

class Surat_master_model extends MY_Model
{
    protected $table = 'tweb_surat_format';

    public function insert()
    {
        $data = $_POST;
        $this->validasi_surat($data);

        $pemohon_surat = $data['pemohon_surat'];
        unset($data['pemohon_surat']);
        $data['url_surat'] = str_replace([' ', '-'], '_', $data['nama']);
        $data['url_surat'] = 'surat_' . strtolower($data['url_surat']);
        /** pastikan belum ada url suratnya */
        if (FormatSurat::isExist($data['url_surat'])) {
            $_SESSION['success'] = -2;

            return;
        }
        $outp     = $this->db->insert('tweb_surat_format', $data);
        $id       = $this->db->insert_id();
        $raw_path = 'template-surat/raw/';

        // Folder untuk surat ini
        $folder_surat = LOKASI_SURAT_DESA . $data['url_surat'] . '/';
        if (! file_exists($folder_surat)) {
            mkdir($folder_surat, 0777, true);
        }

        if ($pemohon_surat == 'warga') {
            $template = 'template.rtf';
            $form     = 'form.raw';
        } else {
            $template = 'template_non_warga.rtf';
            $form     = 'form_non_warga.raw';
        }

        // index.html untuk menutup akses ke folder melalui browser
        copy($raw_path . 'index.html', $folder_surat . 'index.html');

        //doc
        copy($raw_path . $template, $folder_surat . $data['url_surat'] . '.rtf');

        //form
        $file   = $raw_path . $form;
        $handle = fopen($file, 'rb');
        $buffer = stream_get_contents($handle);
        $berkas = $folder_surat . $data['url_surat'] . '.php';
        $handle = fopen($berkas, 'w+b');
        $buffer = str_replace('[nama_surat]', "Surat {$data['nama']}", $buffer);
        fwrite($handle, $buffer);
        fclose($handle);

        if ($pemohon_surat == 'warga') {
            // cetak
            $file       = $raw_path . 'print.raw';
            $handle     = fopen($file, 'rb');
            $buffer     = stream_get_contents($handle);
            $berkas     = $folder_surat . 'print_' . $data['url_surat'] . '.php';
            $handle     = fopen($berkas, 'w+b');
            $nama_surat = strtoupper($data['nama']);
            $buffer     = str_replace('[nama_surat]', "SURAT {$nama_surat}", $buffer);
            fwrite($handle, $buffer);
            fclose($handle);
        } else {
            // data untuk form
            copy($raw_path . 'data_form_non_warga.raw', $folder_surat . 'data_form_' . $data['url_surat'] . '.php');
        }

        status_sukses($outp); //Tampilkan Pesan

        return $id;
    }

    private function validasi_surat(&$data)
    {
        $data['nama'] = nama_terbatas($data['nama']);
    }

    public function update($id = 0)
    {
        $data = $_POST;
        $this->validasi_surat($data);

        $before = FormatSurat::find($id) ?? show_404();

        $outp = $this->db
            ->where('id', $id)
            ->update($this->table, $data);

        if ($outp) {
            $surat_baru  = 'surat_' . str_replace([' ', '-'], '_', strtolower($data['nama']));
            $lokasi_baru = LOKASI_SURAT_DESA . $surat_baru;

            // Ubah nama folder penyimpanan template surat
            rename($before->lokasi_surat, $lokasi_baru);

            // Ubah nama file surat
            rename($lokasi_baru . '/' . $before->url_surat . '.rtf', $lokasi_baru . '/' . $surat_baru . '.rtf');
            rename($lokasi_baru . '/' . $before->url_surat . '.php', $lokasi_baru . '/' . $surat_baru . '.php');
            rename($lokasi_baru . '/data_rtf_' . $before->url_surat . '.php', $lokasi_baru . '/data_rtf_' . $surat_baru . '.php');
            rename($lokasi_baru . '/data_form_' . $before->url_surat . '.php', $lokasi_baru . '/data_form_' . $surat_baru . '.php');
        }

        status_sukses($outp); //Tampilkan Pesan

        return $id;
    }

    public function upload($url = '')
    {
        // Folder desa untuk surat ini
        $folder_surat = LOKASI_SURAT_DESA . $url . '/';
        if (! file_exists($folder_surat)) {
            mkdir($folder_surat, 0755, true);
        }

        $nama_file_rtf = $url . '.rtf';
        $this->uploadBerkas('rtf', $folder_surat, 'surat', 'surat_master', $nama_file_rtf);
        $this->salin_lampiran($url, $folder_surat);
    }

    // Lampiran surat perlu disalin ke folder surata di LOKASI_SURAT_DESA, karena
    // file lampiran surat dianggap ada di folder yang sama dengan tempat template surat RTF
    private function salin_lampiran($url, $folder_surat)
    {
        $this->load->model('surat_model');
        $surat = $this->surat_model->get_surat($url);
        if (! $surat['lampiran']) {
            return;
        }

        // $lampiran_surat dalam bentuk seperti "f-1.08.php, f-1.25.php, f-1.27.php"
        $daftar_lampiran = explode(',', $surat['lampiran']);

        foreach ($daftar_lampiran as $lampiran) {
            if (! file_exists($folder_surat . $lampiran)) {
                copy('template-surat/' . $url . '/' . $lampiran, $folder_surat . $lampiran);
            }
        }
    }

    public function delete($id = null)
    {
        // Ambil data surat sebelum dihapus
        $before = FormatSurat::find($id) ?? show_404();

        if (in_array($before->jenis, [1, 3])) {
            redirect_with('error', 'Gagal Hapus Data, Surat Bawaan Sistem Tidak Dapat Dihapus');
        }

        $outp = $before->delete();

        if ($outp) {
            //hapus file dan folder penyimpanan template surat
            delete_files($before->lokasi_surat, true, false, 1);

            return true;
        }

        return false;
    }

    public function deleteAll()
    {
        $outp = true;

        foreach ($this->input->post('id_cb') as $id) {
            $outp = $outp && $this->delete($id);
        }

        return $outp;
    }

    /**
     * - success: nama berkas yang diunggah
     * - fail: NULL
     *
     * @param mixed $allowed_types
     * @param mixed $upload_path
     * @param mixed $lokasi
     * @param mixed $redirect
     * @param mixed $nama_file
     *
     * @return
     */
    private function uploadBerkas($allowed_types, $upload_path, $lokasi, $redirect, $nama_file)
    {
        // Untuk dapat menggunakan library upload
        $this->load->library('upload');
        // Untuk dapat menggunakan fungsi generator()
        $this->load->helper('donjolib');
        $this->upload_config = [
            'upload_path'   => $upload_path,
            'allowed_types' => $allowed_types,
            'max_size'      => max_upload() * 1024,
            'file_name'     => $nama_file,
            'overwrite'     => true,
        ];
        // Adakah berkas yang disertakan?
        $ada_berkas = ! empty($_FILES[$lokasi]['name']);
        if ($ada_berkas !== true) {
            return null;
        }
        // Tes tidak berisi script PHP
        if (isPHP($_FILES[$lokasi]['tmp_name'], $_FILES[$lokasi]['name'])) {
            set_session('error', ' -> Jenis file ini tidak diperbolehkan ');
            redirect($redirect);
        }

        $upload_data = null;

        // Inisialisasi library 'upload'
        $this->upload->initialize($this->upload_config);
        // Upload sukses
        if ($this->upload->do_upload($lokasi)) {
            $upload_data = $this->upload->data();
        }
        // Upload gagal
        else {
            redirect_with('error', 'Gagal Unggah Data');
        }

        return (! empty($upload_data)) ? $upload_data['file_name'] : null;
    }
}
