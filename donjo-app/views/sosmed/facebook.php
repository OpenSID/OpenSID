<div id="pageC">
  <table class="inner">
  <tr style="vertical-align:top">
    <td class="side-menu">
      <div class="lmenu">
        <ul>
          <li class="selected"><a href="<?php echo site_url('sosmed')?>">Facebook</a></li>
        </ul>
        <ul>
          <li><a href="<?php echo site_url('sosmed/twitter')?>">Twitter</a></li>
        </ul>
        <ul>
          <li><a href="<?php echo site_url('sosmed/google')?>">Google</a></li>
        </ul>
        <ul>
          <li><a href="<?php echo site_url('sosmed/youtube')?>">Youtube</a></li>
        </ul>
      </div>
    </td>
    <td>
      <div id="contentpane">
        <form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
          <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
            <h3>Pengaturan Facebook</h3>
            <table class="form">
              <tr>
                <td width="150">Link Akun Facebook</td><td><textarea name="link" style="resize: none; height:100px; width:250px;" size="300" maxlength='160'><?php  if($main){echo $main['link'];} ?></textarea></td>
              </tr>
            </table>
          </div>

          <div class="ui-layout-south panel bottom">

            <div class="right">
              <div class="uibutton-group">
                <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
                <button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </td>
  </tr>
  </table>
</div>
