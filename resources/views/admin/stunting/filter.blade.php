<div class="col-md-12 no-padding">
    <div class="row col-md-5">
        <div class="col-md-5">
            <div class="form-group">
                <select name="awal_bulan" id="awal_bulan" required class="form-control input-sm" title="Pilih salah satu">
                    @for ($i = 1; $i <= 12; $i++)
                        <option @selected($awalBulan == $i) value="{{ $i }}">{{ getBulan($i) }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="col-md-2">sd</div>
        <div class="col-md-5">
            <div class="form-group">
                <select name="akhir_bulan" id="akhir_bulan" required class="form-control input-sm" title="Pilih salah satu">
                    @for ($i = 1; $i <= 12; $i++)
                        <option @selected($akhirBulan == $i) value="{{ $i }}">{{ getBulan($i) }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <select name="tahun" id="tahun" required class="form-control input-sm" title="Pilih salah satu">
                @foreach ($dataTahun as $item)
                    <option value="{{ $item->tahun }}">{{ $item->tahun }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <select name="id" id="id" required class="form-control input-sm" title="Pilih salah satu">
                <option value="">Semua</option>
                @foreach ($posyandu as $item)
                    <option value="{{ $item->id }}" {{ $item->id == $id ? 'selected' : '' }}>
                        {{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2 no-padding">
        <button type="button" class="btn btn-social btn-info btn-sm" id="cari">
            <i class="fa fa-search"></i> Cari
        </button>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#awal_bulan').change(function() {
                let _val = $(this).val()
                let _akhirBulan = $('#akhir_bulan')

                _akhirBulan.find('option').prop('disabled', false)
                $('#akhir_bulan option').each(function() {
                    if (parseInt($(this).val()) < _val) {
                        $(this).prop('disabled', true);
                    }
                });

                if (_akhirBulan.val() < _val) {
                    _akhirBulan.val(_val)
                }
            })

            $('#cari').click(function() {
                let awal_bulan = $('#awal_bulan').val();
                let akhir_bulan = $('#akhir_bulan').val();
                let periode = `${awal_bulan}__${akhir_bulan}`
                let tahun = $('#tahun option:selected').val();
                let posyandu = $('#id option:selected').val();
                window.location.href = "{{ $urlFilter }}/" + periode + "/" +
                    tahun + "/" + posyandu;
            });

            $('#awal_bulan').trigger('change');
        })
    </script>
@endpush
