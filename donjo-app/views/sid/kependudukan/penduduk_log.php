<style type="text/css">
    div.center {text-align: center;}
</style>
<script  TYPE='text/javascript'>
    $(function() {
        var keyword = <?php echo $keyword?> ;
        $( "#cari" ).autocomplete({
            source: keyword
        });
    });
</script>

<div id="pageC">
    <table class="inner">
        <tr style="vertical-align:top">
            <td style="background:#fff;padding:0px;">
                <div class="content-header">
                    <h3>Log Penduduk</h3>
                </div>
                <div id="contentpane">
                    <form id="mainform" name="mainform" action="" method="post">
                        <input type="hidden" name="rt" value="">
                        <div class="ui-layout-north panel">
                            <div class="left">
                                <div class="uibutton-group">
                                    <button type="button" title="Kembalikan Status" onclick="aksiBorongan('mainform','<?php echo site_url("penduduk_log/kembalikan_status_all")?>', 'Kembalikan Status', 'Apakah Anda yakin?')" class="uibutton tipsy south"><span class="fa fa-undo">&nbsp;</span>Kembalikan Status</button>
                                    <a href="<?php echo site_url("penduduk_log/cetak/$o")?>" class="uibutton tipsy south" title="Cetak Data" target="_blank"><span class="fa fa-print">&nbsp;</span>Cetak</a>
                                    <a href="<?php echo site_url("penduduk_log/excel/$o")?>" class="uibutton tipsy south" title="Unduh Data" target="_blank"><span class="fa fa-file-text">&nbsp;</span>Unduh</a>
                                </div>
                            </div>
                            <div class="right">
                                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('penduduk_log/search')?>');$('#'+'mainform').submit();}" />
                                <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('penduduk_log/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span> Cari </button>
                                <a href="<?php echo site_url()?>penduduk" class="uibutton icon prev">Kembali</a>
                            </div>
                            <div class="center"><h3>Log Penduduk</h3></div>
                        </div>
                        <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
                            <div class="table-panel top">
                                <div class="left">
                                    <select name="status_dasar" onchange="formAction('mainform','<?php echo site_url('penduduk_log/status_dasar')?>')">
                                        <option value="">Semua</option>
                                        <?php foreach ($list_status_dasar as $data): ?>
                                            <?php if (strtolower($data['nama']) != 'hidup'): ?>
                                                <option value="<?php echo $data['id']?>" <?php if($status_dasar==$data['id']):?>selected<?php  endif;?>><?php echo ucwords(strtolower($data['nama']))?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <select name="sex" onchange="formAction('mainform','<?php echo site_url('penduduk_log/sex')?>')">
                                        <option value="">Jenis Kelamin</option>
                                        <option value="1" <?php if($sex==1 ) :?>selected<?php endif?>>Laki-Laki</option>
                                        <option value="2" <?php if($sex==2 ) :?>selected<?php endif?>>Perempuan</option>
                                    </select>

                                    <select name="agama" onchange="formAction('mainform','<?php echo site_url('penduduk_log/agama')?>')">
                                        <option value="">Agama</option>
                    					<?php foreach($list_agama AS $data){?>
                                        <option value="<?php echo $data['id']?>" <?php if($agama == $data['id']) :?>selected<?php endif?>><?php echo $data['nama']?></option>
                    					<?php }?>
                                    </select>

                                    <select name="dusun" onchange="formAction('mainform','<?php echo site_url('penduduk_log/dusun')?>')">
                                        <option value=""><?php echo ucwords($this->setting->sebutan_dusun)?></option>
                    					<?php foreach($list_dusun AS $data){?>
                                        <option value="<?php echo $data['dusun']?>" <?php if($dusun == $data['dusun']) :?>selected<?php endif?>><?php echo unpenetration($data['dusun'])?></option>
                    					<?php }?>
                                    </select>

                    				<?php if($dusun){?>
                                    <select name="rw" onchange="formAction('mainform','<?php echo site_url('penduduk_log/rw')?>')">
                                        <option value="">RW</option>
                    					<?php foreach($list_rw AS $data){?>
                                        <option value="<?php echo $data['rw']?>" <?php if($rw == $data['rw']) :?>selected<?php endif?>><?php echo $data['rw']?></option>
                    					<?php }?>
                                    </select>
                    				<?php }?>

                    				<?php if($rw){?>
                                    <select name="rt" onchange="formAction('mainform','<?php echo site_url('penduduk_log/rt')?>')">
                                        <option value="">RT</option>
                    					<?php foreach($list_rt AS $data){?>
                                        <option value="<?php echo $data['rt']?>" <?php if($rt == $data['rt']) :?>selected<?php endif?>><?php echo $data['rt']?></option>
                    					<?php }?>
                                    </select>
                    				<?php }?>
                                </div>
                            </div>
                            <table class="list">
                            	<thead>
                                    <tr>
                                		<th>No</th>
                            			<th><input type="checkbox" class="checkall"/></th>
                            			<th width="85">Aksi</th>
                            			<?php  if($o==2): ?>
                                			<th align="left"><a href="<?php echo site_url("penduduk_log/index/$p/1")?>">NIK <span class="fa fa-sort-asc fa-sm"></span></a></th>
                            			<?php  elseif($o==1): ?>
                                			<th align="left"><a href="<?php echo site_url("penduduk_log/index/$p/2")?>">NIK <span class="fa fa-sort-desc fa-sm"></span></a></th>
                            			<?php  else: ?>
                                			<th align="left"><a href="<?php echo site_url("penduduk_log/index/$p/1")?>">NIK <span class="fa fa-sort fa-sm"></span></a></th>
                            			<?php  endif; ?>

                            			<?php  if($o==4): ?>
                                			<th align="left"><a href="<?php echo site_url("penduduk_log/index/$p/3")?>">Nama <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
                            			<?php  elseif($o==3): ?>
                                			<th align="left"><a href="<?php echo site_url("penduduk_log/index/$p/4")?>">Nama <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
                            			<?php  else: ?>
                                			<th align="left"><a href="<?php echo site_url("penduduk_log/index/$p/3")?>">Nama <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
                            			<?php  endif; ?>
                            			<th align="left">
                                			<?php  if($o==6): ?>
                                    			<a href="<?php echo site_url("penduduk_log/index/$p/5")?>">No. KK / Nama KK <span class="fa fa-sort-asc fa-sm">
                                			<?php  elseif($o==5): ?>
                                    			<a href="<?php echo site_url("penduduk_log/index/$p/6")?>">No. KK / Nama KK <span class="fa fa-sort-desc fa-sm">
                                			<?php  else: ?>
                                                <a href="<?php echo site_url("penduduk_log/index/$p/5")?>">No. KK / Nama KK <span class="fa fa-sort fa-sm">
                                			<?php  endif; ?>
                                			&nbsp;</span></a>
                                        </th>
                            			<th align="left" align="center"><?php echo ucwords($this->setting->sebutan_dusun)?></th>
                            			<th align="left" align="center">RW</th>
                            			<th align="left" align="center">RT</th>

                            			<th width="50" align="left">
                                			<?php  if($o==8): ?>
                                    			<a href="<?php echo site_url("penduduk_log/index/$p/7")?>">Umur <span class="fa fa-sort-asc fa-sm">
                                			<?php  elseif($o==7): ?>
                                    			<a href="<?php echo site_url("penduduk_log/index/$p/8")?>">Umur <span class="fa fa-sort-desc fa-sm">
                                			<?php  else: ?>
                                                <a href="<?php echo site_url("penduduk_log/index/$p/7")?>">Umur <span class="fa fa-sort fa-sm">
                                			<?php  endif; ?>
                                			&nbsp;</span></a>
                                        </th>

                            			<th align="left" align="center">Status Menjadi</th>
                            			<th align="left" align="center">
                                            <?php  if($o==10): ?>
                                                <a href="<?php echo site_url("penduduk_log/index/$p/9")?>">Tanggal Peristiwa <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a>
                                            <?php  elseif($o==9): ?>
                                                <a href="<?php echo site_url("penduduk_log/index/$p/10")?>">Tanggal Peristiwa <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a>
                                            <?php  else: ?>
                                                <a href="<?php echo site_url("penduduk_log/index/$p/9")?>">Tanggal Peristiwa <span class="fa fa-sort fa-sm">&nbsp;</span></a>
                                            <?php  endif; ?>
                                        </th>
                                        <th align="center">Tanggal Rekam</th>
                              		    <th align="center">Catatan Peristiwa</th>
                            		</tr>
                        		</thead>
                                <tbody>
                                    <?php  foreach($main as $data): ?>
                                		<tr>
                                            <td align="center" width="2"><?php echo $data['no']?></td>
                                            <td align="center" width="5">
                                                <input type="checkbox" name="id_cb[]" value="<?php echo $data['id_log']?>" />
                                            </td>
                                            <td>
                                                <a href="<?php echo site_url("penduduk_log/edit/$p/$o/$data[id_log]")?>" class="uibutton tipsy south"  title="Edit Log Penduduk" target="ajax-modal" rel="window" header="Edit Log Penduduk" modalWidth="auto" modalHeight="auto"><span class="fa fa-edit"></span> Ubah</a>
                                                <a href="<?php echo site_url("penduduk_log/kembalikan_status/$data[id_log]")?>" class="uibutton tipsy south" title="Kembalikan Status" target="confirm" message="Apakah Anda Yakin?" header="Kembalikan Status"><span class="fa fa-undo"></span></a>
                                            </td>
                                            <td>
                                                <a href="<?php echo site_url("penduduk/detail/$p/$o/$data[id]")?>" id="test" name="<?php echo $data['id']?>"><?php echo $data['nik']?></a>
                                            </td>
                                            <td>
                                                <a href="<?php echo site_url("penduduk/detail/$p/$o/$data[id]")?>"><?php echo strtoupper(unpenetration($data['nama']))?></a>
                                            </td>
                                            <td>
                                                <a href="<?php echo site_url("keluarga/kartu_keluarga/$p/$o/$data[id_kk]")?>"><?php echo $data['no_kk']?> </a> <br>
                                                <?php echo $data['nama_kk']?>
                                            </td>
                                            <td><?php echo unpenetration($data['dusun'])?></td>
                                            <td><?php echo $data['rw']?></td>
                                            <td><?php echo $data['rt']?></td>
                                            <td><?php echo $data['umur_pada_peristiwa']?></td>
                                            <td><?php echo $data['status_dasar']?></td>
                                            <td><?php echo tgl_indo($data['tgl_peristiwa'])?></td>
                                            <td><?php echo tgl_indo($data['tanggal'])?></td>
                                            <td><?php echo $data['catatan']?></td>

                                		</tr>
                                    <?php  endforeach; ?>
                        		</tbody>
                            </table>
                        </div>
                    </form>
                    <div class="ui-layout-south panel bottom">
                        <div class="left">
                            <div class="table-info">
                                <form id="paging" action="<?php echo site_url('penduduk_log')?>" method="post">
                                    <label>Tampilkan</label>
                                    <select name="per_page" onchange="$('#paging').submit()" >
                                        <option value="50" <?php  selected($per_page,50); ?> >50</option>
                                        <option value="100" <?php  selected($per_page,100); ?> >100</option>
                                        <option value="200" <?php  selected($per_page,200); ?> >200</option>
                                    </select>
                                    <label>Dari</label>
                                    <label><strong><?php echo $paging->num_rows?></strong></label>
                                    <label>Total Data</label>
                                  </form>
                            </div>
                        </div>
                        <div class="right">
                            <div class="uibutton-group">
                                <?php  if($paging->start_link): ?>
                                    <a href="<?php echo site_url("penduduk_log/index/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
                                <?php  endif; ?>
                                <?php  if($paging->prev): ?>
                                    <a href="<?php echo site_url("penduduk_log/index/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
                                <?php  endif; ?>
                            </div>
                            <div class="uibutton-group">
                                <?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
                                    <a href="<?php echo site_url("penduduk_log/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
                                <?php  endfor; ?>
                            </div>
                            <div class="uibutton-group">
                                <?php  if($paging->next): ?>
                                    <a href="<?php echo site_url("penduduk_log/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
                                <?php  endif; ?>
                                <?php  if($paging->end_link): ?>
                                    <a href="<?php echo site_url("penduduk_log/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
                                <?php  endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
