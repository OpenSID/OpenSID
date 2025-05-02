<form action="{{ ci_route('web.reset', $cat) }}" method="post">
    <div class='modal fade' id='reset-hit' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title' id='myModalLabel'></i> Reset Hit</h4>
                </div>
                <div class='modal-body'>
                    <div class="form-group">
                        <code>Lakukan hapus hit ini jika artikel statis di menu atas website anda terkena kunjungan tak terduga, seperti robot(crawler), yang berlebihan. </code><br><br>
                        <label for="hit">Reset Hit</label>
                        <select class="form-control input-sm" required name="hit" width="100%">
                            <option value="">Pilih persen hit yang akan dihapus</option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i * 10 }}">{{ $i * 10 . '%' }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! batal() !!}
                    <button type="submit" class="btn btn-social btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
