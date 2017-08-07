<?php class Track_Model extends CI_Model{

  function __construct(){
    parent::__construct();

    session_start();
  }

  function track_desa($dari){
    if ($this->setting->enable_track == FALSE) return;
    // Track web dan admin masing2 maksimum sekali sehari
    if (strpos(current_url(), 'first') !== FALSE) {
      if(isset($_SESSION['track_web']) AND $_SESSION['track_web'] == date("Y m d")) return;
    } else {
      if(isset($_SESSION['track_admin']) AND $_SESSION['track_admin'] == date("Y m d")) return;
    }

    $_SESSION['balik_ke'] = $dari;
    if (defined('ENVIRONMENT'))
    {
      switch (ENVIRONMENT)
      {
        case 'development':
          // Di development, panggil tracker hanya jika terinstal
          $tracker = $this->setting->dev_tracker;
          if (empty($tracker)) return;
        break;

        case 'testing':
        case 'production':
          $koneksi = cek_koneksi_internet();
          if(!$koneksi) return;
          $tracker = "tracksid.bangundesa.info";
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
     "url" => current_url(),
     "ip_address" => $_SERVER['SERVER_ADDR'],
     "version" => AmbilVersi()
    );

    if($this->abaikan($desa)) return;

    // echo "httppost ===========";
    // echo httpPost("http://".$tracker."/index.php/track/desa",$desa);
    httpPost("http://".$tracker."/index.php/track/desa",$desa);
    if (strpos(current_url(), 'first') !== FALSE) {
      $_SESSION['track_web'] = date("Y m d");
    } else {
      $_SESSION['track_admin'] = date("Y m d");
    }

  }

  /*
    Jangan track, jika:
    - ada kolom nama wilayah kosong
    - ada kolom wilayah yang masih merupakan contoh
  */
  public function abaikan($data){
    $abaikan = false;
    if ( empty($data['nama_desa']) OR empty($data['nama_kecamatan']) OR empty($data['nama_kabupaten']) OR empty($data['nama_provinsi']) ) {
      $abaikan = true;
    }
    if (strpos($data['nama_desa'], 'Senggig1') !== FALSE OR
        strpos($data['nama_kecamatan'], 'Batulay4r') !== FALSE OR
        strpos($data['nama_kabupaten'], 'Bar4t') !== FALSE OR
        strpos($data['nama_provinsi'], 'NT13') !== FALSE
       ) {
      $abaikan = true;
    }
    return $abaikan;
  }

}
?>