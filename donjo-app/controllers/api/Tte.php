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
use GuzzleHttp\Psr7;

defined('BASEPATH') || exit('No direct script access allowed');

class Tte extends CI_Controller
{
    private $server;
    public $client;

    public function __construct()
    {
        parent::__construct();
        $this->server = site_url();
        $this->client = new \GuzzleHttp\Client();
    }

    public function index()
    {
        $jsonFile = json_decode(file_get_contents(FCPATH . 'tte.json'), true);

        return json($jsonFile);
    }

    public function kirim()
    {
        $request = $this->validation($this->input->post());

        try {
            $data = LogSurat::where('id', '=', $request['id'])->first();

            $response = $this->client->post("{$this->server}api/tte", [
                'headers'   => ['X-Requested-With' => 'XMLHttpRequest'],
                'multipart' => [
                    ['name' => 'file', 'contents' => Psr7\Utils::tryFopen(FCPATH . LOKASI_ARSIP . $data->nama_surat, 'r')],
                    // ['name' => 'imageTTD', 'contents' => Psr7\Utils::tryFopen(DESAPATH . 'config/ttd.png', 'r')],
                    ['name' => 'nik', 'contents' => '1234567890'],
                    ['name' => 'passphrase', 'contents' => ''],
                    ['name' => 'tampilan', 'contents' => ''],
                    ['name' => 'halaman', 'contents' => ''],
                    ['name' => 'page', 'contents' => ''],
                    ['name' => 'linkQR', 'contents' => ''],
                    ['name' => 'xAxis', 'contents' => ''],
                    ['name' => 'yAxis', 'contents' => ''],
                    ['name' => 'width', 'contents' => ''],
                    ['name' => 'height', 'contents' => ''],
                    ['name' => 'tag_koordinat', 'contents' => ''],
                    ['name' => 'reason', 'contents' => ''],
                    ['name' => 'text', 'contents' => ''],
                ],
            ])->getBody()->getContents();

            $data_respon = json_decode($response);
            $data->update(['tte' => 1, 'log_verifikasi' => null]); // update log surat

            return json(['status' => true, 'data' => $data_respon]);
        } catch (GuzzleHttp\Exception\ConnectException $e) { // error karena masalah koneksi
            $message = $e->getHandlerContext()['error'];
            $notif   = [
                'status'      => false,
                'pesan'       => $message,
                'jenis_error' => 'ConnectException',
            ];
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $message = $e->getResponse()->getBody()->getContents();
            $notif   = [
                'status'      => false,
                'pesan'       => $message,
                'jenis_error' => 'ClientException',
            ];
        } catch (GuzzleHttp\Exception\BadResponseException $e) { //Exception when an HTTP error occurs (4xx or 5xx error)
            $message = $e->getResponse()->getBody()->getContents();
            $notif   = [
                'status'      => false,
                'pesan'       => 'BadResponseException : ' . $message,
                'jenis_error' => 'BadResponseException',
            ];
        } catch (RuntimeException $e) { // tangkap error karena RuntimeException
            $message = $e->getMessage();
            $notif   = [
                'status'      => false,
                'pesan'       => $message,
                'jenis_error' => 'RuntimeException',
            ];
        }

        $this->response($notif);
    }

    public function validation($request = [])
    {
        $passphrase = '123456';

        if ($request['passphrase'] != $passphrase || $request['passphrase'] == '') {
            $notif = [
                'status'      => false,
                'pesan'       => 'passphrase salah',
                'jenis_error' => 'Validation',
            ];
            $this->response($notif);
        }

        return [
            'passphrase' => bilangan($request['nama']),
            'id'         => (int) $request['id'],
        ];
    }

    public function response($value = '')
    {
        LogTte::create([
            'message'     => $notif['pesan'],
            'jenis_error' => $notif['jenis_error'],
        ]);

        return json($value);
    }
}
