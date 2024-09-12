<header class="main-header">
    <a href="{{ ci_route('/') }}" target="_blank" class="logo">
        <span class="logo-mini"><b>SID</b></span>
        <span class="logo-lg"><b>OpenSID</b></span>
    </a>

    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @if ($is_mobile = $ci->agent->is_mobile())
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Selengkapnya...</a>
                        <ul class="dropdown-menu">
                            <li>
                                <ul class="menu">
                @endif
                @if ($notif['langganan'] && can('b', 'layanan-pelanggan'))
                    <li>
                        <a href="{{ ci_route('pelanggan') }}">
                            <i class="fa {{ $notif['langganan']['ikon'] }} fa-lg" title="Status Langganan {{ $notif['langganan']['masa'] }} hari" style="color: {{ $notif['langganan']['warna'] }}"></i>&nbsp;
                            @if ($notif['langganan']['status'] > 2)
                                <span class="badge" id="b_langganan"></span>
                            @endif
                            @if ($is_mobile)
                                <span>Status Langganan</span>
                            @endif
                        </a>
                    </li>
                @endif

                {{-- TODO:: Cek ini kenapa statis --}}
                @if (in_array('343', array_column($modul ?? [], 'id')) && can('b', 'pesan'))
                    <li class="komunikasi-opendk">
                        <a href="{{ ci_route('opendk_pesan.clear') }}">
                            <i class="fa fa-university fa-lg" title="Komunikasi OpenDK"></i>&nbsp;
                            @if ($notif['opendkpesan'])
                                <span class="badge" id="b_opendkpesan">{{ $notif['opendkpesan'] }}</span>
                            @endif
                            @if ($is_mobile)
                                <span>Komunikasi OpenDK</span>
                            @endif
                        </a>
                    </li>
                @endif

                @if (can('b', 'permohonan-surat'))
                    <li>
                        <a href="{{ ci_route('permohonan_surat_admin') }}">
                            <i class="fa fa-print fa-lg" title="Cetak Surat"></i>&nbsp;
                            @if ($notif['surat'])
                                <span class="badge" id="b_permohonan_surat">{{ $notif['surat'] }}</span>
                            @endif
                            @if ($is_mobile)
                                <span>Cetak Surat</span>
                            @endif
                        </a>
                    </li>
                @endif

                @if (can('b', 'komentar'))
                    <li>
                        <a href="{{ ci_route('komentar') }}">
                            <i class="fa fa-commenting-o fa-lg" title="Komentar"></i>&nbsp;
                            @if ($notif['komentar'])
                                <span class="badge" id="b_komentar">{{ $notif['komentar'] }}</span>
                            @endif
                            @if ($is_mobile)
                                <span>Komentar</span>
                            @endif
                        </a>
                    </li>
                @endif

                @if (can('b', 'kotak-pesan'))
                    <li>
                        <a href="{{ ci_route('mailbox') }}">
                            <i class="fa fa-envelope-o fa-lg" title="Pesan Masuk"></i>&nbsp;
                            @if ($notif['inbox'])
                                <span class="badge" id="b_inbox">{{ $notif['inbox'] }}</span>
                            @endif
                            @if ($is_mobile)
                                <span>Pesan Masuk</span>
                            @endif
                        </a>
                    </li>
                @endif

                @if (can('b', 'arsip-layanan') && (setting('verifikasi_kades') || setting('verifikasi_sekdes')))
                    <li>
                        <a href="{{ ci_route('keluar.masuk') }}">
                            <span><i class="fa fa-bell-o fa-lg" title="Permohonan Surat"></i>&nbsp;</span>
                            @if ($notif['permohonansurat'])
                                <span class="badge" id="permohonan">{{ $notif['permohonansurat'] }}</span>
                            @endif
                            @if ($is_mobile)
                                <span>Permohonan Surat</span>
                            @endif
                        </a>
                    </li>
                @endif
                @if ($ci->agent->is_mobile())
            </ul>
            </li>
            </ul>
            </li>
            @endif

            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ AmbilFoto($auth->foto) }}" class="user-image" alt="User Image" />
                    <span class="hidden-xs">{{ $auth->nama }}</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="user-header">
                        <img src="{{ AmbilFoto($auth->foto) }}" class="img-circle" alt="User Image" />
                        <p>
                            <small>Anda Masuk Sebagai</small>
                            {{ $auth->nama }}
                        </p>
                    </li>
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="<?= site_url('pengguna') ?>" class="btn bg-maroon btn-sm">Profil</a>
                        </div>
                        <div class="pull-right">
                            <a href="{{ ci_route('siteman.logout') }}" class="btn bg-maroon btn-sm">Keluar</a>
                        </div>
                    </li>
                </ul>
            <li>
                <a href="#" data-toggle="control-sidebar" title="Informasi"><i class="fa fa-question-circle fa-lg"></i></a>
            </li>
            @if ($kategori_pengaturan && can('u', $akses_modul))
                <li>
                    @if ($modul_ini === 'layanan-pelanggan' || $sub_modul_ini === 'layanan-pelanggan')
                        <a href="#" class="atur-token">
                        @else
                            <a href="#" data-remote="false" data-toggle="modal" data-title="Pengaturan {{ ucwords($controller) }}" data-target="#pengaturan">
                    @endif
                    <span><i class="fa fa-gear"></i>&nbsp;</span>
                    </a>
                </li>
            @endif
            </li>
            </ul>
        </div>
    </nav>
</header>
