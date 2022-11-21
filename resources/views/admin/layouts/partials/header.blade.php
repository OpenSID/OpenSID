<header class="main-header">
  <a href="{{ route('/') }}" target="_blank" class="logo">
    <span class="logo-mini"><b>SID</b></span>
    <span class="logo-lg"><b>OpenSID</b></span>
  </a>

  <nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">

        @if ($notif['langganan'])
          <li>
            <a href="{{ route('pelanggan') }}">
              <span><i class="fa {{ $notif['langganan']['ikon'] }} fa-lg" title="Status Langganan {{ $notif['langganan']['masa'] }} hari" style="color: {{ $notif['langganan']['warna'] }}"></i>&nbsp;</span>
              @if ($notif['langganan']['status'] > 2)
                <span class="badge" id="b_langganan"></span>
              @endif
            </a>
          </li>
        @endif

        @if (in_array('343', array_column($modul, 'id')) && can('b', 'opendk_pesan'))
          <li class="komunikasi-opendk">
            <a href="{{ route('opendk_pesan.clear') }}">
              <span><i class="fa fa-university fa-lg" title="Komunikasi OpenDk"></i>&nbsp;</span>
              @if ($notif['opendkpesan'])
                <span class="badge" id="b_opendkpesan">{{ $notif['opendkpesan'] }}</span>
              @endif
            </a>
          </li>
        @endif

        @if (can('b', 'permohonan_surat_admin'))
          <li>
            <a href="{{ route('permohonan_surat_admin.clear') }}">
              <span><i class="fa fa-print fa-lg" title="Permohonan Surat"></i>&nbsp;</span>
              @if ($notif['surat'])
                <span class="badge" id="b_permohonan_surat">{{ $notif['surat'] }}</span>
              @endif
            </a>
          </li>
        @endif

        @if (can('b', 'komentar'))
          <li>
            <a href="{{ route('komentar') }}">
              <span><i class="fa fa-commenting-o fa-lg" title="Komentar"></i>&nbsp;</span>
              @if ($notif['komentar'])
                <span class="badge" id="b_komentar">{{ $notif['komentar'] }}</span>
              @endif
            </a>
          </li>
        @endif

        @if (can('b', 'mailbox'))
          <li>
            <a href="{{ route('mailbox') }}">
              <span><i class="fa fa-envelope-o fa-lg" title="Pesan Masuk"></i>&nbsp;</span>
              @if ($notif['inbox'])
                <span class="badge" id="b_inbox">{{ $notif['inbox'] }}</span>
              @endif
            </a>
          </li>
        @endif
        
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="{{ AmbilFoto($auth->foto) }}" class="user-image" alt="User Image"/>
            <span class="hidden-xs">{{ $auth->nama }}</span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-header">
              <img src="{{ AmbilFoto($auth->foto) }}" class="img-circle" alt="User Image"/>
              <p>
                <small>Anda Masuk Sebagai</small>
                {{ $auth->nama }}
              </p>
            </li>
            <li class="user-footer">
              <div class="pull-left">
                <a href="#"  class="btn bg-maroon btn-sm" data-remote="false" data-toggle="modal" data-target="#profil_pengguna">Profil</a>
              </div>
              <div class="pull-right">
                <a href="{{ route('siteman.logout') }}" class="btn bg-maroon btn-sm">Keluar</a>
              </div>
            </li>
          </ul>
          <li>
            <a href="#" data-toggle="control-sidebar" title="Informasi"><i class="fa fa-question-circle fa-lg"></i></a>
          </li>
          @if ($kategori && can('u', $controller))
          <li>
            <a href="#" data-remote="false" data-toggle="modal" data-target="#pengaturan">
              <span><i class="fa fa-gear"></i>&nbsp;</span>
            </a>
          </li>
          @endif
        </li>
      </ul>
    </div>
  </nav>
</header>