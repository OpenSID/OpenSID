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

namespace Modules\Analisis\Libraries;

use App\Enums\AnalisisRefSubjekEnum;
use App\Models\Keluarga;
use App\Models\Penduduk;
use Exception;
use Google\Client;
use Google\Service\Script;
use Google\Service\Script\ExecutionRequest;
use Illuminate\Http\Request;
use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisKategori;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisParameter;
use Modules\Analisis\Models\AnalisisPeriode;
use Modules\Analisis\Models\AnalisisRespon;

class Gform
{
    private Request $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function save(): array
    {
        $list_error = [];

        // SIMPAN ANALISIS MASTER
        $data_analisis_master = [
            'nama'              => $this->request->get('nama_form') == '' ? 'Response Google Form ' . date('dmY_His') : $this->request->get('nama_form'),
            'subjek_tipe'       => $this->request->get('subjek_analisis') == 0 ? 1 : $this->request->get('subjek_analisis'),
            'id_kelompok'       => 0,
            'lock'              => 1,
            'format_impor'      => 0,
            'pembagi'           => 1,
            'id_child'          => 0,
            'deskripsi'         => '',
            'gform_id'          => $this->request->get('gform-form-id'),
            'gform_nik_item_id' => $this->request->get('gform-id-nik-kk'),
            'gform_last_sync'   => date('Y-m-d H:i:s'),
            'config_id'         => identitas('id'),
        ];
        $analisisMaster = AnalisisMaster::create($data_analisis_master);
        $id_master      = $analisisMaster->id;

        // SIMPAN KATEGORI ANALISIS
        $list_kategori        = $this->request->get('kategori');
        $temp_unique_kategori = [];
        $list_unique_kategori = [];

        // Get Unique Value dari Kategori
        foreach ($list_kategori as $key => $val) {
            if ($this->request->get('is_selected')[$key] != 'true') {
                continue;
            }
            if (in_array($val, $temp_unique_kategori)) {
                continue;
            }
            $temp_unique_kategori[] = $val;
        }

        // Simpan Unique Value dari Kategori
        foreach ($temp_unique_kategori as $key => $val) {
            $data_kategori = [
                'id_master'     => $id_master,
                'kategori'      => $val,
                'kategori_kode' => '',
                'config_id'     => identitas('id'),
            ];
            $kategori = AnalisisKategori::create($data_kategori);

            $list_unique_kategori[$kategori->id] = $val;
        }

        // SIMPAN PERTANYAAN/INDIKATOR ANALISIS
        $id_column_nik_kk = $this->request->get('id-row-nik-kk');
        $count_indikator  = 1;
        $db_idx_parameter = [];
        $db_idx_indikator = [];

        foreach ($this->request->get('pertanyaan') as $key => $val) {
            $temp_idx_parameter = [];
            $id_indikator       = 0;
            if ($this->request->get('is_selected')[$key] == 'true' && $key != $id_column_nik_kk) {
                $data_indikator = [
                    'id_master'    => $id_master,
                    'nomor'        => $count_indikator,
                    'pertanyaan'   => $val,
                    'id_tipe'      => $this->request->get('tipe')[$key],
                    'bobot'        => $this->request->get('bobot')[$key],
                    'act_analisis' => 0,
                    'id_kategori'  => array_search($this->request->get('kategori')[$key], $list_unique_kategori, true),
                    'is_publik'    => 0,
                    'is_teks'      => 0,
                ];

                if ($data_indikator['id_tipe'] != 1) {
                    $data_indikator['act_analisis'] = 2;
                    $data_indikator['bobot']        = 0;
                }

                $data_indikator['config_id'] = identitas('id');
                $analisisIndikator           = AnalisisIndikator::create($data_indikator);
                $id_indikator                = $analisisIndikator->id;

                // Simpan Parameter untuk setiap unique value pada masing-masing indikator
                foreach ($this->request->get('unique-param-value-' . $key) as $param_key => $param_val) {
                    $param_nilai = ($this->request->get('unique-param-nilai-' . $key)[$param_key] == '') ? 0 : $this->request->get('unique-param-nilai-' . $key)[$param_key];

                    $data_parameter = [
                        'id_indikator' => $id_indikator,
                        'jawaban'      => $this->request->get('unique-param-value-' . $key)[$param_key],
                        'nilai'        => $param_nilai,
                        'kode_jawaban' => ($param_key + 1),
                        'asign'        => 0,
                        'config_id'    => identitas('id'),
                    ];
                    $analisisParameter                 = AnalisisParameter::create($data_parameter);
                    $id_parameter                      = $analisisParameter->id;
                    $temp_idx_parameter[$id_parameter] = $param_val;
                }

                $count_indikator++;
            }
            $db_idx_indikator[$id_indikator] = $key;
            $db_idx_parameter[]              = $temp_idx_parameter;
        }

        // SIMPAN PERIODE ANALISIS
        $data_periode = [
            'id_master'         => $id_master,
            'nama'              => 'Pendataan ' . date('dmY_His'),
            'id_state'          => 1,
            'aktif'             => 1,
            'keterangan'        => 0,
            'tahun_pelaksanaan' => $this->request->get('tahun_pendataan') == '' ? date('Y') : $this->request->get('tahun_pendataan'),
            'config_id'         => identitas('id'),
        ];
        $analisisPeriode = AnalisisPeriode::create($data_periode);
        $id_periode      = $analisisPeriode->id;

        // SIMPAN RESPON ANALISIS
        $data_import = session('data_import');

        // Iterasi untuk setiap subjek
        foreach ($data_import['jawaban'] as $key_jawaban => $val_jawaban) {
            // Get Id Subjek berdasarkan Tipe Subjek (Penduduk / Keluarga / Rumah Tangga / Kelompok)
            $nik_kk_subject = $val_jawaban[$id_column_nik_kk];
            if ($data_analisis_master['subjek_tipe'] == AnalisisRefSubjekEnum::KELUARGA) {
                $id_subject = Keluarga::where(['no_kk' => $nik_kk_subject])->first()?->id;
            } else {
                $id_subject = Penduduk::where(['nik' => $nik_kk_subject])->first()?->id;
            }

            if ($id_subject != null && $id_subject != '') {
                // Iterasi untuk setiap indikator / jawaban dari subjek
                foreach ($this->request->get('pertanyaan') as $key_pertanyaan => $val_pertanyaan) {
                    if ($this->request->get('is_selected')[$key_pertanyaan] == 'true' && $key_pertanyaan != $id_column_nik_kk) {
                        $data_respon = [
                            'id_indikator' => array_search($key_pertanyaan, $db_idx_indikator, true),
                            'id_parameter' => array_search($val_jawaban[$key_pertanyaan], $db_idx_parameter[$key_pertanyaan], true),
                            'id_subjek'    => $id_subject,
                            'id_periode'   => $id_periode,
                        ];

                        AnalisisRespon::create($data_respon);
                    }
                }
            } else {
                $list_error[] = 'NIK / No. KK data ke-' . ($key_jawaban + 1) . ' (' . $nik_kk_subject . ') ' . $id_subject . ' tidak valid';
            }
        }

        return ['error' => $list_error];
    }

    protected function getOAuthCredentialsFile()
    {
        // Hanya ambil dari config jika tidak ada setting aplikasi utk redirect_uri
        $api_gform_credential = setting('api_gform_credential') ?? config_item('api_gform_credential');

        return json_decode(str_replace('\"', '"', $api_gform_credential), true);
    }

    public function import_gform($redirect_link = '')
    {
        // Check Credential File
        if (! $oauth_credentials = $this->getOAuthCredentialsFile()) {
            echo 'ERROR - File Credential Not Found';

            return;
        }

        $redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

        // Get the API client and construct the service object.
        $client = new Client();
        $client->setAuthConfig($oauth_credentials);
        $client->setRedirectUri($redirect_uri);
        $client->addScope('https://www.googleapis.com/auth/forms');
        $client->addScope('https://www.googleapis.com/auth/spreadsheets');
        $service = new Script($client);

        // API script id
        // Hanya ambil dari config jika tidak ada setting aplikasi unrtuk redirect_uri
        if (empty(setting('api_gform_id_script')) && empty(setting('api_gform_redirect_uri'))) {
            $script_id = config_item('api_gform_script_id');
        } else {
            $script_id = setting('api_gform_id_script');
        }
        // add "?logout" to the URL to remove a token from the session
        if (isset($_REQUEST['logout'])) {
            unset($_SESSION['upload_token']);
        }

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token);

            // store in the session also
            $_SESSION['upload_token'] = $token;
        }

        // set the access token as part of the client
        if (! empty($_SESSION['upload_token'])) {
            $client->setAccessToken($_SESSION['upload_token']);
            if ($client->isAccessTokenExpired()) {
                unset($_SESSION['upload_token']);
            }
        } else {
            $authUrl = $client->createAuthUrl();
        }

        // Create an execution request object.
        $request = new ExecutionRequest();
        $request->setFunction('getFormItems');
        $form_id = session('google_form_id');
        if ($form_id == '') {
            $form_id = session('gform_id');
        }
        $request->setParameters($form_id);

        try {
            if (isset($authUrl) && $_SESSION['inside_retry'] != true) {
                // If no authentication before
                set_session('form_id', $form_id);
                set_session('inside_retry', true);
                set_session('inside_redirect_link', $redirect_link);
                header('Location: ' . $authUrl);
            } else {
                // If it has authenticated
                // Make the API request.
                $response = $service->scripts->run($script_id, $request);

                if ($response->getError()) {
                    echo 'Error';
                    // The API executed, but the script returned an error.

                    // Extract the first (and only) set of error details. The values of this
                    // object are the script's 'errorMessage' and 'errorType', and an array of
                    // stack trace elements.
                    $error = $response->getError()['details'][0];
                    printf("Script error message: %s\n", $error['errorMessage']);

                    if (array_key_exists('scriptStackTraceElements', $error)) {
                        // There may not be a stacktrace if the script didn't start executing.
                        echo "Script error stacktrace:\n";

                        foreach ($error['scriptStackTraceElements'] as $trace) {
                            printf("\t%s: %d\n", $trace['function'], $trace['lineNumber']);
                        }
                    }
                } else {
                    // Get Response
                    $resp = $response->getResponse();

                    return $resp['result'];
                }
            }
        } catch (Exception $e) {
            // The API encountered a problem before the script started executing.
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        return '0';
    }

    public function update($id, $variabel)
    {
        // Get data analisis master
        $master_data = AnalisisMaster::find($id)->toArray();

        // Get existing data indikator (pertanyaan) dan parameter (jawaban)
        $existing_data = AnalisisIndikator::where(['id_master' => $id])->get()?->toArray();

        // Get existing respon
        $id_periode_aktif = AnalisisPeriode::active()->where(['id_master' => $id])->first()->toArray();
        $existing_respon  = $this->get_respon_by_id_periode($id_periode_aktif, $master_data['subjek_tipe']);

        $id_column_nik_kk = 0;
        $list_error       = [];
        $list_pertanyaan  = [];

        $deleted_responden = [];
        $deleted_jawaban   = [];

        foreach ($variabel['pertanyaan'] as $key_pertanyaan => $val_pertanyaan) {
            // Mencari kolom NIK/No. KK pada form
            if ($val_pertanyaan['itemId'] == $master_data['gform_nik_item_id']) {
                $id_column_nik_kk = $key_pertanyaan;
            }
        }

        // Cek keberadaan existing indikator pada data terkini, jika SALAH SATU SAJA hilang maka proses tidak dapat dilanjutkan
        foreach ($existing_data['indikator'] as $key_indikator => $val_indikator) {
            if (! array_search($val_indikator, array_column($variabel['pertanyaan'], 'title'), true)) {
                $list_error[] = 'Terdapat kolom yang hilang pada hasil response Google Form terkini (' . $val_indikator . ')';
            }
        }

        if ($list_error) {

            return ['error' => $list_error];
        }

        // Mencari nilai untuk pertanyaan-pertanyaan yang dimasukkan sebelumnya
        foreach ($existing_data['indikator'] as $key_indikator => $val_indikator) {
            foreach ($variabel['pertanyaan'] as $val_pertanyaan) {
                if ($val_indikator == $val_pertanyaan['title']) {
                    // Mengisi nilai
                    $list_pertanyaan[$key_indikator] = $val_pertanyaan;

                    // Cek jawaban yang tidak terpakai
                    $deleted_jawaban[$key_indikator] = $existing_data['parameter'][$key_indikator];

                    foreach ($existing_data['parameter'][$key_indikator] as $key_param => $val_param) {
                        if (array_search($val_param, $val_pertanyaan['choices'], true)) {
                            unset($deleted_jawaban[$key_indikator][$key_param]);
                        }
                    }

                    $new_parameter = [];

                    // Insert jawaban baru
                    foreach ($val_pertanyaan['choices'] as $val_choice) {
                        // Jika nilai belum ada di database, maka tambahkan data parameter baru
                        if (! (array_search($val_choice, $existing_data['parameter'][$key_indikator], true))) {
                            $data_parameter = [
                                'id_indikator' => $key_indikator,
                                'jawaban'      => $val_choice,
                                'nilai'        => 0,
                                'kode_jawaban' => 0,
                                'asign'        => 0,
                                'config_id'    => identitas('id'),
                            ];
                            $analisisParameter            = AnalisisParameter::create($data_parameter);
                            $id_parameter                 = $analisisParameter->id;
                            $data_parameter['id']         = $id_parameter;
                            $new_parameter[$id_parameter] = $val_choice;
                        }
                    }

                    // Update list parameter dengan operasi Union antara parameter yang sudah ada dengan parameter yang baru ditambahkan
                    $existing_data['parameter'][$key_indikator] += $new_parameter;

                    break;
                }
            }
        }

        foreach ($existing_respon as $key_respon => $val_respon) {
            if (! in_array($key_respon, array_column($variabel['jawaban'], $id_column_nik_kk), true)) {
                $deleted_responden[$key_respon] = $val_respon;
            }
        }

        foreach ($variabel['jawaban'] as $key_responden => $val_responden) {
            $nik_kk = $val_responden[$id_column_nik_kk];

            if ($master_data['subjek_tipe'] == AnalisisRefSubjekEnum::KELUARGA) {
                $id_subject = Keluarga::where(['no_kk' => $nik_kk])->first()?->id;
            } else {
                $id_subject = Penduduk::where(['no_kk' => $nik_kk])->first()?->id;
            }

            if ($id_subject != null && $id_subject != '') { // Jika NIK valid
                foreach ($val_responden as $key_jawaban => $val_jawaban) {
                    $id_indikator = array_search($variabel['pertanyaan'][$key_jawaban], $list_pertanyaan, true); // Cek apakah kolom yang telah ada

                    if ($id_indikator) {
                        $id_parameter = array_search($val_jawaban, $existing_data['parameter'][$id_indikator], true); // Jawaban terkini

                        if (isset($existing_respon[$val_responden[$id_column_nik_kk]])) {
                            // Jika Responden sudah pernah disimpan
                            $obj_respon = $existing_respon[$nik_kk][$id_indikator];

                            if ($obj_respon['id_parameter'] != $id_parameter) {
                                $where = [
                                    'id_indikator' => $id_indikator,
                                    'id_subjek'    => $obj_respon['id_subjek'],
                                    'id_periode'   => $obj_respon['id_periode'],
                                ];
                                AnalisisRespon::where($where)->delete();

                                $data_respon = [
                                    'id_indikator' => $id_indikator,
                                    'id_parameter' => $id_parameter,
                                    'id_subjek'    => $obj_respon['id_subjek'],
                                    'id_periode'   => $obj_respon['id_periode'],
                                ];
                                AnalisisRespon::create($data_respon);
                            }
                        } else {
                            // Jika Responden belum pernah disimpan (Responden Baru)
                            $data_respon = [
                                'id_indikator' => $id_indikator,
                                'id_parameter' => $id_parameter,
                                'id_subjek'    => $id_subject,
                                'id_periode'   => $id_periode_aktif,
                            ];

                            AnalisisRespon::create($data_respon);
                        }
                    }
                }
            } else {
                $list_error[] = 'NIK / No. KK data ke-' . ($key_responden + 1) . ' (' . $nik_kk . ') tidak valid';
            }
        }

        // Hapus data responden yang tidak ada di response terkini
        foreach (array_keys($deleted_responden) as $key_responden) {
            if ($master_data['subjek_tipe'] == AnalisisRefSubjekEnum::KELUARGA) {
                $id_subject = Keluarga::where(['no_kk' => $nik_kk])->first()?->id;
            } else {
                $id_subject = Penduduk::where(['no_kk' => $nik_kk])->first()?->id;
            }

            $where = [
                'id_subjek'  => $id_subject,
                'id_periode' => $id_periode_aktif,
            ];
            AnalisisRespon::where($where)->delete();
        }

        // Update gform_last_sync
        $update_data = [
            'gform_last_sync' => date('Y-m-d H:i:s'),
        ];

        AnalisisMaster::where('id', $id)->update($update_data);

        return ['error' => $list_error];
    }

    public function get_respon_by_id_periode($id_periode = 0, $subjek = 1)
    {
        $result = [];
        if ($subjek == 1) { // Untuk Subjek Penduduk
            $list_penduduk = AnalisisRespon::selectRaw('analisis_respon.*, tweb_penduduk.nik')->join('tweb_penduduk', 'tweb_penduduk.id', 'analisis_respon.id_subjek')->where(['id_periode' => $id_periode])->get()?->toArray();

            foreach ($list_penduduk as $penduduk) {
                $result[$penduduk['nik']][$penduduk['id_indikator']] = $penduduk;
            }
        } else { // Untuk Subjek Keluarga
            $list_keluarga = AnalisisRespon::selectRaw('analisis_respon.*, tweb_keluarga.no_kkk')->join('tweb_keluarga', 'tweb_keluarga.id', 'analisis_respon.id_subjek')->where(['id_periode' => $id_periode])->get()?->toArray();

            foreach ($list_keluarga as $keluarga) {
                $result[$keluarga['no_kk']][$keluarga['id_indikator']] = $keluarga;
            }
        }

        return $result;
    }
}
