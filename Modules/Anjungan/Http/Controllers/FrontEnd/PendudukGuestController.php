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
use App\Services\Auth\Traits\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

require_once FCPATH . 'Modules/Anjungan/Http/Controllers/FrontEnd/AnjunganController.php';

class PendudukGuestController extends AnjunganController
{
    use LoginRequest;

    protected $guard = 'pendudukGuest';

    public function __construct()
    {
        parent::__construct();

        if ($this->cek_anjungan['permohonan_surat_tanpa_akun'] == 0) {
            show_404();
        }
    }

    public function create()
    {
        $data = $this->sharedData();

        return view('anjungan::frontend.penduduk-guest', $data);
    }

    public function store()
    {
        $request = request();

        $this->authenticateEktp($request);

        return redirect('anjungan-mandiri/surat');
    }

    public function destroy()
    {
        Auth::guard($this->guard)->logout();

        return redirect('anjungan-mandiri');
    }

    protected function authenticateEktp(Request $request)
    {
        $this->ensureIsNotRateLimited();

        $this->validated($request, $this->rules());

        $penduduk = Penduduk::query()
            ->when($request->filled('tag_id_card'), static function ($query) use ($request) {
                $query->where('tag_id_card', $request->tag_id_card);
            }, static function ($query) use ($request) {
                $query->where('nama', $request->nama)
                    ->where('tanggallahir', $request->tanggal_lahir);
            })
            ->first();

        if (! $penduduk) {
            RateLimiter::hit($this->throttleKey(), config_item('lockout_time'));

            try {
                throw ValidationException::withMessages([
                    'credential' => trans('auth.failed'),
                ]);
            } catch (ValidationException $e) {
                return $this->invalid($request, $e);
            }
        }

        Auth::guard($this->guard)->login($penduduk);

        RateLimiter::clear($this->throttleKey());
    }

    protected function rules()
    {
        $request = request();

        $this->session->set_userdata('login_penduduk_guest', true);

        if ($request->filled('tag_id_card')) {
            return [
                'tag_id_card' => [
                    'required',
                    'digits:10',
                    Rule::exists('penduduk_hidup', 'tag_id_card')
                        ->where('config_id', identitas('id')),
                ],
            ];
        }

        return [
            'nama' => [
                'required',
                Rule::exists('penduduk_hidup', 'nama')
                    ->where('config_id', identitas('id')),
            ],
            'tanggal_lahir' => [
                'required',
                'date_format:Y-m-d',
                Rule::exists('penduduk_hidup', 'tanggallahir')
                    ->where('config_id', identitas('id')),
            ],
        ];
    }

    protected function throttleKey()
    {
        $key = request('nama') ?: request('tag_id_card');

        return Str::transliterate($key . '|' . request()->ip());
    }
}
