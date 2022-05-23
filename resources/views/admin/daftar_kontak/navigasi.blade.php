<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">SMS</h3>
    <div class="box-tools">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <div class="box-body no-padding">
    <ul class="nav nav-pills nav-stacked">
      <li class="@active($navigasi === 'penduduk')"><a href="{{ route('daftar_kontak/penduduk') }}"><i class="fa fa-list-alt"></i> Daftar Kontak Penduduk</a></li>
      <li class="@active($navigasi === 'eksternal')"><a href="{{ route('daftar_kontak') }}"><i class="fa fa-list-alt"></i> Daftar Kontak Eksternal</a></li>
      <li class="@active($controller === 'grup_kontak')"><a href="{{ route('grup_kontak') }}"><i class="fa fa-th"></i> Grup Kontak</a></li>
    </ul>
  </div>
</div>