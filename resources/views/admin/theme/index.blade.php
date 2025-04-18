@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Tema
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Tema</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border text-center">
            {{-- @if (!cache('siappakai') && !setting('multi_desa') && can('u'))
                <a href="{{ site_url('theme/unggah') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-upload"></i> Unggah</a>
            @endif --}}
            @if (can('u'))
                <a href="{{ site_url('theme/pindai') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-retweet"></i> Pindai</a>
            @endif
            <a href="{{ site_url() }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-eye"></i> Lihat</a>
        </div>
        <div class="box-body">
            <form action="" method="get">
                <div class="row mepet">
                    <div class="col-sm-2">
                        <select id="kategori" name="kategori" class="form-control input-sm select2">
                            <option value="">Pilih Tipe</option>
                            <option @selected($kategori === '1') value="1">Umum</option>
                            <option @selected($kategori === '2') value="2">Premium</option>
                        </select>
                    </div>
                </div>
            </form>
            <hr class="batas">
            <div class="row">
                @forelse ($themeList as $theme)
                    <div class="col-md-4">
                        @includeIf('admin.theme.components.general.box', collect($theme)->merge($themeOrder)->toArray())
                    </div>
                @empty
                    <div class="col-md-12">
                        <p class="text-center">Tidak ada tema yang tersedia</p>
                    </div>
                @endforelse
            </div>
        </div>
        @if ($themeList->hasPages())
            <div class="box-footer text-center">
                {{ $themeList->onEachSide(1)->links('admin.layouts.components.pagination_default') }}
            </div>
        @endif
    </div>
    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#kategori").on("change", function() {
                let params = new URLSearchParams(window.location.search);

                $(this).val() ? params.set("kategori", $(this).val()) : params.delete("kategori");

                window.location.search = params.toString();
            }).val(new URLSearchParams(window.location.search).get("kategori"));
        });
    </script>
@endpush
