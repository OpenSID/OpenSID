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

use Esyede\Curly;

require_once APPPATH . '/libraries/Curly.php';

class Notif_model extends CI_Model
{
    /**
     * @var Esyede\Curly
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Curly();
    }

    public function status_langganan()
    {
        if (empty($response = $this->api_pelanggan_pemesanan())) {
            return null;
        }

        $tgl_akhir = $response->body->tanggal_berlangganan->akhir;

        if (empty($tgl_akhir)) { // pemesanan bukan premium
            if ($response->body->pemesanan) {
                foreach ($response->body->pemesanan as $pemesanan) {
                    $akhir[] = $pemesanan->tgl_akhir;
                }

                $masa_berlaku = calculate_date_intervals($akhir);
            }
        } else { // pemesanan premium
            $tgl_akhir    = strtotime($tgl_akhir);
            $masa_berlaku = round(($tgl_akhir - time()) / (60 * 60 * 24));
        }

        switch (true) {
            case $masa_berlaku > 30:
                $status = ['status' => 1, 'warna' => 'lightgreen', 'ikon' => 'fa-battery-full'];
                break;

            case $masa_berlaku > 10:
                $status = ['status' => 2, 'warna' => 'orange', 'ikon' => 'fa-battery-half'];
                break;

            default:
                $status = ['status' => 3, 'warna' => 'pink', 'ikon' => 'fa-battery-empty'];
        }
        $status['masa'] = $masa_berlaku;

        return $status;
    }

    public function permohonan_surat_baru()
    {
        return $this->db->where('status', 1)
            ->get('permohonan_surat')->num_rows();
    }

    public function komentar_baru()
    {
        return $this->db->where('id_artikel !=', LAPORAN_MANDIRI)
            ->where('status', 2)
            ->get('komentar')->num_rows();
    }

    /**
     * Tipe 1: Inbox untuk admin, Outbox untuk pengguna layanan mandiri
     * Tipe 2: Outbox untuk admin, Inbox untuk pengguna layanan mandiri
     *
     * @param mixed $tipe
     * @param mixed $nik
     */
    // TODO : Gunakan id penduduk
    public function inbox_baru($tipe = 1, $nik = '')
    {
        if ($nik) {
            $this->db->where('email', $nik);
        }

        return $this->db
            ->where('id_artikel', LAPORAN_MANDIRI)
            ->where('status', 2)
            ->where('tipe', $tipe)
            ->where('is_archived', 0)
            ->get('komentar')
            ->num_rows();
    }

    // Notifikasi pada layanan mandiri, ditampilkan jika ada surat belum lengkap (0) atau surat siap diambil (3)
    public function surat_perlu_perhatian($id = '')
    {
        return $this->db
            ->where('id_pemohon', $id)
            ->where_in('status', [0, 3])
            ->get('permohonan_surat')
            ->num_rows();
    }

    public function get_notif_by_kode($kode)
    {
        return $this->db->where('kode', $kode)->get('notifikasi')->row_array();
    }

    public function notifikasi($notif)
    {
        $aksi                = explode(',', $notif['aksi']);
        $notif['aksi_ya']    = $aksi[0];
        $notif['aksi_tidak'] = $aksi[1];

        return $notif;
    }

    private function masih_berlaku($notif)
    {
        switch ($notif['kode']) {
            case 'tracking_off':
                if ($this->setting->enable_track) {
                    $this->db->where('kode', 'tracking_off')
                        ->update('notifikasi', ['aktif' => 0]);

                    return false;
                }
                break;
        }

        return true;
    }

    public function update_notifikasi($kode, $non_aktifkan = false)
    {
        // update tabel notifikasi
        $notif = $this->notif_model->get_notif_by_kode($kode);

        $tgl_sekarang     = date('Y-m-d H:i:s');
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

        $this->db->where('kode', $kode)
            ->update('notifikasi', $data);
    }

    // Ambil semua notifikasi yang siap untuk tampil
    // Urut persetujuan dulu
    public function get_semua_notif()
    {
        $hari_ini = new DateTime();
        $compare  = $hari_ini->format('Y-m-d H:i:s');

        return $this->db->where('tgl_berikutnya <=', $compare)
            ->select('*')
            ->select("IF (jenis = 'persetujuan', CONCAT('A',id), CONCAT('Z',id)) AS urut")
            ->where('aktif', 1)
            ->order_by('urut', 'ASC')
            ->get('notifikasi')->result_array();
    }

    public function insert_notif($data)
    {
        $sql = $this->db->insert_string('notifikasi', $data) . duplicate_key_update_str($data);
        $this->db->query($sql);
    }

    /**
     * Ambil data pemesanan dari api layanan.opendeda.id
     *
     * @return mixed
     */
    public function api_pelanggan_pemesanan()
    {
        if (empty($token = $this->setting->layanan_opendesa_token)) {
            $this->session->set_userdata('error_status_langganan', 'Token Pelanggan Kosong.');

            return null;
        }

        $host = config_item('server_layanan');

        // simpan cache
        $response = $this->cache->pakai_cache(function () use ($host, $token) {
            // request ke api layanan.opendesa.id
            return $this->client->get(
                "{$host}/api/v1/pelanggan/pemesanan",
                [],
                [
                    CURLOPT_HTTPHEADER => [
                        'X-Requested-With: XMLHttpRequest',
                        "Authorization: Bearer {$token}",
                    ],
                ]
            );
        }, 'status_langganan', 24 * 60 * 60);

        if ($response->header->http_code != 200) {
            $this->cache->hapus_cache_untuk_semua('status_langganan');
            $this->session->set_userdata('error_status_langganan', "{$response->header->http_code} {$response->body->messages->error}");

            return null;
        }

        return $response;
    }
}
