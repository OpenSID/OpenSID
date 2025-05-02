@include('admin.layouts.components.validasi_form')
<form action="{{ $form_action }}" method="post" id="validasi">
    <div class='modal-body'>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12">
                    <label for="nama">Rentang Umur</label>
                </div>
                <div class="col-xs-6">
                    <input class="form-control input-sm required bilangan" type="text" placeholder="Dari" id="dari" name="dari" value="{{ $rentang['dari'] }}"></input>
                </div>
                <div class="col-xs-6">
                    <input id="sampai" class="form-control input-sm required bilangan" type="text" placeholder="Sampai" name="sampai" value="{{ $rentang['sampai'] }}"></input>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>

<script>
    // dokument ready
    $(document).ready(function() {
        function validateMin() {
            var min = parseFloat($('#dari').val());
            var max = parseFloat($('#sampai').val());

            if (min >= max) {
                $('#dari').prop('max', max);
            } else {
                $('#dari').prop('max', '');
                $('#sampai').prop('min', '');
            }
        }

        function validateMax() {
            var min = parseFloat($('#dari').val());
            var max = parseFloat($('#sampai').val());

            if (max > 150) {
                $('#sampai').val(150);
            }

            if (max < min) {
                $('#sampai').prop('min', min);
            } else {
                $('#sampai').prop('min', '');
                $('#dari').prop('max', '');
            }
        }

        $('#dari').on('keyup', function() {
            validateMin();
        });

        $('#sampai').on('keyup', function() {
            validateMax();
        });

        $('#validasi').submit(function(e) {
            var min = parseFloat($('#dari').val());
            var max = parseFloat($('#sampai').val());
            const isSame = min === max;

            if (min >= max && !isSame) {
                e.preventDefault();
            }
        });
    });
</script>
