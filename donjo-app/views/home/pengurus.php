<div class="content-wrapper">
    <section class="content-header">
        <h1>Pemerintahan  <?php echo ucwords($this->setting->sebutan_desa)?></h1>
		    <ol class="breadcrumb">
			      <li><a href="<?php echo site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			      <li class="active">Pemerintahan <?php echo ucwords($this->setting->sebutan_desa)?></li>
		    </ol>
	  </section> 
    <section class="content" id="maincontent">
        <form id="mainform" name="mainform" action="" method="post">
            <div class="row">
              <div class="col-md-12">						
                <div class="box box-info">	
                  <div class="box-header with-border">	
                    <a href="<?php echo site_url('pengurus/form')?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Staf">
                      <i class="fa fa-plus"></i>Tambah Staf Pemerintahan <?php echo ucwords($this->setting->sebutan_desa)?>
                    </a>		
                    <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("pengurus/delete_all")?>')" class="btn btn-social btn-flat		btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                      <i class='fa fa-trash-o'></i> Hapus Data Terpilih
                    </a>		
                  </div>		
                  <div class="box-header with-border">
                  <div class="row">
                    <div class="col-sm-3">												
                      <select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?php echo site_url('pengurus/filter')?>')">
                        <option value="">Semua</option>
                    <option value="1" <?php if($filter==1 ) :?>selected<?php endif?>>Aktif</option>
                    <option value="2" <?php if($filter==2 ) :?>selected<?php endif?>>Tidak Aktif</option>
                      </select>  										
                    </div>
                    </div>
                  </div>							
                  <div class="box-body">		
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="table-responsive">
                          <table class="table table-bordered dataTable table-hover">
                            <thead class="bg-gray disabled color-palette">												
                              <tr>		
                                <th><input type="checkbox" id="checkall" ></th>
                                <th>No</th>     
                                <th width='12%'>Aksi</th>
                                <th>Photo</th>	
                                <th width='50%'>Nama / NIP /NIK</th>	
                                <th>Jabatan</th>
                                <th>Status</th>		
                              </tr>														
                            </thead>
                            <tbody>	
                              <?php  foreach($main as $data): ?>
                                <tr>	
                                  <td><input type="checkbox" name="id_cb[]" value="<?php echo $data['pamong_id']?>" ></td>
                                  <td><?php echo $data['no']?></td>				
                                  <td nowrap>
                                    <?php if($data['pamong_id']!="707"){?>
                                      <a href="<?php echo site_url("pengurus/form/$data[pamong_id]")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a>
                                      <?php if($data['pamong_ttd'] == '1'):?>
                                          <a href="<?php echo site_url('pengurus/ttd_off/'.$data['pamong_id'])?>" class="btn bg-navy btn-flat btn-sm" title="Bukan TTD default"><i class="fa fa-pencil"></i></a>
                                      <?php else : ?>
                                          <a href="<?php echo site_url('pengurus/ttd_on/'.$data['pamong_id'])?>" class="btn bg-purple btn-flat btn-sm" title="Jadikan TTD default"><i  class="fa fa-user"></i></a>
                                      <?php endif?>
                                      <a href="#" data-href="<?php echo site_url("pengurus/delete/$data[pamong_id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                    <?php }?>
                                  </td>
                                  <td>
                                    <div class="user-panel">
                                      <div class="image">
                                        <?php if($data['foto']){?>
                                          <img src="<?php echo AmbilFoto($data['foto'])?>" class="img-circle" alt="User Image"/>
                                        <?php }else{?>
                                          <img src="<?php echo base_url()?>assets/files/user_pict/kuser.png" class="img-circle" alt="User Image"/>
                                        <?php }?>							
                                      </div>	
                                    </div>		
                                  </td>
                                  <td>
                                    <?php echo unpenetration($data['pamong_nama'])?>
                                    <p class='text-blue'>
                                      <i>NIP :<?php echo $data['pamong_nip']?></i></br>
                                      <i>NIK :<?php echo $data['pamong_nik']?></i>
                                    </p>
                                  </td>						
                                  <td><?php echo unpenetration($data['jabatan'])?></td>	
                                  <td class="center">
                                    <?php if($data['pamong_status'] == '1') : ?>
                                      <div title="Aktif">
                                        <center><i class='fa fa-unlock fa-lg text-yellow'></i></center>                                
                                      </div>
                                    <?php else: ?>
                                      <div title="Tidak Aktif">
                                        <center><i class='fa fa-lock fa-lg text-green'></i></center>    
                                        
                                      </div>
                                    <?php endif; ?>
                                  </td>				
                                </tr>		
                              <?php  endforeach; ?>
                            </tbody>											
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                      <div class='modal-dialog'>
                        <div class='modal-content'>            
                          <div class='modal-header'>
                            <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                            <h4 class='modal-title' id='myModalLabel'><i class='fa fa-text-width text-yellow'></i> Konfirmasi</h4>
                          </div>														
                          <div class='modal-body btn-info'>
                            Apakah Anda yakin ingin menghapus data ini?									
                          </div>
                          <div class='modal-footer'>
                            <button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-arrow-circle-o-left '></i> Batal</button>
                            <a class='btn-ok'>
                              <button type="button" class="btn btn-social btn-flat btn-danger btn-sm" id="ok-delete"><i class='fa fa-trash-o'></i> Hapus</button>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>	
                  </div>
                </div>					
              </div>		
            </div>
        </form>
    </section>
</div>

