<div class="breadcrumb">
    <ol>
        <li><a href="{{ ci_route('') }}">Beranda</a></li>
        <li>Data Statistik</li>
    </ol>
</div>
<h1 class="text-h2">{{ $judul }}</h1>
@if (isset($list_tahun))
    <div class="flex justify-between items-center space-x-3 py-2">
        <label for="owner" class="text-xs lg:text-sm">Tahun</label>
        <select class="form-control input-sm" id="tahun" name="tahun">
            <option selected="" value="">Semua</option>
            @foreach ($list_tahun as $item_tahun)
                <option @selected($item_tahun == $selected_tahun) value="{{ $item_tahun }}">{{ $item_tahun }}</option>
            @endforeach
        </select>
    </div>
@endif
<div class="flex justify-between items-center space-x-1 py-5">
    <h2 class="text-h4">Grafik {{ $heading }}</h2>
    <div class="text-right btn-switch-chart space-x-2 text-sm space-y-2 md:space-y-0">
        <button class="btn btn-secondary button-switch" data-type="column">Bar Graph</button>
        <button class="btn btn-secondary button-switch is-active" data-type="pie">Pie Graph</button>
        <a href="{{ ci_route("data-statistik.{$slug_aktif}.cetak.cetak") }}?tahun={{ $selected_tahun }}" class="btn btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Laporan" target="_blank">
            <i class="fa fa-print "></i> Cetak
        </a>
        <a href="{{ ci_route("data-statistik.{$slug_aktif}.cetak.unduh") }}?tahun={{ $selected_tahun }}" class="btn btn-accent btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Laporan" target="_blank">
            <i class="fa fa-print "></i> Unduh
        </a>
    </div>
</div>
<div id="statistics"></div>
<h2 class="text-h4">Tabel {{ $heading }}</h2>
<div class="content py-3">
    <div class="table-responsive">
        <table class="w-full text-sm" id="table-statistik">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Kelompok</th>
                    <th colspan="2">Jumlah</th>
                    <th colspan="2">Laki-laki</th>
                    <th colspan="2">Perempuan</th>
                </tr>
                <tr>
                    <th>n</th>
                    <th>%</th>
                    <th>n</th>
                    <th>%</th>
                    <th>n</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <p style="color: red">
            Diperbarui pada : {{ tgl_indo($last_update) }}
        </p>
    </div>
    <div class="flex justify-between py-5">
        <button class="btn btn-primary button-more" id="showData">Selengkapnya...</button>
        <button id="showZero" class="btn btn-secondary">Tampilkan Nol</button>
    </div>

    @if (setting('daftar_penerima_bantuan') && $bantuan)
        <script>
            const bantuanUrl = '{{ ci_route('internal_api.peserta_bantuan', $key) }}?filter[tahun]={{ $selected_tahun ?? '' }}'
        </script>

        <input id="stat" type="hidden" value="{{ $key }}">
        <h2 class="text-h4">Daftar {{ $heading }}</h2>

        <div class="table-responsive content py-3">
            <table class="w-full text-sm" id="peserta_program">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Program</th>
                        <th>Nama Peserta</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    @endif
</div>
@push('styles')
    <style>
        .button-switch.is-active {
            background-color: #efefef;
            color: #999;
        }
    </style>
@endpush
@push('scripts')
    <script>
        let dataStats = [];
        $(function() {
            $.ajax({
                url: `{{ ci_route('internal_api.statistik', $key) }}?tahun={{ $selected_tahun ?? '' }}`,
                method: 'get',
                data: {},
                beforeSend: function() {
                    $('#showData').hide()
                },
                success: function(json) {
                    dataStats = json.data.map(item => {
                        const {
                            id
                        } = item;
                        const {
                            nama,
                            jumlah,
                            laki,
                            perempuan,
                            persen,
                            persen1,
                            persen2
                        } = item.attributes;
                        return {
                            id,
                            nama,
                            jumlah,
                            laki,
                            perempuan,
                            persen,
                            persen1,
                            persen2
                        };
                    });

                    const table = document.getElementById('table-statistik')
                    const tbody = table.querySelector('tbody')
                    let _showBtnSelengkapnya = false
                    // Populate table rows
                    dataStats.forEach((item, index) => {
                        const row = document.createElement('tr');
                        if (index > 11 && !['666', '777', '888'].includes(item['id'])) {
                            row.className = 'more';
                            _showBtnSelengkapnya = true
                        }
                        for (let key in item) {
                            const cell = document.createElement('td');
                            let text = item[key]
                            let className = 'text-right'
                            if (key == 'id') {
                                className = 'text-center'
                                text = index + 1
                                if (['666', '777', '888'].includes(item[key])) {
                                    text = ''
                                }
                            }
                            if (key == 'nama') {
                                className = 'text-left'
                            }
                            if (key == 'jumlah' && item[key] <= 0) {
                                if (!['666', '777', '888'].includes(item['id'])) {
                                    className += ' zero'
                                }

                            }
                            cell.className = className
                            cell.textContent = text;
                            row.appendChild(cell);
                        }

                        tbody.appendChild(row);
                    });
                    if (_showBtnSelengkapnya) {
                        $('#showData').show()
                    }
                    $('#statistics').trigger('change')

                },
            })
            $('#tahun').change(function() {
                const current_url = window.location.href.split('?')[0]
                window.location.href = `${current_url}?tahun=${$(this).val()}`;
            })

            const _chartType = '{{ $default_chart_type ?? 'pie' }}';

            if (_chartType == 'column') {
                setTimeout(function() {
                    $('.btn-switch-chart>.button-switch[data-type=column]').click()
                }, 1000)
            }
        })
    </script>
@endpush
