@extends('admin.layouts.index')

@push('css')
    <style type="text/css">
        .direct-chat-primary .right>.direct-chat-text {
            background: #3c8dbc;
            border-color: #3c8dbc;
            color: #fff;
        }

        .right .direct-chat-text {
            margin-right: 50px;
            margin-left: 0;
        }
    </style>
@endpush

@section('title')
    <h1>
        Pesan
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ site_url($controller) }}">Pesan</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.layouts.components.asset_tinymce')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ site_url('opendk_pesan') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left "></i>Kembali ke Pesan
            </a>
        </div>

        <div class="box-body no-padding">
            <div class="mailbox-controls">
                &nbsp;
                {{ $pesan->detailPesan()->paginate(20)->links('vendor.pagination.pesan') }}
            </div>
            <div class="mailbox-read-info">
                <h3 class="text-bold">Judul Pesan : {{ $pesan->judul }}</h3>
                <h5>
                    @if ($pesan->jenis === 'Pesan Masuk')
                        Dari
                    @else
                        Ditujukan untuk
                    @endif:
                    Desa {{ $pesan->dataDesa->nama }}
                    <span class="mailbox-read-time pull-right">{{ $pesan->custom_date }}</span>
                </h5>
            </div>
            @foreach ($pesan->detailPesan()->paginate(20) as $key => $data)
                <div class="mailbox-read-message">
                    <div class="row">
                        <div class="col-xs-1">
                            <div class="card img-thumbnail profil">
                                @if ($data->pengirim == 'kecamatan')
                                    <img class="img-responsive" src="{{ Foto_Default('kuser.png', $sex = 1) }}">
                                @else
                                    <img class="img-responsive" src="{{ gambar_desa($desa['logo']) }}">
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-11">
                            <h5>
                                <span class="username ">
                                    <span class="text-bold">{{ $data->nama_pengirim }}</span>
                                    <span class="text-muted pull-right">{{ $data->created_at }}</span>
                                </span>
                            </h5>
                            {!! $data->text !!}
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($pesan->diarsipkan == '0')
                <div class="form-group" style="padding: 20px;">
                    {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
                    <div class="col-sm-12">
                        <div class="form-group">
                            <textarea class="form-control" id="pesan" name="pesan"></textarea>
                        </div>
                        <div class="form-group">
                            <button id="action-reply" type="submit" class="btn btn-default"><i class="fa fa-reply"></i> Balas</button>
                        </div>
                    </div>
                    </form>
                </div>
            @endif
        </div>
    @endsection

    @push('scripts')
        <script type="text/javascript">
            $(function() {
                $('#prev-links').click(function() {
                    let page = $(this).data('currentPage');
                    if (page <= 1) {
                        return;
                    } else {
                        window.location = window.location.origin +
                            window.location.pathname + '?' + $.param({
                                page: page - 1
                            })
                    }
                })

                $('#next-links').click(function() {
                    let last = $(this).data('lastPage');
                    let page = $(this).data('currentPage');
                    if (last <= page) {
                        return;
                    } else {
                        window.location = window.location.origin +
                            window.location.pathname + '?' + $.param({
                                page: page + 1
                            })
                    }
                })

                tinymce.init({
                    selector: 'textarea',
                    height: 300,
                    promotion: false,
                    theme: 'silver',
                    plugins: [
                        'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'print', 'preview', 'hr', 'anchor', 'pagebreak',
                        'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'insertdatetime', 'media', 'nonbreaking',
                        'table', 'contextmenu', 'directionality', 'emoticons', 'paste', 'textcolor', 'code'
                    ],
                    toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blocks",
                    toolbar2: "| link unlink anchor | image media | forecolor backcolor | print preview code | fontfamily fontsize",
                    image_advtab: true,
                    templates: [{
                            title: 'Test template 1',
                            content: 'Test 1'
                        },
                        {
                            title: 'Test template 2',
                            content: 'Test 2'
                        }
                    ],
                    content_css: [
                        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                        '//www.tinymce.com/css/codepen.min.css'
                    ],
                    skin: 'tinymce-5',
                    relative_urls: false,
                    remove_script_host: false
                });
            });
        </script>
    @endpush
