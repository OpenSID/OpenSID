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

namespace App\Traits;

use App\Models\User;

trait UploadFotoUser
{
    // Konfigurasi untuk library 'upload'
    protected array $uploadConfig = [];

    /**
     * - success: nama berkas yang diunggah
     * - fail: nama berkas lama, kalau ada
     *
     * @param mixed $idUser
     */
    public function urusFoto($idUser = '')
    {
        $this->uploadConfig = [
            'upload_path'   => LOKASI_USER_PICT,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => max_upload() * 1024,
        ];
        if ($idUser) {
            $berkasLama       = User::select('foto')->where('id', $idUser)->first();
            $berkasLama       = is_object($berkasLama) ? $berkasLama->foto : 'kuser.png';
            $lokasiBerkasLama = $this->uploadConfig['upload_path'] . 'kecil_' . $berkasLama;
            $lokasiBerkasLama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH . $lokasiBerkasLama);
        } else {
            $berkasLama = 'kuser.png';
        }

        $nama_foto = $this->uploadFoto('foto', 'man_user');

        if (! empty($nama_foto)) {
            // Ada foto yang berhasil diunggah --> simpan ukuran 100 x 100
            $tipe_file = TipeFile($_FILES['foto']);
            $dimensi   = ['width' => 100, 'height' => 100];
            resizeImage(LOKASI_USER_PICT . $nama_foto, $tipe_file, $dimensi);
            // Nama berkas diberi prefix 'kecil'
            $nama_kecil  = 'kecil_' . $nama_foto;
            $fileRenamed = rename(
                LOKASI_USER_PICT . $nama_foto,
                LOKASI_USER_PICT . $nama_kecil
            );
            if ($fileRenamed) {
                $nama_foto = $nama_kecil;
            }
            // Hapus berkas lama
            if ($berkasLama && $berkasLama !== 'kecil_kuser.png') {
                unlink($lokasiBerkasLama);
                if (file_exists($lokasiBerkasLama)) {
                    $this->session->success = -1;
                }
            }
        }

        return null === $nama_foto ? $berkasLama : str_replace('kecil_', '', $nama_foto);
    }

    /**
     * - success: nama berkas yang diunggah
     * - fail: NULL
     *
     * @param mixed $lokasi
     * @param mixed $redirect
     */
    private function uploadFoto(string $lokasi, string $redirect)
    {
        $this->load->library('upload');
        // Adakah berkas yang disertakan?
        $adaBerkas = ! empty($_FILES[$lokasi]['name']);
        if (! $adaBerkas) {
            return null;
        }

        if ((strlen($_FILES[$lokasi]['name']) + 20) >= 100) {
            set_session('error', 'Nama berkas foto terlalu panjang, maksimal 80 karakter. ' . session('flash_error_msg'));
            redirect($redirect);
        }

        $uploadData = null;
        // Inisialisasi library 'upload'
        $this->upload->initialize($this->uploadConfig);
        // Upload sukses
        if ($this->upload->do_upload($lokasi)) {
            $uploadData = $this->upload->data();
            // Buat nama file unik agar url file susah ditebak dari browser
            $namaClean    = preg_replace('/[^A-Za-z0-9.]/', '_', $uploadData['file_name']);
            $namaFileUnik = tambahSuffixUniqueKeNamaFile($namaClean); // suffix unik ke nama file
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
            $this->session->success   = -1;
            $this->session->error_msg = $this->upload->display_errors(null, null);
        }

        return (empty($uploadData)) ? null : $uploadData['file_name'];
    }
}
