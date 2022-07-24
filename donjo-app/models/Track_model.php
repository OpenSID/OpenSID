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

use App\Models\Artikel;
use App\Models\BantuanPeserta;
use App\Models\Config;
use App\Models\Dokumen;
use App\Models\LogSurat;
use App\Models\Penduduk;
use App\Models\PendudukMandiri;
use App\Models\Persil;
use App\Models\User;

defined('BASEPATH') || exit('No direct script access allowed');

class Track_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function track_desa($dari)
    {
        if ($this->setting->enable_track == false) {
            return;
        }
        // Track web dan admin masing2 maksimum sekali sehari
        if (strpos(current_url(), 'first') !== false) {
            if (isset($_SESSION['track_web']) && $_SESSION['track_web'] == date('Y m d')) {
                return;
            }
        } else {
            if (isset($_SESSION['track_admin']) && $_SESSION['track_admin'] == date('Y m d')) {
                return;
            }
        }

        $_SESSION['balik_ke'] = $dari;
        $this->kirim_data();
    }

    public function kirim_data()
    {
        // Jangan kirim data ke pantau jika versi demo
        if (config_item('demo_mode')) {
            return;
        }

        if (defined('ENVIRONMENT')) {
            switch (ENVIRONMENT) {
                case 'development':
                    // Jangan kirim data ke pantau jika versi development
                    return;

                case 'testing':
                case 'production':
                    $tracker = config_item('server_pantau');
                    break;

                default:
                    exit('The application environment is not set correctly.');
            }
        }

        $config = Config::first();

        $desa = [
            'nama_desa'           => $config->nama_desa,
            'kode_desa'           => $config->kode_desa,
            'kode_pos'            => $config->kode_pos,
            'nama_kecamatan'      => $config->nama_kecamatan,
            'kode_kecamatan'      => $config->kode_kecamatan,
            'nama_kabupaten'      => $config->nama_kabupaten,
            'kode_kabupaten'      => $config->kode_kabupaten,
            'nama_provinsi'       => $config->nama_propinsi,
            'kode_provinsi'       => $config->kode_propinsi,
            'lat'                 => $config->lat,
            'lng'                 => $config->lng,
            'alamat_kantor'       => $config->alamat_kantor,
            'email_desa'          => $config->email_desa,
            'telepon'             => $config->telepon,
            'url'                 => current_url(),
            'ip_address'          => $_SERVER['SERVER_ADDR'],
            'external_ip'         => get_external_ip(),
            'version'             => AmbilVersi(),
            'jml_penduduk'        => Penduduk::status(1)->count(),
            'jml_artikel'         => Artikel::count(),
            'jml_surat_keluar'    => LogSurat::count(),
            'jml_peserta_bantuan' => BantuanPeserta::count(),
            'jml_mandiri'         => PendudukMandiri::count(),
            'jml_pengguna'        => User::count(),
            'jml_unsur_peta'      => $this->jml_unsur_peta(),
            'jml_persil'          => Persil::count(),
            'jml_dokumen'         => Dokumen::hidup()->count(),
        ];

        if ($this->abaikan($desa)) {
            return;
        }

        $trackSID_output = httpPost($tracker . '/index.php/api/track/desa?token=' . config_item('token_pantau'), $desa);
        $this->cek_notifikasi_TrackSID($trackSID_output);
        if (strpos(current_url(), 'first') !== false) {
            $_SESSION['track_web'] = date('Y m d');
        } else {
            $_SESSION['track_admin'] = date('Y m d');
        }
    }

    private function cek_notifikasi_TrackSID($trackSID_output)
    {
        if (! empty($trackSID_output)) {
            $array_output = json_decode($trackSID_output, true);

            foreach ($array_output as $notif) {
                unset($notif['id']);
                $notif['tgl_berikutnya'] = date('Y-m-d H:i:s');
                $notif['updated_by']     = 0;
                $notif['aksi_ya']        = $this->aksi_valid($notif['aksi_ya']) ?: 'notif/update_pengumuman';
                $notif['aksi_tidak']     = $this->aksi_valid($notif['aksi_tidak']) ?: 'notif/update_pengumuman';
                $notif['aksi']           = $notif['aksi_ya'] . ',' . $notif['aksi_tidak'];
                unset($notif['aksi_ya'], $notif['aksi_tidak']);

                $this->load->model('notif_model');
                $this->notif_model->insert_notif($notif);
            }
        }
    }

    private function aksi_valid($aksi)
    {
        $aksi_valid = ['setting/aktifkan_tracking'];

        return in_array($aksi, $aksi_valid) ? $aksi : '';
    }

    /*
     * Jangan rekam, jika:
     * - ada kolom nama wilayah kurang dari 4 karakter, kecuali desa boleh 3 karakter
     * - ada kolom wilayah yang masih merupakan contoh (berisi karakter non-alpha atau tulisan 'contoh', 'demo' atau 'sampel')
    */
    public function abaikan($data)
    {
        $regex   = '/[^\.a-zA-Z\s:-]|contoh|demo\s+|sampel\s+/i';
        $abaikan = false;
        $desa    = trim($data['nama_desa']);
        $kec     = trim($data['nama_kecamatan']);
        $kab     = trim($data['nama_kabupaten']);
        $prov    = trim($data['nama_provinsi']);
        if (strlen($desa) < 3 || strlen($kec) < 4 || strlen($kab) < 4 || strlen($prov) < 4) {
            $abaikan = true;
        } elseif (preg_match($regex, $desa) || preg_match($regex, $kec) || preg_match($regex, $kab) || preg_match($regex, $prov)) {
            $abaikan = true;
        }

        return $abaikan;
    }

    private function jml_unsur_peta()
    {
        return $this->db->get('area')->num_rows() + $this->db->get('garis')->num_rows() + $this->db->get('lokasi')->num_rows();
    }
}
