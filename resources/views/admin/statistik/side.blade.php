@foreach ($allKategori as $kategoriBox => $data)
    <div id="{{ $kategoriBox }}" class="box box-info {{ $kategori == $kategoriBox ? '' : 'collapsed-box' }}">
        <div class="box-header with-border">
            <h3 class="box-title">Statistik {{ $kategoriBox }}</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa {{ $kategori == $kategoriBox ? 'fa-minus' : 'fa-plus' }}"></i>
                </button>
            </div>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                @foreach ($data['data'] as $id => $nama)
                    <li class="@active((string) $id == $lap && $kategori == $kategoriBox)">
                        {!! anchor("statistik/{$data['kategori']}/{$id}", $nama) !!}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endforeach
