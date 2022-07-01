@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Daftar Surat
        <small>{{ $action }} Pengaturan Surat</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('surat_master') }}">Daftar Surat</a></li>
    <li class="active">{{ $action }} Pengaturan Surat</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    {!! form_open($formAction, 'id="validasi" enctype="multipart/form-data"') !!}
    <input type="hidden" id="id_surat" value="{{ $suratMaster->id }}">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#pengaturan-umum" data-toggle="tab">Umum</a></li>
            <li><a href="#template-surat" data-toggle="tab">Template</a></li>
            <li><a href="#kode-isian" data-toggle="tab">Kode Isian</a></li>
        </ul>
        <div class="tab-content">

            @include('admin.pengaturan_surat.umum')

            @if (in_array($suratMaster->jenis, [1, 2]))
                @include('admin.pengaturan_surat.rtf')
            @else
                @include('admin.pengaturan_surat.tinymce')
            @endif

            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i
                        class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
        </div>
    </div>
    </form>

    @include('admin.pengaturan_surat.info')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            syarat($('input[name=mandiri]:checked').val());
            $('input[name="mandiri"]').change(function() {
                syarat($(this).val());
            });
        });

        function masaBerlaku() {
            var masa_berlaku = $('#masa_berlaku').val();
            if (masa_berlaku < 1) {
                $('#masa_berlaku').val(1);
            } else if (masa_berlaku > 31) {
                $('#masa_berlaku').val(31);
            }
        }

        function syarat(tipe) {
            (tipe == '1' || tipe == null) ? $('#syarat').show(): $('#syarat').hide();
        }

        function reset_form() {
            var mandiri = "{{ $surat_master['mandiri'] }}";

            $(".tipe").removeClass("active");
            $("input[name=mandiri").prop("checked", false);
            if (mandiri) {
                $("#m1").addClass('active');
                $("#n1").addClass('active');
                $("#o1").addClass('active');
                $("#g1").prop("checked", true);
                $("#q1").prop("checked", true);
                $("#bg1").prop("checked", true);
            } else {
                $("#m2").addClass('active');
                $("#n2").addClass('active');
                $("#o2").addClass('active');
                $("#g2").prop("checked", true);
                $("#q2").prop("checked", true);
                $("#bg2").prop("checked", true);
            }
            syarat($('input[name=mandiri]:checked').val());
        };
    </script>
@endpush
