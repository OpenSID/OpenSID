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

                @yield('notifikasi')

                @if ($notif['langganan'] && can('b', 'layanan-pelanggan'))
                    <li>
                        <a href="{{ ci_route('pelanggan') }}">
                            <i class="fa {{ $notif['langganan']['ikon'] }} fa-sm" title="Status Langganan {{ $notif['langganan']['masa'] }} hari" style="color: {{ $notif['langganan']['warna'] }}"></i>&nbsp;
                            @if ($notif['langganan']['status'] > 2)
                                <span class="badge" id="b_langganan"></span>
                            @endif
                            @if ($is_mobile)
                                <span>Status Langganan</span>
                            @endif
                        </a>
                    </li>
                @endif

                {{-- Notifikasi Dropdown --}}
                @php
                    $total_notifications = array_sum($notif_counts ?? []);
                @endphp
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        @if ($total_notifications > 0)
                            <span class="label label-danger notification-badge">{{ $total_notifications }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">
                            @if ($total_notifications > 0)
                                Anda memiliki notifikasi baru
                            @else
                                Anda tidak memiliki notifikasi baru
                            @endif
                        </li>
                        @if ($total_notifications > 0 && ! empty($notif_list))
                            <li style="max-height: 350px; overflow-y: auto;">
                                <ul class="menu">
                                    @forelse ($notif_list as $notifikasi)
                                        <li style="padding: 8px 10px; border-bottom: 1px solid #f4f4f4; @if($notifikasi->unread()) background-color: #f0f8ff; @endif; display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;" data-notif-id="{{ $notifikasi->id }}">
                                            <a href="{{ ci_route('notifikasi.show', $notifikasi->id) }}" style="padding: 0; display: block; flex: 1; min-width: 0;">
                                                <div style="font-size: 12px;">
                                                    @if (!empty($notifikasi->data['icon']))
                                                        <i class="fa {{ $notifikasi->data['icon'] }}" style="margin-right: 5px; color: {{ $notifikasi->data['color'] ?? '#666' }};"></i>
                                                    @endif
                                                    <strong>{{ $notifikasi->data['title'] ?? 'Notifikasi' }}</strong>
                                                </div>
                                                <div style="font-size: 11px; color: #666; margin-top: 2px;">
                                                    {{ $notifikasi->data['message'] ?? '' }}
                                                </div>
                                                <div style="font-size: 10px; color: #999; margin-top: 2px;">
                                                    {{ $notifikasi->created_at->diffForHumans() }}
                                                </div>
                                            </a>
                                            @if ($notifikasi->unread())
                                                <button type="button" class="btn btn-xs bg-info mark-as-read-btn" data-notif-id="{{ $notifikasi->id }}" style="flex-shrink: 0; white-space: nowrap; padding: 4px 8px; margin-top: 13px;" title="Tandai Sudah Dibaca">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <i class="fa fa-circle" style="margin-right: 5px; color: #28a745; font-size: 8px; margin-top: 22px;" title="Notifikasi Baru"></i>
                                            @endif
                                        </li>
                                    @empty
                                        <li style="padding: 10px; text-align: center; color: #999; font-size: 12px;">
                                            Tidak ada notifikasi untuk ditampilkan
                                        </li>
                                    @endforelse
                                </ul>
                            </li>
                        @elseif ($total_notifications == 0)
                            <li style="padding: 10px; text-align: center; color: #999; font-size: 12px;">
                                Tidak ada notifikasi untuk ditampilkan
                            </li>
                        @endif
                        <li class="footer"><a href="{{ ci_route('notifikasi') }}">Selengkapnya...</a></li>
                    </ul>
                </li>

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
            </ul>
        </div>
    </nav>
</header>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle mark as read button click
    $(document).on('click', '.mark-as-read-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var notifId = $(this).data('notif-id');
        var $btn = $(this);
        var $item = $(`[data-notif-id="${notifId}"]`);
        
        $.ajax({
            url: `{{ ci_route("notifikasi.mark-as-read") }}/${notifId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Remove background color to indicate read
                $item.css('background-color', 'transparent');
                // Remove the button
                $btn.fadeOut(300, function() {
                    $(this).remove();
                });
                // Remove green circle icon
                $item.find('.fa-circle').fadeOut(300, function() {
                    $(this).remove();
                });
                    
                // Update total notifications badge
                var totalBadge = $('.notifications-menu .label-danger');
                var currentCount = parseInt(totalBadge.text()) || 0;
                if (currentCount > 1) {
                    totalBadge.text(currentCount - 1);
                } else {
                    totalBadge.fadeOut();
                }
            },
            error: function() {
                swal.fire('Error', 'Gagal menandai notifikasi sebagai dibaca', 'error');
            }
        });
    });
});
</script>
@endpush
