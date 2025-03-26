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
        <div class="text-center">
            <center>
                @php $file = $asset_path . '/thumbnail/preview-1.jpg' @endphp
                @if (file_exists(FCPATH . $file))
                    <img style="width:100%; max-height: 160px;" src="{{ base_url($asset_path . '/thumbnail/preview-1.jpg') }}" class="img-responsive" alt="{{ $nama }}">
                @elseif ($thumbnail)
                    <img style="width:100%; max-height: 160px;" src="{{ $thumbnail }}" class="img-responsive" alt="{{ $nama }}">
                @else
                    <img style="max-height: 160px;" src="{{ asset('images/404-image-not-found.jpg') }}" class="img-responsive" alt="{{ $nama }}">
                @endif
            </center>
        </div>
        <br>
        <div class="text-center">
            @if ($status == 1)
                <a href="#" class="btn btn-social btn-success btn-sm" readonly><i class="fa fa-star"></i>Aktif</a>
            @elseif ($marketplace)
                @if ($providers)
                    <a href="{{ $providers }}" class="btn btn-social btn-info btn-sm" target="_blank"><i class="fa fa-eye"></i>Preview</a>
                @endif
                <a href="https://opendesa.id/tema-premium" class="btn btn-social btn-warning btn-sm" target="_blank"><i class="fa fa-info"></i>Hubungi</a>
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
                    <a href="{{ site_url('theme/aktifkan/' . $id) }}" class="btn btn-info btn-sm" title="Aktifkan Tema"><i class="fa fa-star-o"></i></a>
                @endif
                @if (!cache('siappakai') && !setting('multi_desa') && can('h') && $sistem !== 1)
                    <a href="#" data-href="{{ site_url('theme/delete/' . $id) }}" class="btn btn-danger btn-sm" title="Hapus Tema" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>
                @endif
            @endif
            @if (!$marketplace && can('u'))
                <a href="{{ site_url('theme/pengaturan/' . $id) }}" class="btn bg-navy btn-sm" title="Pengaturan Tema"><i class="fa fa-cog"></i></a>
            @endif
        </div>
    </div>

</div>
