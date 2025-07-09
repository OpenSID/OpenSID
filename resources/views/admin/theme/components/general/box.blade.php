<div class="box box-{{ $status == 1 ? 'success' : 'info' }}">
    <div class="box-header with-border text-center">
        <strong>{{ $nama }}</strong>

        @if ($sistem === 1)
            <div class="box-tools pull-right" data-toggle="tooltip" title="" data-original-title="Tema Bawaan">
                <i class="fa fa-square text-green"></i>
            </div>
        @endif
    </div>

    <div class="box-body">
        <div class="text-center">
            <center>
                @php $file = $asset_path . '/thumbnail/preview-1.jpg' @endphp
                @if (file_exists(FCPATH . $file))
                    <img style="width:100%; max-height: 160px;" src="{{ base_url($asset_path . '/thumbnail/preview-1.jpg') }}" class="img-responsive" alt="{{ $nama }}">
                @else
                    <img style="max-height: 160px;" src="{{ asset('images/404-image-not-found.jpg') }}" class="img-responsive" alt="{{ $nama }}">
                @endif
            </center>
        </div>
        <br>
        <div class="text-center">
            @if ($status == 1)
                <a href="#" class="btn btn-social btn-success btn-sm" readonly><i class="fa fa-star"></i>Aktif</a>
            @else
                @if (can('u'))
                    <a href="{{ site_url('theme/aktifkan/' . $id) }}" class="btn btn-info btn-sm" title="Aktifkan Tema"><i class="fa fa-star-o"></i></a>
                @else
                    <br style="margin-top: 5px;">
                @endif
                @if (can('h') && $sistem !== 1)
                    <a href="#" data-href="{{ site_url('theme/delete/' . $id) }}" class="btn btn-danger btn-sm" title="Hapus Tema" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>
                @endif
            @endif
            @if (can('u'))
                <a href="{{ site_url('theme/pengaturan/' . $id) }}" class="btn bg-navy btn-sm" title="Pengaturan Tema"><i class="fa fa-cog"></i></a>
            @endif
        </div>
    </div>

</div>
