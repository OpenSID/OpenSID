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

use App\Enums\JawabanKepuasanEnum;
use App\Enums\StatusEnum;
use Modules\BukuTamu\Events\TamuSubmitted;
use App\Models\RefJabatan;
use App\Rules\NotSpam;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Modules\BukuTamu\Models\KeperluanModel;
use Modules\BukuTamu\Models\KepuasanModel;
use Modules\BukuTamu\Models\PertanyaanModel;
use Modules\BukuTamu\Models\TamuModel;
use NotificationChannels\Telegram\Telegram;

defined('BASEPATH') || exit('No direct script access allowed');

class BukuTamuController extends WebModulController
{
    public $moduleName = 'BukuTamu';

    public function __construct()
    {
        parent::__construct();
        if (setting('layanan_mandiri') == 0) {
            show_404();
        }

        if (null === $this->cek_anjungan) {
            show_404();
        }
    }

    public function index()
    {
        return view('bukutamu::frontend.registrasi', [
            'aksi'      => ci_route('buku-tamu.registrasi'),
            'bertemu'   => RefJabatan::pluck('nama', 'id'),
            'keperluan' => KeperluanModel::whereStatus(StatusEnum::YA)->pluck('keperluan', 'id'),
            'kamera'    => setting('buku_tamu_kamera'),
        ]);
    }

    public function registrasi()
    {
        $validated = $this->validated(request(), [
            'nama'              => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L}\s\.\,\-\']+$/u', 'not_regex:/<[^>]*>/', new NotSpam(threshold: 70)],
            'telepon'           => ['required', 'regex:/^[0-9]{9,20}$/', 'max:20'],
            'instansi'          => ['required', 'string', 'max:100', 'not_regex:/<[^>]*>/', new NotSpam(threshold: 70)],
            'jenis_kelamin'     => 'required|in:1,2',
            'alamat'            => ['nullable', 'string', 'max:500', 'not_regex:/<[^>]*>/', new NotSpam(threshold: 70)],
            'keperluan'         => ['required', 'string', 'max:500'],
            'keperluan_lainnya' => ['nullable', 'string', 'max:500', 'not_regex:/<[^>]*>/', new NotSpam(threshold: 70)],
            'id_bidang'         => ['required', 'numeric'],
            'foto'              => 'nullable|string',
        ]);

        $duplikat = TamuModel::whereNama(nama(bersihkan_xss($validated['nama'])))
            ->whereTelepon(bilangan($validated['telepon']))
            ->whereJenisKelamin(bilangan($validated['jenis_kelamin']))
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($duplikat) {
            return redirect_with('error', 'Registrasi Gagal Disimpan<br>Anda Sudah Melakukan Registrasi Hari Ini', 'buku-tamu');
        }

        $data = [
            'nama'          => nama(bersihkan_xss($validated['nama'])),
            'telepon'       => bilangan($validated['telepon']),
            'instansi'      => bersihkan_xss($validated['instansi']),
            'jenis_kelamin' => bilangan($validated['jenis_kelamin']),
            'alamat'        => bersihkan_xss($validated['alamat'] ?? ''),
            'keperluan'     => $this->resolveKeperluan($validated),
            'bidang'        => bilangan($validated['id_bidang']),
            'foto'          => $this->foto($validated['foto'] ?? null),
        ];

        $tamu = TamuModel::create($data);

        if (! $tamu) {
            return redirect_with('error', 'Registrasi Gagal Disimpan', 'buku-tamu');
        }

        event(new TamuSubmitted($tamu));

        if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
            $this->sendTelegramNotification($tamu);
        }

        return redirect_with('success', 'Registrasi Berhasil Disimpan', 'buku-tamu/kepuasan');
    }

    /**
     * {@inheritdoc}
     */
    protected function invalid($request, ValidationException $exception)
    {
        $firstError = collect($exception->errors())->flatten()->first();

        return redirect_with('error', $firstError, 'buku-tamu/kepuasan');
    }

    /**
     * Resolve keperluan: gunakan keperluan_lainnya jika keperluan == '0' (Temuan #1)
     *
     * Perbandingan string '0', bukan cast (int) yang akan mengubah teks apapun jadi 0
     */
    private function resolveKeperluan(array $validated): string
    {
        $keperluan = (string) $validated['keperluan'];

        if ($keperluan === '0' && ! empty($validated['keperluan_lainnya'])) {
            return bersihkan_xss($validated['keperluan_lainnya']);
        }

        return bersihkan_xss($keperluan);
    }

    /**
     * Kirim notifikasi registrasi ke Telegram
     */
    private function sendTelegramNotification($tamu): void
    {
        try {
            $pesan = <<<HTML
<b>Registrasi Buku Tamu Baru</b>

<b>Nama:</b> {$tamu->nama}
<b>Telepon:</b> {$tamu->telepon}
<b>Instansi:</b> {$tamu->instansi}
<b>Jenis Kelamin:</b> {$tamu->jenis_kelamin}
<b>Alamat:</b> {$tamu->alamat}
<b>Bertemu:</b> {$tamu->bidang}
<b>Keperluan:</b> {$tamu->keperluan}
HTML;

            $telegram = new Telegram(setting('telegram_token'));
            $telegram->sendMessage([
                'text'       => $pesan,
                'parse_mode' => 'HTML',
                'chat_id'    => setting('telegram_user_id'),
            ]);
        } catch (Exception $e) {
            log_message('error', 'Telegram notification failed: ' . $e->getMessage());
        }
    }

    public function kepuasan($id = null)
    {
        $data['ada_pertanyaan'] = PertanyaanModel::whereStatus(StatusEnum::YA)->exists();

        if ($data['ada_pertanyaan']) {
            if ($id) {
                $data['pertanyaan'] = $this->cek_pertanyaan($id);
                $data['id']         = $id;
                $view               = 'bukutamu::frontend.pertanyaan';
            } else {
                $kepuasan              = KepuasanModel::whereDate('created_at', Carbon::today())->pluck('id_nama');
                $data['tamu_hari_ini'] = TamuModel::whereNotIn('id', $kepuasan)->whereDate('created_at', Carbon::today())->latest()->get();
                $view                  = 'bukutamu::frontend.kepuasan';
            }
        } else {
            $data['tamu_hari_ini'] = null;
            $view                  = 'bukutamu::frontend.kepuasan';
        }

        return view($view, $data);
    }

    public function jawaban($id = null, $jawaban = null): void
    {
        $tamu = TamuModel::find($id);

        if (! $tamu || ! in_array($jawaban, JawabanKepuasanEnum::keys())) {
            set_session('error', 'Jawaban Gagal Disimpan');
        } else {
            $cek_pertanyaan = KepuasanModel::whereIdNama($id)->pluck('id_pertanyaan');
            $pertanyaan     = PertanyaanModel::whereNotIn('id', $cek_pertanyaan)->whereStatus(StatusEnum::YA)->first();
            KepuasanModel::create([
                'id_nama'           => $tamu->id,
                'id_pertanyaan'     => $pertanyaan->id,
                'pertanyaan_statis' => $pertanyaan->pertanyaan,
                'id_jawaban'        => $jawaban,
            ]);

            // jika masih ada pertanyaan
            if ($this->cek_pertanyaan($id)) {
                set_session('success', '<h1>Jawaban Berhasil Disimpan</h1><br><br>Ke Pertanyaan Selanjutnya');
                redirect('buku-tamu/kepuasan/' . $id);
            }
        }

        redirect('buku-tamu/kepuasan/' . $id);
    }

    private function cek_pertanyaan($id = null)
    {
        $sudah_ada  = KepuasanModel::whereIdNama($id)->pluck('id_pertanyaan');
        $pertanyaan = PertanyaanModel::whereNotIn('id', $sudah_ada)->whereStatus(StatusEnum::YA)->first();

        if (! $pertanyaan) {
            set_session('success', '<h1>TERIMA KASIH</h1><br><br>Anda Telah Membantu Kami Untuk Melayani Lebih Baik Lagi.');
            redirect('buku-tamu');
        }

        return $pertanyaan;
    }

    /**
     * Simpan foto dari webcam capture (base64) dengan validasi ketat
     *
     * @param string|null $base64
     * @return string|null Nama file jika berhasil, null jika gagal
     */
    private function foto(?string $base64 = null): ?string
    {
        if (! $base64) {
            return null;
        }

        try {

            $base64    = preg_replace('#^data:image/\w+;base64,#', '', $base64);
            $imageData = base64_decode($base64, true);

            if ($imageData === false) {
                log_message('warning', 'Invalid base64 foto data');
                return null;
            }

            $imageInfo = @getimagesizefromstring($imageData);
            if ($imageInfo === false) {
                log_message('warning', 'Uploaded data is not a valid image');
                return null;
            }

            if (strlen($imageData) > 5 * 1024 * 1024) {
                log_message('warning', 'Foto size exceeds 5MB limit');
                return null;
            }

            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            $mimeType     = $imageInfo['mime'] ?? '';
            if (! in_array($mimeType, $allowedMimes, true)) {
                log_message('warning', 'Invalid image MIME type: ' . $mimeType);
                return null;
            }

            $extension = match ($mimeType) {
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                default      => 'jpg',
            };

            $namaFile = time() . random_int(10000, 999999) . '.' . $extension;
            $filePath = FCPATH . LOKASI_FOTO_BUKU_TAMU . $namaFile;

            if (file_put_contents($filePath, $imageData) !== false) {
                return $namaFile;
            }

            log_message('error', 'Failed to save foto file');
            return null;
        } catch (Exception $e) {
            log_message('error', 'Foto processing error: ' . $e->getMessage());
            return null;
        }
    }
}
