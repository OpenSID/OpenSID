<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">
			<div class="lmenu">
				<ul><a href="<?=site_url('sosmed')?>">
				<li  class="selected">Facebook</li></a>
				</ul>
				<ul><a href="<?=site_url('sosmed/twitter')?>">
				<li >Twitter</li></a>
				</ul>
				<ul><a href="<?=site_url('sosmed/goole')?>">
				<li>Google</li></a>
				</ul>
				<ul><a href="<?=site_url('sosmed/youtube')?>">
				<li>Youtube</li></a>
				</ul>
			</div>
		
	</td>
		<td> 
<div id="contentpane">
    <form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding-left: 5px;">
    <h3>Pengaturan Facebook</h3>
        <table class="form">
		<tr>
			<td width="150">Link Akun Facebook</td><td><textarea name="link" class=" required" style="resize: none; height:100px; width:250px;" size="300" maxlength='160'><? if($main){echo $main['link'];} ?></textarea></td>
		</tr>
        </table>
    </div>
   
    <div class="ui-layout-south panel bottom">
        
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
