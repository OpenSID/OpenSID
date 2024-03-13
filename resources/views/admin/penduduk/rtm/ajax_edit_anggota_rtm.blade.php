@if (can('u'))
    @include('admin.layouts.components.form_modal_validasi')
    <form action="{{ $form_action }}" method="post" id="validasi">
        <div class='modal-body'>
            <div class="form-group">
                <label for="rtm_level">Hubungan</label>
                <select name="rtm_level" class="form-control input-sm required">
                    @foreach ($hubungan as $key => $data)
                        <option value="{{ $key }}" @selected($key == $main['rtm_level'])>{{ $data }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
            {!! batal() !!}
            <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
        </div>
    </form>
@endif
