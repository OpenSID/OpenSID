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

use App\Models\Notifikasi;
use App\Repositories\SettingAplikasiRepository;
use App\Traits\Upload;

defined('BASEPATH') || exit('No direct script access allowed');

class Notif extends Admin_Controller
{
    use Upload;

    public function update_pengumuman(): void
    {
        $kode         = $this->input->post('kode');
        $non_aktifkan = $this->input->post('non_aktifkan');

        // update tabel notifikasi
        $notif            = Notifikasi::where('kode', $kode)->first()->toArray();
        $frekuensi        = $notif['frekuensi'];
        $string_frekuensi = '+' . $frekuensi . ' Days';
        $tambah_hari      = strtotime($string_frekuensi); // tgl hari ini ditambah frekuensi
        $data             = [
            'tgl_berikutnya' => date('Y-m-d H:i:s', $tambah_hari),
            'updated_by'     => ci_auth()->id,
            'updated_at'     => date('Y-m-d H:i:s'),
            'aktif'          => 1,
        ];
        // Non-aktifkan pengumuman kalau dicentang
        if ($notif['jenis'] == 'pengumuman' && $non_aktifkan) {
            $data['aktif'] = 0;
        }
        Notifikasi::where('kode', $kode)->update($data);
    }

    public function update_setting(): void
    {
        $data = $this->input->post();
        $this->uploadImgSetting($data);
        if ((new SettingAplikasiRepository())->updateSetting($data)) {
            set_session('success', 'Berhasil Ubah Data');
        } else {
            set_session('error', 'Gagal Ubah Data. ' . session('flash_error_msg'));
        }

        redirect($_SERVER['HTTP_REFERER']);
    }
}
