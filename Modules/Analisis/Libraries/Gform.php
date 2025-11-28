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

use App\Models\Keluarga;
use App\Models\Penduduk;
use CI_Controller;
use CI_Session;
use Exception;
use Google\Client;
use Google\Service\Script;
use Google\Service\Script\ExecutionRequest;
use Illuminate\Http\Request;
use Modules\Analisis\Enums\AnalisisRefSubjekEnum;
use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisKategori;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisParameter;
use Modules\Analisis\Models\AnalisisPeriode;
use Modules\Analisis\Models\AnalisisRespon;

class Gform
{
    private Request $request;
    private CI_Controller $ci;
    private CI_Session $session;

    public function __construct($request)
    {
        $this->request = $request;
        $this->ci      = &get_instance();
        $this->session = $this->ci->session;
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

        // Ambil data import dari session
        $data_import = $this->session->data_import;

        // SIMPAN PERTANYAAN/INDIKATOR ANALISIS
        $id_column_nik_kk            = $this->request->get('id-row-nik-kk');
        $count_indikator             = 1;
        $map_pertanyaan_to_indikator = [];
        $map_indikator_to_parameter  = [];
        $map_pertanyaan_to_jawaban   = [];

        // Build unique values dari jawaban langsung untuk setiap kolom
        $unique_values_per_column = [];

        foreach ($data_import['jawaban'] as $row) {
            foreach ($row as $col_index => $value) {
                if (! isset($unique_values_per_column[$col_index])) {
                    $unique_values_per_column[$col_index] = [];
                }
                if (! in_array($value, $unique_values_per_column[$col_index])) {
                    $unique_values_per_column[$col_index][] = $value;
                }
            }
        }

        $index_jawaban = 0;

        foreach ($this->request->get('pertanyaan') as $key => $val) {
            $id_indikator = 0;

            // Cek apakah ini PAGE_BREAK dari session data
            $is_page_break = false;

            if (isset($data_import['pertanyaan'][$key])) {
                $pertanyaan_data = $data_import['pertanyaan'][$key];

                if (isset($pertanyaan_data['type']) && $pertanyaan_data['type'] === 'PAGE_BREAK') {
                    $is_page_break = true;
                }
            }

            // Skip PAGE_BREAK
            if ($is_page_break) {
                continue;
            }

            // Skip NIK/KK column
            if ($key == $id_column_nik_kk) {
                $map_pertanyaan_to_jawaban[$key] = $index_jawaban;
                $index_jawaban++;

                continue;
            }

            if ($this->request->get('is_selected')[$key] == 'true') {
                // Ambil unique values dari kolom jawaban yang sesuai
                $choices_values = $unique_values_per_column[$index_jawaban] ?? [];

                $map_pertanyaan_to_jawaban[$key] = $index_jawaban;

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

                $map_pertanyaan_to_indikator[$key]         = $id_indikator;
                $map_indikator_to_parameter[$id_indikator] = [];

                // Simpan Parameter dari unique values yang sudah dikumpulkan dari jawaban
                if (! empty($choices_values)) {
                    foreach ($choices_values as $param_key => $param_val) {
                        // Cari nilai parameter dari request
                        $param_nilai = 0;
                        if ($this->request->has('unique-param-value-' . $key)) {
                            $unique_values = $this->request->get('unique-param-value-' . $key);
                            $search_index  = array_search($param_val, $unique_values);
                            if ($search_index !== false && $this->request->has('unique-param-nilai-' . $key)) {
                                $nilai_array = $this->request->get('unique-param-nilai-' . $key);
                                if (isset($nilai_array[$search_index]) && $nilai_array[$search_index] !== '') {
                                    $param_nilai = $nilai_array[$search_index];
                                }
                            }
                        }

                        $data_parameter = [
                            'id_indikator' => $id_indikator,
                            'jawaban'      => $param_val,
                            'nilai'        => $param_nilai,
                            'kode_jawaban' => ($param_key + 1),
                            'asign'        => 0,
                            'config_id'    => identitas('id'),
                        ];
                        $analisisParameter = AnalisisParameter::create($data_parameter);
                        $id_parameter      = $analisisParameter->id;

                        $map_indikator_to_parameter[$id_indikator][$param_val] = $id_parameter;
                    }
                }

                $count_indikator++;
                $index_jawaban++;
            } else {
                // Jika tidak dipilih, tetap increment untuk menjaga mapping
                $index_jawaban++;
            }
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
        $this->session->unset_userdata('data_import');

        foreach ($data_import['jawaban'] as $key_jawaban => $val_jawaban) {
            $index_nik_kk   = $map_pertanyaan_to_jawaban[$id_column_nik_kk] ?? 0;
            $nik_kk_subject = $val_jawaban[$index_nik_kk] ?? null;

            if (! $nik_kk_subject) {
                $list_error[] = 'NIK / No. KK data ke-' . ($key_jawaban + 1) . ' tidak ditemukan';

                continue;
            }

            $subjectID = null;

            if ($data_analisis_master['subjek_tipe'] == AnalisisRefSubjekEnum::KELUARGA) {
                $id_subject = Keluarga::where(['no_kk' => $nik_kk_subject])->first()?->id;
                $subjectID  = 'keluarga_id';
            } else {
                $id_subject = Penduduk::where(['nik' => $nik_kk_subject])->first()?->id;
                $subjectID  = 'penduduk_id';
            }

            if ($id_subject != null && $id_subject != '') {
                foreach ($this->request->get('pertanyaan') as $key_pertanyaan => $val_pertanyaan) {
                    // Skip PAGE_BREAK
                    if (isset($data_import['pertanyaan'][$key_pertanyaan]['type'])
                        && $data_import['pertanyaan'][$key_pertanyaan]['type'] === 'PAGE_BREAK') {
                        continue;
                    }

                    if ($this->request->get('is_selected')[$key_pertanyaan] == 'true' && $key_pertanyaan != $id_column_nik_kk) {

                        $id_indikator_respon = $map_pertanyaan_to_indikator[$key_pertanyaan] ?? null;

                        if ($id_indikator_respon === null) {
                            continue;
                        }

                        $index_jawaban_subjek = $map_pertanyaan_to_jawaban[$key_pertanyaan] ?? null;

                        if ($index_jawaban_subjek === null || ! isset($val_jawaban[$index_jawaban_subjek])) {
                            $list_error[] = "Jawaban tidak ditemukan untuk pertanyaan '{$val_pertanyaan}' pada data ke-" . ($key_jawaban + 1);

                            continue;
                        }

                        $jawaban_subjek = $val_jawaban[$index_jawaban_subjek];

                        $id_parameter_respon = $map_indikator_to_parameter[$id_indikator_respon][$jawaban_subjek] ?? null;

                        if ($id_parameter_respon === null) {
                            $list_error[] = "Parameter tidak ditemukan untuk pertanyaan '{$val_pertanyaan}' dengan jawaban '{$jawaban_subjek}' pada data ke-" . ($key_jawaban + 1);

                            continue;
                        }

                        $data_respon = [
                            'id_indikator' => $id_indikator_respon,
                            'id_parameter' => $id_parameter_respon,
                            'id_subjek'    => $id_subject,
                            'id_periode'   => $id_periode,
                        ];

                        if ($subjectID) {
                            $data_respon[$subjectID] = $id_subject;
                        }

                        AnalisisRespon::create($data_respon);
                    }
                }
            } else {
                $list_error[] = 'NIK / No. KK data ke-' . ($key_jawaban + 1) . ' (' . $nik_kk_subject . ') tidak valid';
            }
        }

        return ['error' => $list_error];
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

    protected function getOAuthCredentialsFile()
    {
        // Hanya ambil dari config jika tidak ada setting aplikasi utk redirect_uri
        $api_gform_credential = setting('api_gform_credential') ?? config_item('api_gform_credential');

        return json_decode(str_replace('\"', '"', $api_gform_credential), true);
    }
}
