<link type='text/css' href="<?php echo base_url()?>assets/css/bulk-menu.css" rel='Stylesheet' />

<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>
<style>
    table.list td.perhatian {background-color: rgba(255, 127, 80, 0.35);}
    table.list tr.perhatian {background-color: rgba(255, 127, 80, 0.35);}

    div.uibutton-group select {vertical-align: top;}

    div.ui-layout-north.panel.ui-layout-pane.ui-layout-pane-north{
        z-index: 9999999 !important;
    }
</style>

<div id="pageC">
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">


<td style="background:#fff;padding:0px;">
<div class="content-header">
    <h3>Data Keluarga</h3>
</div>
<div id="contentpane">
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel" style="z-index: 999999 !important;">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?php echo site_url('keluarga/form')?>" class="uibutton tipsy south" title="Tambah Data KK Baru" ><span class="fa fa-plus-square">&nbsp;</span>Tambah KK Baru</a>
                <a href="<?php echo site_url('keluarga/form_old')?>" target="ajax-modal" rel="window" header="Tambah Data Kepala Keluarga" class="uibutton tipsy south" title="Tambah Data KK dari penduduk yang sudah ter-input" ><span class="fa fa-plus">&nbsp;</span>Tambah KK</a>
				<a href="<?php echo site_url("keluarga/cetak/$o")?>" target="_blank" class="uibutton tipsy south" title="Cetak Data" ><span class="fa fa-print">&nbsp;</span>Cetak</a>
				<a href="<?php echo site_url("keluarga/excel/$o")?>" target="_blank" class="uibutton tipsy south" title="Unduh Data" ><span class="fa fa-file-text">&nbsp;</span>Unduh</a>
				&nbsp;

                <div id='menu-borongan' style="display: inline-block;">
                    <ul id="siteman-nav" class="top">
                        <li class="uibutton"><span class="judul">Aksi Ganda</span><span class="fa fa-caret-down fa-lg">&nbsp;</span>
                            <ul>
                                <?php  if($grup==1): ?>
                                    <li onclick="aksiBorongan('mainform','<?php echo site_url("keluarga/delete_all/$p/$o")?>','Hapus Data','Apakah anda yakin mau menghapus data ini?')">
                                        <span class="judul">Hapus</span>
                                        <span class="fa fa-trash fa-lg">&nbsp;</span>
                                    </li>
                                <?php endif; ?>
                                <li onclick="aksiBorongan('mainform','<?php echo site_url("keluarga/cetak_kk_all")?>','Cetak Kartu Keluarga','Apakah anda yakin mau mencetak KK ini?','_blank')">
                                    <span class="judul">Cetak Kartu Keluarga</span>
                                    <span class="fa fa-print fa-lg">&nbsp;</span>
                                </li>
                                <li onclick="aksiBorongan('mainform','<?php echo site_url("keluarga/doc_kk_all")?>','Unduh Kartu Keluarga','Apakah anda yakin mau mengunduh KK ini?','_blank')">
                                    <span class="judul">Unduh Kartu Keluarga</span>
                                    <span class="fa fa-download fa-lg">&nbsp;</span>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <select name="status_dasar" onchange="formAction('mainform','<?php echo site_url('keluarga/status_dasar')?>')">
                    <option value="">Semua KK</option>
                    <option value="1" <?php if($status_dasar == 1) :?>selected<?php endif?>>KK Aktif</option>
                    <option value="2" <?php if($status_dasar == 2) :?>selected<?php endif?>>KK Hilang/Pindah/Mati</option>
                </select>
				<select name="dusun" onchange="formAction('mainform','<?php echo site_url('keluarga/dusun')?>')">
                    <option value=""><?php echo ucwords($this->setting->sebutan_dusun)?></option>
					<?php foreach($list_dusun AS $data){?>
                    <option value="<?php echo $data['dusun']?>" <?php if($dusun == $data['dusun']) :?>selected<?php endif?>><?php echo strtoupper(unpenetration(ununderscore($data['dusun'])))?></option>
					<?php }?>
                </select>
				<?php if($dusun){?>
                <select name="rw" onchange="formAction('mainform','<?php echo site_url('keluarga/rw')?>')">
                    <option value="">RW</option>
					<?php foreach($list_rw AS $data){?>
                    <option value="<?php echo $data['rw']?>" <?php if($rw == $data['rw']) :?>selected<?php endif?>><?php echo $data['rw']?></option>
					<?php }?>
                </select>
				<?php }?>

				<?php if($rw){?>
                <select name="rt" onchange="formAction('mainform','<?php echo site_url('keluarga/rt')?>')">
                    <option value="">RT</option>
					<?php foreach($list_rt AS $data){?>
                    <option value="<?php echo $data['rt']?>" <?php if($rt == $data['rt']) :?>selected<?php endif?>><?php echo $data['rt']?></option>
					<?php }?>
                </select>
				<?php }?>

                <select name="sex" onchange="formAction('mainform','<?php echo site_url('keluarga/sex')?>')">
                    <option value="">Jenis Kelamin</option>
                    <option value="1" <?php if($sex==1 ) :?>selected<?php endif?>>Laki-Laki</option>
                    <option value="2" <?php if($sex==2 ) :?>selected<?php endif?>>Perempuan</option>
                </select>

            </div>
        </div>

        <div class="right">
            <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('keluarga/search')?>');$('#'+'mainform').submit();}" />
            <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('keluarga/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
        </div>

    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
      <table class="list">
		<thead>
            <?php if ($judul_statistik): ?>
              <tr>
                <td colspan="15" style="text-align: center;"><strong style="font-size:14px;"><?php  echo $judul_statistik; ?></strong></td>
              </tr>
            <?php endif; ?>
          <tr>
            <th>No</th>
            <th><input type="checkbox" class="checkall"/></th>
            <th width="160">Aksi</th>

			<th width="120" align="center">
			<?php  if($o==2): ?>
			<a href="<?php echo site_url("keluarga/index/$p/1")?>">Nomor KK <span class="fa fa-sort-asc fa-sm">
			<?php  elseif($o==1): ?>
			<a href="<?php echo site_url("keluarga/index/$p/2")?>">Nomor KK <span class="fa fa-sort-desc fa-sm">
			<?php  else: ?>
			<a href="<?php echo site_url("keluarga/index/$p/1")?>">Nomor KK <span class="fa fa-sort fa-sm">
			<?php  endif; ?>
			&nbsp;</span></a></th>

			<th align="center">
			<?php  if($o==4): ?>
			<a href="<?php echo site_url("keluarga/index/$p/3")?>">Kepala Keluarga <span class="fa fa-sort-asc fa-sm">
			<?php  elseif($o==3): ?>
			<a href="<?php echo site_url("keluarga/index/$p/4")?>">Kepala Keluarga <span class="fa fa-sort-desc fa-sm">
			<?php  else: ?>
			<a href="<?php echo site_url("keluarga/index/$p/3")?>">Kepala Keluarga <span class="fa fa-sort fa-sm">
			<?php  endif; ?>
			&nbsp;</span></a></th>

            <th width="100" align="center">NIK</th>
			<th width="50" align="center">Jumlah Anggota</th>
			<th align="center" width="80">Jenis Kelamin</th>
            <th align="center" width="180">Alamat</th>
            <th align="center" width="120"><?php echo ucwords($this->setting->sebutan_dusun)?></th>
            <th align="center" width="30">RW</th>
            <th align="center" width="30">RT</th>
			<th align="center" width="100">Tanggal Terdaftar</th>
            <th align="center" width="100">Tanggal Cetak KK</th>
    			</tr>
    		</thead>
    		<tbody>
          <?php  foreach($main as $data): ?>
      		<tr class="<?php if($data['status_dasar'] != 1) echo 'perhatian'?>">
            <td align="center" width="2"><?php echo $data['no']?></td>
      			<td align="center" width="5">
      				<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
      			</td>
            <td width="5"><div class="uibutton-group">
          		<a href="<?php echo site_url("keluarga/anggota/$p/$o/$data[id]")?>" class="uibutton tipsy south fa-tipis" title="Rincian Anggota Keluarga"><span class="fa fa-list"></span> Rincian</a>
              <a href="<?php echo site_url("keluarga/edit_nokk/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data" target="ajax-modal" rel="window" modalWidth="auto" modalHeight="auto" header="Ubah Data KK"><span class="fa fa-edit"></span></a>

        			<a href="<?php echo site_url("keluarga/form_a/$p/$o/$data[id]")?>" header="Tambah Anggota Keluarga" class="uibutton tipsy south" title="Tambah Anggota Keluarga"><span class="fa fa-plus-circle"></span></a>
        			<a href="<?php echo site_url("keluarga/ajax_penduduk_pindah/$data[id]")?>"  class="uibutton tipsy south" title="Ubah Alamat/Pindah Keluarga dalam Desa" target="ajax-modal" rel="window" header="Ubah/Pindah Alamat Keluarga" modalWidth="auto" modalHeight="auto"><span class="fa fa-share-square-o"></span></a>
                <?php  if($grup==1){?><a href="<?php echo site_url("keluarga/delete/$p/$o/$data[id]")?>"  class="uibutton tipsy south"  title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span> </a><?php  } ?>
        		</td>
            <td align="center"><a href="<?php echo site_url("keluarga/kartu_keluarga/$p/$o/$data[id]")?>"> <?php echo $data['no_kk']?> </a></td>
        	<td class=<?php echo empty($data['kepala_kk']) ? "perhatian" : ""?>><?php echo strtoupper(unpenetration($data['kepala_kk']))?></td>
            <td align="center" class=<?php echo empty($data['nik']) ? "perhatian" : ""?>><?php echo strtoupper(unpenetration($data['nik']))?></td>
            <td align="center"><a href="<?php echo site_url("keluarga/anggota/$p/$o/$data[id]")?>"><?php echo $data['jumlah_anggota']?></a></td>
            <td><?php echo strtoupper($data['sex'])?></td>
            <td><?php echo strtoupper($data['alamat'])?></td>
            <td><?php echo strtoupper(unpenetration(ununderscore($data['dusun'])))?></td>
            <td align="center"><?php echo strtoupper($data['rw'])?></td>
            <td align="center"><?php echo strtoupper($data['rt'])?></td>
            <td><?php echo tgl_indo($data['tgl_daftar'])?></td>
            <td><?php echo tgl_indo($data['tgl_cetak_kk'])?></td>
    		  </tr>
          <?php  endforeach; ?>
    		</tbody>
      </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left">
		<div class="table-info">
          <form id="paging" action="<?php echo site_url('keluarga')?>" method="post">
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
				<a href="<?php echo site_url("keluarga/index/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("keluarga/index/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">

				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("keluarga/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("keluarga/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("keluarga/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
			<?php  endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
