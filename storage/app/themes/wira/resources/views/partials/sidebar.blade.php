<aside class="space-y-5 sidebar">
    <!-- <form action="{{ site_url('/') }}" role="form" class="relative">
        <i class="fas fa-search absolute top-1/2 left-0 transform -translate-y-1/2 z-10 px-3 text-gray-500"></i>
        <input type="text" name="cari" class="form-input px-10 w-full h-12 bg-white relative inline-block" placeholder="Cari...">
    </form> -->
    @if ($widgetAktif)
        @foreach ($widgetAktif as $widget)
            @php
                $judul_widget = [
                    'judul_widget' => str_replace('Desa', ucwords(setting('sebutan_desa')), strip_tags($widget['judul'])),
                ];
            @endphp
            <div class="rounded-xl bg-white shadow-sm overflow-hidden border border-gray-100">
                @if ($widget['jenis_widget'] == 3 && strtolower($widget['judul']) !== 'sejarah' && strtolower($widget['judul']) !== 'pengembangan' && strtolower($widget['visi misi']) !== 'visi misi')
                    <div class="box box-primary box-solid flex flex-col items-center">
                        <div class="w-full bg-green-600 flex items-center justify-center py-3 px-6 mb-1">
                            <h3 class="text-md font-semibold text-white text-center">
                                {{ strtoupper(strip_tags($widget['judul'])) }}
                            </h3>
                        </div>
                        <div class="w-full h-1 bg-green-500 mb-2"></div>
                        
                        <div class="widget-content prose prose-sm max-w-none p-4">
                            {!! html_entity_decode($widget['isi']) !!}
                        </div>
                    </div>
                @else
                    @includeIf("theme::widgets.{$widget['isi']}", $judul_widget)
                @endif
            </div>
        @endforeach
    @endif
</aside>