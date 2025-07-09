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

use App\Models\Penduduk;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(['mandiri_model', 'theme_model']);

        if (! setting('tampilkan_pendaftaran')) {
            show_404();
        }

        if (auth('penduduk')->check()) {
            redirect('layanan-mandiri/beranda');
        }
    }

    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('layanan_mandiri.auth.register', [
            'header'              => $this->header,
            'latar_login_mandiri' => $this->theme_model->latar_login_mandiri(),
            'form_action'         => site_url('layanan-mandiri/proses-daftar'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $request = request();

        // Validate the request data
        $data = $this->validated($request, [
            'nama'         => ['required'],
            'tanggallahir' => ['required', 'date_format:Y-m-d'],
            'nik'          => ['required', 'digits:16', 'regex:/^\d{16}$/'],
            'no_kk'        => ['required', 'digits:16', 'regex:/^\d{16}$/'],
            'email'        => ['required', 'email', "unique:penduduk_hidup,email,{$request->email},email"],
            'telegram'     => ['required', 'regex:/^[0-9]{1,20}$/', "unique:penduduk_hidup,telegram,{$request->telegram},telegram"],
            'password'     => ['required', 'digits:6', 'regex:/^\d{6}$/', 'confirmed'],
            'scan_1'       => 'required|image|mimes:gif,jpeg,jpg,png|max:1024',
            'scan_2'       => 'required|image|mimes:gif,jpeg,jpg,png|max:1024',
            'scan_3'       => 'required|image|mimes:gif,jpeg,jpg,png|max:1024',
        ]);

        // Retrieve the 'Penduduk' model based on the provided criteria
        $penduduk = Penduduk::query()
            ->whereRelation('keluarga', 'no_kk', $data['no_kk'])
            ->where($request->only(['nama', 'tanggallahir', 'nik']))
            ->first();

        // Return a error if the Penduduk is not found
        if (! $penduduk) {
            $this->session->set_flashdata('notif', 'Pendaftaran Anda tidak dapat diproses. Periksa kembali dan pastikan semua data yang Anda masukkan sudah benar dan sesuai.');
            $this->withInput();

            return redirect('layanan-mandiri/daftar');
        }

        // Insert / update email and telegram if not verified
        if (null === $penduduk->tgl_verifikasi_email) {
            $penduduk->email = $data['email'];
        }
        if ($penduduk->tgl_verifikasi_telegram == null) {
            $penduduk->telegram = $data['telegram'];
        }
        $penduduk->save();

        // Check if the 'Penduduk' is already registered for 'Layanan Mandiri'
        if (null !== $mandiri = $penduduk->mandiri()->first()) {
            // Check if it is not already verified
            if (! $mandiri->hasVerifiedEmail() || ! $mandiri->hasVerifiedTelegram()) {
                Auth::guard('penduduk')->login($mandiri);
                event(new Registered($mandiri));

                return redirect('layanan-mandiri/daftar/verifikasi/email');
            }

            return redirect_with('notif', 'Anda sudah terdaftar di Layanan Mandiri. Saat ini, akun Anda sedang ditinjau oleh admin. Silakan tunggu konfirmasi lebih lanjut sebelum dapat melakukan login.', 'layanan-mandiri/masuk');
        }

        // Store files
        $filePaths = [
            'scan_ktp'    => $request->file('scan_1')->store('upload/pendaftaran', 'desa'),
            'scan_kk'     => $request->file('scan_2')->store('upload/pendaftaran', 'desa'),
            'foto_selfie' => $request->file('scan_3')->store('upload/pendaftaran', 'desa'),
        ];

        // Create a new 'mandiri' record for the 'Penduduk'
        $mandiri = $penduduk->mandiri()->create([
            'aktif'       => 0,
            'scan_ktp'    => basename($filePaths['scan_ktp']),
            'scan_kk'     => basename($filePaths['scan_kk']),
            'foto_selfie' => basename($filePaths['foto_selfie']),
            'ganti_pin'   => 0,
            'pin'         => Hash::driver('md5')->make($data['password']),
        ]);

        Auth::guard('penduduk')->login($mandiri);

        event(new Registered($mandiri));

        return redirect('layanan-mandiri/daftar/verifikasi/email');
    }
}
