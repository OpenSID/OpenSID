<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">SMS</h3>
    <div class="box-tools">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <div class="box-body no-padding">
    <ul class="nav nav-pills nav-stacked">
      <li><a href="{{ route('sms') }}"><i class="fa fa-inbox"></i> Kotak Masuk</a></li>
      <li><a href="{{ route('sms/outbox') }}"><i class="fa fa-pencil"></i> Tulis Pesan</a></li>
      <li><a href="{{ route('sms/sentitem') }}"><i class="fa fa-envelope-o"></i> Pesan Terkirim</a></li>
      <li><a href="{{ route('sms/pending') }}"><i class="fa fa-file-text-o"></i> Pesan Tertunda</a></li>
    </ul>
  </div>
</div>
<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">Hubung Warga</h3>
    <div class="box-tools">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <div class="box-body no-padding">
    <ul class="nav nav-pills nav-stacked">
      <li {{ jecho($navigasi, 'kirim', 'class="active"') }}><a href="{{ route('sms/kirim') }}"><i class="fa fa-inbox"></i> Kirim Pesan Grup</a></li>
      <li {{ jecho($navigasi, 'arsip', 'class="active"') }}><a href="{{ route('sms/arsip') }}"><i class="fa fa-file-archive-o"></i> Arsip Hubung Warga</a></li>
    </ul>
  </div>
</div>