@include('admin.layouts.components.datetime_picker')
@extends('admin.layouts.index')

@section('title')
<h1>
  Jam Kerja Kehadiran
  <small>{{ $action }} Data</small>
</h1>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('kehadiran_jam_kerja') }}">Daftar Jam Kerja</a></li>
<li class="active">{{ $action }} Data</li>
@endsection

@section('content')

@include('admin.layouts.components.notifikasi')

<div class="box box-info">
	<div class="box-header with-border">
		<a href="{{ route('kehadiran_jam_kerja') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Hari Libur</a>
	</div>
	{!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
		<div class="box-body">
			<div class="form-group">
				<label class="col-sm-3 control-label" for="jam_mulai">Jam Masuk</label>
				<div class="col-sm-7">
					<input class="form-control input-sm required" placeholder="Jam Masuk" type="text" id="jammenit_1" name="jam_masuk" value="{{ date('H:i', strtotime($kehadiran_jam_kerja->jam_masuk)) }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="jam_akhir">Jam Keluar</label>
				<div class="col-sm-7">
					<input class="form-control input-sm required" placeholder="Jam Keluar" type="text" id="jammenit_2" name="jam_keluar" value="{{ date('H:i', strtotime($kehadiran_jam_kerja->jam_keluar)) }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
				<div class="col-sm-7">
					<textarea name="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="3" style="resize:none;">{{ $kehadiran_jam_kerja->keterangan }}</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="status">Status</label>
				<div class="btn-group col-sm-7" data-toggle="buttons">
					<label id="sx3" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ jecho($kehadiran_jam_kerja->status, '1', 'active') }}">
						<input type="radio" name="status" class="form-check-input" type="radio" value="1"  {{ jecho($kehadiran_jam_kerja->status, '1', 'checked') }} > Hari Kerja
					</label>
					<label id="sx4" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label  {{ jecho($kehadiran_jam_kerja->status != '1', true, 'active') }}">
						<input type="radio" name="status" class="form-check-input" type="radio" value="0" {{ jecho($kehadiran_jam_kerja->status != '1', true, 'checked') }} > Hari Libur
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