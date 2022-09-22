@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Pengaturan Anjungan
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('anjungan_pengaturan') }}">Pengaturan Anjungan</a></li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="ip_address">Kategori Artikel</label>
                    <div class="col-sm-9">
                        <select class="form-control input-sm required artikel-multiple" name="artikel[]" multiple="multiple">
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}" {{ in_array($item->id, $anjungan_artikel) ? 'selected' : '' }}>{{ $item->kategori }}</option>    
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i
                        class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    $('.artikel-multiple').select2();
  })
</script>
@endpush