<script>
    $(function() {
        var cd_item_width = (parseInt($('#cd_item').width()) / 2);
        var label_width = (parseInt($('#cd_item').width()) / 2) - 32;
        $('#cd_item div').css('clear', 'both');
        $('#cd_item div').css('float', 'left');
        $('#cd_item div').css('width', cd_item_width);
        $('#cd_item label').css('width', label_width);
        $('#cd_item input:checked').parent().css({
            'background': '#c9cdff',
            'border': '1px solid #7a82eb'
        });
        $('#cd_item input').change(function() {
            if ($(this).is('input:checked')) {
                $('#cd_item input').parent().css({
                    'background': '#ffffff',
                    'border': '1px solid #ddd'
                });
                $('#cd_item input:checked').parent().css({
                    'background': '#c9cdff',
                    'border': '1px solid #7a82eb'
                });
                $(this).parent().css({
                    'background': '#c9cdff',
                    'border': '1px solid #7a82eb'
                });
            } else {
                $(this).parent().css({
                    'background': '#fafafa',
                    'border': '1px solid #ddd'
                });
            }
        });
        $('#cd_item label').click(function() {
            $(this).prev().trigger('click');
        })
        $('#kirim button').click(function() {
            $('#' + 'child').submit();
        })
    });
</script>
<!-- TODO: Pindahkan ke external css -->
<style>
    #cd_item div {
        margin: 1px 0;
        background: #fafafa;
        border: 1px solid #ddd;
    }

    #cd_item input {
        vertical-align: middle;
        margin: 0px 2px;
    }

    #cd_item label {
        padding: 4px 10px 0px 2px;
        font-size: 11px;
        line-height: 14px;
        font-weight: normal;
    }

    table.head {
        font-size: 14px;
        font-weight: bold;
    }
</style>
<form id="child" action="{{ $form_action }}" method="POST">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-danger">
                    <div class="box-body">
                        <div class="table-responsive" style="height:60vh;">
                            <table class="table table-bordered dataTable nowrap">
                                @foreach ($list_jawab as $data)
                                    <tr>
                                        <td><label class='tanya'>{{ $data['pertanyaan'] }}</label></td>
                                    </tr>
                                    @if ($data['id_tipe'] == 1)
                                        <tr>
                                            <td id="cd_item">
                                                @foreach ($data['parameter_respon'] as $data2)
                                                    <div>
                                                        <input type="radio" name="rb[{{ $data['id'] }}]" value="{{ $data['id'] }}.{{ $data2['id_parameter'] }}" @checked($data2['cek'])>
                                                        <label>{{ $data2['kode_jawaban'] }}. {{ $data2['jawaban'] }}</label>
                                                    </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @elseif ($data['id_tipe'] == 2)
                                        @foreach ($data['parameter_respon'] as $data2)
                                            <tr>
                                                <td id="cd_item">
                                                    <div>
                                                        <input type="checkbox" name="cb[{{ $data2['id_parameter'] }}]" value="{{ $data2['id_parameter'] }}.{{ $data['id'] }}" @checked($data2['cek'])>
                                                        <label>{{ $data2['kode_jawaban'] }}. {{ $data2['jawaban'] }}</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @elseif ($data['id_tipe'] == 3)
                                        @if ($data['parameter_respon'])
                                            @php $data2 = $data['parameter_respon']; @endphp
                                            <tr>
                                                <td id="">
                                                    <div style="display:inline-block;"><input name="ia[{{ $data['id'] }}]" type="text" class="inputbox number" size="10" value="{{ $data2['jawaban'] }}" /></div>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td id="">
                                                    <div style="display:inline-block;"><input name="ia[{{ $data['id'] }}]" type="text" class="inputbox number" size="10" value="" /></div>
                                                </td>
                                            </tr>
                                        @endif
                                    @elseif ($data['id_tipe'] == 4)
                                        @if ($data['parameter_respon'])
                                            @php $data2 = $data['parameter_respon'] @endphp
                                            <tr>
                                                <td id="">
                                                    <div style="width:100%"><input name="it[{{ $data['id'] }}]" type="text" class="form-control input-sm" value="{{ $data2['jawaban'] }}" /></div>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td id="">
                                                    <div style="width:100%"><input name="it[{{ $data['id'] }}]" type="text" class="form-control input-sm" value="" /></div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class='fa fa-times'></i> Batal</button>
                        <button type="submit" class="btn btn-social btn-info btn-sm" id="kirim"><i class='fa fa-check'></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</form>
