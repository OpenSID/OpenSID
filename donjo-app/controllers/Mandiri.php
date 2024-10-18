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

use App\Models\Penduduk;
use App\Models\PendudukHidup;

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\PendudukMandiri;

class Mandiri extends Admin_Controller
{
    public $modul_ini     = 'layanan-mandiri';
    public $sub_modul_ini = 'pendaftar-layanan-mandiri';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->library('OTP/OTP_manager', null, 'otp_library');
        $this->load->library('email');
        $this->email->initialize(config_email());
        $this->telegram = new Telegram();
    }

    public function index()
    {
        return view('admin.layanan_mandiri.daftar.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(PendudukMandiri::with('penduduk'))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';
                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('mandiri.ajax_pin', $row->id_pend) . '" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Reset PIN Warga" title="Reset PIN Warga" class="btn btn-primary btn-sm"><i class="fa fa-key"></i></a> ';
                        $aksi .= '<a href="' . ci_route('mandiri.ajax_hp', $row->id_pend) . '" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="' . ($row->telepon ? 'Ubah' : 'Tambah') . ' Telepon Warga" title="' . ($row->telepon ? 'Ubah' : 'Tambah') . ' Telepon" class="btn btn-sm ' . ($row->telepon ? 'bg-teal' : 'bg-green') . '"><i class="fa fa-phone"></i></a> ';

                        if (! $row->aktif) {
                            $aksi .= '<a href="' . ci_route('mandiri.ajax_verifikasi_warga', $row->id_pend) . '" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Verifikasi Pendaftaran Warga" title="Verifikasi Pendaftaran Warga" class="btn bg-purple btn-sm"><i class="fa fa-eye"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('mandiri.delete', $row->id_pend) . '" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('tanggal_buat', static fn ($row) => tgl_indo2($row->getRawOriginal('tanggal_buat')))
                ->editColumn('last_login', static fn ($row) => tgl_indo2($row->getRawOriginal('last_login')))
                ->rawColumns(['aksi'])
                ->make();
        }

        return show_404();
    }

    public function ajax_pin($id_pend = '')
    {
        isCan('u');
        $data['penduduk'] = PendudukHidup::select(['id', 'nik', 'nama'])->whereDoesntHave('mandiri')->get()->toArray();

        if ($id_pend) {
            $cek                 = PendudukHidup::find($id_pend)->toArray() ?? show_404();
            $data['id_pend']     = $cek['id'];
            $data['form_action'] = ci_route("{$this->controller}.update", $id_pend);
        } else {
            $data['id_pend']     = null;
            $data['form_action'] = ci_route("{$this->controller}.insert");
        }

        $data['tgl_verifikasi_telegram'] = $this->otp_library->driver('telegram')->cek_verifikasi_otp($data['id_pend']);
        $data['tgl_verifikasi_email']    = $this->otp_library->driver('email')->cek_verifikasi_otp($data['id_pend']);

        return view('admin.layanan_mandiri.daftar.ajax_pin', $data);
    }

    public function ajax_hp($id_pend)
    {
        isCan('u');
        $data['form_action'] = ci_route("{$this->controller}.ubah_hp", $id_pend);
        $data['penduduk']    = PendudukHidup::select(['id', 'nik', 'nama', 'telepon'])->find($id_pend)->toArray() ?? show_404();

        return view('admin.layanan_mandiri.daftar.ajax_hp', $data);
    }

    public function ajax_verifikasi_warga($id_pend)
    {
        isCan('u');
        $data['tgl_verifikasi_telegram'] = $this->otp_library->driver('telegram')->cek_verifikasi_otp($id_pend);
        $data['tgl_verifikasi_email']    = $this->otp_library->driver('email')->cek_verifikasi_otp($id_pend);
        $data['form_action']             = ci_route("{$this->controller}.verifikasi_warga", $id_pend);
        $data['penduduk']                = PendudukMandiri::where(['id_pend' => $id_pend])->join('penduduk_hidup', 'penduduk_hidup.id', '=', 'tweb_penduduk_mandiri.id_pend')->first()->toArray();

        return view('admin.layanan_mandiri.daftar.ajax_verifikasi_warga', $data);
    }

    public function verifikasi_warga($id_pend): void
    {
        isCan('u');

        $this->input->post();
        $pilihan_kirim = $this->request['pilihan_kirim'];
        // TODO: Sederhanakan query ini, pindahkan ke model
        $data = Penduduk::select(['telegram', 'email', 'nama'])->find($id_pend);

        switch ($pilihan_kirim) {
            case 'kirim_telegram':
                PendudukMandiri::where(['id_pend' => $id_pend])->update(['aktif' => true]);
                $pesan = [
                    'chat_id' => $data->telegram,
                    'text'    => <<<EOD
                        HALO {$data->nama},

                        SELAMAT AKUN LAYANAN MANDIRI ANDA SUDAH DIVERIFIKASI DAN TELAH DISETUJUI
                        SAAT INI ANDA SUDAH DAPAT LOGIN DI FITUR LAYANAN MANDIRI

                        TERIMA KASIH.
                        EOD,
                    'parse_mode' => 'Markdown',
                ];
                $this->kirimTelegram($pesan);
                break;

            case 'kirim_email':
                PendudukMandiri::where(['id_pend' => $id_pend])->update(['aktif' => true]);
                $this->kirimEmail($data);
                break;

            default:
                redirect($this->controller);
                break;
        }
    }

    protected function kirimTelegram($data): void
    {
        try {
            // TODO: Sederhanakan query ini, pindahkan ke model
            $this->telegram->sendMessage($data);
        } catch (Exception $e) {
            log_message('error', $e);

            status_sukses(false);
            redirect($this->controller);
        }

        redirect($this->controller);
    }

    protected function kirimEmail($data)
    {
        try {
            // TODO: OpenKab - Perlu disesuaikan ulang setelah semua modul selesai
            $message = view('admin.layanan_mandiri.daftar.email.verifikasi-berhasil', ['nama' => $data->nama], [], true);
            // log_message('error','email '. $message);
            $this->email->from($this->email->smtp_user, 'OpenSID')
                ->to($data->email)
                ->subject('Verifikasi Akun Layanan Mandiri')
                ->set_mailtype('html')
                ->message($message);

            if (! $this->email->send()) {
                throw new Exception($this->email->print_debugger());
            }
        } catch (Exception $e) {
            log_message('error', $e);

            status_sukses(false);
            redirect($this->controller);
        }

        redirect($this->controller);
    }

    public function ubah_hp($id_pend): void
    {
        isCan('u');

        try {
            Penduduk::where(['id' => $id_pend])->update(['telepon' => bilangan($this->request['telepon'])]);
            redirect_with('success', 'Data berhasil disimpan');
        } catch (Exception  $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Data gagal disimpan');
        }
    }

    public function insert(): void
    {
        isCan('u');

        try {
            $mandiri = new PendudukMandiri();
            $pin     = bilangan($this->request['pin'] ?: $mandiri->generate_pin());

            $mandiri->pin     = hash_pin($pin); // Hash PIN
            $mandiri->id_pend = $this->request['id_pend'];
            $mandiri->save();

            // Ambil data sementara untuk ditampilkan
            $flash        = PendudukHidup::find($this->request['id_pend'])->toArray();
            $flash['pin'] = $pin; // Normal PIN
            set_session('info', $flash);

            redirect_with('success', 'Data berhasil disimpan');
        } catch (Exception  $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Data gagal disimpan');
        }
    }

    public function update($id_pend): void
    {
        akun_demo($id_pend);
        isCan('u');

        try {
            $mandiri = PendudukMandiri::find($id_pend) ?? show_404();

            $pin      = bilangan($this->request['pin'] ?? $mandiri->generate_pin());
            $penduduk = PendudukHidup::select(['nik', 'nama', 'email', 'telepon', 'telegram'])->find($id_pend);

            $pilihan_kirim = $this->request['pilihan_kirim'];
            $data['pin']   = hash_pin($pin); // Hash PIN
            $media         = null;

            switch ($pilihan_kirim) {
                case 'kirim_telegram':
                    $media             = 'telegram';
                    $data['ganti_pin'] = 0;
                    break;

                case 'kirim_email':
                    $media             = 'email';
                    $data['ganti_pin'] = 0;
                    break;

                default:
                    $data['ganti_pin'] = 1;
            }

            $mandiri->update($data);
            $this->kirimPinBaru($media, $pin, $penduduk);
            $flash        = array_merge($mandiri->toArray(), $penduduk->toArray());
            $flash['pin'] = $pin;
            set_session('info', $flash);

            redirect_with('success', 'Data berhasil disimpan');
        } catch (Exception  $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Data gagal disimpan');
        }
    }

    public function delete($id = ''): void
    {
        isCan('h');
        PendudukMandiri::where(['id_pend' => $id])->delete();
        redirect($this->controller);
    }

    public function kirim($id_pend = ''): void
    {
        isCan('u');
        $pin  = $this->input->post('pin');
        $data = PendudukMandiri::where(['id_pend' => $id_pend])->join('penduduk_hidup', 'penduduk_hidup.id', '=', 'tweb_penduduk_mandiri.id_pend')->first()->toArray();
        $desa = $this->header['desa'];
        if (cek_koneksi_internet() && $data['telepon']) {
            $no_tujuan = '+62' . substr($data['telepon'], 1);

            $pesan = 'Selamat Datang di Layanan Mandiri ' . ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) . ' %0A%0AUntuk Menggunakan Layanan Mandiri, silahkan kunjungi ' . site_url('layanan-mandiri') . '%0AAkses Layanan Mandiri : %0A- NIK : ' . sensor_nik_kk($data['nik']) . ' %0A- PIN : ' . $pin . '%0A%0AHarap merahasiakan NIK dan PIN untuk keamanan data anda.%0A%0AHormat kami %0A' . setting('sebutan_kepala_desa') . ' ' . $desa['nama_desa'] . '%0A%0A%0A' . $desa['nama_kepala_desa'];

            redirect("https://api.whatsapp.com/send?phone={$no_tujuan}&text={$pesan}");
        }
        redirect($this->controller);
    }

    private function kirimPinBaru(?string $media, $pin, $penduduk): void
    {
        switch ($media) {
            case 'telegram':
                $this->otp_library->driver('telegram')->kirim_pin_baru($penduduk->telegram, $pin, $penduduk->nama);
                break;

            case 'email':
                $this->otp_library->driver('email')->kirim_pin_baru($penduduk->email, $pin, $penduduk->nama);
                break;
        }
    }
}
