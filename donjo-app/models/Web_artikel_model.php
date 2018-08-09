<?php class Web_artikel_model extends CI_Model{

    function __construct(){
        parent::__construct();
    }

    function autocomplete(){
        $sql   = "SELECT judul FROM artikel
                    UNION SELECT isi FROM artikel";
        $query = $this->db->query($sql);
        $data  = $query->result_array();

        $i=0;
        $outp='';
        while($i<count($data)){
            $outp .= ",'" .$data[$i]['judul']. "'";
            $i++;
        }
        $outp = strtolower(substr($outp, 1));
        $outp = '[' .$outp. ']';
        return $outp;
    }

    function search_sql(){
        if(isset($_SESSION['cari'])){
        $cari = $_SESSION['cari'];
            $kw = $this->db->escape_like_str($cari);
            $kw = '%' .$kw. '%';
            $search_sql= " AND (judul LIKE '$kw' OR isi LIKE '$kw')";
            return $search_sql;
            }
        }

    function filter_sql(){
        if(isset($_SESSION['filter'])){
            $kf = $_SESSION['filter'];
            $filter_sql= " AND a.enabled = $kf";
        return $filter_sql;
        }
    }

    function grup_sql(){
        if($_SESSION['grup'] == 4){
            $kf = $_SESSION['user'];
            $filter_sql= " AND a.id_user = $kf";
        return $filter_sql;
        }
    }

    function paging($cat=0,$p=1,$o=0){

        $sql      = "SELECT COUNT(a.id) AS id FROM artikel a WHERE a.id_kategori = ?";
        $sql     .= $this->search_sql();
        $sql .= $this->filter_sql();
        $query    = $this->db->query($sql,$cat);
        $row      = $query->row_array();
        $jml_data = $row['id'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    function list_data($cat=0,$o=0,$offset=0,$limit=500){
        switch($o){
        case 1: $order_sql = ' ORDER BY judul'; break;
        case 2: $order_sql = ' ORDER BY judul DESC'; break;
        case 3: $order_sql = ' ORDER BY enabled'; break;
        case 4: $order_sql = ' ORDER BY enabled DESC'; break;
        case 5: $order_sql = ' ORDER BY tgl_upload'; break;
        case 6: $order_sql = ' ORDER BY tgl_upload DESC'; break;
        default:$order_sql = ' ORDER BY id DESC';
        }

        $paging_sql = ' LIMIT ' .$offset. ',' .$limit;

        $sql   = "SELECT a.*,k.kategori AS kategori FROM artikel a LEFT JOIN kategori k ON a.id_kategori = k.id WHERE id_kategori = ? ";

        $sql .= $this->search_sql();
        $sql .= $this->filter_sql();
        $sql .= $this->grup_sql();
        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql,$cat);
        $data=$query->result_array();

        $i=0;
        $j=$offset;
        while($i<count($data)){
            $data[$i]['no']=$j+1;

            if($data[$i]['enabled']==1)
                $data[$i]['aktif']="Ya";
            else
                $data[$i]['aktif']="Tidak";

            $i++;
            $j++;
        }
        return $data;
    }

    function list_kategori(){
        $sql     = "SELECT * FROM kategori WHERE 1 order by urut";
        $query    = $this->db->query($sql);
        return  $query->result_array();
    }

    function get_kategori_artikel($id){
        return $this->db->select('id_kategori')->where('id',$id)->get('artikel')->row_array();
    }

    function get_kategori($cat=0){
        $sql     = "SELECT kategori FROM kategori WHERE id=?";
        $query    = $this->db->query($sql,$cat);
        return  $query->row_array();
    }

    function insert($cat=1) {
        $_SESSION['success']=1;
        $_SESSION['error_msg'] = "";
        $data = $_POST;

    if (empty($data['judul']) || empty($data['isi'])) {
            $_SESSION['error_msg'].= " -> Data harus diisi";
      $_SESSION['success'] = -1;
      return;
    }

        $fp = time();
        $list_gambar = array('gambar','gambar1','gambar2','gambar3');
        foreach ($list_gambar as $gambar) {
          $lokasi_file = $_FILES[$gambar]['tmp_name'];
          $nama_file   = urlencode($fp."_".$_FILES[$gambar]['name']);
          if (!empty($lokasi_file)){
              $tipe_file = TipeFile($_FILES[$gambar]);
                $hasil = UploadArtikel($nama_file,$gambar,$fp,$tipe_file);
                if ($hasil) $data[$gambar] = $nama_file;
          }
        }
        $data['id_kategori'] = $cat;
        $data['id_user'] = $_SESSION['user'];

        if($_SESSION['grup'] == 4){
            $data['enabled'] = 2;
        }



        //$data['isi'] = $data['isi'].$data['link_dokumen'];
        //unset($data['link_dokumen']);

        // Upload dokumen lampiran

        $lokasi_file = $_FILES['dokumen']['tmp_name'];
        $tipe_file = TipeFile($_FILES['dokumen']);
        $nama_file   = $_FILES['dokumen']['name'];
        $nama_file   = str_replace(' ', '-', $nama_file);    // normalkan nama file

        if ($nama_file AND !empty($lokasi_file)){
            if(!in_array($tipe_file, unserialize(MIME_TYPE_DOKUMEN))){
                unset($data['link_dokumen']);
                $_SESSION['error_msg'].= " -> Jenis file salah: " . $tipe_file;
                $_SESSION['success']=-1;
            } else {
            $data['dokumen']=$nama_file;
                if($data['link_dokumen']=='')
                $data['link_dokumen']= $data['judul'];
                UploadDocument2($nama_file);
            }
        }

        foreach ($list_gambar as $gambar) {
            unset($data['old_'.$gambar]);
        }
    if ($data['tgl_upload'] == '') {
        unset($data['tgl_upload']);
    } else {
        $tempTgl = date_create_from_format('d-m-Y H:i:s', $data['tgl_upload']);
        $data['tgl_upload'] = $tempTgl->format('Y-m-d H:i:s');
    }

    $outp = $this->db->insert('artikel', $data);
    if (!$outp) $_SESSION['success'] = -1;
    }

    function update($cat, $id=0){
        $_SESSION['success']=1;
        $_SESSION['error_msg'] = "";

      $data = $_POST;
    if (empty($data['judul']) || empty($data['isi'])) {
            $_SESSION['error_msg'].= " -> Data harus diisi";
      $_SESSION['success'] = -1;
      return;
    }

      $fp = time();
        $list_gambar = array('gambar','gambar1','gambar2','gambar3');
        foreach ($list_gambar as $gambar) {
          $lokasi_file = $_FILES[$gambar]['tmp_name'];
          $nama_file   = urlencode($fp."_".$_FILES[$gambar]['name']);

          if (!empty($lokasi_file)){
              $tipe_file = TipeFile($_FILES[$gambar]);
                $hasil = UploadArtikel($nama_file,$gambar,$fp,$tipe_file);
                if ($hasil) {
                    $data[$gambar] = $nama_file;
                    HapusArtikel($data['old_'.$gambar]);
                } else {
                unset($data[$gambar]);
                }
          } else {
            unset($data[$gambar]);
          }
        }

        foreach ($list_gambar as $gambar) {
            if(isset($data[$gambar.'_hapus'])){
                HapusArtikel($data[$gambar.'_hapus']);
                $data[$gambar] = "";
                unset($data[$gambar.'_hapus']);
            }
        }

        // Upload dokumen lampiran

        $lokasi_file = $_FILES['dokumen']['tmp_name'];
      	$tipe_file = TipeFile($_FILES['dokumen']);
        $nama_file   = $_FILES['dokumen']['name'];
        $nama_file   = str_replace(' ', '-', $nama_file);    // normalkan nama file

        if ($nama_file AND !empty($lokasi_file)){
            if(!in_array($tipe_file, unserialize(MIME_TYPE_DOKUMEN))){
                unset($data['link_dokumen']);
                $_SESSION['error_msg'].= " -> Jenis file salah: " . $tipe_file;
                $_SESSION['success']=-1;
            } else {
            $data['dokumen']=$nama_file;
                if($data['link_dokumen']=='')
                $data['link_dokumen']= $data['judul'];
                UploadDocument2($nama_file);
            }
        }

        foreach ($list_gambar as $gambar) {
            unset($data['old_'.$gambar]);
        }
        if ($data['tgl_upload'] == '') {
            unset($data['tgl_upload']);
        } else {
            $tempTgl = date_create_from_format('d-m-Y H:i:s', $data['tgl_upload']);
            $data['tgl_upload'] = $tempTgl->format('Y-m-d H:i:s');
        }

        $this->db->where('id',$id);
        $outp = $this->db->update('artikel',$data);
        if(!$outp) $_SESSION['success']=-1;
    }

    function update_kategori($id, $id_kategori) {
        $this->db->where('id', $id)->update('artikel', array('id_kategori' => $id_kategori));
    }

    function delete($id=''){
        $list_gambar = $this->db->select('gambar, gambar1, gambar2, gambar3')->where('id',$id)->get('artikel')->row_array();
        foreach ($list_gambar as $key => $gambar) {
            HapusArtikel($gambar);
        }
        $outp = $this->db->where('id',$id)->delete('artikel');
        return $outp;
    }

    function delete_all(){
        $_SESSION['success']=1;
        $id_cb = $_POST['id_cb'];
        foreach($id_cb as $id){
            $outp = $this->delete($id);
            if(!$outp) $_SESSION['success']=-1;
        }
    }

    function hapus($id=''){
        $sql  = "DELETE FROM kategori WHERE id=?";
        $outp = $this->db->query($sql,array($id));

        if($outp) $_SESSION['success']=1;
            else $_SESSION['success']=-1;
    }

    function artikel_lock($id='',$val=0){

        $sql  = "UPDATE artikel SET enabled=? WHERE id=?";
        $outp = $this->db->query($sql, array($val,$id));

        if($outp) $_SESSION['success']=1;
            else $_SESSION['success']=-1;
    }

    function komentar_lock($id='',$val=0){
        $_SESSION['success'] = 1;
        $outp = $this->db->where('id',$id)->update('artikel',array('boleh_komentar'=>$val));
        if(!$outp) $_SESSION['success'] = -1;
    }

    function get_artikel($id=0){
        $sql   = "SELECT a.*,u.nama AS owner FROM artikel a LEFT JOIN user u ON a.id_user = u.id WHERE a.id=?";
        $query = $this->db->query($sql,$id);
        $data  = $query->row_array();
        $data['judul'] = $this->security->xss_clean($data['judul']);
        if (empty($this->setting->user_admin) or $data['id_user'] != $this->setting->user_admin)
            $data['isi'] = $this->security->xss_clean($data['isi']);

            //$judul=str_split($data['nama'],15);
            //$data['judul'] = "<h3>".$judul[6]."</h3>";
        //digunakan untuk timepicker
        $tempTgl = date_create_from_format('Y-m-d H:i:s', $data['tgl_upload']);
        $data['tgl_upload'] = $tempTgl->format('d-m-Y H:i:s');

        return $data;
    }

    function get_headline(){
        $sql   = "SELECT a.*,u.nama AS owner FROM artikel a LEFT JOIN user u ON a.id_user = u.id WHERE headline = 1 ORDER BY tgl_upload DESC LIMIT 1 ";
        $query = $this->db->query($sql);
        $data  = $query->row_array();

        if(empty($data))
            $data = null;
        else{
            $id = $data['id'];

            $panjang=str_split($data['isi'],300);
            $data['isi'] = "<label>".$panjang[0]."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
        }

        return $data;
    }

    function artikel_show(){
        $sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=? AND k.tipe = 1 ORDER BY a.tgl_upload DESC LIMIT 4";
        $query = $this->db->query($sql,1);
        $data  = $query->result_array();

        $i=0;
        while($i<count($data)){

            //$judul=str_split($data[$i]['nama'],15);
            //$data[$i]['judul'] = "<h3>".$judul[6]."</h3>";
            $id = $data[$i]['id'];
            //$data['link'] = site_url("first/artikel/$id");

            $pendek=str_split($data[$i]['isi'],100);
            $data[$i]['isi_short'] = $pendek[0];
            $panjang=str_split($data[$i]['isi'],150);
            $data[$i]['isi'] = "<label>".$panjang[0]."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
            $i++;
        }
        return $data;
    }

    function insert_kategori(){
        $data['kategori'] = $_POST['kategori'];
        $data['tipe'] = '2';
        $outp = $this->db->insert('kategori',$data);

        if($outp) $_SESSION['success']=1;
        else $_SESSION['success']=-1;
    }

    function insert_comment($id=0){
        $data = $_POST;
        $data['enabled'] = 2;
        $data['id_artikel'] = $id;
        $outp = $this->db->insert('komentar',$data);

        if($outp) $_SESSION['success']=1;
        else $_SESSION['success']=-1;
    }

    function list_komentar($id=0){
        $sql   = "SELECT * FROM komentar WHERE id_artikel = ? ORDER BY tgl_upload DESC";
        $query = $this->db->query($sql,$id);
        $data  = $query->result_array();

        $i=0;
        while($i<count($data)){
            $i++;
        }

        return $data;
    }

    function headline($id=0){

        $sql1  = "UPDATE artikel SET headline = 0 WHERE headline =1";
        $this->db->query($sql1);

        $sql  = "UPDATE artikel SET headline = 1 WHERE id=?";
        $outp = $this->db->query($sql,$id);

        if($outp) $_SESSION['success']=1;
            else $_SESSION['success']=-1;
    }

    function slide($id=0){
        $sql   = "SELECT * FROM artikel WHERE id=?";
        $query = $this->db->query($sql,$id);
        $data  = $query->row_array();

        if($data['headline']=='3'){
            $sql  = "UPDATE artikel SET headline = 0 WHERE id=?";
            $outp = $this->db->query($sql,$id);
            }else{
            $sql  = "UPDATE artikel SET headline = 3 WHERE id=?";
            $outp = $this->db->query($sql,$id);
        }

        if($outp) $_SESSION['success']=1;
            else $_SESSION['success']=-1;
    }
}