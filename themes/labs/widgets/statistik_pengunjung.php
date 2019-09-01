<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed block-mode-hidden">
<?php
  $ip = $_SERVER['REMOTE_ADDR']."{}";
  if(!isset($_SESSION['MemberOnline'])){
    $cek = $this->db->query("SELECT Tanggal,ipAddress FROM sys_traffic WHERE Tanggal='".date("Y-m-d")."'");
    if($cek->num_rows()==0){
      $up = $this->db->query("INSERT  INTO sys_traffic (Tanggal,ipAddress,Jumlah) VALUES ('".date("Y-m-d")."','".$ip."','1')");
      $_SESSION['MemberOnline']=date('Y-m-d H:i:s');
    }else{
      $res  = $cek->result_array();
      $ipaddr = $res['ipAddress'].$ip;
      $up = $this->db->query("UPDATE sys_traffic SET Jumlah=Jumlah + 1,ipAddress='".$ipx."' WHERE Tanggal='".date("Y-m-d")."'");
      $_SESSION['MemberOnline']=date('Y-m-d H:i:s');
    }
  }
  $rs = $this->db->query('SELECT Jumlah AS Visitor FROM sys_traffic WHERE Tanggal="'.date("Y-m-d").'" LIMIT 1');
  if($rs->num_rows()>0){
    $visitor = $rs->row(0);
    $today = $visitor->Visitor;
  }else{
    $today = 0;
  }
  $strSQL = "SELECT Jumlah AS Visitor FROM sys_traffic WHERE
  Tanggal=(SELECT DATE_ADD(CURDATE(),INTERVAL -1 DAY) FROM sys_traffic LIMIT 1)
  LIMIT 1";
  $rs = $this->db->query($strSQL);
  if($rs->num_rows()>0){
    $visitor = $rs->row(0);
    $yesterday = $visitor->Visitor;
  }else{
    $yesterday = 0;
  }
  $rs = $this->db->query('SELECT SUM(Jumlah) as Total FROM sys_traffic');
  $visitor = $rs->row(0);
  $total = $visitor->Total;


  function num_toimage($tot,$jumlah){
    $pattern='';
    for($j=0;$j<$jumlah;$j++){
      $pattern .= '0';
    }
    $len     = strlen($tot);
    $length  = strlen($pattern)-$len;
    $start   = substr($pattern,0,$length).substr($tot,0,$len-1);
    $last    = substr($tot,$len-1,1);
    $last_rpc= '<img src="_BASE_URL_/assets/images/counter/animasi/'.$last.'.gif" align="absmiddle" />';
    $inc     = str_replace($last,$last_rpc,$last);
    for($i=0;$i<=9;$i++){
      $rpc ='<img src="_BASE_URL_/assets/images/counter/'.$i.'.gif" align="absmiddle"/>';
      $start=str_replace($i,$rpc,$start);
    }
    $num = $start.$inc;
    $num = str_replace('_BASE_URL_',base_url(),$num);
    return $num;
  }
  ?>
    <div class="block-header bg-gd-sea block-header-default">
        <h3 class="block-title">
            <i class="si si-cursor"></i>
             Pengunjung</h3>
        <div class="block-options">
            <button
                type="button"
                class="btn-block-option"
                data-toggle="block-option"
                data-action="fullscreen_toggle">
                <i class="si si-size-fullscreen"></i>
            </button>
            <button
                type="button"
                class="btn-block-option"
                data-toggle="block-option"
                data-action="content_toggle">
                <i class="si si-arrow-up"></i>
            </button>
        </div>
    </div>
    <div class="block-content">
        <ul class="list list-activity">
            <li>
                <i class="si si-event text-danger"></i>
                <div class="h6 font-size-sm text-muted">Pengunjung Hari Ini :
                    <span><?= num_toimage($today,6); ?></span>
                </div>
            </li>
            <li>
                <i class="si si-event text-danger"></i>
                <div class="h6 font-size-sm text-muted">Pengunjun Kemarin :
                    <span><?= num_toimage($yesterday,6); ?></span></div>
            </li>
            <li>
                <i class="si si-event text-danger"></i>
                <div class="h6 font-size-sm text-muted">Total Pengunjung :
                    <span><?= num_toimage($total,6); ?></span>
                </div>
            </li>
        </ul>
    </div>
</div>