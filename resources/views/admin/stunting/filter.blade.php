<div class="row mepet">
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            <div class="row">
                <div class="col-xs-5">
                    <select name="awal_bulan" id="awal_bulan" required class="form-control input-sm" 
                            title="Bulan Awal">
                        @for ($i = 1; $i <= 12; $i++)
                            <option @selected($awalBulan == $i) value="{{ $i }}">{{ getBulan($i) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-xs-2 text-center">
                    <small class="text-muted">s/d</small>
                </div>
                <div class="col-xs-5">
                    <select name="akhir_bulan" id="akhir_bulan" required class="form-control input-sm" 
                            title="Bulan Akhir">
                        @for ($i = 1; $i <= 12; $i++)
                            <option @selected($akhirBulan == $i) value="{{ $i }}">{{ getBulan($i) }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-3 col-xs-6">
        <div class="form-group">
            <select name="tahun" id="tahun" required class="form-control input-sm" title="Tahun">
                @foreach ($dataTahun as $item)
                    <option value="{{ $item->tahun }}">{{ $item->tahun }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            <select name="id" id="id" class="form-control input-sm" title="Posyandu">
                <option value="">-- Semua Posyandu --</option>
                @foreach ($posyandu as $item)
                    <option value="{{ $item->id }}" {{ $item->id == $id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-3 col-xs-6">
        <div class="form-group">
            <button type="button" class="btn btn-primary btn-sm btn-block" id="cari">
                <i class="fa fa-search"></i> 
                <span class="hidden-xs">Cari</span>
            </button>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            // Validasi periode bulan
            $('#awal_bulan').change(function() {
                let selectedMonth = parseInt($(this).val());
                let akhirBulanSelect = $('#akhir_bulan');
                
                // Reset dan disable option yang tidak valid
                akhirBulanSelect.find('option').prop('disabled', false);
                akhirBulanSelect.find('option').each(function() {
                    if (parseInt($(this).val()) < selectedMonth) {
                        $(this).prop('disabled', true);
                    }
                });

                // Auto-adjust jika bulan akhir lebih kecil dari bulan awal
                if (parseInt(akhirBulanSelect.val()) < selectedMonth) {
                    akhirBulanSelect.val(selectedMonth);
                }
            });

            // Handler tombol cari
            $('#cari').click(function() {
                let btn = $(this);
                let originalHtml = btn.html();
                
                // Validasi input
                let awalBulan = $('#awal_bulan').val();
                let akhirBulan = $('#akhir_bulan').val();
                let tahun = $('#tahun').val();
                
                if (!awalBulan || !akhirBulan || !tahun) {
                    alert('Mohon lengkapi periode bulan dan tahun!');
                    return;
                }

                // Loading state
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <span class="hidden-xs">Tunggu...</span>');
                
                // Build URL
                let periode = awalBulan + '__' + akhirBulan;
                let posyandu = $('#id').val() || '';
                let url = "{{ $urlFilter }}/" + periode + "/" + tahun + "/" + posyandu;
                
                // Navigate
                window.location.href = url;
            });

            // Trigger initial validation
            $('#awal_bulan').trigger('change');
        });
    </script>
@endpush
