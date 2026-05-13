  @if ($widgetAktif)
    @foreach($widgetAktif as $widget)
      @php        
        $judul_widget = [
          'judul_widget' => str_replace('Desa', ucwords(setting('sebutan_desa')), strip_tags($widget['judul']))
        ];
      @endphp      
      @if ($widget["jenis_widget"] == 1)        
        @includeIf("theme::widgets.{$widget['isi']}", $judul_widget)
      @elseif($widget['jenis_widget'] == 2)
        {{-- TODO: KONVERSI TEMA, PERBAIKI WIDGET YANG DIAMBIL DARI FOLDER DESA --}}
        @includeIf("../../{$widget['isi']}", $judul_widget)
      @else
        <div class="box-def">
          <div class="head-widget l-flex">
            <div class="head-widget-title l-flex">
            <i class="fa fa-folder"></i><h1>{{ strip_tags($widget['judul']) }}</h1>
            </div>
          </div>
          <div class="widgetbox">
            <div class="embed-responsive embed-responsive-16by9">
            {!! html_entity_decode($widget['isi']) !!}
            </div>
          </div>
        </div>          
      @endif      
    @endforeach
  @endif
