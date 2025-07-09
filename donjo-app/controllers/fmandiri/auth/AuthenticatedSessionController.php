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

use App\Models\PendudukMandiri;
use App\Services\Auth\Traits\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Web_Controller
{
    use LoginRequest;

    /**
     * Attempt to get the guard.
     */
    protected $guard = 'penduduk';

    public function __construct()
    {
        parent::__construct();

        $this->load->model(['mandiri_model', 'theme_model']);

        if (setting('layanan_mandiri') == 0) {
            show_404();
        }
    }

    /**
     * Display the login view.
     */
    public function create()
    {
        $macAddress = $this->input->get('mac_address', true);
        $token      = $this->input->get('token_layanan', true);

        // TODO: apa masih digunakan untuk autentikasi dengan mac address?
        if (($macAddress && $token == setting('layanan_opendesa_token')) || Auth::guard($this->guard)->check()) {
            $this->session->mac_address = $macAddress;

            return redirect('layanan-mandiri/beranda');
        }

        return view('layanan_mandiri.auth.login', [
            'header'              => $this->header,
            'latar_login_mandiri' => $this->theme_model->latar_login_mandiri(),
            'cek_anjungan'        => $this->cek_anjungan,
            'form_action'         => site_url('layanan-mandiri/cek'),
        ]);
    }

    /**
     * Display the login view.
     */
    public function createEktp()
    {
        $macAddress = $this->input->get('mac_address', true);
        $token      = $this->input->get('token_layanan', true);

        if (($macAddress && $token == setting('layanan_opendesa_token')) || Auth::guard($this->guard)->check()) {
            $this->session->mac_address = $macAddress;

            return redirect('layanan-mandiri/beranda');
        }

        return view('layanan_mandiri.auth.login-ektp', [
            'header'              => $this->header,
            'latar_login_mandiri' => $this->theme_model->latar_login_mandiri(),
            'cek_anjungan'        => $this->cek_anjungan,
            'form_action'         => site_url('layanan-mandiri/cek-ektp'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store()
    {
        $request = request();

        if ($request->has('nik') || ($request->has('tag_id_card') && $request->has('password'))) {
            // Login menggunakan NIK atau E-KTP dan password
            $this->authenticate([
                'query' => fn ($q) => $q->when(
                    $this->caseQueryExist($request),
                    static fn ($q) => $q->status(0),
                    static fn ($q) => $q->status(1)
                ),
            ]);
        } elseif ($request->has('tag_id_card')) {
            // Login menggunakan E-KTP tanpa password
            $this->authenticateEktp($request);
        }

        $this->session->sess_regenerate();

        if ($this->session->is_anjungan) {
            redirect(route('anjungan.beranda.index'));
        }
        redirect(route('layanan-mandiri.beranda.index'));
    }

    public function destroy()
    {
        auth('penduduk')->logout();

        $this->session->unset_userdata([
            'mandiri', 'is_login',
            'is_anjungan', 'data_permohonan',
            'auth_mandiri', 'login_ektp',
        ]);

        redirect('layanan-mandiri/masuk');
    }

    protected function authenticateEktp(Request $request)
    {
        $this->ensureIsNotRateLimited();

        $this->validated($request, $this->rules());

        $user = PendudukMandiri::query()
            ->whereRelation('penduduk', 'tag_id_card', $request->tag_id_card)
            ->when(
                $this->caseQueryExist($request),
                static fn ($q) => $q->status(0),
                static fn ($q) => $q->status(1)
            )
            ->first();

        if (! $user) {
            RateLimiter::hit($this->throttleKey(), config_item('lockout_time'));

            try {
                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            } catch (ValidationException $e) {
                return $this->invalid($request, $e);
            }
        }

        Auth::guard($this->guard)->login($user);

        RateLimiter::clear($this->throttleKey());
    }

    protected function rules()
    {
        $request    = request();
        $credential = [];

        if ($request->has('nik')) {
            // Validasi login menggunakan NIK
            $this->session->set_userdata('login_ektp', false);
            $credential = [
                'nik'      => ['required', 'digits:16', 'regex:/^\d{16}$/'],
                'password' => 'required',
            ];
        } elseif ($request->has('tag_id_card') && $request->has('password')) {
            // Validasi login menggunakan E-KTP dan password
            $this->session->set_userdata('login_ektp', true);
            $credential = [
                'tag_id_card' => ['required', 'digits:10', 'regex:/^\d{10}$/'],
                'password'    => 'required',
            ];
        } else {
            // Validasi login menggunakan E-KTP tanpa password
            $this->session->set_userdata('login_ektp', true);
            $credential = [
                'tag_id_card' => ['required', 'digits:10', 'regex:/^\d{10}$/'],
            ];
        }

        return $credential;
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey()
    {
        $key = request('nik') ?: request('tag_id_card');

        return Str::transliterate($key . '|' . request()->ip());
    }

    /**
     * Query untuk memeriksa pendaftaran yang belum melakukan verifikasi,
     * pendaftar akan tetap bisa login meskipun belum aktif
     * untuk melakukan verifikasi.
     */
    protected function caseQueryExist(Request $request): bool
    {
        return PendudukMandiri::query()
            ->when(
                $request->nik,
                static fn ($query) => $query->whereRelation('penduduk', 'nik', $request->nik),
                static fn ($query) => $query->whereRelation('penduduk', 'tag_id_card', $request->tag_id_card)
            )
            ->whereNotNull('scan_ktp')
            ->whereNotNull('scan_kk')
            ->whereNotNull('foto_selfie')
            ->whereHas('penduduk', static function ($query) {
                $query->whereNull('email_tgl_verifikasi')
                    ->orWhereNull('telegram_tgl_verifikasi');
            })
            ->exists();
    }
}
