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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Events\Pengaduan\PengaduanSubmitted;
use App\Models\Pengaduan as PengaduanModel;
use App\Rules\CaptchaRule;
use App\Rules\NotSpam;
use App\Traits\Upload;
use Illuminate\Validation\ValidationException;
use Spatie\Image\Image;

defined('BASEPATH') || exit('No direct script access allowed');

class Pengaduan extends Web_Controller
{
    use Upload;

    public function __construct()
    {
        parent::__construct();
        $this->hak_akses_menu('pengaduan');
    }

    public function index()
    {
        $data['form_action'] = ci_route('pengaduan.kirim');
        $data['cari']        = $this->input->get('cari', true);
        $data['caristatus']  = $this->input->get('caristatus', true);

        return view('theme::partials.pengaduan.index', $data);
    }

    public function kirim()
    {
        $batasAduan = (int) setting('jumlah_aduan_pengguna');
        $sudahKirim = PengaduanModel::where('ip_address', $this->input->ip_address())
            ->whereNull('id_pengaduan')
            ->whereDate('created_at', date('Y-m-d'))
            ->count();

        if ($sudahKirim >= $batasAduan) {
            $msg = "Pengaduan gagal dikirim. Anda hanya dapat mengirimkan {$batasAduan} pengaduan dalam satu hari.";
            set_session('notif', ['status' => -1, 'pesan' => $msg]);

            return redirect_with('error', $msg);
        }

        // Normalisasi empty string ke null untuk field nullable agar validasi nullable berjalan benar (ConvertEmptyStringsToNull tidak selalu aktif di CI route)
        $req = request();
        foreach (['nik', 'email', 'telepon'] as $field) {
            if ($req->input($field) === '') {
                $req->merge([$field => null]);
            }
        }

        $validated = $this->validated($req, [
            'nik'          => 'nullable|digits:16',
            'nama'         => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\p{L}\s\.\,\-\']+$/u'],
            'email'        => 'nullable|email:rfc,dns|max:100',
            'telepon'      => ['nullable', 'regex:/^(\+62|62|0)[0-9]{8,12}$/', 'max:15'],
            'judul'        => ['required', 'string', 'min:5', 'max:255', 'not_regex:/<[^>]*>/', new NotSpam(threshold: 65)],
            'isi'          => ['required', 'string', 'min:10', 'max:5000', 'regex:/\S/', 'not_regex:/<[^>]*>/', new NotSpam(threshold: 70)],
            'foto'         => 'nullable|file|mimes:jpg,jpeg,png,webp|max:1024',
            'captcha_code' => ['required', new CaptchaRule()],
        ]);


        $foto = null;
        if (! empty($validated['foto'])) {
            $foto = $this->upload(
                file: 'foto',
                config: [
                    'upload_path'   => LOKASI_PENGADUAN,
                    'allowed_types' => 'jpg|jpeg|png|webp',
                    'max_size'      => 1024,
                    'file_name'     => namafile(strip_tags($validated['judul'])),
                    'overwrite'     => true,
                ],
                callback: static function ($uploadData) {
                    Image::load($uploadData['full_path'])
                        ->format('webp')
                        ->save("{$uploadData['file_path']}{$uploadData['raw_name']}.webp");

                    unlink($uploadData['full_path']);

                    return "{$uploadData['raw_name']}.webp";
                }
            ) ?? null;
        }

        $data = [
            'nik'        => ! empty($validated['nik']) ? bilangan($validated['nik']) : null,
            'nama'       => nama($validated['nama']),
            'email'      => isset($validated['email']) ? email($validated['email']) : null,
            'telepon'    => isset($validated['telepon']) ? bilangan($validated['telepon']) : null,
            'judul'      => bersihkan_xss($validated['judul']),
            'isi'        => bersihkan_xss($validated['isi']),
            'foto'       => $foto,
            'ip_address' => $this->input->ip_address(),
        ];

        $pengaduan    = PengaduanModel::create($data);
        $id_pengaduan = $pengaduan->id;

        event(new PengaduanSubmitted($pengaduan));

        $payload = '/pengaduan/detail/' . $id_pengaduan;
        $isi     = 'Halo! Ada pengaduan baru dari warga, mohon untuk segera ditindak lanjuti. Terima kasih.';
        $this->kirim_notifikasi_admin('all', $isi, $data['judul'], $payload);

        // Set notif legacy untuk tema seruit-lite yang masih cek session('notif')
        set_session('notif', ['status' => 1, 'pesan' => 'Pengaduan berhasil dikirim.']);

        return redirect_with('success', 'Pengaduan berhasil dikirim.');
    }

    /**
     * {@inheritdoc}
     */
    protected function invalid($request, ValidationException $exception)
    {
        // Set session('data') agar JS membuka modal kembali
        // dan form ter-repopulate — sesuai mekanisme legacy view
        set_session('data', array_intersect_key(
            $request->all(),
            array_flip(['nik', 'nama', 'email', 'telepon', 'judul', 'isi'])
        ));

        // Set pesan error pertama dari semua field untuk ditampilkan
        // via notifikasi yang sudah ada di view
        $firstError = collect($exception->errors())->flatten()->first();

        // Set notif legacy untuk tema seruit-lite yang masih cek session('notif')
        set_session('notif', ['status' => -1, 'pesan' => $firstError]);

        return redirect_with('error', $firstError);
    }
}
