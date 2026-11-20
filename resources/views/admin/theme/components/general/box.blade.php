@php
    $pengembang = '-';
    $composerPath = $full_path ? FCPATH . $full_path . '/composer.json' : null;

    if ($composerPath && file_exists($composerPath)) {
        $composerTema = json_decode(file_get_contents($composerPath), true);
        $pengembang = $composerTema['authors'][0]['name'] ?? '-';
    }

    $detailModalId = 'detail-tema-' . ($id ?? $slug);
@endphp
<div class="box box-{{ $status == 1 ? 'success' : ($sistem == 1 ? 'info' : 'danger') }}">
    <div class="box-header with-border text-center">
        <strong>{{ $nama }}</strong>
        <div class="ribbon-wrapper">
            @php
                $ribbonClass = $status == 1 ? 'btn-success' : ($sistem == 1 ? 'btn-info' : 'btn-danger');
                $ribbonText = $status == 1 ? 'Aktif' : ($sistem == 1 ? 'Umum' : 'Premium');
            @endphp
            <div class="{{ $ribbonClass }} ribbon">
                {{ $ribbonText }}
            </div>
        </div>
    </div>

    <div class="box-body">
        <div class="theme-thumbnail-wrapper" style="width: 100%; height: 180px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; border-radius: 4px; margin-bottom: 15px;">
            @php $file = $asset_path . '/thumbnail/preview-1.jpg' @endphp
            @if (file_exists(FCPATH . $file))
                <img
                    style="width: 100%; height: 100%; object-fit: cover;"
                    src="{{ base_url($asset_path . '/thumbnail/preview-1.jpg') }}"
                    alt="{{ $nama }}"
                    onerror="this.onerror=null; this.src='{{ asset('images/404-image-not-found.jpg') }}';"
                >
            @elseif ($thumbnail)
                <img
                    style="width: 100%; height: 100%; object-fit: cover;"
                    src="{{ $thumbnail }}"
                    alt="{{ $nama }}"
                    onerror="this.onerror=null; this.src='{{ asset('images/404-image-not-found.jpg') }}';"
                >
            @else
                <img
                    style="width: 100%; height: 100%; object-fit: cover;"
                    src="{{ asset('images/404-image-not-found.jpg') }}"
                    alt="{{ $nama }}"
                >
            @endif
        </div>
        <br>
        <div class="text-center">
            @if ($status == 1)
                <a href="#" class="btn btn-social btn-success btn-sm" readonly><i class="fa fa-toggle-on"></i>Aktif</a>
            @elseif ($marketplace)
                @if ($providers)
                    <a href="{{ $providers }}" class="btn btn-social btn-info btn-sm" target="_blank"><i class="fa fa-eye"></i>Preview</a>
                @endif
                <a href="{{ config_item('website') . '/tema-pro-opensid' }}" class="btn btn-social btn-warning btn-sm" target="_blank"><i class="fa fa-info"></i>Hubungi</a>
                @if ($themeOrder?->firstWhere('nama', $nama))
                    <form action="{{ site_url('theme/unduh') }}" method="POST" style="display:inline;">
                        <input type="hidden" name="nama" value="{{ $nama }}">
                        <input type="hidden" name="url" value="{{ $url }}">
                        <button type="submit" class="btn btn-social bg-navy btn-sm" title="Unduh Tema">
                            <i class="fa fa-download"></i> Unduh
                        </button>
                    </form>
                @endif
            @else
                @if (can('u'))
                    <a href="{{ site_url('theme/aktifkan/' . $id) }}" class="btn btn-info btn-sm" title="Aktifkan Tema"><i class="fa fa-toggle-off"></i></a>
                @endif
                @if (!cache('siappakai') && !setting('multi_desa') && can('h') && $sistem !== 1)
                    <a href="#" data-href="{{ site_url('theme/delete/' . $id) }}" class="btn btn-danger btn-sm" title="Hapus Tema" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>
                @endif
            @endif
            @if (!$marketplace && can('u'))
                <a href="{{ site_url('theme/pengaturan/' . $id) }}" class="btn bg-navy btn-sm" title="Pengaturan Tema"><i class="fa fa-cog"></i></a>
                <a href="#" class="btn btn-primary btn-sm" title="Detail Tema" data-toggle="modal" data-target="#{{ $detailModalId }}"><i class="fa fa-info-circle"></i></a>
            @endif
        </div>
    </div>

</div>

<div class="modal fade modal-detail-tema" id="{{ $detailModalId }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-info-circle"></i> Detail Tema</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 150px;"><i class="fa fa-bookmark-o"></i> Nama</th>
                        <td>{{ $nama }}</td>
                    </tr>
                    <tr>
                        <th><i class="fa fa-user-o"></i> Pengembang</th>
                        <td>{{ $pengembang }}</td>
                    </tr>
                    <tr>
                        <th><i class="fa fa-code-fork"></i> Versi</th>
                        <td><span class="label label-info">{{ $versi }}</span></td>
                    </tr>
                    <tr>
                        <th><i class="fa fa-align-left"></i> Deskripsi</th>
                        <td>{{ $keterangan }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
