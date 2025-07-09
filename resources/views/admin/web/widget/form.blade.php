@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        <h1>Widget</h1>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('web_widget') }}"> Widget</a></li>
    <li class="active">{{ $aksi }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ ci_route('web_widget') }}" class="btn btn-social  btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Widget">
                        <i class="fa fa-arrow-circle-left "></i>Kembali Ke Widget
                    </a>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="judul">Judul Widget</label>
                        <div class="col-sm-6">
                            <input id="judul" name="judul" class="form-control input-sm strip_tags judul required" type="text" placeholder="Judul Widget" value="{{ $widget['judul'] }}"></input>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="judul">Gambar Widget</label>
                        <div class="col-sm-6">
                            @if (is_file(LOKASI_GAMBAR_WIDGET . $widget['foto']))
                                <img class="img-responsive" src="{{ to_base64(LOKASI_GAMBAR_WIDGET . $widget['foto']) }}" alt="Gambar Utama Widget">
                            @else
                                <img class="img-responsive" src="{{ to_base64('assets/images/404-image-not-found.jpg') }}" alt="Gambar Utama Widget" />
                            @endif
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="file_path">
                                <input type="file" class="hidden" id="file" name="foto" accept=".jpg,.jpeg,.png,.gif">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info " id="file_browser"><i class="fa fa-search"></i></button>
                                </span>
                                <span class="input-group-addon" style="background-color: red; border: 1px solid #ccc;">
                                    <input type="checkbox" title="Centang Untuk Hapus Gambar" name="hapus_foto" value="hapus">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="jenis">Jenis Widget</label>
                        <div class="col-sm-6">
                            <select id="jenis_widget" name="jenis_widget" class="form-control input-sm select2 required">
                                <option value="">-- Pilih Jenis Widget --</option>
                                <option value="2" @selected($widget['jenis_widget'] === 2)>Statis</option>
                                <option value="3" @selected($widget['jenis_widget'] === 3)>Dinamis</option>
                            </select>
                        </div>
                    </div>
                    @php
                        if ($widget['jenis_widget'] && $widget['jenis_widget'] !== 1 && $widget['jenis_widget'] !== 2) {
                            $dinamis = true;
                        }
                    @endphp

                    <div id="dinamis" class="form-group" @if (!$dinamis) style="display:none;" @endif>
                        <label class="col-sm-3 control-label" for="isi-dinamis">Kode Widget</label>
                        <div class="col-sm-6">
                            <textarea style="resize:none;height:150px;" id="isi-dinamis" name="isi-dinamis" class="form-control input-sm required" placeholder="Kode Widget">{{ $widget['isi'] }}</textarea>
                        </div>
                    </div>
                    @php
                        if ($widget['jenis_widget'] && $widget['jenis_widget'] === 2) {
                            $statis = true;
                        }
                    @endphp
                    <div id="statis" class="form-group">
                        <label class="col-sm-3 control-label" for="isi-statis">Nama File Widget (.php)</label>
                        <div class="col-sm-6">
                            @if ($list_widget)
                                <select id="isi-statis" name="isi-statis" class="form-control input-sm select2 required">
                                    <option value="">-- Pilih Widget --</option>
                                    @foreach ($list_widget as $list)
                                        <option value="{{ $list }}" {{ selected($list, $widget['isi']) }}>
                                            {{ $list }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <span class="help-block"><code>Widget tidak tersedia atau sudah ditambahkan semua (desa/widgets atau desa/themes/nama_tema/resorces/views/widgets)</code></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class='box-footer'>
                    <button type='reset' class='btn btn-social  btn-danger btn-sm'><i class='fa fa-times'></i>
                        Batal</button>
                    <button type='submit' class='btn btn-social  btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#jenis_widget").change(function() {
                var selectedValue = $(this).val();
                var dinamis = $("#dinamis");
                var statis = $("#statis");
                var isiStatisInput = $("#isi-statis");
                var isiDinamisInput = $("#isi-dinamis");

                if (selectedValue == 2) {
                    dinamis.hide();
                    statis.show();
                    isiStatisInput.addClass("required");
                    isiDinamisInput.removeClass("required");
                } else if (selectedValue == 3) {
                    dinamis.show();
                    statis.hide();
                    isiStatisInput.removeClass("required");
                    isiDinamisInput.addClass("required");
                } else {
                    dinamis.hide();
                    statis.hide();
                    isiStatisInput.removeClass("required");
                    isiDinamisInput.removeClass("required");
                }
            });

            $("#jenis_widget").trigger("change");
        });
    </script>
@endpush
