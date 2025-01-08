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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Exports\DokumentasiPembangunanOpendkExport;
use App\Exports\PembangunanOpendkExport;
use App\Exports\PendudukOpendkExport;
use App\Exports\PesertaBantuanOpendkExport;
use App\Exports\ProgramBantuanOpendkExport;
use App\Models\LogSinkronisasi;
use App\Models\PembangunanDokumentasi;
use App\Services\DataEkspor;
use GuzzleHttp\Psr7;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\CSV\Writer;

class Sinkronisasi extends Admin_Controller
{
    public $modul_ini     = 'opendk';
    public $sub_modul_ini = 'sinkronisasi';
    protected string $kode_desa;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->kode_desa = kode_wilayah($this->header['desa']['kode_desa']);
        $this->load->library('zip');
        $this->load->model('ekspor_model');
        $this->sterilkan();
    }

    public function index(): void
    {
        $modul = [
            'Program Bantuan' => [
                [
                    'path'        => 'kirim_program_bantuan',
                    'modul'       => 'program-bantuan',
                    'model'       => 'Bantuan',
                    'inkremental' => 0,
                ],
                [
                    'path'        => 'kirim_peserta_program_bantuan',
                    'modul'       => 'program-bantuan-peserta',
                    'model'       => 'BantuanPeserta',
                    'inkremental' => 0,
                ],
            ],
            'Pembangunan' => [
                [
                    'path'        => 'kirim_pembangunan',
                    'modul'       => 'pembangunan',
                    'model'       => 'Pembangunan',
                    'inkremental' => 1,
                ],
                [
                    'path'        => 'kirim_dokumentasi_pembangunan',
                    'modul'       => 'pembangunan-dokumentasi',
                    'model'       => 'PembangunanDokumentasi',
                    'inkremental' => 1,
                ],
            ],
        ];

        $data['notif']      = $this->session->flashdata('notif');
        $data['controller'] = $this->controller;

        $data = [
            'kirim_data' => ['Identitas Desa', 'Penduduk', 'Laporan Penduduk', 'Program Bantuan', 'Laporan APBDes', 'Pembangunan'],
            'modul'      => $modul,
        ];

        view('admin.sinkronisasi.index', $data);
    }

    public function sterilkan(): void
    {
        foreach (glob(LOKASI_SINKRONISASI_ZIP . '*_opendk.*') as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    public function kirim($modul): void
    {
        isCan('u');

        switch ($modul) {
            case 'penduduk':
                // Penduduk
                $notif = $this->sinkronisasi_data_penduduk();
                break;

            case 'laporan-penduduk':
                // Laporan Penduduk
                redirect('laporan_penduduk');

                // no break
            case 'laporan-apbdes':
                // Laporan APBDes
                redirect('laporan_apbdes');

                // no break
            case 'identitas-desa':
                // identitas desa
                $notif = $this->sinkronisasi_identitas_desa();
                break;

            default:
                // Data Lainnya
                break;
        }

        redirect_with('notif', $notif);
    }

    public function unduh($modul): void
    {
        switch ($modul) {
            case 'penduduk':
                // Data Penduduk
                $filename = $this->eksporPenduduk();
                break;

            case 'program-bantuan':
                // Data Program Bantuan
                $this->eksporPesertaBantuan();
                $filename = $this->eksporProgramBantuan();
                break;

            default:
                redirect($this->controller);
        }
        ambilBerkas($filename, null, null, LOKASI_SINKRONISASI_ZIP);
    }

    public function make_dokumentasi_pembangunan()
    {
        $limit = 100;
        $p     = $this->input->get('p');

        // cek tanggal akhir sinkronisasi
        $tgl_sinkronisasi = LogSinkronisasi::where('modul', '=', 'program-bantuan')->first()->updated_at ?? null;

        $data_dokumentasi = LOKASI_SINKRONISASI_ZIP . namafile('dokumentasi pembangunan') . '_opendk.csv';
        $writer           = new Writer();
        $writer->openToFile($data_dokumentasi);

        // Header Tabel
        $daftar_kolom_dokumentasi = [
            'desa_id',
            'id',
            'id_pembangunan',
            'gambar',
            'persentase',
            'keterangan',
            'created_at',
            'updated_at',
        ];
        $header = Row::fromValues($daftar_kolom_dokumentasi);
        $writer->addRow($header);
        $get_dokumentasi = PembangunanDokumentasi::when($tgl_sinkronisasi != null, static fn ($q) => $q->where('updated_at', '>', $tgl_sinkronisasi))
            ->when($tgl_sinkronisasi == null, static fn ($q) => $q->skip($p * $limit)->take($limit))->get();

        foreach ($get_dokumentasi as $row) {
            $dokumentasi = [
                $this->kode_desa,
                $row->id,
                $row->id_pembangunan,
                $row->gambar,
                $row->persentase,
                $row->keterangan,
                $row->created_at->format('Y-m-d'),
                $row->updated_at->format('Y-m-d'),
            ];

            $file_foto = LOKASI_GALERI . $row->gambar;
            if (is_file($file_foto)) {
                $this->zip->read_file($file_foto);
            }

            $rowFromValues = Row::fromValues($dokumentasi);
            $writer->addRow($rowFromValues);
        }

        $writer->close();
        $this->zip->read_file($data_dokumentasi);

        $filename = namafile('dokumentasi pembangunan') . '_opendk.zip';
        $this->zip->archive(LOKASI_SINKRONISASI_ZIP . $filename);

        return $filename;
    }

    public function eksporPenduduk()
    {
        return (new PendudukOpendkExport())->zip();
    }

    public function eksporProgramBantuan()
    {
        return (new ProgramBantuanOpendkExport())->zip();
    }

    public function eksporPesertaBantuan()
    {
        return (new PesertaBantuanOpendkExport())->zip();
    }

    public function eksporPembangunan($p = 0)
    {
        return (new PembangunanOpendkExport($p))->zip();
    }

    public function eksporDokumentasiPembangunan($p = 0)
    {
        return (new DokumentasiPembangunanOpendkExport($p))->zip();
    }

    // TODO:: Ganti dan sesuaikan cara sinkronisasi ini dengan yang baru
    private function sinkronisasi_data_penduduk()
    {
        $filename = $this->eksporPenduduk();

        //Tambah/Ubah Data
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => setting('api_opendk_server') . '/api/v1/penduduk/storedata',
            // Jika http gunakan url ini :
            //CURLOPT_URL => setting('api_opendk_server')."/api/v1/penduduk/storedata?token=".setting('api_opendk_key'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => ['file' => new CURLFILE(LOKASI_SINKRONISASI_ZIP . $filename)],
            CURLOPT_HTTPHEADER     => [
                'content-Type: multipart/form-data',
                'Authorization: Bearer ' . setting('api_opendk_key'),
            ],
        ]);

        $response  = json_decode(curl_exec($curl), null);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        //Hapus Data
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "{setting('api_opendk_server')}/api/v1/penduduk",
            // Jika http gunakan url ini :
            //CURLOPT_URL => setting('api_opendk_server')."/api/v1/penduduk?token=".setting('api_opendk_key'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode(DataEkspor::hapus_penduduk_sinkronasi_opendk(), JSON_THROW_ON_ERROR),
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . setting('api_opendk_key'),
            ],
        ]);

        $response  = json_decode(curl_exec($curl), null);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl) || $http_code === 422) {
            $notif = [
                'status' => 'danger',
                'pesan'  => '<b> ' . curl_error($curl) . "</b><br/>{$response->message}<br/>{$response->errors}",
            ];
        } else {
            $notif = [
                'status' => $response->status,
                'pesan'  => $response->message,
            ];
        }

        curl_close($curl);

        return $notif;
    }

    public function total()
    {
        if ($this->input->is_ajax_request()) {
            $modul       = $this->input->post('modul');
            $model       = $this->input->post('model');
            $inkremental = $this->input->post('inkremental');
            if ($inkremental == '0') {
                return json(1); // tanpa inkremental
            }
            $model            = 'App\\Models\\' . $model;
            $tgl_sinkronisasi = LogSinkronisasi::where('modul', '=', $modul)->first()->updated_at ?? null;
            if ($tgl_sinkronisasi) {
                return json(1); // jika sudah pernah sinkronisasi, tidak usah paginasi
            }

            return json(ceil($model::count() / 100));
        }
    }

    // MULAI IDENTITAS DESA
    private function sinkronisasi_identitas_desa()
    {
        return opendk_api('/api/v1/identitas-desa', [
            'form_params' => [
                'kode_desa'    => $this->kode_desa,
                'sebutan_desa' => setting('sebutan_desa'),
                'website'      => empty($this->header['desa']['website']) ? base_url() : $this->header['desa']['website'],
                'path'         => $this->header['desa']['path'],
            ],
        ], 'post');
    }
    // SELESAI IDENTITAS DESA

    // MULAI PROGRAM BANTUAN
    public function kirim_program_bantuan()
    {
        $filename = $this->eksporProgramBantuan();
        $akhir    = $this->input->get('akhir');

        $notif = opendk_api('/api/v1/program-bantuan', [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => Psr7\Utils::tryFopen(LOKASI_SINKRONISASI_ZIP . $filename, 'r'),
                    'filename' => $filename,
                ],
                [
                    'name'     => 'desa_id',
                    'contents' => $this->kode_desa,
                ],
            ],
        ], 'post');

        if ($akhir && $notif['status'] != 'danger') {
            $log             = LogSinkronisasi::firstOrCreate(['modul' => 'program-bantuan'], ['created_by' => $this->session->user]);
            $log->updated_by = $this->session->user;
            $log->save();
        }

        return json($notif);
    }

    public function kirim_peserta_program_bantuan()
    {
        $filename = $this->eksporPesertaBantuan();
        $akhir    = $this->input->get('akhir');

        $notif = opendk_api('/api/v1/program-bantuan/peserta', [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => Psr7\Utils::tryFopen(LOKASI_SINKRONISASI_ZIP . $filename, 'r'),
                    'filename' => $filename,
                ],
                [
                    'name'     => 'desa_id',
                    'contents' => $this->kode_desa,
                ],
            ],
        ], 'post');

        if ($akhir && $notif['status'] != 'danger') {
            $log             = LogSinkronisasi::firstOrCreate(['modul' => 'peserta-bantuan'], ['created_by' => $this->session->user]);
            $log->updated_by = $this->session->user;
            $log->save();
        }

        return json($notif);
    }

    // SELESAI PROGRAM BANTUAN

    // MULAI PEMBANGUNAN
    public function kirim_pembangunan()
    {
        $p                = $this->input->get('p');
        $file_pembangunan = $this->eksporPembangunan($p);
        $akhir            = $this->input->get('akhir');

        $notif = opendk_api('/api/v1/pembangunan', [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => Psr7\Utils::tryFopen(LOKASI_SINKRONISASI_ZIP . $file_pembangunan, 'r'),
                    'filename' => $file_pembangunan,
                ],
                [
                    'name'     => 'desa_id',
                    'contents' => $this->kode_desa,
                ],
            ],
        ], 'post');

        if ($akhir && $notif['status'] != 'danger') {
            $log             = LogSinkronisasi::firstOrCreate(['modul' => 'pembangunan'], ['created_by' => $this->session->user]);
            $log->updated_by = $this->session->user;
            $log->save();
        }

        return json($notif);
    }

    public function kirim_dokumentasi_pembangunan($value = '')
    {
        $file_dokumentasi = $this->eksporDokumentasiPembangunan();
        $akhir            = $this->input->get('akhir');

        $notif = opendk_api('/api/v1/pembangunan/dokumentasi', [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => Psr7\Utils::tryFopen(LOKASI_SINKRONISASI_ZIP . $file_dokumentasi, 'r'),
                    'filename' => $file_dokumentasi,
                ],
                [
                    'name'     => 'desa_id',
                    'contents' => $this->kode_desa,
                ],
            ],
        ], 'post');

        if ($akhir && $notif['status'] != 'danger') {
            $log             = LogSinkronisasi::firstOrCreate(['modul' => 'pembangunan-dokumentasi'], ['created_by' => $this->session->user]);
            $log->updated_by = $this->session->user;
            $log->save();
        }

        return json($notif);
    }
    // SELESAI PEMBANGUNAN
}
