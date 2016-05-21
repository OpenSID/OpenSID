<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
		<td style="background:#fff;padding:0px;"> 

<div class="content-header">
    <h3>Form Manajemen Dokumen</h3>
</div>
<div id="contentpane">
    <form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="form">
            <tr>
                <th>Judul Dokumen</th>
                <td><input name="nama" type="text" class="inputbox" size="100" value="<?=$dokumen['nama']?>"/></td>
            </tr>
				<?if($dokumen['satuan']){?>
            <tr>
                <th class="top">Dokumen</th>
                <td>
				<div class="slidebox-avatar">
					<img src="<?=base_url()?>assets/front/dokumen/<?=$dokumen['satuan']?>" alt=""/>
				</div>
				</td>
				<input type="hidden" name="old_file" value="<?=$dokumen['satuan']?>">
            </tr>
				<?}?>
            <tr>
                <th>Upload Dokumen</th>
                <td><input type="file" name="satuan" /> <span style="color: #aaa;">(Kosongkan jika tidak ingin mengubah dokumen)</span></td>
            </tr>
        </table>
    </div>
   
    <div class="ui-layout-south panel bottom">
        <div class="left">     
            <a href="<?=site_url()?>dokumen" class="uibutton icon prev">Kembali</a>
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset">Clear</button>
                <button class="uibutton confirm" type="submit" >Simpan</button>
            </div>
        </div>
    </div> </form>
</div>
</td></tr></table>
</div>
