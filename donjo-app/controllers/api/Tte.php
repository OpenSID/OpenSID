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

// End of file Tte.php
// Location: .//D/kerjoan/web/opendesa/premium/donjo-app/controllers/api/Tte.php

use App\Models\LogSurat;
use App\Models\LogTte;
use App\Models\Pamong;
use App\Models\PermohonanSurat;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Tte extends Premium
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $nik;

    public function __construct()
    {
        parent::__construct();

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->setting->tte_api,
            'auth'     => [
                $this->setting->tte_username,
                $this->setting->tte_password,
            ],
        ]);

        $this->nik = Pamong::kepalaDesa()->first()->pamong_nik;
    }

    /**
     * Periksa status nik.
     *
     * @param string $nik
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

            return json(json_decode($response));
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return json(json_decode($e->getResponse()->getBody()));
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
            if ($response->getStatusCode() == 200) {
                $file = fopen(FCPATH . LOKASI_ARSIP . $data->nama_surat, 'wb');
                fwrite($file, $response->getBody()->getContents());
                fclose($file);
            }

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
            $data = LogSurat::where('id', '=', $request['id'])->first();
            $mandiri = PermohonanSurat::where('id_surat', $data->id_format_surat)->where('isian_form->nomor', $data->no_surat)->first();

            $response = $this->client->post('api/sign/pdf', [
                'headers'   => ['X-Requested-With' => 'XMLHttpRequest'],
                'multipart' => [
                    ['name' => 'file', 'contents' => Psr7\Utils::tryFopen(FCPATH . LOKASI_ARSIP . $data->nama_surat, 'r')],
                    ['name' => 'nik', 'contents' => $this->nik],
                    ['name' => 'passphrase', 'contents' => $request['passphrase']],
                    ['name' => 'tampilan', 'contents' => 'visible'],
                    ['name' => 'linkQR', 'contents' => 'https://tte.kominfo.go.id/verifyPDF'],
                    ['name' => 'width', 'contents' => 90],
                    ['name' => 'height', 'contents' => 90],
                    ['name' => 'tag_koordinat', 'contents' => '[qr_bsre]'],
                ],
            ]);

            $data->update(['tte' => 1, 'log_verifikasi' => null]); // update log surat
            $mandiri->update(['status' => 3]); // update status surat dari layanan mandiri

            DB::commit();

            // overwrite dokumen lama dengan response dari bsre
            if ($response->getStatusCode() == 200) {
                $file = fopen(FCPATH . LOKASI_ARSIP . $data->nama_surat, 'wb');
                fwrite($file, $response->getBody()->getContents());
                fclose($file);
            }

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
}
