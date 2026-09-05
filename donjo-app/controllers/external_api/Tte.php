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

use App\Libraries\TinyMCE;
use App\Models\LogSurat;
use App\Models\LogSuratDinas;
use App\Models\LogTte;
use App\Models\Pamong;
use App\Models\PermohonanSurat;
use App\Models\Urls;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Tte extends Tte_Controller
{
    protected GuzzleHttp\Client $client;
    protected bool $demo;

    /**
     * @var string
     */
    protected $nik;

    public function __construct()
    {
        parent::__construct();

        $this->client = new GuzzleHttp\Client([
            'base_uri' => empty(setting('tte_api')) || get_domain(setting('tte_api')) === get_domain(APP_URL) ? site_url() : setting('tte_api'),
            'auth'     => [
                setting('tte_username'),
                setting('tte_password'),
            ],
            'verify' => setting('ssl_tte') == App\Enums\AktifEnum::AKTIF,
        ]);

        $this->demo = empty(setting('tte_api')) || get_domain(setting('tte_api')) === get_domain(APP_URL);
        $this->nik  = Pamong::kepalaDesa()->first()->pamong_nik;
    }

    /**
     * Periksa status nik.
     *
     * @return object
     */
    public function periksa_status(?string $nik = '')
    {
        try {
            $response = $this->client
                ->get("api/user/status/{$nik}")
                ->getBody()
                ->getContents();

            return json(json_decode($response, null));
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return json(json_decode($e->getResponse()->getBody(), null));
        }
    }

    public function sign_invisible()
    {
        $request      = $this->input->post();
        $errorMessage = null;
        $typeError    = null;
        DB::beginTransaction();

        try {
            $data    = LogSurat::where('id', '=', $request['id'])->first();
            $mandiri = PermohonanSurat::where('id_surat', $data->id_format_surat)->where('isian_form->nomor', $data->no_surat)->first();

            $response = $this->client->post('api/sign/pdf', [
                'headers'   => ['X-Requested-With' => 'XMLHttpRequest'],
                'multipart' => [
                    ['name' => 'file', 'contents' => Psr7\Utils::tryFopen(FCPATH . LOKASI_ARSIP . $data->nama_surat, 'r')],
                    ['name' => 'nik', 'contents' => $this->nik],
                    ['name' => 'passphrase', 'contents' => $request['passphrase']],
                    ['name' => 'tampilan', 'contents' => 'invisible'],
                ],
            ]);

            $data->update(['tte' => 1, 'log_verifikasi' => null]); // update log surat
            $mandiri->update(['status' => 3]); // update status surat dari layanan mandiri

            DB::commit();

            // overwrite dokumen lama dengan response dari bsre
            if ($response->getStatusCode() == 200 && ! $this->demo) {
                $file = fopen(FCPATH . LOKASI_ARSIP . $data->nama_surat, 'wb');
                fwrite($file, $response->getBody()->getContents());
                fclose($file);
            }

            $this->kirim_notifikasi($mandiri);

            // catat aktivitas dan kembalikan response JSON yang valid untuk klien
            $this->logActivity('TTE', 'sign_invisible', 'TTE Surat Berhasil', [
                'id_surat'   => $data->id,
                'no_surat'   => $data->no_surat,
                'nama_surat' => $data->nama_surat,
            ]);

            return json([
                'status'     => true,
                'pesan'      => 'TTE Surat Berhasil',
                'id_surat'   => $data->id,
                'no_surat'   => $data->no_surat,
                'nama_surat' => $data->nama_surat,
            ]);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            log_message('error', $e);

            DB::rollback();
            $errorMessage = $e->getResponse()->getBody()->getContents() ?: $e->getMessage();
            $typeError    = 'ClientException';
        } catch (Exception $e) {
            log_message('error', $e);
            DB::rollback();
            $errorMessage = $e->getMessage();
            $typeError    = 'Exception';
        }
            // periksa apakah ada error pada response
            if ($typeError || $errorMessage) {
                $this->logActivity('TTE', 'sign_invisible', 'TTE Surat Gagal', [
                    'id_surat'    => $data->id,
                    'no_surat'    => $data->no_surat,
                    'nama_surat'  => $data->nama_surat,
                    'pesan'       => $errorMessage,
                    'jenis_error' => $typeError ?: 'UnknownError',
                ]);

                return $this->response([
                    'pesan'       => $errorMessage ?: 'TTE Surat Gagal',
                    'jenis_error' => $typeError ?: 'UnknownError',
                ]);
            }

    }

    public function sign_visible()
    {
        $request = $this->input->post();
        DB::beginTransaction();
        $errorMessage = null;
        $typeError    = null;

        try {

            $tipe = $request['tipe'] ?? 'layanan_surat';
            $data = $tipe == 'surat_dinas' ? LogSuratDinas::where('id', '=', $request['id'])->first() : LogSurat::where('id', '=', $request['id'])->first();

            $mandiri  = PermohonanSurat::where('id_surat', $data->id_format_surat)->where('isian_form->nomor', $data->no_surat)->first();
            $tag      = TinyMCE::TAG_TTE;
            $tampilan = 'visible';
            if (setting('visual_tte') == 1) {
                $urls = Urls::urlPendek($data->toArray());

                $width  = setting('visual_tte_weight') ?? 90;
                $height = setting('visual_tte_height') ?? 90;
                $image  = setting('visual_tte_gambar') ? LOKASI_MEDIA . setting('visual_tte_gambar') : 'assets/images/bsre.png';

                $visible = [
                    ['name' => 'tag_koordinat', 'contents' => $tag],
                    ['name' => 'image', 'contents' => true],
                    ['name' => 'imageTTD', 'contents' => Psr7\Utils::tryFopen(FCPATH . $image, 'r')],
                ];
            } else {
                $urls    = Urls::urlPendek($data->toArray());
                $width   = 90;
                $height  = 90;
                $visible = [
                    ['name' => 'tag_koordinat', 'contents' => $tag],
                    ['name' => 'linkQR', 'contents' => $urls['isiqr']],
                ];
            }

            $multipart = [
                ['name' => 'file', 'contents' => Psr7\Utils::tryFopen(FCPATH . LOKASI_ARSIP . $data->nama_surat, 'r')],
                ['name' => 'nik', 'contents' => $this->nik],
                ['name' => 'passphrase', 'contents' => $request['passphrase']],
                ['name' => 'tampilan', 'contents' => $tampilan],
                ['name' => 'width', 'contents' => $width],
                ['name' => 'height', 'contents' => $height],
            ];

            $response = $this->client->post('api/sign/pdf', [
                'headers'   => ['X-Requested-With' => 'XMLHttpRequest'],
                'multipart' => [...$multipart, ...$visible],
            ]);

            $data->update(['tte' => 1, 'log_verifikasi' => null]); // update log surat
            if ($mandiri) {
                $mandiri->update(['status' => 3]); // update status surat dari layanan mandiri
            }

            DB::commit();

            // overwrite dokumen lama dengan response dari bsre
            if ($response->getStatusCode() == 200 && ! $this->demo) {
                $file = fopen(FCPATH . LOKASI_ARSIP . $data->nama_surat, 'wb');
                fwrite($file, $response->getBody()->getContents());
                fclose($file);
            }

            $this->kirim_notifikasi($mandiri);

            // catat aktivitas dan kembalikan response JSON yang valid untuk klien
            $this->logActivity('TTE', 'sign_visible', 'TTE Surat Berhasil', [
                'id_surat'   => $data->id,
                'no_surat'   => $data->no_surat,
                'nama_surat' => $data->nama_surat,
            ]);

            return json([
                'status'     => true,
                'pesan'      => 'TTE Surat Berhasil',
                'id_surat'   => $data->id,
                'no_surat'   => $data->no_surat,
                'nama_surat' => $data->nama_surat,
            ]);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            log_message('error', $e->getMessage());

            DB::rollback();
            $errorMessage = $e->getResponse()->getBody()->getContents() ?: $e->getMessage();
            $typeError    = 'ClientException';
        } catch (Exception $e) {
            log_message('error', $e);
            DB::rollback();
            $errorMessage = $e->getMessage();
            $typeError    = 'Exception';
        }
            // periksa apakah ada error pada response
        if ($typeError || $errorMessage) {
            $this->logActivity('TTE', 'sign_visible', 'TTE Surat Gagal', [
                'id_surat'    => $data->id,
                'no_surat'    => $data->no_surat,
                'nama_surat'  => $data->nama_surat,
                'pesan'       => $errorMessage,
                'jenis_error' => $typeError ?: 'UnknownError',
            ]);

            return $this->response([
                'pesan'       => $errorMessage ?: 'TTE Surat Gagal',
                'jenis_error' => $typeError ?: 'UnknownError',
            ]);
        }

    }

    public function kirim_notifikasi($mandiri): void
    {
        // kirim notifikasi ke pemohon bahwa suratnya siap untuk diambil
        $id_penduduk = $mandiri['id_pemohon'];
        $pesan       = 'Surat ' . $mandiri->surat->nama . ' siap untuk dambil';
        $judul       = 'Surat ' . $mandiri->surat->nama . ' siap untuk dambil';

        $this->kirim_notifikasi_penduduk($id_penduduk, $pesan, $judul);
    }

    /**
     * Generate response dan log.
     *
     * @param array $notif
     *
     * @return object
     */
    protected function response($notif = [])
    {
        LogTte::create([
            'message'     => $notif['pesan'],
            'jenis_error' => $notif['jenis_error'],
        ]);

        $message = $notif['pesan'] ?? 'TTE Surat Gagal';
        $code    = $notif['code'] ?? 422;

        header(sprintf('HTTP/1.1 %d %s', $code, $message), true, $code);
        header('Content-Type: text/plain; charset=utf-8');
        echo $message;

        exit;
    }

    private function logActivity(string $logName, $event, $description, $property): void
    {
        activity()
            ->causedBy(auth()->id)
            ->inLog($logName)
            ->event($event)
            ->withProperties($property)
            ->log($description);
    }
}
