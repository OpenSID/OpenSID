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

use App\Models\AbsensiJamKerja;
use App\Models\AbsensiLibur;
use App\Models\Anjungan;
use App\Models\Kehadiran;
use App\Models\User;

defined('BASEPATH') || exit('No direct script access allowed');

class Absensi extends Web_Controller
{
    private $tgl;
    private $jam;
    private $ip;
    private $mac;

    public function __construct()
    {
        parent::__construct();
        $this->tgl = date('Y-m-d');
        $this->jam = date('H:i');
        $this->ip  = $this->input->ip_address();
        $this->mac = $this->input->get('mac_address', true);
    }

    public function index()
    {
        $this->cekLogin();

        $data = [
            'masuk'       => $this->session->masuk,
            'success'     => $this->session->kehadiran,
            'ip_address'  => $this->ip,
            'mac_address' => $this->mac,
            'kehadiran'   => Kehadiran::where('tanggal', '=', $this->tgl)->where('pamong_id', '=', $this->session->masuk['pamong_id'])->first(),
        ];

        return view('kehadiran.absensi.index', $data);
    }

    public function cek($ektp = false, $url = 'kehadiran/masuk')
    {
        if (! $this->input->post()) {
            redirect($url);
        }

        $username = trim($this->request['username']);
        $password = trim($this->request['password']);
        $tag      = trim($this->request['tag']);

        $user = User::with(['pamong'])
            ->whereHas('pamong', static function ($query) use ($username) {
                $query->where('username', $username)
                    ->orWhere('pamong_nik', $username)
                    ->orWhereHas('penduduk', static function ($query) use ($username) {
                        $query->where('nik', $username);
                    });
            })
            ->orWhereHas('pamong', static function ($query) use ($tag) {
                $query->where('pamong_tag_id_card', $tag);
            })
            ->first();

        if ($ektp) {
            if (! $user) {
                set_session('error', 'ID Card Salah. Coba Lagi');
                redirect($url);
            }
        } else {
            if (password_verify($password, $user->password) === false) {
                set_session('error', 'Username atau Password Salah');
                redirect($url);
            }
        }

        // cek absen keluar sudah atau belum. jika sudah tampilkan warning
        $keluar = Kehadiran::where('tanggal', '=', $this->tgl)
            ->where('pamong_id', '=', $user->pamong_id)
            ->whereNotNull('jam_pulang')
            ->first();

        if ($keluar) {
            set_session('error', 'Anda sudah melakukan absen keluar hari ini');
            redirect($url);
        }

        $this->session->masuk = [
            'pamong_id'   => $user->pamong_id,
            'pamong_nama' => $user->pamong->penduduk->nama ?? $user->pamong->pamong_nama ?? $user->nama,
            'jabatan'     => $user->pamong->jabatan,
            'sex'         => $user->pamong->penduduk->sex ?? $user->pamong->pamong_sex,
            'foto'        => $user->pamong->penduduk->foto ?? $user->pamong->foto ?? $user->foto,
        ];

        $this->cekAbsenKeluar();

        redirect('kehadiran');
    }

    public function masuk_ektp()
    {
        $this->masuk(true);
    }

    public function cek_ektp()
    {
        $this->cek(true, 'kehadiran/masuk-ektp');
    }

    public function masuk($ektp = false)
    {
        $cek_gawai = Anjungan::where(function ($query) {
            $query->where('ip_address', $this->ip)->orWhere('mac_address', $this->mac);
        })
            ->where('tipe', 'absensi')
            ->where('status', 1)
            ->first();

        $cek_hari    = AbsensiLibur::where('tanggal', '=', date('Y-m-d'))->first();
        $cek_weekend = AbsensiJamKerja::libur()->first();
        $cek_jam     = AbsensiJamKerja::jamKerja()->first();

        $data = [
            'ip_address'  => $this->ip,
            'mac_address' => $this->mac,
            'ektp'        => $ektp,
            'cek'         => [
                'status' => null === $cek_hari && null === $cek_jam && null === $cek_weekend && null !== $cek_gawai,
                'judul'  => 'Tidak bisa masuk!',
                'pesan'  => $this->getStatusPesan([
                    'cek_gawai'   => $cek_gawai,
                    'cek_hari'    => $cek_hari,
                    'cek_weekend' => $cek_weekend,
                    'cek_jam'     => $cek_jam,
                ]),
            ],
        ];

        return view('kehadiran.absensi.masuk', $data);
    }

    public function check_in_out()
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

            $this->session->kehadiran = $check_in ? true : false;
        } else {
            $check_out = Kehadiran::where('tanggal', $this->tgl)->where('pamong_id', $pamong_id)->update(['jam_pulang' => $this->jam]);

            $this->session->kehadiran = $check_out ? true : false;
        }

        redirect('kehadiran');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('kehadiran');
    }

    private function cekLogin()
    {
        if (! $this->session->masuk) {
            redirect('kehadiran/masuk');
        }
    }

    private function getStatusPesan(array $cek)
    {
        $pesan = '';

        switch (true) {
            case $cek['cek_gawai'] === null:
                $pesan = 'Gawai ini belum terdaftar.';
                break;

            case $cek['cek_hari'] !== null:
                $pesan = $cek['cek_hari']->keterangan;
                break;

            case $cek['cek_weekend'] !== null:
                $pesan = "Hari {$cek['cek_weekend']->nama_hari} libur!";
                break;

            case $cek['cek_jam'] !== null:
                $pesan = "Jam kerja hari ini di mulai dari {$cek['cek_jam']->jam_mulai} hingga {$cek['cek_jam']->jam_akhir}";
                break;

            default:
                $pesan = '';
                break;
        }

        return $pesan;
    }

    private function cekAbsenKeluar()
    {
        if ($this->session->masuk) {
            Kehadiran::lupaAbsen();
        }
    }
}
