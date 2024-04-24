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

use App\Models\LogSurat;
use App\Models\LogSuratDinas;
use App\Models\LogTte;
use App\Models\Pamong;
use App\Models\PermohonanSurat;
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
            'base_uri' => empty($this->setting->tte_api) || get_domain($this->setting->tte_api) === get_domain(APP_URL) ? site_url() : $this->setting->tte_api,
            'auth'     => [
                $this->setting->tte_username,
                $this->setting->tte_password,
            ],
        ]);

        $this->demo = empty($this->setting->tte_api) || get_domain($this->setting->tte_api) === get_domain(APP_URL);
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
        $request = $this->input->post();

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

            return $this->response([
                'status'      => true,
                'pesan'       => 'success',
                'jenis_error' => null,
            ]);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            log_message('error', $e);

            DB::rollback();

            return $this->response([
                'status'      => false,
                'pesan'       => $e->getResponse()->getBody()->getContents(),
                'jenis_error' => 'ClientException',
            ]);
        }
    }

    public function sign_visible()
    {
        $request = $this->input->post();
        DB::beginTransaction();

        try {
            $tipe    = $request['tipe'] ?? 'layanan_surat';
            $data    = $tipe == 'surat_dinas' ? LogSuratDinas::where('id', '=', $request['id'])->first() : LogSurat::where('id', '=', $request['id'])->first();
            $mandiri = PermohonanSurat::where('id_surat', $data->id_format_surat)->where('isian_form->nomor', $data->no_surat)->first();

            if (setting('visual_tte') == 1) {
                $width  = setting('visual_tte_weight') ?? 90;
                $height = setting('visual_tte_height') ?? 90;
                $image  = setting('visual_tte_gambar') ?: 'assets/images/bsre.png';

                $visible = [
                    ['name' => 'tag_koordinat', 'contents' => '[qr_bsre]'],
                    ['name' => 'image', 'contents' => true],
                    ['name' => 'imageTTD', 'contents' => Psr7\Utils::tryFopen(FCPATH . $image, 'r')],
                ];
            } else {
                $this->load->model('url_shortener_model');
                $urls    = $this->url_shortener_model->url_pendek($data);
                $tag     = '[qr_bsre]';
                $width   = 90;
                $height  = 90;
                $visible = [
                    ['name' => 'tag_koordinat', 'contents' => '[qr_bsre]'],
                    ['name' => 'linkQR', 'contents' => $urls['isiqr']],
                ];
            }

            $multipart = [
                ['name' => 'file', 'contents' => Psr7\Utils::tryFopen(FCPATH . LOKASI_ARSIP . $data->nama_surat, 'r')],
                ['name' => 'nik', 'contents' => $this->nik],
                ['name' => 'passphrase', 'contents' => $request['passphrase']],
                ['name' => 'tampilan', 'contents' => 'visible'],
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

            return $this->response([
                'status'      => true,
                'pesan'       => 'success',
                'jenis_error' => null,
            ]);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            log_message('error', $e);

            DB::rollback();

            return $this->response([
                'status'      => false,
                'pesan'       => $e->getResponse()->getBody()->getContents(),
                'jenis_error' => 'ClientException',
            ]);
        }
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

        return json($notif);
    }

    public function kirim_notifikasi($mandiri): void
    {
        // kirim notifikasi ke pemohon bahwa suratnya siap untuk diambil
        $id_penduduk = $mandiri['id_pemohon'];
        $pesan       = 'Surat ' . $mandiri->surat->nama . ' siap untuk dambil';
        $judul       = 'Surat ' . $mandiri->surat->nama . ' siap untuk dambil';

        $this->kirim_notifikasi_penduduk($id_penduduk, $pesan, $judul);
    }
}
