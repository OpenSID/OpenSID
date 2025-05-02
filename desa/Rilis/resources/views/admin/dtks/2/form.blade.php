@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Formulir DTKS {{ \App\Enums\Dtks\DtksEnum::VERSION_LIST[$dtks->versi_kuisioner] }}
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('dtks') }}">DTKS</a></li>
    <li class="active">Formulir DTKS</li>
@endsection

@section('content')
    <div class="row">
        @include('admin.dtks.2.form_isian')
    </div>
@endsection

@push('scripts')
    @include('admin.layouts.components.ajax_dtks')
    {{--
        karena ada kode $('#tabel1').DataTable(); pada script.js baris 16
        sedangkan file admin.layouts.index.blade.php tidak meload datatable
        kemudian karena file admin.layouts.components.asset_datatables.blade.php meload
        script.js lagi sehingga ada beberapa double event yg terdaftar
        menyebabkan beberapa interaksi dengan kamera rusak ketika mau mengambil foto
        --}}
    <script src="{{ asset('bootstrap/js/jquery.dataTables.min.js') }}"></script>

    <script>
        // BASE_URL dipanggil di main-camera.js sedangkan di script.js menggunakan huruf kecil bukannya huruf kapital
        var BASE_URL = file_ini.replace('assets/js/script.js', '');

        $(document).ready(function() {
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            // Select2 dengan fitur pencarian karena tidak ngeload /js/custom.select2.js
            $('.select2').select2({
                width: '100%',
                dropdownAutoWidth: true
            });

            // Select2 dengan fitur pencarian dan boleh isi sendiri
            $('.select2-tags').select2({
                tags: true
            });
            $('.select2-tags').siblings().eq(2).hide();
        });
    </script>
@endpush
