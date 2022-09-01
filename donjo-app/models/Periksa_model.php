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

class Periksa_model extends CI_Model
{
    public $email_ganda;
    public $tag_id_ganda;
    public $migrasi_utk_diulang;
    public $kode_panjang;
    public $masalah = [];

    public function __construct()
    {
        parent::__construct();
        $this->migrasi_utk_diulang = $this->deteksi_masalah();
    }

    private function tag_id_ganda()
    {
        return $this->db
            ->select('tag_id_card, COUNT(tag_id_card) as jml')
            ->from('tweb_penduduk')
            ->group_by('tag_id_card')
            ->having('jml > 1')
            ->get()
            ->result_array();
    }

    private function deteksi_masalah()
    {
        $current_version = $this->db
            ->select('value')
            ->where('key', 'current_version')
            ->get('setting_aplikasi')
            ->row()->value;

        $calon = $current_version;

        // pamong_id belum ada
        if ($this->session->db_error['code'] == 1406) {
            $pos       = strpos($this->session->message, "CONCAT_WS('_', kode, id)");
            $calon_ini = $current_version;
            if ($pos !== false) {
                $calon_ini          = '20.12';
                $this->masalah[]    = 'kode_kelompok';
                $this->kode_panjang = $this->deteksi_kode_panjang();
            }
            $calon = version_compare($calon, $calon_ini, '<') ? $calon : $calon_ini;
        }

        // tag_id_ganda
        if ($this->session->db_error['code'] == 1054) {
            $pos       = strpos($this->session->db_error['message'], 'pamong_id');
            $calon_ini = $current_version;
            if ($pos !== false) {
                $calon_ini          = '21.04';
                $this->masalah[]    = 'tag_id_ganda';
                $this->tag_id_ganda = $this->tag_id_ganda();
            }
            $calon = version_compare($calon, $calon_ini, '<') ? $calon : $calon_ini;
        }

        // kartu_tempat_lahir atau kartu_alamat berisi null
        if ($this->session->db_error['code'] == 1138) {
            $pos1      = strpos($this->session->message_query, 'kartu_tempat_lahir');
            $pos2      = strpos($this->session->message_query, 'kartu_alamat');
            $calon_ini = $current_version;
            if ($pos1 !== false && $pos2 !== false) {
                $calon_ini       = '21.05';
                $this->masalah[] = 'kartu_alamat';
            }
            $calon = version_compare($calon, $calon_ini, '<') ? $calon : $calon_ini;
        }

        // Email ganda, menyebabkan migrasi 22.02 gagal.
        // Untuk masalah yg tidak melalui exception, letakkan sesuai urut migrasi
        $this->email_ganda = $this->deteksi_email_ganda();
        if ($this->session->db_error['code'] == 99001 || ! empty($this->email_ganda)) {
            $calon_ini       = '22.02';
            $this->masalah[] = 'email_ganda';
            $calon           = version_compare($calon, $calon_ini, '<') ? $calon : $calon_ini;
        }

        return $calon;
    }

    private function deteksi_kode_panjang()
    {
        // Jika di CONCAT akan melebihi panjang yg diperbolehkan untuk kode (16)
        return $this->db
            ->select('id, kode')
            ->from('kelompok')
            ->where('CHAR_LENGTH(kode) + CHAR_LENGTH(id) + 1 > 16')
            ->get()
            ->result_array();
    }

    private function deteksi_email_ganda()
    {
        $email = $this->db
            ->select('email, COUNT(id) as jml')
            ->from('tweb_penduduk p')
            ->where('email IS NOT NULL')
            ->group_by('email')
            ->having('jml >', 1)
            ->get()
            ->result_array();
        if (! empty($email) && empty($this->session->db_error)) {
            // Berikan kode kustom karena exception dihindari di migrasi.
            $this->session->db_error = [
                'code'    => 99001,
                'message' => 'Email penduduk ganda',
            ];
        }

        return $email;
    }

    public function perbaiki()
    {
        // TODO: login
        $this->session->user_id = $this->session->user_id ?: 1;

        // Perbaiki masalah data yg terdeteksi untuk error yg dilaporkan
        log_message('error', '========= Perbaiki masalah data =========');

        foreach ($this->masalah as $masalah_ini) {
            switch ($masalah_ini) {
                case 'kode_kelompok':
                    $this->perbaiki_kode_kelompok();
                    break;

                case 'email_ganda':
                    $this->perbaiki_email();
                    break;

                case 'tag_id_ganda':
                    $this->perbaiki_tag_id();
                    break;

                case 'kartu_alamat':
                    $this->perbaiki_kartu_alamat();
                    break;

                default:
                    break;
            }
        }
        $this->session->db_error = null;

        // Ulangi migrasi mulai dari migrasi_utk_diulang
        $this->db
            ->where('versi_database', VERSI_DATABASE)
            ->delete('migrasi');
        $this->db
            ->set('value', $this->migrasi_utk_diulang)
            ->where('key', 'current_version')
            ->update('setting_aplikasi');
        $this->load->model('database_model');
        $this->database_model->migrasi_db_cri();
    }

    private function perbaiki_kode_kelompok()
    {
        if (empty($this->kode_panjang)) {
            return;
        }

        $kode = array_column($this->kode_panjang, 'kode');
        log_message('error', 'Kode kelompok berikut telah diperpendek: ' . print_r($this->kode_panjang, true));
        // Ubah kode kelompok dengan panjang id + 1
        $this->db
            ->set('kode', 'SUBSTRING(kode, 1, (CHAR_LENGTH(kode) - CHAR_LENGTH(id) - 1))', false)
            ->where_in('kode', $kode)
            ->update('kelompok');
    }

    // Migrasi 22.02 sengaja gagal jika ada email ganda
    private function perbaiki_email()
    {
        if (empty($this->email_ganda)) {
            return;
        }

        // Ubah semua email kosong menjadi null
        $this->db
            ->set('email', null)
            ->where('email', '')
            ->update('tweb_penduduk');
        // Ubah semua email ganda
        $email_ganda = [];

        foreach ($this->email_ganda as $email) {
            if (empty($email['email'])) {
                continue;
            }
            $daftar_ubah = $this->db
                ->select('id, nik, nama, CONCAT(id, "_", email)')
                ->from('tweb_penduduk')
                ->where('email', $email['email'])
                ->get()
                ->result_array();
            log_message('error', 'Email penduduk berikut telah diubah dengan menambah id: ' . print_r($daftar_ubah, true));
            $email_ganda = array_merge($email_ganda, array_column($daftar_ubah, 'id'));
        }
        // Ubah email ganda dengan menambah id
        $this->db
            ->set('email', 'CONCAT(id, "_", email)', false)
            ->where_in('id', $email_ganda)
            ->update('tweb_penduduk');
    }

    // Migrasi 21.04 tidak antiCONCAT(id, "_", email)sipasi tag_id_card ganda
    private function perbaiki_tag_id()
    {
        if (empty($this->tag_id_ganda)) {
            return;
        }
        $daftar_tag_id = [];

        // Ubah semua tag_id_card kosong menjadi null
        $this->db
            ->set('tag_id_card', null)
            ->where('tag_id_card', '')
            ->update('tweb_penduduk');

        // Ubah semua email ganda
        $email_ganda = [];

        // Ubah tag_id_card ganda menjadi kosong selain yg pertama
        foreach ($this->tag_id_ganda as $tag_ganda) {
            if (empty($tag_ganda['tag_id_card'])) {
                continue;
            }
            $select_pertama = $this->db
                ->select('MIN(id) as id')
                ->from('tweb_penduduk')
                ->where('tag_id_card', $tag_ganda['tag_id_card'])
                ->group_by('tag_id_card')
                ->get_compiled_select();
            $daftar_ubah = $this->db
                ->select('p.id, nik, nama, tag_id_card')
                ->from('tweb_penduduk p')
                ->join('(' . $select_pertama . ') p_pertama', 'p.id > p_pertama.id')
                ->where('p.tag_id_card', $tag_ganda['tag_id_card'])
                ->get()
                ->result_array();
            log_message('error', 'Ubah tag_id_card penduduk berikut menjadi kosong: ' . print_r($daftar_ubah, true));
            $daftar_tag_id = array_merge($daftar_tag_id, array_column($daftar_ubah, 'id'));
        }
        // Ubah tag_id ganda menjadi kosong
        $this->db
            ->set('tag_id_card', null)
            ->where_in('id', $daftar_tag_id)
            ->update('tweb_penduduk');
    }

    // Migrasi 21.05 tidak antisipasi kartu_tempat_lahir atau kartu_alamat berisi null
    private function perbaiki_kartu_alamat()
    {
        $this->db
            ->set('kartu_tempat_lahir', '')
            ->where('kartu_tempat_lahir is null')
            ->update('program_peserta');
        $this->db
            ->set('kartu_alamat', '')
            ->where('kartu_alamat is null')
            ->update('program_peserta');
        log_message('error', "Kolom kartu_tempat_lahir dan kartu_alamat peserta bantuan yang berisi null telah diubah menjadi '' ");
    }
}
