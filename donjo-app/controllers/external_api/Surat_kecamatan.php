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

use App\Enums\StatusSuratKecamatanEnum;
use App\Models\LogSurat;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat_kecamatan extends Tte_Controller
{
    /**
     * @var GuzzleHttp\Client
     */
    protected $client;

    protected string $kode_desa;

    public function __construct()
    {
        parent::__construct();

        if (! empty($this->setting->sinkronisasi_opendk)) {
            $this->client = new GuzzleHttp\Client([
                'base_uri' => "{$this->setting->api_opendk_server}/api/v1/surat/",
            ]);
        }
        $this->kode_desa = kode_wilayah(identitas()->kode_desa);
    }

    public function kirim()
    {
        $request = $this->input->post();

        DB::beginTransaction();

        try {
            $surat = LogSurat::where('id', '=', $request['id'])->first();

            $this->load->model('keluar_model');
            $data = $this->keluar_model->verifikasi_data_surat($surat->id, $this->kode_desa);

            if ($this->client) {
                $this->client->post('kirim', [
                    'headers' => [
                        'Accept'        => 'application/json',
                        'Authorization' => "Bearer {$this->setting->api_opendk_key}",
                    ],
                    'multipart' => [
                        ['name' => 'file', 'contents' => Psr7\Utils::tryFopen(FCPATH . LOKASI_ARSIP . $surat->nama_surat, 'r')],
                        ['name' => 'desa_id', 'contents' => $this->kode_desa],
                        ['name' => 'nik', 'contents' => $surat->penduduk->nik],
                        ['name' => 'tanggal', 'contents' => $surat->tanggal],
                        ['name' => 'nomor', 'contents' => $data->nomor_surat],
                        ['name' => 'nama', 'contents' => $surat->formatSurat->nama],
                    ],
                ]);

                $surat->update(['kecamatan' => StatusSuratKecamatanEnum::SudahDikirim]); // update log surat
            }

            DB::commit();

            return json([
                'status'      => true,
                'pesan'       => 'success',
                'jenis_error' => null,
            ]);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            log_message('error', $e);

            DB::rollback();

            return json([
                'status'      => false,
                'pesan'       => $e->getResponse()->getBody()->getContents(),
                'jenis_error' => 'ClientException',
            ]);
        }
    }

    public function download($jenis, $nomor, $desa, $bulan, $tahun)
    {
        if ($this->client) {
            try {
                $response = $this->client->get("download?desa_id={$this->kode_desa}&nomor={$jenis}/{$nomor}/{$desa}/{$bulan}/{$tahun}", [
                    'headers' => [
                        'Accept'        => 'application/pdf',
                        'Authorization' => "Bearer {$this->setting->api_opendk_key}",
                    ],
                ]);

                $filename = "kecamatan_{$jenis}_{$nomor}_{$bulan}_{$tahun}.pdf";

                if ($response->getStatusCode() == 200) {
                    $file = fopen(FCPATH . LOKASI_ARSIP . $filename, 'wb');
                    fwrite($file, $response->getBody()->getContents());
                    fclose($file);
                }

                ambilBerkas($filename, 'keluar/kecamatan', null, LOKASI_ARSIP, true);
            } catch (GuzzleHttp\Exception\ClientException $e) {
                log_message('error', $e);

                $_SESSION['success']   = -99;
                $_SESSION['error_msg'] = $e->getResponse()->getBody()->getContents();

                return redirect('keluar/kecamatan');
            }
        }

        $_SESSION['success']   = -99;
        $_SESSION['error_msg'] = 'Tidak bisa mengambil surat dari kecamatan, apikey opendk belum disetting';

        return redirect('keluar/kecamatan');
    }
}
