<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">
<div class="content-header">
</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Setting Aplikasi</h3>
</div>
    <form action="<?php echo site_url('setting/update')?>" method="POST">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="list">
            <tr>
                <th width="160px">Setting</th>
                <th width="320px">Nilai Setting</th>
                <th>Keterangan</th>
            </tr>
            <?php foreach($this->list_setting as $setting) : ?>
                <?php if($setting->kategori != 'development' OR ($this->config->item("environment") == 'development' )) : ?>
                    <tr>
                        <td><strong><?php echo $setting->key?></strong></td>
                        <td>
                            <?php if($setting->key == 'offline_mode'): ?>
                                <select name="<?php echo $setting->key?>" >
                                    <option value="0" <?php if($setting->value==0) :?>selected<?php endif?>>Web bisa diakses publik</option>
                                    <option value="1" <?php if($setting->value==1) :?>selected<?php endif?>>Web dan peta hanya bisa diakses admin/operator/redaksi</option>
                                    <option value="2" <?php if($setting->value==2) :?>selected<?php endif?>>Web dan peta non-aktif sama sekali</option>
                                </select>
                            <?php elseif($setting->key == 'sumber_gambar_slider'): ?>
                                <select name="<?php echo $setting->key?>" >
                                    <option value="1" <?php if($setting->value==1) :?>selected<?php endif?>>Gambar utama artikel terbaru</option>
                                    <option value="2" <?php if($setting->value==2) :?>selected<?php endif?>>Gambar utama artikel terbaru yang masuk ke slider atas</option>
                                    <option value="3" <?php if($setting->value==3) :?>selected<?php endif?>>Gambar dalam album galeri yang dimasukkan ke slider</option>
                                </select>
                            <?php elseif($setting->jenis == 'boolean'): ?>
                                <select name="<?php echo $setting->key?>" >
                                    <option value="1" <?php if($setting->value==1) :?>selected<?php endif?>>Ya</option>
                                    <option value="0" <?php if($setting->value==0) :?>selected<?php endif?>>Tidak</option>
                                </select>
                            <?php else : ?>
                                <input name="<?php echo $setting->key?>" type="text" class="inputbox" size="50" value="<?php echo $setting->value?>">
                            <?php endif; ?>
                        </td>
                        <td><?php echo $setting->keterangan?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>

        </table>
    </div>

    <div class="ui-layout-south panel bottom">
        <div class="left">
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
                <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
            </div>
        </div>
    </div>
    </form>
</div>
</td></tr></table>
</div>
