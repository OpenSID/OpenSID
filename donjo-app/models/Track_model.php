<?php class Track_model extends CI_Model {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('penduduk_model');
    $this->load->model('web_artikel_model');
    $this->load->model('keluar_model');
  }

  public function track_desa($dari)
  {
    if ($this->setting->enable_track == FALSE) return;
    // Track web dan admin masing2 maksimum sekali sehari
    if (strpos(current_url(), 'first') !== FALSE)
    {
      if(isset($_SESSION['track_web']) AND $_SESSION['track_web'] == date("Y m d")) return;
    }
    else
    {
      if(isset($_SESSION['track_admin']) AND $_SESSION['track_admin'] == date("Y m d")) return;
    }

    $_SESSION['balik_ke'] = $dari;
    $this->kirim_data();
  }

  public function kirim_data()
  {
    if (defined('ENVIRONMENT'))
    {
      switch (ENVIRONMENT)
      {
        case 'development':
          // Di development, panggil tracker hanya jika terinstal
          if (empty($this->setting->dev_tracker)) return;
          $tracker = $this->setting->dev_tracker;
        break;

        case 'testing':
        case 'production':
          $tracker = $this->setting->tracker;
        break;

        default:
          exit('The application environment is not set correctly.');
      }
    }
    $this->db->where('id', 1);
    $query = $this->db->get('config');
    $config = $query->row_array();
    $desa = array(
     "nama_desa" => $config['nama_desa'],
     "kode_desa" => $config['kode_desa'],
     "kode_pos" => $config['kode_pos'],
     "nama_kecamatan" => $config['nama_kecamatan'],
     "kode_kecamatan" => $config['kode_kecamatan'],
     "nama_kabupaten" => $config['nama_kabupaten'],
     "kode_kabupaten" => $config['kode_kabupaten'],
     "nama_provinsi" => $config['nama_propinsi'],
     "kode_provinsi" => $config['kode_propinsi'],
     "lat" => $config['lat'],
     "lng" => $config['lng'],
     "alamat_kantor" => $config['alamat_kantor'],
     "email_desa" => $config['email_desa'],
     "telepon" => $config['telepon'],
     "url" => current_url(),
     "ip_address" => $_SERVER['SERVER_ADDR'],
     "external_ip" => get_external_ip(),
     "version" => AmbilVersi(),
     "jml_penduduk" => $this->penduduk_model->jml_penduduk(),
     "jml_artikel" => $this->web_artikel_model->jml_artikel(),
     "jml_surat_keluar" => $this->keluar_model->jml_surat_keluar()
    );

    if ($this->abaikan($desa)) return;

    $trackSID_output = httpPost($tracker."/index.php/api/track/desa?token=".$this->token_opensid(), $desa);
    $this->cek_notifikasi_TrackSID($trackSID_output);
    if (strpos(current_url(), 'first') !== FALSE)
    {
      $_SESSION['track_web'] = date("Y m d");
    }
    else
    {
      $_SESSION['track_admin'] = date("Y m d");
    }
  }

  // token_opensid digunakan sebagai tanda pemanggilan memang di lakukan dari aplikasi OpenSID
  // Buat token_opensid kalau belum ada, menggunakan hash file LISENSI
  private function token_opensid()
  {
    if (empty($this->setting->token_opensid))
    {
      $lisensi = fopen('LICENSE', 'r');
      $token_opensid = sha1(file_get_contents($lisensi));
      // TODO: Ganti nama, karena ada masalah dengan loading setting_model dari proses migrasi
      $this->load->model('setting_model', 'settingmodel');
      $this->settingmodel->update_setting(['token_opensid' => $token_opensid]);
    }
    return $this->setting->token_opensid;
  }

  private function cek_notifikasi_TrackSID($trackSID_output)
  {
    if (!empty($trackSID_output))
    {
      $array_output = json_decode($trackSID_output, true);
      foreach ($array_output as $notif)
      {
        unset($notif['id']);
        $notif['tgl_berikutnya'] = date("Y-m-d H:i:s");
        $notif['updated_by'] = 0;
        $notif['aksi_ya'] = $this->aksi_valid($notif['aksi_ya']) ?: "notif/update_pengumuman";
        $notif['aksi_tidak'] = $this->aksi_valid($notif['aksi_tidak']) ?: "notif/update_pengumuman";
        $notif['aksi'] = $notif['aksi_ya'] . "," . $notif['aksi_tidak'];
        unset($notif['aksi_ya']);
        unset($notif['aksi_tidak']);
        $this->load->model('notif_model');
        $this->notif_model->insert_notif($notif);
      }
    }
  }

  private function aksi_valid($aksi)
  {
    $aksi_valid = ['setting/aktifkan_tracking'];
    $aksi = in_array($aksi, $aksi_valid) ? $aksi : '';
    return $aksi;
  }

  /*
    Jangan rekam, jika:
    - ada kolom nama wilayah kurang dari 4 karakter, kecuali desa boleh 3 karakter
    - ada kolom wilayah yang masih merupakan contoh (berisi karakter non-alpha atau tulisan 'contoh', 'demo' atau 'sampel')
  */
  public function abaikan($data)
  {
    $regex = '/[^\.a-zA-Z\s:-]|contoh|demo\s+|sampel\s+/i';
    $abaikan = false;
    $desa = trim($data['nama_desa']);
    $kec = trim($data['nama_kecamatan']);
    $kab = trim($data['nama_kabupaten']);
    $prov = trim($data['nama_provinsi']);
    if ( strlen($desa)<3 OR strlen($kec)<4 OR strlen($kab)<4 OR strlen($prov)<4 )
    {
      $abaikan = true;
    }
    elseif (preg_match($regex, $desa) OR
        preg_match($regex, $kec) OR
        preg_match($regex, $kab) OR
        preg_match($regex, $prov)
       )
    {
      $abaikan = true;
    }
    return $abaikan;
  }

}
?>
