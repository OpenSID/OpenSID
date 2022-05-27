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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use App\Models\LogSinkronisasi;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use GuzzleHttp\Psr7;

class Sinkronisasi extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini     = 343;
        $this->sub_modul_ini = 326;
        $this->load->library('zip');
        $this->load->model('ekspor_model');
        $this->sterilkan();
    }

    public function index()
    {
        $modul = [
            'Program Bantuan' => [
                [
                    'path'  => 'kirim_program_bantuan',
                    'modul' => 'program-bantuan',
                    'model' => 'Bantuan',
                ],
                [
                    'path'  => 'kirim_peserta_program_bantuan',
                    'modul' => 'program-bantuan-peserta',
                    'model' => 'BantuanPeserta',
                ],
            ],
        ];

        $data = [
            'kirim_data' => ['Penduduk', 'Laporan Penduduk', 'Laporan APBDes', 'Pembangunan', 'Program Bantuan', 'Identitas Desa'],
            'modul'      => $modul,
        ];

        $this->render("{$this->controller}/index", $data);
    }

    public function sterilkan()
    {
        foreach (glob(LOKASI_SINKRONISASI_ZIP . '*_opendk.*') as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    public function kirim($modul)
    {
        $this->redirect_hak_akses('u');

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
            case 'pembangunan':
                // Pembangunan
                redirect('admin_pembangunan');

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

    public function unduh($modul)
    {
        switch ($modul) {
            case 'penduduk':
                // Data Penduduk
                $filename = $this->data_penduduk();
                ambilBerkas($filename, null, null, LOKASI_SINKRONISASI_ZIP);
                break;

            case 'program-bantuan':
                // Data Program Bantuan
                $this->data_peserta_program_bantuan();
                $filename = $this->data_program_bantuan();
                ambilBerkas($filename, null, null, LOKASI_SINKRONISASI_ZIP);
                break;

            default:
                // Data Lainnya
                break;
        }

        redirect($this->controller);
    }

    private function data_penduduk()
    {
        $writer = WriterEntityFactory::createXLSXWriter();

        //Nama File
        $tgl    = date('d_m_Y');
        $lokasi = LOKASI_SINKRONISASI_ZIP . 'penduduk_' . $tgl . '_opendk.xlsx';
        $writer->openToFile($lokasi);

        //Header Tabel
        $daftar_kolom = [
            ['Alamat', 'alamat'],
            ['Dusun', 'dusun'],
            ['RW', 'rw'],
            ['RT', 'rt'],
            ['Nama', 'nama'],
            ['Nomor KK', 'nomor_kk'],
            ['Nomor NIK', 'nomor_nik'],
            ['Jenis Kelamin', 'jenis_kelamin'],
            ['Tempat Lahir', 'tempat_lahir'],
            ['Tanggal Lahir', 'tanggal_lahir'],
            ['Agama', 'agama'],
            ['Pendidikan (dlm KK)', 'pendidikan_dlm_kk'],
            ['Pendidikan (sdg ditempuh)', 'pendidikan_sdg_ditempuh'],
            ['Pekerjaan', 'pekerjaan'],
            ['Kawin', 'kawin'],
            ['Hub. Keluarga', 'hubungan_keluarga'],
            ['Kewarganegaraan', 'kewarganegaraan'],
            ['Nama Ayah', 'nama_ayah'],
            ['Nama Ibu', 'nama_ibu'],
            ['Gol. Darah', 'gol_darah'],
            ['Akta Lahir', 'akta_lahir'],
            ['Nomor Dokumen Paspor', 'nomor_dokumen_pasport'],
            ['Tanggal Akhir Paspor', 'tanggal_akhir_pasport'],
            ['Nomor Dokumen KITAS', 'nomor_dokumen_kitas'],
            ['NIK Ayah', 'nik_ayah'],
            ['NIK Ibu', 'nik_ibu'],
            ['Nomor Akta Perkawinan', 'nomor_akta_perkawinan'],
            ['Tanggal Perkawinan', 'tanggal_perkawinan'],
            ['Nomor Akta Perceraian', 'nomor_akta_perceraian'],
            ['Tanggal Perceraian', 'tanggal_perceraian'],
            ['Cacat', 'cacat'],
            ['Cara KB', 'cara_kb'],
            ['Hamil', 'hamil'],
            ['KTP-el', 'ktp_el'],
            ['Status Rekam', 'status_rekam'],
            ['Alamat Sekarang', 'alamat_sekarang'],
        ];
        $judul = array_column($daftar_kolom, 1);

        // Kolom tambahan khusus OpenDK
        $judul[] = 'id';
        $judul[] = 'foto';
        $judul[] = 'status_dasar';
        $judul[] = 'created_at';
        $judul[] = 'updated_at';
        $judul[] = 'desa_id';

        $header = WriterEntityFactory::createRowFromArray($judul);
        $writer->addRow($header);

        $get = $this->ekspor_model->tambah_penduduk_sinkronasi_opendk();

        foreach ($get as $row) {
            $penduduk = [
                $row->alamat,
                $row->dusun,
                $row->rw,
                $row->rt,
                $row->nama,
                $row->no_kk,
                $row->nik,
                $row->sex,
                $row->tempatlahir,
                $row->tanggallahir,
                $row->agama_id,
                $row->pendidikan_kk_id,
                $row->pendidikan_sedang_id,
                $row->pekerjaan_id,
                $row->status_kawin,
                $row->kk_level,
                $row->warganegara_id,
                $row->nama_ayah,
                $row->nama_ibu,
                $row->golongan_darah_id,
                $row->akta_lahir,
                $row->dokumen_pasport,
                $row->tanggal_akhir_pasport,
                $row->dokumen_kitas,
                $row->ayah_nik,
                $row->ibu_nik,
                $row->akta_perkawinan,
                $row->tanggalperkawinan,
                $row->akta_perceraian,
                $row->tanggalperceraian,
                $row->cacat_id,
                $row->cara_kb_id,
                $row->hamil,
                $row->ktp_el,
                $row->status_rekam,
                $row->alamat_sekarang,
                $row->id,
                $row->foto,
                $row->status_dasar,
                $row->created_at,
                $row->updated_at,
                kode_wilayah($this->header['desa']['kode_desa']),
            ];

            $file_foto = LOKASI_USER_PICT . $row->foto;
            if (is_file($file_foto)) {
                $this->zip->read_file($file_foto);
            }

            $rowFromValues = WriterEntityFactory::createRowFromArray($penduduk);
            $writer->addRow($rowFromValues);
        }

        $writer->close();
        $this->zip->read_file($lokasi);

        $filename = 'penduduk_' . $tgl . '_opendk.zip';
        $this->zip->archive(LOKASI_SINKRONISASI_ZIP . $filename);

        return $filename;
    }

    private function sinkronisasi_data_penduduk()
    {
        $filename = $this->data_penduduk();

        //Tambah/Ubah Data
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "{$this->setting->api_opendk_server}/api/v1/penduduk/storedata",
            // Jika http gunakan url ini :
            //CURLOPT_URL => $this->setting->api_opendk_server."/api/v1/penduduk/storedata?token=".$this->setting->api_opendk_key,
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
                "Authorization: Bearer {$this->setting->api_opendk_key}",
            ],
        ]);

        $response  = json_decode(curl_exec($curl));
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        //Hapus Data
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "{$this->setting->api_opendk_server}/api/v1/penduduk",
            // Jika http gunakan url ini :
            //CURLOPT_URL => $this->setting->api_opendk_server."/api/v1/penduduk?token=".$this->setting->api_opendk_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($this->ekspor_model->hapus_penduduk_sinkronasi_opendk()),
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/json',
                "Authorization: Bearer {$this->setting->api_opendk_key}",
            ],
        ]);

        $response  = json_decode(curl_exec($curl));
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
            $modul            = $this->input->post('modul');
            $model            = $this->input->post('model');
            $model            = 'App\\Models\\' . $model;
            $tgl_sinkronisasi = LogSinkronisasi::where('modul', '=', $modul)->first()->updated_at ?? null;
            if ($tgl_sinkronisasi) {
                return json(1); // jika sudah pernah sinkronisasi, tidak usah paginasi
            }

            return json(ceil($model::count() / 100));
        }
    }

    private function sinkronisasi_identitas_desa()
    {
        return opendk_api('/api/v1/identitas-desa', [
            'form_params' => [
                'kode_desa'    => kode_wilayah($this->header['desa']['kode_desa']),
                'sebutan_desa' => $this->setting->sebutan_desa,
                'website'      => empty($this->header['desa']['website']) ? base_url() : $this->header['desa']['website'],
                'path'         => $this->header['desa']['path'],
            ],
        ], 'post');
    }

    public function kirim_program_bantuan()
    {
        $filename = $this->data_program_bantuan();
        $akhir    = $this->input->get('akhir');
        // kirim ke api opendk
        $multipart = [
            [
                'name'     => 'file',
                'contents' => Psr7\Utils::tryFopen(LOKASI_SINKRONISASI_ZIP . $filename, 'r'),
                'filename' => $filename,
            ],
            [
                'name'     => 'desa_id',
                'contents' => kode_wilayah($this->header['desa']['kode_desa']),
            ],
        ];
        $options = ['multipart' => $multipart];
        $notif   = opendk_api('/api/v1/program-bantuan', $options, 'post');

        if ($akhir && $notif['status'] != 'danger') {
            $log             = LogSinkronisasi::firstOrCreate(['modul' => 'program-bantuan'], ['created_by' => $this->session->user]);
            $log->updated_by = $this->session->user;
            $log->save();
        }

        return json($notif);
    }

    public function data_program_bantuan()
    {
        $limit = 100;
        $p     = $this->input->get('p');

        // cek tanggal akhir sinkronisasi
        $tgl_sinkronisasi = LogSinkronisasi::where('modul', '=', 'program-bantuan')->first()->updated_at ?? null;
        $writer           = WriterEntityFactory::createCSVWriter();

        // Buat data Program bantuan
        // Nama File
        $bantuan_opendk = LOKASI_SINKRONISASI_ZIP . namafile('bantuan') . '_opendk.csv';
        $writer->openToFile($bantuan_opendk);

        //Header Tabel
        $judul = [
            'id',
            'nama',
            'sasaran',
            'ndesc',
            'sdate',
            'edate',
            'userid',
            'status',
            'asaldana',
            'kode_desa',
        ];

        $header = WriterEntityFactory::createRowFromArray($judul);
        $writer->addRow($header);

        $get = Bantuan::when($tgl_sinkronisasi != null, static function ($q) use ($tgl_sinkronisasi) {
            return $q->where('updated_at', '>', $tgl_sinkronisasi);
        })
            ->when($tgl_sinkronisasi == null, static function ($q) use ($limit, $p) {
                return $q->skip($p * $limit)->take($limit);
            })->get();

        foreach ($get as $row) {
            $program = [
                $row->id,
                $row->nama,
                $row->sasaran,
                $row->ndesc,
                $row->sdate,
                $row->edate,
                $row->userid,
                $row->status,
                $row->asaldana,
                kode_wilayah($this->header['desa']['kode_desa']),
            ];

            $rowFromValues = WriterEntityFactory::createRowFromArray($program);
            $writer->addRow($rowFromValues);
        }

        $writer->close();
        $this->zip->read_file($bantuan_opendk);

        // Masukan ke File Zip
        $filename = namafile('bantuan') . '_opendk.zip';
        $this->zip->archive(LOKASI_SINKRONISASI_ZIP . $filename);

        return $filename;
    }

    public function kirim_peserta_program_bantuan()
    {
        $filename = $this->data_peserta_program_bantuan();
        $akhir    = $this->input->get('akhir');
        // kirim ke api opendk
        $multipart = [
            [
                'name'     => 'file',
                'contents' => Psr7\Utils::tryFopen(LOKASI_SINKRONISASI_ZIP . $filename, 'r'),
                'filename' => $filename,
            ],
            [
                'name'     => 'desa_id',
                'contents' => kode_wilayah($this->header['desa']['kode_desa']),
            ],
        ];
        $options = ['multipart' => $multipart];
        $notif   = opendk_api('/api/v1/program-bantuan/peserta', $options, 'post');

        if ($akhir && $notif['status'] != 'danger') {
            $log             = LogSinkronisasi::firstOrCreate(['modul' => 'peserta-bantuan'], ['created_by' => $this->session->user]);
            $log->updated_by = $this->session->user;
            $log->save();
        }

        return json($notif);
    }

    public function data_peserta_program_bantuan()
    {
        $limit = 100;
        $p     = $this->input->get('p');

        // cek tanggal akhir sinkronisasi
        $tgl_sinkronisasi = LogSinkronisasi::where('modul', '=', 'peserta-bantuan')->first()->updated_at ?? null;

        // Buat data Peserta Program Bantuan
        $writer  = WriterEntityFactory::createCSVWriter();
        $peserta = LOKASI_SINKRONISASI_ZIP . namafile('peserta bantuan') . '_opendk.csv';
        $writer->openToFile($peserta);
        //Header Tabel
        $judul = [
            'id',
            'peserta',
            'program_id',
            'no_id_kartu',
            'kartu_nik',
            'kartu_nama',
            'kartu_tempat_lahir',
            'kartu_tanggal_lahir',
            'kartu_alamat',
            'kartu_peserta',
            'kartu_id_pend',
            'kode_desa',
            'sasaran',
        ];

        $header = WriterEntityFactory::createRowFromArray($judul);
        $writer->addRow($header);

        $get = BantuanPeserta::when($tgl_sinkronisasi != null, static function ($q) use ($tgl_sinkronisasi) {
            return $q->where('updated_at', '>', $tgl_sinkronisasi);
        })
            ->when($tgl_sinkronisasi == null, static function ($q) use ($limit, $p) {
                return $q->skip($p * $limit)->take($limit);
            })
            ->get();

        foreach ($get as $row) {
            $program = [
                $row->id,
                $row->peserta,
                $row->program_id,
                $row->no_id_kartu,
                $row->kartu_nik,
                $row->kartu_nama,
                $row->kartu_tempat_lahir,
                $row->kartu_tanggal_lahir,
                $row->kartu_alamat,
                $row->kartu_peserta,
                $row->kartu_id_pend,
                kode_wilayah($this->header['desa']['kode_desa']),
                $row->bantuan->sasaran,
            ];

            $rowFromValues = WriterEntityFactory::createRowFromArray($program);
            $writer->addRow($rowFromValues);
        }

        $writer->close();
        $this->zip->read_file($peserta);

        // Masukan ke File Zip
        $filename = namafile('peserta bantuan') . '_opendk.zip';
        $this->zip->archive(LOKASI_SINKRONISASI_ZIP . $filename);

        return $filename;
    }
}
