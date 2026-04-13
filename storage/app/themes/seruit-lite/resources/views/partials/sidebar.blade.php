@php
    $active_gradient = 'from-green-500 to-teal-500';
@endphp

<div class="space-y-6">
    @if ($widgetAktif)
        @foreach ($widgetAktif as $widget)
            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                @if ($widget['judul'])
                    <div class="px-4 py-3 bg-gradient-to-r {{ $active_gradient }} text-white font-bold text-sm uppercase tracking-wider">
                        {{ str_replace('Desa', ucwords(setting('sebutan_desa')), strip_tags($widget['judul'])) }}
                    </div>
                @endif
                
                <div class="p-4">
                    @if ($widget['jenis_widget'] == 3)
                        <div class="prose prose-sm dark:prose-invert max-w-none">
                            {!! html_entity_decode($widget['isi']) !!}
                        </div>
                    @else
                        @includeIf("theme::widgets.{$widget['isi']}")
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>