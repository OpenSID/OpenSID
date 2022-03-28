@extends('admin.layouts.index')

@section('title')
<h1>
  Gawai Kehadiran
  <small>Ubah Data</small>
</h1>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('gawai') }}">Daftar Gawai</a></li>
<li class="active">Ubah Data</li>
@endsection

@section('content')

@include('admin.layouts.components.notifikasi')

<div class="box box-info">
	<div class="box-header with-border">
		<a href="{{ route('gawai') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Gawai</a>
	</div>
	{!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
		<div class="box-body">
			<div class="form-group">
				<label class="col-sm-3 control-label" for="ip_address">IP Address</label>
				<div class="col-sm-7">
					<input class="form-control input-sm ip_address required" type="text" placeholder="IP address statis untuk Gawai" name="ip_address" value="{{ $gawai->ip_address }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="ip_address">Mac Address</label>
				<div class="col-sm-7">
					<input class="form-control input-sm mac_address" type="text" placeholder="00:1B:44:11:3A:B7" name="mac_address" value="{{ $gawai->mac_address }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
				<div class="col-sm-7">
					<textarea name="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="3" style="resize:none;">{{ $gawai->keterangan }}</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="status">Status</label>
				<div class="btn-group col-sm-7" data-toggle="buttons">
					<label id="sx3" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ jecho($gawai->status, '1', 'active') }}">
						<input type="radio" name="status" class="form-check-input" type="radio" value="1"  {{ jecho($gawai->status, '1', 'checked') }} > Aktif
					</label>
					<label id="sx4" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label  {{ jecho($gawai->status, '0', 'active') }}">
						<input type="radio" name="status" class="form-check-input" type="radio" value="0" {{ jecho($anjungan->status, '0', 'checked') }} > Tidak Aktif
					</label>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
			<button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
		</div>
	</form>
</div>

@endsection