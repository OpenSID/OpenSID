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
                            <?php if($setting->jenis == 'boolean'): ?>
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
                <button class="uibutton" type="reset">Clear</button>
                <button class="uibutton confirm" type="submit">Simpan</button>
            </div>
        </div>
    </div>
    </form>
</div>
</td></tr></table>
</div>
