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

use Illuminate\Support\Facades\Schema;

class Notif_model extends MY_Model
{
    public function permohonan_surat_baru()
    {
        return $this->config_id()
            ->where('status', 1)
            ->get('permohonan_surat')->num_rows();
    }

    public function komentar_baru()
    {
        return $this->config_id()
            ->where('status', 2)
            ->get('komentar')
            ->num_rows();
    }

    /**
     * Tipe 1: Inbox untuk admin, Outbox untuk pengguna layanan mandiri
     * Tipe 2: Outbox untuk admin, Inbox untuk pengguna layanan mandiri
     *
     * @param mixed $tipe
     * @param mixed $nik
     * @param mixed $penduduk_id
     */
    // TODO : Gunakan id penduduk
    public function inbox_baru($tipe = 1, $penduduk_id = '')
    {
        if (! Schema::hasTable('pesan_mandiri')) {
            return 0;
        }
        if ($penduduk_id) {
            $this->db->where('penduduk_id', $penduduk_id);
        }

        return $this->config_id()
            ->where('status', 2)
            ->where('tipe', $tipe)
            ->where('is_archived', 0)
            ->get('pesan_mandiri')
            ->num_rows();
    }

    // Notifikasi pada layanan mandiri, ditampilkan jika ada surat belum lengkap (0) atau surat siap diambil (3)
    public function surat_perlu_perhatian($id = '')
    {
        return $this->config_id()
            ->where('id_pemohon', $id)
            ->where_in('status', [0, 3])
            ->get('permohonan_surat')
            ->num_rows();
    }

    public function get_notif_by_kode($kode)
    {
        return $this->config_id()->where('kode', $kode)->get('notifikasi')->row_array();
    }

    public function notifikasi($notif)
    {
        $aksi                = explode(',', $notif['aksi']);
        $notif['aksi_ya']    = $aksi[0];
        $notif['aksi_tidak'] = $aksi[1];
        $notif['isi']        = str_replace(['\n', '\"SEBAGAIMANA ADANYA\"'], ['', '"SEBAGAIMANA ADANYA"'], $notif['isi']);

        return $notif;
    }

    public function update_notifikasi($kode, $non_aktifkan = false): void
    {
        // update tabel notifikasi
        $notif            = $this->get_notif_by_kode($kode);
        $frekuensi        = $notif['frekuensi'];
        $string_frekuensi = '+' . $frekuensi . ' Days';
        $tambah_hari      = strtotime($string_frekuensi); // tgl hari ini ditambah frekuensi
        $data             = [
            'tgl_berikutnya' => date('Y-m-d H:i:s', $tambah_hari),
            'updated_by'     => $this->session->user,
            'updated_at'     => date('Y-m-d H:i:s'),
            'aktif'          => 1,
        ];
        // Non-aktifkan pengumuman kalau dicentang
        if ($notif['jenis'] == 'pengumuman' && $non_aktifkan) {
            $data['aktif'] = 0;
        }

        $this->config_id()->where('kode', $kode)
            ->update('notifikasi', $data);
    }

    // Ambil semua notifikasi yang siap untuk tampil
    // Urut persetujuan dulu
    public function get_semua_notif()
    {
        $hari_ini = new DateTime();
        $compare  = $hari_ini->format('Y-m-d H:i:s');

        return $this->config_id()
            ->where('tgl_berikutnya <=', $compare)
            ->select('*')
            ->select("IF (jenis = 'persetujuan', CONCAT('A',id), CONCAT('Z',id)) AS urut")
            ->where('aktif', 1)
            ->order_by('urut', 'ASC')
            ->get('notifikasi')
            ->result_array();
    }

    public function insert_notif($data): void
    {
        $sql = $this->db->insert_string('notifikasi', $data) . duplicate_key_update_str($data);
        $this->db->query($sql);
    }
}
