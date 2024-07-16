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

class Mandiri_model extends MY_Model
{
    protected $table = 'tweb_penduduk_mandiri';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('anjungan_model');
        $this->cek_anjungan = $this->anjungan_model->cek_anjungan();
        $this->load->library('OTP/OTP_manager', null, 'otp_library');
    }

    public function autocomplete()
    {
        $data = $this->config_id('pm')
            ->select('p.nama')
            ->from('tweb_penduduk_mandiri pm')
            ->join('penduduk_hidup p', 'p.id = pm.id_pend', 'left')
            ->limit(15)
            ->get()
            ->result_array();

        return autocomplete_data_ke_str($data);
    }

    private function search_sql(): void
    {
        $cari = $this->session->cari;
        if ($cari) {
            $this->db
                ->group_start()
                ->like('p.nik', $cari)
                ->or_like('p.nama', $cari)
                ->group_end();
        }
    }

    private function list_data_sql(): void
    {
        $this->config_id('pm')
            ->from('tweb_penduduk_mandiri pm')
            ->join('penduduk_hidup p', 'pm.id_pend = p.id');

        $this->search_sql();
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $this->db->select('pm.*, p.nama, p.nik, p.telepon');

        $this->list_data_sql();

        switch ($o) {
            case 1:
                $this->db->order_by('p.nik');
                break;

            case 2:
                $this->db->order_by('p.nik', 'DESC');
                break;

            case 3:
                $this->db->order_by('p.nama');
                break;

            case 4:
                $this->db->order_by('p.nama', 'DESC');
                break;

            case 5:
                $this->db->order_by('pm.tanggal_buat');
                break;

            case 6:
                $this->db->order_by('pm.tanggal_buat', 'DESC');
                break;

            case 7:
                $this->db->order_by('pm.last_login');
                break;

            case 8:
                $this->db->order_by('pm.last_login', 'DESC');
                break;

            default:
                $this->db->order_by('p.nik');
        }

        $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }

    private function generate_pin()
    {
        return strrev(random_int(100000, 999999));
    }

    // TODO : Digunakan dimana ?
    private function list_data_ajax_sql($cari = ''): void
    {
        $this->config_id('u')
            ->from('tweb_penduduk_mandiri u')
            ->join('penduduk_hidup n', 'u.id_pend = n.id', 'left')
            ->join('tweb_wil_clusterdesa w', 'n.id_cluster = w.id', 'left');

        if ($cari) {
            $this->db->where("(nik like '%{$cari}%' or nama like '%{$cari}%')");
        }
    }

    // TODO : Digunakan dimana ?
    public function list_data_ajax($cari, $page)
    {
        $this->list_data_ajax_sql($cari);
        $jml = $this->db
            ->select('count(u.id_pend) as jml')
            ->get()
            ->row()
            ->jml;

        $result_count = 25;
        $offset       = ($page - 1) * $result_count;

        $this->list_data_ajax_sql($cari);
        $this->db
            ->distinct()
            ->select('u.id_pend, nik, nama, w.dusun, w.rw, w.rt')
            ->limit($result_count, $offset);
        $data = $this->db->get()->result_array();

        foreach ($data as $row) {
            $nama                = addslashes($row['nama']);
            $alamat              = addslashes("Alamat: RT-{$row['rt']}, RW-{$row['rw']} {$row['dusun']}");
            $outp                = "{$row['nik']} - {$nama} \n {$alamat}";
            $pendaftar_mandiri[] = [
                'id'   => $row['nik'],
                'text' => $outp,
            ];
        }

        $end_count  = $offset + $result_count;
        $more_pages = $end_count < $jml;

        return [
            'results'    => $pendaftar_mandiri,
            'pagination' => [
                'more' => $more_pages,
            ],
        ];
    }

    public function get_pendaftar_mandiri($nik)
    {
        return $this->config_id()
            ->select('id, nik, nama')
            ->from('penduduk_hidup')
            ->where('status', 1)
            ->where('nik', $nik)
            ->get()
            ->row_array();
    }

    //Pendaftaran Layanan Mandiri oleh Masing-masing Penduduk secara mandiri
    public function pendaftaran_mandiri($data): void
    {
        //cek penduduk apakah sudah terdaftar di data kependudukan
        if (null !== ($penduduk = $this->cek_pendaftaran($data['nama'], $data['nik'], $data['tgl_lahir'], $data['kk']))) {
            if (! $this->cek_layanan_mandiri($penduduk->id)) {
                $data_penduduk['id'] = $penduduk->id;
                $this->insert_daftar($data_penduduk, $data);

                $session = [
                    'is_verifikasi' => $data_penduduk,
                ];
                $this->session->set_userdata($session);

                // Ambil data sementara untuk ditampilkan
                $respon = [
                    'status' => 1,
                    'nik'    => $data['nik'],
                    'pin'    => $data['pin2'],
                    'pesan'  => 'untuk melengkapi pendaftaran Silahkan Verifikasi Email dan Telegram',
                    'aksi'   => site_url('/layanan-mandiri/daftar/verifikasi/telegram'), // TODO issue
                ];
            } elseif ($this->cek_layanan_mandiri($penduduk->id) && ! $this->otp_library->driver('telegram')->cek_verifikasi_otp($penduduk->id)) {
                $data_penduduk['id']        = $penduduk->id;
                $data_penduduk['config_id'] = $this->config_id;

                $session = [
                    'is_verifikasi' => $data_penduduk,
                ];
                $this->session->set_userdata($session);

                $respon = [
                    'status' => 1,
                    'nik'    => $data['nik'],
                    'pin'    => $data['pin2'],
                    'pesan'  => 'untuk melengkapi pendaftaran Silahkan Verifikasi Email dan Telegram',
                    'aksi'   => site_url('/layanan-mandiri/daftar/verifikasi/telegram'), // TODO issue
                ];
            } else {
                $respon = [
                    'status' => 0,
                    'pesan'  => 'Anda sudah terdaftar di Akun Layanan Mandiri, <br/> Silahkan klik tombol Masuk untuk melanjutkan Login ke Layanan Mandiri',
                    'aksi'   => site_url('layanan-mandiri/masuk'),
                ];
            }
        } else {
            $respon = [
                'status' => -1,
                'pesan'  => 'Mohon Maaf, Anda tidak dapat melakukan Pendaftaran Layanan Mandiri, <br/> Silahkan menghubungi admin untuk konfirmasi',
            ];
        }
        $this->session->set_flashdata('info_pendaftaran', $respon);
    }

    //Cek di data Kependudukan
    public function cek_pendaftaran($nama, $nik, $tanggallahir, $kk)
    {
        return $this->config_id('p')
            ->select('p.id, p.nik')
            ->from('penduduk_hidup p')
            ->join('tweb_keluarga k', 'p.id_kk = k.id')
            ->where('nama', $nama)
            ->where('nik', $nik)
            ->where('tanggallahir', $tanggallahir)
            ->where('no_kk', $kk)
            ->get()
            ->row();
    }

    //Cek Penduduk sudah terdaftar di Layanan Mandiri
    public function cek_layanan_mandiri($id_pend)
    {
        $cek = $this->config_id()
            ->from('tweb_penduduk_mandiri')
            ->select('id_pend')
            ->where('id_pend', $id_pend)
            ->get()
            ->row();

        return $cek != null;
    }

    public function insert_daftar($data_penduduk, &$data): void
    {
        $scan = [];

        for ($i = 0; $i < 3; $i++) {
            $value = $this->upload_scan($i + 1);
            if ($value == null) {
                continue;
            }
            $scan[] = $value;
        }

        if (count($scan) == 3) {
            $this->db->insert('tweb_penduduk_mandiri', [
                'id_pend'      => $data_penduduk['id'],
                'config_id'    => $this->config_id,
                'aktif'        => 0,
                'scan_ktp'     => empty($scan[0]) ? null : $scan[0],
                'scan_kk'      => empty($scan[1]) ? null : $scan[1],
                'foto_selfie'  => empty($scan[2]) ? null : $scan[2],
                'ganti_pin'    => 0,
                'pin'          => hash_pin(bilangan($data['pin2'])),
                'tanggal_buat' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $respon = [
                'status' => -1,
                'pesan'  => 'Mohon Maaf, pendaftaran anda tidak dapat di proses. Silahkan periksa kembali unggahan anda.',
            ];
            $this->session->set_flashdata('info_pendaftaran', $respon);

            redirect('layanan-mandiri/daftar');
        }
    }

    protected function upload_scan($key = 1)
    {
        $this->load->library('MY_Upload', null, 'upload');
        $this->uploadConfig = [
            'upload_path'   => LOKASI_PENDAFTARAN,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => max_upload() * 1024,
        ];

        $uploadData = null;
        // Inisialisasi library 'upload'
        $this->upload->initialize($this->uploadConfig);
        // Upload sukses
        if ($this->upload->do_upload("scan_{$key}")) {
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
            session_error($this->upload->display_errors());
        }

        return (empty($uploadData)) ? null : $uploadData['file_name'];
    }

    //Login Layanan Mandiri
    public function siteman(): void
    {
        session_error_clear();

        $masuk = $this->input->post();
        $nik   = bilangan(bilangan($masuk['nik']));
        $pin   = hash_pin(bilangan($masuk['pin']));

        $data = $this->config_id('pm')
            ->select('pm.*, p.nama, p.nik, p.tag_id_card, p.sex, p.foto, p.kk_level, p.id_kk, k.no_kk, c.rt, c.rw, c.dusun')
            ->from('tweb_penduduk_mandiri pm')
            ->join('penduduk_hidup p', 'pm.id_pend = p.id')
            ->join('tweb_keluarga k', 'p.id_kk = k.id', 'left')
            ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left')
            ->where('p.nik', $nik)
            ->get()
            ->row();

        session_error_clear();
        $this->session->aktif = false;

        if (akun_demo($data->id_pend, false)) {
            $data->pin       = hash_pin(config_item('demo_akun')[$data->id_pend]);
            $data->ganti_pin = 1;
            $data->aktif     = 1;
        }

        if ($data->aktif == 1) {
            switch (true) {
                case $data && $pin == $data->pin:
                    $session = [
                        'mandiri'    => 1,
                        'is_login'   => $data,
                        'login_ektp' => false,
                    ];
                    $this->session->set_userdata($session);
                    break;

                case $this->session->mandiri_try > 2:
                    --$this->session->mandiri_try;
                    $this->session->login_ektp = false;
                    break;

                default:
                    $this->session->mandiri_wait = 1;
                    $this->session->login_ektp   = false;
            }
        }

        if ($data->aktif == 0) {
            $this->session->aktif = true;
        }
    }

    //Login Layanan Mandiri E-KTP
    public function siteman_ektp(): void
    {
        session_error_clear();

        $masuk = $this->input->post();
        $pin   = hash_pin(bilangan($masuk['pin']));
        $tag   = bilangan(bilangan($masuk['tag']));

        $data = $this->config_id('pm')
            ->select('pm.*, p.nama, p.nik, p.tag_id_card, p.foto, p.kk_level, p.id_kk, k.no_kk')
            ->from('tweb_penduduk_mandiri pm')
            ->join('penduduk_hidup p', 'pm.id_pend = p.id')
            ->join('tweb_keluarga k', 'p.id_kk = k.id', 'left')
            ->where('p.tag_id_card', $tag)
            ->get()
            ->row();

        session_error_clear();
        $this->session->aktif = false;

        if (akun_demo($data->id_pend, false)) {
            $data->ganti_pin = 1;
            $data->aktif     = 1;
        }

        if ($data->aktif == 1) {
            switch (true) {
                case $data && $this->cek_anjungan && $tag == $data->tag_id_card:

                case $data && ! $this->cek_anjungan && $tag == $data->tag_id_card && $pin == $data->pin:
                    $session = [
                        'mandiri'    => 1,
                        'is_login'   => $data,
                        'login_ektp' => true,
                    ];
                    $this->session->set_userdata($session);
                    break;

                case $this->session->mandiri_try > 2:
                    --$this->session->mandiri_try;
                    $this->session->login_ektp = true;
                    break;

                default:
                    $this->session->mandiri_wait = 1;
                    $this->session->login_ektp   = true;
                    break;
            }
        }

        if ($data->aktif == 0) {
            $this->session->aktif = true;
        }
    }

    public function logout(): void
    {
        $data = [
            'id_pend'    => $this->is_login->id_pend,
            'last_login' => date('Y-m-d H:i:s', NOW()),
        ];

        if (isset($data['id_pend'])) {
            $this->update_login($data);
        }

        $this->session->unset_userdata(['mandiri', 'is_login', 'data_permohonan']);
    }

    public function update_login(array $data = []): void
    {
        $this->config_id()->where('id_pend', $data['id_pend'])->update('tweb_penduduk_mandiri', $data);
    }

    public function ganti_pin(): void
    {
        $id_pend  = $this->is_login->id_pend;
        $nama     = $this->session->is_login->nama;
        $ganti    = $this->input->post();
        $pin_lama = hash_pin(bilangan($ganti['pin_lama']));
        hash_pin(bilangan($ganti['pin_baru1']));
        $pin_baru2 = hash_pin(bilangan($ganti['pin_baru2']));

        $pilihan_kirim = $ganti['pilihan_kirim'];

        // Ganti password
        $pin = $this->config_id()
            ->select('pin')
            ->where('id_pend', $id_pend)
            ->get('tweb_penduduk_mandiri')
            ->row()
            ->pin;

        $data = [
            'id_pend'    => $id_pend,
            'pin'        => $pin_baru2,
            'last_login' => date('Y-m-d H:i:s', NOW()),
            'ganti_pin'  => 0,
        ];

        switch (true) {
            case akun_demo($id_pend, false):
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => 'Tidak dapat mengubah PIN akun demo',
                ];
                break;

            case $pin_lama != $pin:
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => 'PIN gagal diganti, <b>PIN Lama</b> yang anda masukkan tidak sesuai',
                ];
                break;

            case $pin_baru2 == $pin:
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => '<b>PIN</b> gagal diganti, Silahkan ganti <b>PIN Lama</b> anda dengan <b>PIN Baru</b> ',
                ];
                break;

            case $pilihan_kirim == 'kirim_telegram':
                if ($this->kirim_telegram(['id_pend' => $id_pend, 'pin' => $ganti['pin_baru2'], 'nama' => $nama])) {
                    $respon = [
                        'status' => 1, // Notif berhasil
                        'aksi'   => site_url('layanan-mandiri/keluar'),
                        'pesan'  => 'PIN Baru sudah dikirim ke Akun Telegram Anda',
                    ];
                } else {
                    $respon = [
                        'status' => -1, // Notif gagal
                        'pesan'  => '<b>PIN Baru</b> gagal dikirim ke Telegram, silahkan hubungi operator',
                    ];
                }
                break;

            case $pilihan_kirim == 'kirim_email':
                if ($this->kirim_email(['id_pend' => $id_pend, 'pin' => $ganti['pin_baru2'], 'nama' => $nama])) {
                    $respon = [
                        'status' => 1, // Notif berhasil
                        'aksi'   => site_url('layanan-mandiri/keluar'),
                        'pesan'  => 'PIN Baru sudah dikirim ke Akun Email Anda',
                    ];
                } else {
                    $respon = [
                        'status' => -1, // Notif gagal
                        'pesan'  => '<b>PIN Baru</b> gagal dikirim ke Email, silahkan hubungi operator',
                    ];
                }
                break;

            default:
                $this->update_login($data);
                $respon = [
                    'status' => 1, // Notif berhasil
                    'aksi'   => site_url('layanan-mandiri/keluar'),
                    'pesan'  => 'PIN berhasil diganti, silahkan masuk kembali dengan Kode PIN : ' . $ganti['pin_baru2'],
                ];
                break;
        }

        set_session('notif', $respon);
    }

    //Permintaan Pendaftaran Layanan Mandiri
    public function jml_mandiri_non_aktif()
    {
        $this->list_data_sql();

        return $this->db->where('pm.aktif', 0)->get()->num_rows();
    }

    public function cek_verifikasi($nik = 0): void
    {
        // cek metode pengiriman pin melalui telegram atau email
        $metode = alfa_spasi($this->input->post('send'));

        if ($metode == 'telegram') {
            $this->db->where('p.telegram_tgl_verifikasi !=', null);
        } else {
            $this->db->where('p.email_tgl_verifikasi !=', null);
        }

        $data = $this->config_id('pm')
            ->select('p.id, p.telegram, p.email, p.nama')
            ->from("{$this->table} pm")
            ->join('penduduk_hidup p', 'p.id = pm.id_pend', 'left')
            ->where('p.nik', $nik)
            ->get()
            ->row();

        switch (true) {
            case $data:
                $pin_baru = $this->generate_pin();

                $this->db->trans_begin();

                try {
                    if ($metode == 'telegram') {
                        $this->otp_library->driver('telegram')->kirim_pin_baru($data->telegram, $pin_baru, $data->nama);
                    } else {
                        $this->otp_library->driver('email')->kirim_pin_baru($data->email, $pin_baru, $data->nama);
                    }

                    $this->config_id()->where('id_pend', $data->id)->update($this->table, ['pin' => hash_pin($pin_baru), 'ganti_pin' => 0]);
                    $this->db->trans_commit();
                } catch (Exception $e) {
                    log_message('error', $e);
                    $this->db->trans_rollback();
                }

                $respon = [
                    'status' => 1, // Notif berhasil
                    'pesan'  => 'Informasi reset PIN telah dikirim ke akun ' . (($metode == 'telegram') ? 'Telegram' : 'Email') . ' anda. Jika anda tidak menerima pesan itu, periksa ulang NIK yang diisi dan pastikan akun ' . (($metode == 'telegram') ? 'Telegram' : 'Email') . ' anda di OpenSID telah diverifikasi. Silakan hubungi Operator Desa untuk penjelasan lebih lanjut.',
                ];
                break;

            case $this->session->mandiri_try > 2:
                --$this->session->mandiri_try;
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => 'Informasi reset PIN telah dikirim ke akun ' . (($metode == 'telegram') ? 'Telegram' : 'Email') . ' anda. Jika anda tidak menerima pesan itu, periksa ulang NIK yang diisi dan pastikan akun ' . (($metode == 'telegram') ? 'Telegram' : 'Email') . ' anda di OpenSID telah diverifikasi. Silakan hubungi Operator Desa untuk penjelasan lebih lanjut.',
                ];
                break;

            default:
                $this->session->mandiri_wait = 1;
                break;
        }

        $this->session->set_flashdata('lupa_pin', $respon);
    }

    protected function kirim_telegram(array $user)
    {
        $this->db->trans_begin();

        try {
            $telegramID        = $this->config_id()->where('id', $user['id_pend'])->get('penduduk_hidup')->row()->telegram;
            $data['pin']       = hash_pin($user['pin']); // Hash PIN
            $data['ganti_pin'] = 0;

            $this->session->set_flashdata('notif_kirim_verifikasi', [
                'status' => 1,
                'pesan'  => 'PIN Baru sudah dikirim ke Akun Telegram Anda',
            ]);

            $this->otp_library->driver('telegram')->kirim_pin_baru($telegramID, $user['pin'], $user['nama']);

            $this->config_id()->where('id_pend', $user['id_pend'])->update('tweb_penduduk_mandiri', $data);

            $this->db->trans_commit();
        } catch (Exception $e) {
            log_message('error', $e);

            $this->db->trans_rollback();

            return false;
        }

        return true;
    }

    protected function kirim_email(array $user)
    {
        $this->db->trans_begin();

        try {
            $email             = $this->config_id()->where('id', $user['id_pend'])->get('penduduk_hidup')->row()->email;
            $data['pin']       = hash_pin($user['pin']); // Hash PIN
            $data['ganti_pin'] = 0;

            $this->session->set_flashdata('notif_kirim_verifikasi', [
                'status' => 1,
                'pesan'  => 'PIN Baru sudah dikirim ke Akun Email Anda',
            ]);

            $this->otp_library->driver('email')->kirim_pin_baru($email, $user['pin'], $user['nama']);

            $this->config_id()->where('id_pend', $user['id_pend'])->update('tweb_penduduk_mandiri', $data);

            $this->db->trans_commit();
        } catch (Exception $e) {
            log_message('error', $e);

            $this->db->trans_rollback();

            return false;
        }

        return true;
    }
}
