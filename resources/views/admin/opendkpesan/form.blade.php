@extends('admin.layouts.index')

@section('title')
<h1>
  Pesan
  <small>{{ $action }} Data</small>
</h1>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ site_url($controller) }}">Pesan</a></li>
<li class="active">{{ $action }} Data</li>
@endsection

@section('content')
@include('admin.layouts.components.notifikasi')
@include('admin.layouts.components.asset_tinymce')

<div class="box box-info">
  <div class="box-header with-border">
    <a href="{{ site_url('opendk_pesan') }}" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
      <i class="fa fa-arrow-circle-left "></i>Kembali ke Pesan
    </a>
  </div>
  <div class="box-body">
    {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
      <div class="box-body">
        <div class="form-group">
          <label class="col-sm-3 control-label">Judul Pesan</label>
          <div class="col-sm-8">
              <input type="text" class="form-control required" id="judul" name="judul" placeholder="Judul Pesan" value=""/>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Isi Pesan</label>
          <div class="col-sm-8">
            <textarea class="form-control" id="pesan" name="pesan"></textarea>
          </div>
        </div>
      </div>
      <div class="box-footer">
        <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
        <button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Kirim</button>
      </div>
    </form>

  </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
  $(function() {
    tinymce.init(
    {
      selector: 'textarea',
      height: 500,
      theme: 'silver',
      plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor code"
      ],
      toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
      toolbar2: "| link unlink anchor | image media | forecolor backcolor | print preview code | fontselect fontsizeselect",
      image_advtab: true ,
      templates: [
      {
        title: 'Test template 1', content: 'Test 1'
      },
      {
        title: 'Test template 2', content: 'Test 2'
      }],
      content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tinymce.com/css/codepen.min.css'
      ],
      relative_urls : false,
      remove_script_host : false
    });
  });
</script>
@endpush