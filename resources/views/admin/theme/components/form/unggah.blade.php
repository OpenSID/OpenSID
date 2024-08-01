<div class="box box-info">
    <div class="box-header with-border text-center">
        <b>{{ $value['judul'] }}</b>
    </div>
    <div class="box-body box-profile text-center">
        @php $default = $value['default']; @endphp
        @if ($default && file_exists(FCPATH . $default))
            <img id="preview-{{ $value['key'] }}" src="{{ base_url($default) }}" alt="{{ $value['judul'] }}" width="100%" />
        @else
            <img id="preview-{{ $value['key'] }}" src="{{ asset('images/404-image-not-found.jpg') }}" alt="{{ $value['judul'] }}" width="100%" />
        @endif
        <p class="text-muted text-center text-red">(Kosongkan, jika tidak ada perubahan)</p>
        <div class="input-group">
            <input type="text" class="form-control input-sm" id="file_path-{{ $value['key'] }}" name="{{ $value['key'] }}" />
            <input type="file" class="hidden" id="file-{{ $value['key'] }}" name="{{ $value['key'] }}" accept=".jpg,.jpeg,.png,.gif" />
            <span class="input-group-btn">
                <button type="button" class="btn btn-info btn-flat btn-sm" id="file_browser-{{ $value['key'] }}"><i class="fa fa-search"></i>&nbsp;</button>
            </span>
        </div>
        <div class="form-group" style="margin-top: 10px;">
            <input type="text" class="form-control input-sm" name="opsi[url_{{ $value['key'] }}]" value="{{ theme_config('url_' . $value['key']) }}" placeholder="URL" />
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#file_browser-{{ $value['key'] }}').click(function() {
                $("#file-{{ $value['key'] }}").click();
            });

            $('#file-{{ $value['key'] }}').change(function() {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#preview-{{ $value['key'] }}").attr("src", e.target.result);
                }
                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush
