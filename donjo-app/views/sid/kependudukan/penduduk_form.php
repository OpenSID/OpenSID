<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">

<div class="content-header">
<h3>Form Data Penduduk</h3>
</div>
<div id="contentpane">

<form id="mainform" name="mainform" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
  <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
    <table class="form">
      <?php $edit_lokasi = ((empty($penduduk) OR (isset($_SESSION['validation_error']) AND $_SESSION['validation_error'])) AND empty($id)); ?>
      <?php if($edit_lokasi) {?>
        <tr>
          <th width="100"><?php echo ucwords($this->setting->sebutan_dusun)?></th>
          <td>
            <select name="dusun" onchange="formAction('mainform','<?php echo site_url('penduduk/form')?>')" <?php if($dusun){?>class="required"<?php }?>>
              <option value="">Pilih <?php echo ucwords($this->setting->sebutan_dusun)?></option>
              <?php foreach($dusun as $data){?>
                <option value="<?php echo $data['dusun']?>" <?php if($dus_sel==$data['dusun']){?>selected<?php }?>><?php echo unpenetration(ununderscore($data['dusun']))?></option>
              <?php }?>
            </select>
          </td>
        </tr>

        <tr>
          <th>RW</th>
          <td>
            <select name="rw" onchange="formAction('mainform','<?php echo site_url('penduduk/form')?>')" <?php if($rw){?>class="required"<?php }?>>
              <option value="">Pilih RW</option>
              <?php foreach($rw as $data){?>
                <option value="<?php echo $data['rw']?>" <?php if($rw_sel==$data['rw']){?>selected<?php }?>><?php echo $data['rw']?></option>
              <?php }?>
            </select>
          </td>
        </tr>

        <tr>
          <th>RT</th>
          <td>
            <select name="rt" onchange="formAction('mainform','<?php echo site_url('penduduk/form')?>')" <?php if($rt){?>class="required"<?php }?>>
              <option value="">Pilih RT</option>
              <?php foreach($rt as $data){?>
                <option value="<?php echo $data['id']?>" <?php if($rt_sel==$data['id']){?>selected<?php }?>><?php echo $data['rt']?></option>
              <?php }?>
            </select>
          </td>
        </tr>
      <?php }?>
      <?php if(!empty($rt_sel) OR (!empty($penduduk))){?>
      	<?php include("donjo-app/views/sid/kependudukan/penduduk_form_isian.php"); ?>
      <?php }?>
    </table>
  </div>

  <div class="ui-layout-south panel bottom">
    <div class="left">
      <?php if($penduduk['id']): ?>
        <a href="<?php echo site_url()?>penduduk/detail/1/0/<?php echo $penduduk['id']?>" class="uibutton icon prev">Kembali</a>
      <?php else: ?>
        <a href="<?php echo site_url()?>penduduk" class="uibutton icon prev">Kembali</a>
      <?php endif; ?>
    </div>
    <div class="right">
      <div class="uibutton-group">
        <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
        <button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
      </div>
    </div>
  </div>
</form>
</div>
</td></tr></table>
</div>