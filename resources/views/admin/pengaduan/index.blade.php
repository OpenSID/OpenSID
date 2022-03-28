@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
<h1>
  Pengaduan Perangkat Desa
</h1>
@endsection

@section('breadcrumb')
<li class="active">Pengaduan Perangkat Desa</li>
@endsection

@section('content')

@include('admin.layouts.components.notifikasi')

<div class="box box-info">
  <div class="box-header with-border">
  </div>
  <div class="box-body">
    {!! form_open(null, 'id="mainform" name="mainform"') !!}
    <div class="table-responsive">
      <table class="table table-bordered table-hover" id="tabeldata">
        <thead>
          <tr>
            <th class="padat">NO</th>
            <th class="padat">AKSI</th>
            <th>WAKTU</th>
            <th>PELAPOR</th>
            <th>TERLAPOR</th>
            <th>KETERANGAN</th>
          </tr>
        </thead>
      </table>
    </div>
    </form>
  </div>
</div>

@include('admin.layouts.components.konfirmasi_hapus')

@endsection

@push('scripts')
<script>
  $(document).ready(function () {
    var TableData = $('#tabeldata').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: "{{ route('absensi_pengaduan.datatables') }}",
      columns: [
        { data: 'DT_RowIndex', class: 'padat', searchable: false, orderable: false },
        { data: 'aksi', class: 'aksi', searchable: false, orderable: false},
        { data: 'waktu', name: 'waktu', searchable: true, orderable: true },
        { data: 'penduduk', name: 'penduduk', searchable: true, orderable: true },
        { data: 'pamong', name: 'pamong', searchable: true, orderable: true },
        { data: 'keterangan', name: 'keterangan', searchable: true, orderable: true },
      ],
      order: [[ 2, 'asc' ]]
    });
  });
</script>
@endpush
