<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ gambar_desa($desa['logo']) }}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <strong>{{ ucwords($setting->sebutan_desa . ' ' . $desa['nama_desa']) }}</strong>
        </br>
        
        @php
          $seb_kec = $setting->sebutan_kecamatan;
          $nam_kec = $desa['nama_kecamatan'];
          $seb_kab = $setting->sebutan_kabupaten;
          $nam_kab = $desa['nama_kabupaten'];
        @endphp

        @if (strlen($nam_kec) <= 12 && strlen($nam_kab) <= 12)
          {{ ucwords($seb_kec . ' ' . $nam_kec) }}
          </br>
          {{ ucwords($seb_kab . ' ' . $nam_kab) }}
        @else
          {{ ucwords(substr($seb_kec, 0, 3) . '. ' . $nam_kec) }}
          </br>
          {{ ucwords(substr($seb_kab, 0, 3) . '. ' . $nam_kab) }}
        @endif
        
      </div>
    </div>
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MENU UTAMA</li>

      @foreach ($modul as $mod)

        @if (count($mod['submodul']) == 0)
        <li class="{{ jecho($modul_ini, $mod['id'], 'active') }}">
          <a href="{{ route($mod['url']) }}">
            <i class="fa {{ $mod['ikon'] }} {{ jecho($modul_ini, $mod['id'], 'text-aqua') }}"></i><span>{{ $mod['modul'] }}</span>
            <span class="pull-right-container"></span>
          </a>
        </li>
        @else 
        <li class="treeview {{ jecho($modul_ini, $mod['id'], 'active') }}">
          <a href="{{ route($mod['url']) }}">
            <i class="fa {{ $mod['ikon'] }} {{ jecho($modul_ini, $mod['id'], 'text-aqua') }}"></i><span>{{ $mod['modul'] }}</span>
            <span class="pull-right-container"><i class='fa fa-angle-left pull-right'></i></span>
          </a>
          <ul class="treeview-menu {{ jecho($modul_ini, $mod['id'], 'active') }}">

            @foreach ($mod['submodul'] as $submod)
            <li class="{{ jecho($sub_modul_ini, $submod['id'], 'active') }}">
              <a href="{{ route($submod['url']) }}">
                <i class="fa {{ ($submod['ikon'] != null) ? $submod['ikon'] : 'fa-circle-o' }} {{ jecho($sub_modul_ini, $submod['id'], 'text-red') }}"></i>
                {{ $submod['modul'] }}
              </a>
            </li>
            @endforeach

          </ul>
        </li>
        @endif

      @endforeach
    </ul>
  </section>
</aside>