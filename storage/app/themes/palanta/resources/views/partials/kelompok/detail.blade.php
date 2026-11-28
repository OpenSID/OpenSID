@extends('theme::layouts.right-sidebar')

@section('content')
@include('theme::commons.asset_sweetalert')

<div class="heading-module l-flex">
	<div class="heading-module-inner l-flex">
		<i class="fa fa-group"></i><h1>Data Kelompok</h1>
	</div>
</div>

<div id="kelompok-wrapper">
</div>
@endsection

@push('scripts')    
<script>
  $(document).ready(function() {
    var route = "{{ route('api.' . $tipe . '.detail', ['slug' => $slug]) }}";
      $.ajax({
          url: route,
          method: 'GET',
            beforeSend: function() {
              const kelompokList = document.getElementById('kelompok-wrapper');
              kelompokList.innerHTML = `@include('theme::commons.loading')`;
            },
            success: function (data) {
              var detail = data.data.attributes;
              var pengurus = detail.pengurus;
              var tipe = detail.tipe;
              var gambar_desa = `{{ gambar_desa('${detail.logo}') }}`;     
              var detailElemen = `<div style="margin:20px 0 20px;">
                <h3 style="margin:0 0 10px;">Rinci Data ${tipe}</h3>                            
                <div class="table-responsive content">
                  <table class="table table-striped">
                    <tbody>
                      <tr>
                        <td width="20%">Nama ${tipe}</td>
                        <td width="1%">:</td>
                        <td>${detail.nama}</td>
                        <td width="20%" rowspan="5" style="text-align: center; vertical-align: middle;">
                          <img src="${gambar_desa}" alt="Logo ${tipe}" class="w-full">
                        </td>
                      </tr>
                      <tr>
                        <td>Kode ${tipe}</td>
                        <td>:</td>
                        <td>${detail.kode}</td>
                      </tr>
                      <tr>
                        <td>Kategori ${tipe}</td>
                        <td>:</td>
                        <td>${detail.kategori}</td>
                      </tr>
                      <tr>
                        <td>No. SK Pendirian</td>
                        <td>:</td>
                        <td>${detail.no_sk_pendirian}</td>
                      </tr>
                      <tr>
                        <td>Keterangan</td>
                        <td>:</td>
                        <td>${detail.keterangan}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>  
              </div>`;

              var pengurusElemen = `<div style="margin:0 0 20px;">
              <h3 style="margin:0 0 10px;">Daftar Pengurus</h3>
              <div class="table-responsive">
                <table width="100%" class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width:50px;text-align:center;">No</th>
                      <th>Nama</th>
                      <th>Jabatan</th>
                      <th>Alamat</th>                      
                    </tr>
                  </thead>
                  <tbody>`;

              pengurus.forEach((data, key) => {
                pengurusElemen += `
                    <tr>
                      <td>${key + 1}</td>
                      <td>${data.nama_jabatan}</td>
                      <td nowrap>${data.nama_penduduk}</td>
                      <td>${data.alamat_lengkap}</td>
                    </tr>`;
              });

              pengurusElemen += `</tbody>
                  </table>
                </div>
              </div>`;

              var anggotaElemen = `
              <div style="margin:0 0 20px;">
                <h3 style="margin:0 0 10px;">Daftar Anggota</h3>
                <div class="table-responsive">
                  <table width="100%" id="table-anggota" class="table table-striped">
                    <thead>
                      <tr>
                        <th style="width:50px;text-align:center;">No</th>
                        <th>No. Anggota</th>
                        <th>Nama</th>                        
                        <th>Alamat</th>
                        <th>L/P</th>
                      </tr>
                    </thead>

                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>`;

              $('#kelompok-wrapper').html(detailElemen + pengurusElemen + anggotaElemen);

              anggotaTable();
            },
            error: function(xhr) {
              console.error('AJAX Error:', xhr.responseText);
              Swal.fire('Error', 'Terjadi kesalahan saat memuat data.', 'error');
            }
          });

          const anggotaTable = () => {
            $('#table-anggota').DataTable({
              processing: true,
              serverSide: true,
              autoWidth: false,
              ordering: true,
              ajax: {
                url: `{{ route('api.' . $tipe . '.anggota', ['slug' => $slug]) }}`,
                method: 'POST',
                data: row => ({
                  "page[size]": row.length,
                  "page[number]": (row.start / row.length) + 1,
                  "filter[search]": row.search.value,
                  "sort": `${row.order[0]?.dir === "asc" ? "" : "-"}${row.columns[row.order[0]?.column]?.name}`
                }),
                dataSrc: json => {
                  json.recordsTotal = json.meta.pagination.total;
                  json.recordsFiltered = json.meta.pagination.total;
                  return json.data;
                },
                error: function(xhr) {
                  console.error('AJAX Error:', xhr.responseText);
                  Swal.fire('Error', 'Terjadi kesalahan saat memuat data.', 'error');
                }
              },
              columnDefs: [
                { targets: '_all', className: 'text-nowrap' },
              ],
              columns: [
                { data: null, searchable: false, orderable: false },
                { data: 'no_anggota', name: 'no_anggota', render: (data, type, row) => row.attributes.no_anggota },
                { data: 'alamat', name: 'alamat', render: (data, type, row) => row.attributes.nama_penduduk },
                { data: 'jenis_kelamin', name: 'jenis_kelamin', render: (data, type, row) => row.attributes.sex },
              ],
              drawCallback: function(settings) {
                var api = this.api();
                api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
                  cell.innerHTML = api.page.info().start + i + 1;
                });
              }
            });
          }
  });
</script>
@endpush