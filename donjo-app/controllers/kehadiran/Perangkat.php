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

use App\Models\AlasanKeluar;
use App\Models\HariLibur;
use App\Models\JamKerja;
use App\Models\Kehadiran;
use App\Models\User;

defined('BASEPATH') || exit('No direct script access allowed');

class Perangkat extends Web_Controller
{
    private string $tgl;
    private string $jam;
    private $ip;
    private $mac;
    private $pengunjung;
    private string $url;

    public function __construct()
    {
        parent::__construct();
        if (setting('tampilkan_kehadiran') == '0') {
            show_404();

            return;
        }

        $this->tgl        = date('Y-m-d');
        $this->jam        = date('H:i');
        $this->ip         = $this->input->ip_address();
        $this->mac        = $this->input->get('mac_address', true) ?? $this->session->mac_address;
        $this->pengunjung = $_COOKIE['pengunjung'];
        $this->url        = 'kehadiran/masuk';

        if ($this->mac) {
            $this->session->mac_address = $this->mac;
        }
    }

    public function index()
    {
        $this->cekLogin();

        $data = [
            'masuk'       => $this->session->masuk,
            'success'     => $this->session->kehadiran,
            'ip_address'  => $this->ip,
            'mac_address' => $this->mac,
            'kehadiran'   => Kehadiran::where('tanggal', '=', $this->tgl)->where('pamong_id', '=', $this->session->masuk['pamong_id'])->where('status_kehadiran', '=', 'hadir')->first(),
            'alasan'      => AlasanKeluar::get(),
        ];

        return view('kehadiran.index', $data);
    }

    public function cek($ektp = false): void
    {
        if (! $this->input->post()) {
            redirect($this->url);
        }

        $username = trim($this->request['username']);
        $password = trim($this->request['password']);
        $tag      = trim($this->request['tag']);

        $user = User::with(['pamong'])
            ->whereHas('pamong', static function ($query) use ($username): void {
                $query
                    ->status('1') // pamong aktif
                    ->where(static function ($query) use ($username): void {
                        $query
                            ->orWhere('username', $username)
                            ->orWhere('pamong_nik', $username)
                            ->orWhereHas('penduduk', static function ($query) use ($username): void {
                                $query->where('nik', $username);
                            });
                    });
            })
            ->orWhereHas('pamong', static function ($query) use ($tag): void {
                $query
                    ->status('1') // pamong aktif
                    ->where(static function ($query) use ($tag): void {
                        $query
                            ->orWhere('pamong_tag_id_card', $tag)
                            ->orWhereHas('penduduk', static function ($query) use ($tag): void {
                                $query->where('tag_id_card', $tag);
                            });
                    });
            })
            ->first();

        if ($ektp) {
            if (! $user) {
                set_session('error', 'ID Card Salah. Coba Lagi');
                redirect($this->url);
            }
        } elseif (password_verify($password, $user->password) === false) {
            set_session('error', 'Username atau Password Salah');
            redirect($this->url);
        }

        $this->session->masuk = [
            'pamong_id'   => $user->pamong_id,
            'pamong_nama' => $user->pamong->penduduk->nama ?? $user->pamong->pamong_nama ?? $user->nama,
            'jabatan'     => $user->pamong->jabatan,
            'sex'         => $user->pamong->penduduk->sex ?? $user->pamong->pamong_sex,
            'foto'        => $user->pamong->penduduk->foto ?? $user->pamong->foto ?? $user->foto,
        ];

        redirect('kehadiran');
    }

    public function masukEktp(): void
    {
        $this->masuk(true);
    }

    public function cekEktp(): void
    {
        $this->url = 'kehadiran/masuk-ektp';
        $this->cek(true);
    }

    public function masuk($ektp = false)
    {
        $data = [
            'ip_address'    => $this->ip,
            'mac_address'   => $this->mac,
            'id_pengunjung' => $this->pengunjung,
            'ektp'          => $ektp,
            'cek'           => $this->deteksi(),
        ];

        return view('kehadiran.masuk', $data);
    }

    public function checkInOut(): void
    {
        $this->cekLogin();
        $pamong_id        = $this->session->masuk['pamong_id'];
        $status_kehadiran = $this->request['status_kehadiran'];

        if ($status_kehadiran == 'hadir') {
            $check_in = Kehadiran::create([
                'tanggal'          => $this->tgl,
                'pamong_id'        => $pamong_id,
                'jam_masuk'        => $this->jam,
                'status_kehadiran' => $status_kehadiran,
            ]);

            $this->session->kehadiran = (bool) $check_in;
        } else {
            $check_out = Kehadiran::where('tanggal', $this->tgl)->where('pamong_id', $pamong_id)->latest('jam_masuk')->take(1)->update([
                'jam_keluar'       => $this->jam,
                'status_kehadiran' => $status_kehadiran,
            ]);

            $this->session->kehadiran = (bool) $check_out;
        }

        redirect('kehadiran');
    }

    public function logout(): void
    {
        $this->session->unset_userdata(['masuk', 'kehadiran', 'mac_address']);

        redirect('kehadiran/masuk');
    }

    private function cekLogin()
    {
        // Paksa keluar jika perangkat tidak terdeteksi
        if (! $this->deteksi()['status']) {
            return $this->logout();
        }

        if (! $this->session->masuk) {
            redirect($this->url);
        }
    }

    private function deteksi()
    {
        $ip_address    = (setting('ip_adress_kehadiran') === $this->ip && setting('ip_adress_kehadiran') !== null);
        $mac_adress    = (setting('mac_adress_kehadiran') === $this->mac && setting('mac_adress_kehadiran') !== null);
        $id_pengunjung = (setting('id_pengunjung_kehadiran') === $this->pengunjung && setting('id_pengunjung_kehadiran') !== null);
        $cek_gawai     = ($ip_address || $mac_adress || $id_pengunjung);
        $cek_hari      = HariLibur::where('tanggal', '=', date('Y-m-d'))->first();
        $cek_weekend   = JamKerja::libur()->first();
        $cek_jam       = JamKerja::jamKerja()->first();

        return [
            'status' => null === $cek_hari && null === $cek_jam && null === $cek_weekend && $cek_gawai,
            'judul'  => 'Tidak bisa masuk!',
            'pesan'  => $this->getStatusPesan([
                'cek_gawai'   => $cek_gawai,
                'cek_hari'    => $cek_hari,
                'cek_weekend' => $cek_weekend,
                'cek_jam'     => $cek_jam,
            ]),
        ];
    }

    private function getStatusPesan(array $cek)
    {
        $pesan = '';

        switch (true) {
            case $cek['cek_gawai'] === false:
                $pesan = 'Gawai ini belum terdaftar.';
                break;

            case $cek['cek_hari'] !== null:
                $pesan = $cek['cek_hari']->keterangan;
                break;

            case $cek['cek_weekend'] !== null:
                $pesan = "Hari {$cek['cek_weekend']->nama_hari} libur!";
                break;

            case $cek['cek_jam'] !== null:
                $pesan = "Jam kerja hari ini di mulai dari {$cek['cek_jam']->jam_masuk} hingga {$cek['cek_jam']->jam_keluar}";
                break;

            default:
                $pesan = '';
                break;
        }

        return $pesan;
    }
}
