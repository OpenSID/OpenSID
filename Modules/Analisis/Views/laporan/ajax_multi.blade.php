<script>
    (function() {
        var opsi_width = (parseInt($('#opsi').width()) / 2) - 10;
        $('#opsi div').css('width', opsi_width);
        $('#opsi label').css('width', opsi_width - 36);
        $('#opsi input:checked').parent().css({
            'background': '#c9cdff',
            'border': '1px solid #7a82eb'
        });
        $('#opsi input').change(function() {
            if ($(this).is(':checked')) {
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
        $('#opsi label').click(function() {
            $(this).prev().trigger('click');
        })

    })();
</script>
<!-- TODO: Pindahkan ke external css -->
<style>
    #opsi div {
        margin: 1px 0;
        background: #fafafa;
        border: 1px solid #ddd;
    }

    #opsi input {
        vertical-align: middle;
        margin: 0px 2px;
    }

    #opsi label {
        padding: 4px 10px 0px 2px;
        font-size: 11px;
        line-height: 12px;
        font-weight: normal;
    }
</style>
<form method="post" action="{{ $form_action }}">
    <div class='modal-body'>
        <input type="hidden" name="rt" value="">
        <div class="table-responsive" style="height:60vh;">
            @php
                $jumlah = count($main);
                $last = '';
            @endphp
            @if ($jumlah != 0)
                <table class="table table-bordered dataTable nowrap">
                    @foreach ($main as $data)
                        @if ($data['pertanyaan'] != $last)
                            <tr>
                                <td><label>{{ $data['pertanyaan'] }}</label></td>
                            </tr>
                            <tr>
                                <td id="opsi">
                                    <div style="display:inline-block;">
                                        <input type="checkbox" name="id_cb[]" value="{{ $data['id_jawaban'] }}" @checked($data['cek'])>
                                        <label>{{ $data['kode_jawaban'] . '. ' . $data['jawaban'] }}</label>
                                    </div>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td id="opsi">
                                    <div style="display:inline-block;">
                                        <input type="checkbox" name="id_cb[]" value="{{ $data['id_jawaban'] }}" @checked($data['cek'])>
                                        <label>{{ $data['kode_jawaban'] . '. ' . $data['jawaban'] }}</label>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        @php $last = $data['pertanyaan'] @endphp
                    @endforeach
                </table>
            @else
                <div class="text-center">
                    <h4>Tidak Ada Data...</h4>
                </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left" data-dismiss="modal"><i class='fa fa-times'></i> Batal</button>
        @if ($jumlah != 0)
            <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i>
                Simpan</button>
        @endif
    </div>
</form>
