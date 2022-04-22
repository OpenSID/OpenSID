<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">SMS</h3>
    <div class="box-tools">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <div class="box-body no-padding">
    <ul class="nav nav-pills nav-stacked">
      <li {{ jecho($controller, 'daftar_kontak', 'class="active"') }}><a href="{{ route('daftar_kontak') }}"><i class="fa fa-list-alt"></i> Daftar Kontak</a></li>
      <li {{ jecho($controller, 'grup_kontak', 'class="active"') }}><a href="{{ route('grup_kontak') }}"><i class="fa fa-th"></i> Grup Kontak</a></li>
    </ul>
  </div>
</div>