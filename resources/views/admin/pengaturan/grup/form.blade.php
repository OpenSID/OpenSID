@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Grup Pengguna
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('grup') }}">Grup Pengguna</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
        <div class="box box-primary">
            <div class="box-header with-border">
                <a href="{{ ci_route('grup') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Pengaturan Grup Pengguna</a>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="group">Nama Grup</label>
                    <input name="nama" class="form-control input-sm required nama_terbatas" type="text" maxlength="20" placeholder="Nama Grup" value="{{ $grup['nama'] }}"></input>
                </div>

                <div class="form-group">
                    <label for="modul">Akses Modul</label>
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                            <thead class="bg-gray color-palette">
                                <tr>
                                    <th><input type="checkbox" id="checkall" /></th>
                                    <th colspan="2">No</th>
                                    <th>Nama Modul</th>
                                    <th>Hak Baca</th>
                                    <th>Hak Ubah</th>
                                    <th>Hak Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($moduls)
                                    @foreach ($moduls as $key => $modulActive)
                                        @php
                                            $aksesParent = $grup_akses[$modulActive->id]->akses ?? 0;
                                            $modulActive->children->each(function ($item) use (&$aksesParent, $grup_akses) {
                                                $aksesParent += $grup_akses[$item->id]->akses ?? 0;
                                            });
                                        @endphp
                                        <tr class="bg-aqua">
                                            <td class="padat"><input id="m{{ $key + 1 }}" type="checkbox" name="modul[id][]" value="{{ $modulActive->id }}" @checked($aksesParent) /></td>
                                            <td class="padat" colspan="2">{{ $key + 1 }}</td>
                                            <td>{{ SebutanDesa($modulActive->modul) }}</td>
                                            @if ($modulActive->children->isEmpty())
                                                <td class="padat">
                                                    <input class="m{{ $key + 1 }}" type="checkbox" name="modul[akses_baca][{{ $modulActive->id }}]" value="1" @checked(decbin($grup_akses[$modulActive->id]->akses ?? 0) & 1) />
                                                </td>
                                                <td class="padat">
                                                    <input class="m{{ $key + 1 }}" type="checkbox" name="modul[akses_ubah][{{ $modulActive->id }}]" value="1" @checked(decbin($grup_akses[$modulActive->id]->akses ?? 0) & 2) />
                                                </td>
                                                <td class="padat">
                                                    <input class="m{{ $key + 1 }}" type="checkbox" name="modul[akses_hapus][{{ $modulActive->id }}]" value="1" @checked(decbin($grup_akses[$modulActive->id]->akses ?? 0) & 4) />
                                                </td>
                                            @else
                                                <td colspan="3"></td>
                                            @endif
                                        </tr>
                                        @foreach ($modulActive->children as $subkey => $submodul)
                                            <tr>
                                                <td class="padat">
                                                    <input id="m{{ $key + 1 . '.' . ($subkey + 1) }}" class="m{{ $key + 1 }}" type="checkbox" name="modul[id][]" value="{{ $submodul->id }}" @checked($grup_akses[$submodul->id]->akses ?? false) />
                                                </td>
                                                <td></td>
                                                <td class="padat">{{ $key + 1 . '.' . ($subkey + 1) }}</td>
                                                <td>{{ SebutanDesa($submodul->modul) }}</td>
                                                <td class="padat">
                                                    <input class="m{{ $key + 1 }}" type="checkbox" name="modul[akses_baca][{{ $submodul->id }}]" value="1" @checked(decbin($grup_akses[$submodul->id]->akses ?? 0) & 1) />
                                                </td>
                                                <td class="padat">
                                                    <input class="m{{ $key + 1 }}" type="checkbox" name="modul[akses_ubah][{{ $submodul->id }}]" value="1" @checked(decbin($grup_akses[$submodul->id]->akses ?? 0) & 2) />
                                                </td>
                                                <td class="padat">
                                                    <input class="m{{ $key + 1 }}" type="checkbox" name="modul[akses_hapus][{{ $submodul->id }}]" value="1" @checked(decbin($grup_akses[$submodul->id]->akses ?? 0) & 4) />
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="padat" colspan="4">Data Tidak Tersedia</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if (!$view)
                <div class='box-footer'>
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                </div>
            @endif
        </div>
    </form>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            var viewOnly = "{{ $view }}";

            if (viewOnly) {
                $('input').attr('disabled', true);
            } else {
                $("input[name*='modul[id]']").change(function() {
                    var val = $(this).val();
                    var id = $(this).attr('id');
                    $('input[type=checkbox][name*="[' + val + ']"]').prop('checked', $(this).is(':checked'));
                    // Ubah suhmodul sesuai modul
                    // Cara berikut karena trigger('change') tidak jalan (?)
                    // Submodul aktif tergantung modul
                    if (this.checked) {
                        $("input." + id).removeAttr("disabled");
                        $('input[type=checkbox][id^="' + id + '."]').prop('checked', !$(this).is(':checked'));
                        $('input[type=checkbox][id^="' + id + '."]').trigger('click');
                    } else {
                        $('input[type=checkbox][id^="' + id + '."]').prop('checked', !$(this).is(':checked'));
                        $('input[type=checkbox][id^="' + id + '."]').trigger('click');
                        $("input." + id).attr("disabled", true);
                    }
                });

                $("input[name*='akses']").change(function() {
                    var name = $(this).attr('name');
                    var modul = $(this).parent().parent().find(":checkbox")[0];
                    if ($(this).is(':checked')) {
                        $(modul).prop('checked', true);
                    }
                    if (name.indexOf('akses_baca') > 0) {
                        var ubah = name.replace("baca", "ubah")
                        var hapus = name.replace("baca", "hapus")
                        if (!$(this).is(':checked')) {
                            // Pastikan akses_ubah dan akses_hapus tidak checked
                            $("input[name='" + ubah + "']").prop('checked', false);
                            $("input[name='" + hapus + "']").prop('checked', false);
                        }
                    } else if (name.indexOf('akses_ubah') > 0) {
                        var baca = name.replace("ubah", "baca")
                        var hapus = name.replace("ubah", "hapus")
                        if ($(this).is(':checked')) {
                            // Pastikan akses_baca juga checked
                            $("input[name='" + baca + "']").prop('checked', true);
                        } else {
                            // Pastikan akses_hapus tidak checked
                            $("input[name='" + hapus + "']").prop('checked', false);
                        }
                    } else if (name.indexOf('akses_hapus') > 0) {
                        var baca = name.replace("hapus", "baca")
                        var ubah = name.replace("hapus", "ubah")
                        if ($(this).is(':checked')) {
                            // Pastikan akses_baca dan akses_ubah juga checked
                            $("input[name='" + baca + "']").prop('checked', true);
                            $("input[name='" + ubah + "']").prop('checked', true);
                        }
                    }
                });

                $("input[name*='modul[id]']").each(function(index) {
                    var id = $(this).attr('id');
                    if (this.checked) {
                        $("input." + id).removeAttr("disabled");
                        $('input[type=checkbox][id^="' + id + '."]').prop('checked', !$(this).is(':checked'));
                    } else {
                        $('input[type=checkbox][id^="' + id + '."]').prop('checked', $(this).is(':checked'));
                        $("input." + id).attr("disabled", true);
                    }
                });

                $("input[name*='akses']").each(function(index) {
                    var modul = $(this).parent().parent().find(":checkbox")[0];
                    if ($(this).is(':checked')) {
                        $(modul).prop('checked', true);
                    }
                });
            }
        });
    </script>
@endpush
