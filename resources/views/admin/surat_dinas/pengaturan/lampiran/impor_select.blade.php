@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Pilih Lampiran Yang Akan Ditambah/Diganti
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('lampiran') }}">Daftar Lampiran</a></li>
    <li class="active">Impor Lampiran</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <div class="btn-group-vertical radius-3">
                    <button class="btn btn-social btn-sm bg-olive" id="btn-impor" title="Impor Lampiran"><i class="fa fa-upload"></i> Impor Lampiran</button>
                </div>
            @endif
        </div>
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        <div class="box-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                    <thead class="bg-gray">
                        <tr>
                            <th class="padat"><input type="checkbox" id="checkall" /></th>
                            <th class="padat">NO</th>
                            <th>NAMA</th>
                            <th>JENIS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $row)
                            <tr>
                                <td><input type="checkbox" name="id_cb[]" value='{!! json_encode($row) !!}' /></td>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $row['nama'] }}</td>
                                <td>{{ App\Models\LampiranSurat::JENIS_LAMPIRAN[$row['jenis']] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#btn-impor').prop('disabled', 1)
            $('#btn-impor').click(function() {
                formAction('mainform', '{{ ci_route('lampiran.impor_store') }}');
            })
            $('input[name="id_cb[]"]').change(function() {
                if ($('input[name="id_cb[]"]:checked').length) {
                    $('#btn-impor').prop('disabled', 0)
                } else {
                    $('#btn-impor').prop('disabled', 1)
                }
            })

        });
    </script>
@endpush
