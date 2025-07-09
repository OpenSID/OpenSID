<aside class="space-y-5 sidebar">
    <form action="{{ site_url('/') }}" role="form" class="relative">
        <i class="fas fa-search absolute top-1/2 left-0 transform -translate-y-1/2 z-10 px-3 text-gray-500"></i>
        <input type="text" name="cari" class="form-input px-10 w-full h-12 bg-white relative inline-block" placeholder="Cari...">
    </form>
    @if ($widgetAktif)
        @foreach ($widgetAktif as $widget)
            @php
                $judul_widget = [
                    'judul_widget' => str_replace('Desa', ucwords(setting('sebutan_desa')), strip_tags($widget['judul'])),
                ];
            @endphp
            <div class="shadow rounded-lg bg-white overflow-hidden">
                @if ($widget['jenis_widget'] == 3)
                    <div class="box-header">
                        <h3 class="box-title">{{ strip_tags($widget['judul']) }}</h3>
                    </div>
                    <div class="box-body">
                        {!! html_entity_decode($widget['isi']) !!}
                    </div>
                @else
                    @includeIf("theme::widgets.{$widget['isi']}", $judul_widget)
                @endif
            </div>
        @endforeach
    @endif
</aside>
