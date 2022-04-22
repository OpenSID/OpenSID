@if (session('success'))
<div id="notifikasi" class="alert alert-success alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h4><i class="icon fa fa-check"></i> Berhasil</h4>
  <p>{!! session('success') !!}</p>
</div>
@endif

@if (session('error'))
<div id="notifikasi" class="alert alert-danger alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h4><i class="icon fa fa-ban"></i> Gagal</h4>
  <p>{!! session('error') !!}</p>
</div>
@endif

@if(session('warning'))
<div id="notifikasi" class="alert alert-warning alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h4><i class="icon fa fa-warning"></i> Peringatan</h4>
  <p>{!! session('warning') !!}</p>
</div>
@endif

@if(session('information'))
<div id="notifikasi" class="alert alert-info alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h4><i class="icon fa fa-info"></i> Informasi</h4>
  <p>{!! session('information') !!}</p>
</div>
@endif