@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_validasi')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small> {{ $action }} Data Kesehatan Ibu dan Anak</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('stunting/kia') }}">Kesehatan Ibu dan Anak</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    @include('admin.stunting.widget')

    <div class="row">
        @include('admin.stunting.navigasi')

        <div class="col-md-9 col-lg-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ ci_route('stunting.kia') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Kesehatan Ibu dan Anak
                    </a>
                </div>
                {!! form_open($formAction, 'class="form-horizontal" id="validasi"') !!}
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nomor KIA</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-sm required" name="no_kia" placeholder="Masukkan nomor KIA" value="{{ $kia->no_kia }}" />
                            <input type="text" class="form-control input-sm" name="no_kia_lama" placeholder="Masukkan nomor KIA" style="display: none" value="{{ $kia->no_kia }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Ibu</label>
                        <div class="col-sm-9">
                            <select class="form-control required input-sm select2" id="ibu" name="id_ibu" style="width:100%;" data-placeholder="-- Cari NIK / Nama Ibu --">
                                <option value="">-- Cari NIK / Nama Ibu --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Anak</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm select2" id="anak" name="id_anak" style="width:100%;" {{ $kia->ibu_id == null ? 'disabled' : '' }}>
                                <option value="">-- Cari NIK / Nama Anak --</option>
                                @foreach ($anak as $data)
                                    @if ($data)
                                        <option value="{{ $data->id }}" @selected($kia->anak_id == $data->id)>NIK :
                                            {{ $data->nik . ' - ' . $data->nama }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Hari Perkiraan Lahir</label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                {{ $kia->anak_id == null ? '' : 'disabled' }}
                                class="form-control input-sm datepicker"
                                id="perkiraan_lahir"
                                name="perkiraan_lahir"
                                placeholder="Masukkan hari perkiraan lahir"
                                value="{{ $kia->hari_perkiraan_lahir }}"
                            />
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                        Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                        Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#ibu').select2({
            ajax: {
                url: "{{ ci_route('stunting.getIbu') }}",
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term || '',
                        page: params.page || 1,
                    };
                },
                cache: true
            },
            placeholder: function() {
                $(this).data('placeholder');
            },
            minimumInputLength: 0,
            allowClear: true,
            escapeMarkup: function(markup) {
                return markup;
            },
        });

        var idIbu = "{{ $kia->ibu_id }}";
        if (idIbu != undefined) {
            var txtIbu = "{{ $ibu_text }}";
            var data = {
                id: idIbu,
                text: txtIbu
            }
            console.log(data);
            var $select = $('#ibu');
            var option = new Option(data.text, data.id, true, true);
            $select.append(option).trigger('change');

            $select.trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            });
        }

        $('#anak').on('change', function() {
            if (this.value == "") {
                $('#perkiraan_lahir').prop("disabled", false);
            } else {
                $('#perkiraan_lahir').prop("disabled", true);
            }
        });

        $("#anak").select2();

        $(function() {
            $('#ibu').on('change', function() {
                var ibu = $(this).val();
                if (ibu) {
                    $.ajax({
                        url: "{{ ci_route('stunting.getAnak') }}",
                        type: "GET",
                        data: {
                            ibu: ibu,
                        },
                        success: function(data) {
                            $('#anak').empty();
                            if (Object.keys(data).length === 0) {
                                $('#anak').prop('disabled', true);
                            } else {
                                $('#anak').prop('disabled', false);
                                $('#anak').append($('<option>', {
                                    value: '',
                                    text: '-- Cari NIK / Nama Anak --'
                                }));
                                $.each(data, function(key, value) {
                                    $('#anak').append($('<option>', {
                                        value: value.id,
                                        text: 'NIK: ' + value.nik + ' - ' + value.nama
                                    }));
                                });
                            }
                        }
                    });
                } else {
                    $('#anak').empty();
                    $('#anak').prop('disabled', true);
                }
            });
        });
    </script>
@endpush
