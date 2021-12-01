<?php
require_once 'vendor/google-api-php-client/vendor/autoload.php';

class Analisis_import_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('penduduk_model');
		$this->load->model('keluarga_model');
		$this->load->model('analisis_indikator_model');
		$this->load->model('analisis_master_model');
		$this->load->model('analisis_periode_model');
		$this->load->model('analisis_respon_model');
		$this->load->library('Spreadsheet_Excel_Reader');
	}

	public function import_excel($file='', $kode='00000', $jenis=2)
	{
		if (empty($file)) $file = $_FILES['userfile']['tmp_name'];
		$data = new Spreadsheet_Excel_Reader($file);
		$sheet=0;

		$master['nama']	= $data->val(1, 2, $sheet);
		$master['subjek_tipe'] = $data->val(2, 2, $sheet);
		$master['lock']	= $data->val(3, 2, $sheet);
		$master['pembagi'] = $data->val(4, 2, $sheet);
		$master['deskripsi'] = $data->val(5, 2, $sheet);
		$master['kode_analisis'] = $kode;
		$master['jenis'] = $jenis;

		$outp = $this->db->insert('analisis_master',$master);
		$id_master = $this->db->insert_id();

		$periode['id_master']	= $id_master;
		$periode['nama'] = $data->val(6, 2, $sheet);
		$periode['tahun_pelaksanaan']	= $data->val(7, 2, $sheet);
		$periode['keterangan'] = $data->val(5, 2, $sheet);
		$periode['aktif']	= 1;
		$this->db->insert('analisis_periode', $periode);

		$sheet = 1;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);

		for ($i=2; $i<=$baris; $i++)
		{
			$sql = "SELECT * FROM analisis_kategori_indikator WHERE kategori=? AND id_master=?";
			$query = $this->db->query($sql, array($data->val($i, 3, $sheet), $id_master));
			$cek = $query->row_array();

			if (!$cek)
			{
				$kategori['id_master'] = $id_master;
				$kategori['kategori']	= $data->val($i, 3, $sheet);
				$this->db->insert('analisis_kategori_indikator', $kategori);
			}
		}

		for ($i=2; $i<=$baris; $i++)
		{
			$indikator['id_master']	= $id_master;
			$indikator['nomor']	= $data->val($i, 1, $sheet);
			$indikator['pertanyaan'] = $data->val($i, 2, $sheet);

			$sql = "SELECT * FROM analisis_kategori_indikator WHERE kategori=? AND id_master=?";
			$query = $this->db->query($sql, array($data->val($i, 3, $sheet), $id_master));
			$kategori = $query->row_array();

			$indikator['id_kategori']	= $kategori['id'];
			$indikator['id_tipe']	= $data->val($i, 4, $sheet);
			$indikator['bobot']	= $data->val($i, 5, $sheet) ?: 0;
			$indikator['act_analisis'] = $data->val($i, 6, $sheet) ?: 2;

			$this->db->insert('analisis_indikator', $indikator);
		}

		$sheet = 2;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);

		for ($i=2; $i<=$baris; $i++)
		{
			$kode	= explode(".", $data->val($i, 3, $sheet));

			$parameter['kode_jawaban'] = $data->val($i, 2, $sheet);
			$parameter['jawaban']	= $data->val($i, 3, $sheet);

			$sql = "SELECT id FROM analisis_indikator WHERE nomor=? AND id_master=?";
			$query = $this->db->query($sql, array($data->val($i, 1, $sheet), $id_master));
			$indikator = $query->row_array();

			$parameter['id_indikator'] = $indikator['id'];
			$parameter['nilai']	= $data->val($i, 4, $sheet) ?: 0;
			$parameter['asign']	= 1;

			$this->db->insert('analisis_parameter',$parameter);
		}

		$sheet = 3;
		$baris = $data->rowcount($sheet_index=$sheet);
		$kolom = $data->colcount($sheet_index=$sheet);

		for ($i=2; $i<=$baris; $i++)
		{
			$klasifikasi['id_master']	= $id_master;
			$klasifikasi['nama'] = $data->val($i, 1, $sheet);
			$klasifikasi['minval'] = $data->val($i, 2, $sheet);
			$klasifikasi['maxval'] = $data->val($i, 3, $sheet);

			$this->db->insert('analisis_klasifikasi', $klasifikasi);
		}

		status_sukses($outp); //Tampilkan Pesan

		return $id_master;
	}

	public function save_import_gform()
	{
		$list_error = array();

		// SIMPAN ANALISIS MASTER
		$data_analisis_master = [
			'nama' => $this->input->post('nama_form') == "" ? "Response Google Form " . date('dmY_His') : $this->input->post('nama_form'),
			'subjek_tipe' => $this->input->post('subjek_analisis') == 0 ? 1 : $this->input->post('subjek_analisis'),
			'id_kelompok' => 0,
			'lock' => 1,
			'format_impor' => 0,
			'pembagi' => 1,
			'id_child' => 0,
			'deskripsi' => "",
			'gform_id' => $this->input->post('gform-form-id'),
			'gform_nik_item_id' => $this->input->post('gform-id-nik-kk'),
			'gform_last_sync' => date('Y-m-d H:i:s')
		];

		$outp = $this->db->insert('analisis_master', $data_analisis_master);
		$id_master = $this->db->insert_id();

		// SIMPAN KATEGORI ANALISIS
		$list_kategori = $this->input->post('kategori');
		$temp_unique_kategori = array();
		$list_unique_kategori = array();

		// Get Unique Value dari Kategori
		foreach ($list_kategori as $key => $val)
		{
			if ($this->input->post('is_selected')[$key] == 'true')
			{
				if (! in_array($val, $temp_unique_kategori))
				{
					array_push($temp_unique_kategori, $val);
				}
			}
		}

		// Simpan Unique Value dari Kategori
		foreach ($temp_unique_kategori as $key => $val)
		{
			$data_kategori = [
				'id_master'		=> $id_master,
				'kategori' 		=> $val,
				'kategori_kode'	=> ""
			];

			$outp = $this->db->insert('analisis_kategori_indikator', $data_kategori);
			$id_kategori = $this->db->insert_id();

			$list_unique_kategori[$id_kategori] = $val;
		}

		// SIMPAN PERTANYAAN/INDIKATOR ANALISIS
		$id_column_nik_kk = $this->input->post('id-row-nik-kk');
		$count_indikator = 1;
		$db_idx_parameter = array();
		$db_idx_indikator = array();

		foreach ($this->input->post('pertanyaan') as $key => $val)
		{
			$temp_idx_parameter = array();
			$id_indikator = 0;
			if ($this->input->post('is_selected')[$key] == 'true' && $key != $id_column_nik_kk)
			{
				$data_indikator = [
					'id_master' => $id_master,
					'nomor' => $count_indikator,
					'pertanyaan' => $val,
					'id_tipe' => $this->input->post('tipe')[$key],
					'bobot' => $this->input->post('bobot')[$key],
					'act_analisis' => 0,
					'id_kategori' => array_search($this->input->post('kategori')[$key], $list_unique_kategori),
					'is_publik' => 0,
					'is_teks' => 0
				];

				if ($data_indikator['id_tipe'] != 1)
				{
					$data_indikator['act_analisis']	= 2;
					$data_indikator['bobot'] = 0;
				}

				$outp = $this->db->insert('analisis_indikator', $data_indikator);
				$id_indikator = $this->db->insert_id();

				// Simpan Parameter untuk setiap unique value pada masing-masing indikator
				foreach ($this->input->post('unique-param-value-' . $key) as $param_key => $param_val)
				{
					$param_nilai = ($this->input->post('unique-param-nilai-' . $key)[$param_key] == "") ? 0 : $this->input->post('unique-param-nilai-' . $key)[$param_key];

					$data_parameter = [
						'id_indikator'	=> $id_indikator,
						'jawaban'		=> $this->input->post('unique-param-value-' . $key)[$param_key],
						'nilai' 		=> $param_nilai,
						'kode_jawaban' 	=> ($param_key+1),
						'asign' 		=> 0
					];

					$outp = $this->db->insert('analisis_parameter', $data_parameter);
					$id_parameter = $this->db->insert_id();
					$temp_idx_parameter[$id_parameter] = $param_val;
				}

				$count_indikator += 1;
			}
			$db_idx_indikator[$id_indikator] = $key;
			array_push($db_idx_parameter, $temp_idx_parameter);
		}

		// SIMPAN PERIODE ANALISIS
		$data_periode = [
			'id_master' 		=> $id_master,
			'nama' 				=> "Pendataan " . date('dmY_His'),
			'id_state' 			=> 1,
			'aktif' 			=> 1,
			'keterangan' 		=> 0,
			'tahun_pelaksanaan'	=> $this->input->post('tahun_pendataan') == "" ? date('Y') : $this->input->post('tahun_pendataan')
		];

		$outp = $this->db->insert('analisis_periode', $data_periode);
		$id_periode = $this->db->insert_id();

		// SIMPAN RESPON ANALISIS
		$data_import = $this->session->data_import;
		// Iterasi untuk setiap subjek
		foreach ($data_import['jawaban'] as $key_jawaban => $val_jawaban)
		{
			// Get Id Subjek berdasarkan Tipe Subjek (Penduduk / Keluarga / Rumah Tangga / Kelompok)
			$nik_kk_subject = $val_jawaban[$id_column_nik_kk];
			if ($data_analisis_master['subjek_tipe'] == 2)
			{
				$id_subject = $this->keluarga_model->get_keluarga_by_no_kk($nik_kk_subject)['id'];
			}
			else
			{
				$id_subject = $this->penduduk_model->get_penduduk_by_nik($nik_kk_subject)['id'];
			}

			if ($id_subject != NULL && $id_subject != "")
			{
				// Iterasi untuk setiap indikator / jawaban dari subjek
				foreach ($this->input->post('pertanyaan') as $key_pertanyaan => $val_pertanyaan)
				{
					if ($this->input->post('is_selected')[$key_pertanyaan] == 'true' && $key_pertanyaan != $id_column_nik_kk)
					{
						$data_respon = [
							'id_indikator'	=> array_search($key_pertanyaan, $db_idx_indikator),
							'id_parameter'	=> array_search($val_jawaban[$key_pertanyaan], $db_idx_parameter[$key_pertanyaan]),
							'id_subjek' 	=> $id_subject,
							'id_periode' 	=> $id_periode
						];

						$outp = $this->db->insert('analisis_respon', $data_respon);
					}
				}
			}
			else
			{
				array_push($list_error, 'NIK / No. KK data ke-' . ($key_jawaban+1) . " (" . $nik_kk_subject . ") " . $id_subject . " tidak valid");
			}
		}

		$this->session->list_error = $list_error;
		status_sukses($outp);
	}

	protected function getOAuthCredentialsFile()
	{
		// Hanya ambil dari config jika tidak ada setting aplikasi utk redirect_uri
		if ($this->setting->api_gform_credential)
		{
			$api_gform_credential = $this->setting->api_gform_credential;
		}
		elseif (empty($this->setting->api_gform_redirect_uri))
		{
			$api_gform_credential = config_item('api_gform_credential');
		}
		return json_decode(str_replace('\"' , '"', $api_gform_credential), true);
	}

	public function import_gform($redirect_link = "")
	{
		// Check Credential File
		if ( ! $oauth_credentials = $this->getOAuthCredentialsFile())
		{
			echo 'ERROR - File Credential Not Found';
			return;
		}

		$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

		// Get the API client and construct the service object.
		$client = new Google\Client();
		$client->setAuthConfig($oauth_credentials);
		$client->setRedirectUri($redirect_uri);
		$client->addScope("https://www.googleapis.com/auth/forms");
		$client->addScope("https://www.googleapis.com/auth/spreadsheets");
		$service = new Google_Service_Script($client);

		// API script id
		// Hanya ambil dari config jika tidak ada setting aplikasi unrtuk redirect_uri
		if (empty($this->setting->api_gform_id_script) && empty($this->setting->api_gform_redirect_uri))
		{
			$script_id = config_item('api_gform_script_id');
		}
		else
		{
			$script_id = $this->setting->api_gform_id_script;
		}
		// add "?logout" to the URL to remove a token from the session
		if (isset($_REQUEST['logout']))
			unset($_SESSION['upload_token']);

		if (isset($_GET['code'])) {
			$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
			$client->setAccessToken($token);

			// store in the session also
			$_SESSION['upload_token'] = $token;
		}

		// set the access token as part of the client
		if (!empty($_SESSION['upload_token']))
		{
			$client->setAccessToken($_SESSION['upload_token']);
			if ($client->isAccessTokenExpired())
				unset($_SESSION['upload_token']);
		}
		else
		{
			$authUrl = $client->createAuthUrl();
		}

		// Create an execution request object.
		$request = new Google_Service_Script_ExecutionRequest();
		$request->setFunction('getFormItems');
		$form_id = $this->session->google_form_id;
		if ($form_id == "")
			$form_id = $this->session->gform_id;
		$request->setParameters($form_id);

		try
		{
			if (isset($authUrl) && $_SESSION['inside_retry'] != true)
			{
				// If no authentication before
				$this->session->gform_id = $form_id;
				$this->session->inside_retry = true;
				$this->session->inside_redirect_link = $redirect_link;
				header('Location: ' . $authUrl);
			}
			else
			{
				// If it has authenticated
				// Make the API request.
				$response = $service->scripts->run($script_id, $request);

				if ($response->getError())
				{
					echo 'Error';
					// The API executed, but the script returned an error.

					// Extract the first (and only) set of error details. The values of this
					// object are the script's 'errorMessage' and 'errorType', and an array of
					// stack trace elements.
					$error = $response->getError()['details'][0];
					printf("Script error message: %s\n", $error['errorMessage']);

					if (array_key_exists('scriptStackTraceElements', $error))
					{
						// There may not be a stacktrace if the script didn't start executing.
						print "Script error stacktrace:\n";
						foreach($error['scriptStackTraceElements'] as $trace)
							printf("\t%s: %d\n", $trace['function'], $trace['lineNumber']);
					}
				}
				else
				{
					// Get Response
					$resp = $response->getResponse();
					return $resp['result'];
				}
			}

		} catch (Exception $e)
		{
			// The API encountered a problem before the script started executing.
			echo 'Caught exception: ', $e->getMessage(), "\n";
		}

		return '0';
	}

	public function update_import_gform($id=0, $variabel)
	{
		// Get data analisis master
		$master_data = $this->analisis_master_model->get_analisis_master($id);

		// Get existing data indikator (pertanyaan) dan parameter (jawaban)
		$existing_data = $this->analisis_indikator_model->get_analisis_indikator_by_id_master($id);

		// Get existing respon
		$id_periode_aktif = $this->analisis_periode_model->get_id_periode_aktif($id);
		$existing_respon = $this->analisis_respon_model->get_respon_by_id_periode($id_periode_aktif, $master_data['subjek_tipe']);

		$id_column_nik_kk = 0;
		$list_error = array();
		$list_pertanyaan = array();

		$deleted_responden = array();
		$deleted_jawaban = array();

		foreach ($variabel['pertanyaan'] as $key_pertanyaan => $val_pertanyaan)
		{
			// Mencari kolom NIK/No. KK pada form
			if ($val_pertanyaan['itemId'] == $master_data['gform_nik_item_id'])
				$id_column_nik_kk = $key_pertanyaan;
		}

		// Cek keberadaan existing indikator pada data terkini, jika SALAH SATU SAJA hilang maka proses tidak dapat dilanjutkan
		foreach ($existing_data['indikator'] as $key_indikator => $val_indikator)
		{
			if(! array_search($val_indikator, array_column($variabel['pertanyaan'], 'title')))
				array_push($list_error, 'Terdapat kolom yang hilang pada hasil response Google Form terkini (' . $val_indikator . ')');
		}

		if (! empty($list_error))
		{
			$this->session->list_error = $list_error;
			status_sukses(-1, true, "Beberapa data gagal disimpan");
			return 0;
		}


		// Mencari nilai untuk pertanyaan-pertanyaan yang dimasukkan sebelumnya
		foreach ($existing_data['indikator'] as $key_indikator => $val_indikator)
		{
			foreach ($variabel['pertanyaan'] as $key_pertanyaan => $val_pertanyaan)
			{
				if ($val_indikator == $val_pertanyaan['title'])
				{
					// Mengisi nilai
					$list_pertanyaan[$key_indikator] = $val_pertanyaan;

					// Cek jawaban yang tidak terpakai
					$deleted_jawaban[$key_indikator] = $existing_data['parameter'][$key_indikator];
					foreach ($existing_data['parameter'][$key_indikator] as $key_param => $val_param)
					{
						if (array_search($val_param, $val_pertanyaan['choices']))
							unset($deleted_jawaban[$key_indikator][$key_param]);
					}

					$new_parameter = array();
					// Insert jawaban baru
					foreach ($val_pertanyaan['choices'] as $key_choice => $val_choice)
					{
						// Jika nilai belum ada di database, maka tambahkan data parameter baru
						if (! (array_search($val_choice, $existing_data['parameter'][$key_indikator])))
						{
							$data_parameter = [
								'id_indikator'	=> $key_indikator,
								'jawaban'		=> $val_choice,
								'nilai' 		=> 0,
								'kode_jawaban' 	=> 0,
								'asign' 		=> 0
							];

							$outp = $this->db->insert('analisis_parameter', $data_parameter);
							$id_parameter = $this->db->insert_id();
							$data_parameter['id'] = $id_parameter;
							$new_parameter[$id_parameter] = $val_choice;
						}
					}

					// Update list parameter dengan operasi Union antara parameter yang sudah ada dengan parameter yang baru ditambahkan
					$existing_data['parameter'][$key_indikator] = $existing_data['parameter'][$key_indikator] + $new_parameter;

					break;
				}
			}
		}

		foreach ($existing_respon as $key_respon => $val_respon)
		{
			if (array_search($key_respon, array_column($variabel['jawaban'], $id_column_nik_kk)) === false)
				$deleted_responden[$key_respon] = $val_respon;
		}

		foreach ($variabel['jawaban'] as $key_responden => $val_responden)
		{
			$nik_kk = $val_responden[$id_column_nik_kk];
			if ($master_data['subjek_tipe'] == 2)
			{
				$id_subject = $this->keluarga_model->get_keluarga_by_no_kk($nik_kk)['id'];
			}
			else
			{
				$id_subject = $this->penduduk_model->get_penduduk_by_nik($nik_kk)['id'];
			}

			if ($id_subject != NULL && $id_subject != "") // Jika NIK valid
			{
				foreach ($val_responden as $key_jawaban => $val_jawaban)
				{
					$id_indikator = array_search($variabel['pertanyaan'][$key_jawaban], $list_pertanyaan); // Cek apakah kolom yang telah ada

					if ($id_indikator){
						$id_parameter = array_search($val_jawaban, $existing_data['parameter'][$id_indikator]); // Jawaban terkini

						if (isset($existing_respon[$val_responden[$id_column_nik_kk]]))
						{
							// Jika Responden sudah pernah disimpan
							$obj_respon = $existing_respon[$nik_kk][$id_indikator];

							if ($obj_respon['id_parameter'] != $id_parameter)
							{
								$where = [
									'id_indikator' => $id_indikator,
									'id_subjek' => $obj_respon['id_subjek'],
									'id_periode' => $obj_respon['id_periode']
								];
								$this->db->delete('analisis_respon', $where);

								$data_respon = [
									'id_indikator'	=> $id_indikator,
									'id_parameter'	=> $id_parameter,
									'id_subjek' 	=> $obj_respon['id_subjek'],
									'id_periode' 	=> $obj_respon['id_periode']
								];

								$outp = $this->db->insert('analisis_respon', $data_respon);
							}
						}
						else
						{
							// Jika Responden belum pernah disimpan (Responden Baru)
							$data_respon = [
								'id_indikator'	=> $id_indikator,
								'id_parameter'	=> $id_parameter,
								'id_subjek' 	=> $id_subject,
								'id_periode' 	=> $id_periode_aktif
							];

							$outp = $this->db->insert('analisis_respon', $data_respon);
						}
					}
				}
			}
			else
			{
				array_push($list_error, 'NIK / No. KK data ke-' . ($key_responden+1) . " (" . $nik_kk . ") tidak valid");
			}
		}

		// Hapus data responden yang tidak ada di response terkini
		foreach ($deleted_responden as $key_responden => $val_responden)
		{
			if ($master_data['subjek_tipe'] == 2)
			{
				$id_subject = $this->keluarga_model->get_keluarga_by_no_kk($key_responden)['id'];
			}
			else
			{
				$id_subject = $this->penduduk_model->get_penduduk_by_nik($key_responden)['id'];
			}

			$where = [
				'id_subjek' => $id_subject,
				'id_periode' => $id_periode_aktif
			];
			$this->db->delete('analisis_respon', $where);
		}

		// Update gform_last_sync
		$update_data = [
			'gform_last_sync' => date('Y-m-d H:i:s')
		];

		$this->db->where('id', $id);
		$outp = $this->db->update('analisis_master', $update_data);

		$this->session->list_error = $list_error;
		if (!empty($list_error))
			status_sukses(-1, false, "Beberapa data gagal disimpan");
		else
			status_sukses(1);
	}
}
