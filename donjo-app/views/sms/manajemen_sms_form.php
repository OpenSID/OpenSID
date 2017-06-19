<script>
$(function(){
    if ($('input[name=group]:checked').next('label').text()=='SKPD' || $('input[name=group]:checked').next('label').text()=='UPTD'){
            $('tr.skpd_uptd').show();
    }
    $('input[name=group]').click(function(){
        if ($(this).next('label').text()=='SKPD' || $(this).next('label').text()=='UPTD'){
            $('tr.skpd_uptd').show();
        } else {
            $('tr.skpd_uptd').hide();
        }
    });
    if ($('input[name=group]:checked').next('label').text()=='SKPD'){
            $('tr.skpd').show();
    }
    $('input[name=group]').click(function(){
        if ($(this).next('label').text()=='SKPD'){
            $('tr.skpd').show();
        } else {
            $('tr.skpd').hide();
        }
    });
    if ($('input[name=group]:checked').next('label').text()=='UPTD'){
            $('tr.uptd').show();
    }
    $('input[name=group]').click(function(){
        if ($(this).next('label').text()=='UPTD'){
            $('tr.uptd').show();
        } else {
            $('tr.uptd').hide();
        }
    });
});
</script>
<style>
tr.skpd_uptd{
    display:none;
    background:#fcfff6;
}
tr.skpd{
    display:none;
    background:#fcfff6;
}
tr.uptd{
    display:none;
    background:#fcfff6;
}
</style>
<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
		<td style="width:240px;background:#fff;padding:5px;font-size:.9em">
		<div class="tipbox" style="margin: 0 0 5px 0;">
            <h4>Info Terbaru</h4>
            <div class="content">
				<p>
				If your Windows Vista or 7, has problems running one of our programs, there is a compatibility mode you can easily set per application. To configure this for your application, just locate the installation directory and right click on the .exe, select Properties from the menu. - Select the Compatibility tab and choose what compatibility you would like to run the program in. You can also choose - Run? this program as an administrator. There will be a tutorial coming soon on this subject.
				</p>
            </div>
        </div>
		</td>
		<td style="background:#fff;padding:5px;">

<div class="content-header">
    <h3>Form Manajemen User</h3>
</div>
<div id="contentpane">
    <form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="form">
            <tr>
                <th width="100">Group</th>
                <td>
                    <div class="uiradio">
					<?php $ch='checked';?>
						<?php if($user['id_grup'] != '1'){?>
							<input type="radio" id="group3" name="group" value="3"/<?php if($user['id_grup'] == '3' OR $user['id_grup'] == ''){echo $ch;}?>><label for="group3">Redaksi</label>
							<input type="radio" id="group2" name="group" value="2"/<?php if($user['id_grup'] == '2'){echo $ch;}?>><label for="group2">Operator</label>
						<?php }?>
                		<input type="radio" id="group1" name="group" value="1"/<?php if($user['id_grup'] == '1'){echo $ch;}?>><label for="group1">Administrator</label>
                	</div>
                </td>
            </tr>
            <tr>
                <th>Username</th>
                <td><input name="username" type="text" class="inputbox required" size="40" value="<?php echo $user['username']?>"/></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><input name="password" type="password" class="inputbox" size="20" value="<?php echo $user['password']?>"/></td>
            </tr>
            <tr>
                <th>Nama</th>
                <td><input name="nama" type="text" class="inputbox" size="60" value="<?php echo $user['nama']?>"/></td>
            </tr>
            <tr>
                <th>Nomor HP</th>
                <td><input name="nomor_hp" type="text" class="inputbox" size="20"  value="<?php echo $user['phone']?>"/></td>
            </tr>
            <tr>
                <th>Mail</th>
                <td><input name="email" type="text" class="inputbox" size="20"  value="<?php echo $user['email']?>"/></td>
            </tr>
            <tr class="skpd_uptd">
                <th>Nama Bendahara</th>
                <td><input name="nama_bendahara" type="text" class="inputbox" size="50" value="<?php echo $user['nama_bendahara']?>"/></td>
            </tr>
            <tr class="skpd_uptd">
                <th>NIP Bendahara</th>
                <td><input name="nip_bendahara" type="text" class="inputbox" size="25" value="<?php echo $user['nip_bendahara']?>"/></td>
            </tr>
			<tr class="skpd_uptd">
                <th>Nama Pengguna Anggaran</th>
                <td><input name="nama_pengguna" type="text" class="inputbox" size="50" value="<?php echo $user['nama_pengguna']?>"/></td>
            </tr>
            <tr class="skpd_uptd">
                <th>NIP Pengguna Anggaran</th>
                <td><input name="nip_pengguna" type="text" class="inputbox" size="25" value="<?php echo $user['nip_pengguna']?>"/></td>
            </tr>
            <tr>
                <th class="top">Foto</th>
                <td>
				<div class="userbox-avatar">
				<?php if($user['foto']){?>
					<img src="<?php echo AmbilFoto($user['foto'])?>" alt=""/>
				<?php }else{?>
					<img src="<?php echo base_url()?>assets/files/user_pict/kuser.png" alt=""/>
				<?php }?>
				</div>
				</td>
				<input type="hidden" name="old_foto" value="<?php echo $user['foto']?>">
            </tr>
            <tr>
                <th>Ganti Foto</th>
                <td><input type="file" name="foto" /> <span style="color: #aaa;">(Kosongi jika tidak ingin merubah foto)</span></td>
            </tr>
        </table>
    </div>

    <div class="ui-layout-south panel bottom">
        <div class="left">
            <a href="<?php echo site_url()?>man_user" class="uibutton icon prev">Kembali</a>
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
                <button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
            </div>
        </div>
    </div> </form>
</div>
</td></tr></table>
</div>