@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="row" style="margin-bottom:3px; margin-top:10px;">
    <div class="col-lg-12 col-md-12">
        <div class="header_top">
            <div class="header_top_left" style="margin-bottom:0px;">
                <ul class="top_nav">
                    <li>
                        <table>
                            <tr>
                                <td class="hidden-xs"><img class="tlClogo" src="{{ gambar_desa($desa['logo']) }}" width="30" valign="top" alt="{{ $desa['nama_desa'] }}" /></td>
                                <td>
                                    <a href="{{ ci_route('/') }}">
                                        <font size="4">{{ setting('website_title') . ' ' . ucwords(setting('sebutan_desa')) . ($desa['nama_desa'] ? ' ' . $desa['nama_desa'] : '') }}</font><br />
                                        <font size="2">
                                            {{ ucwords(setting('sebutan_kecamatan_singkat') . ' ' . $desa['nama_kecamatan']) }}
                                            {{ ucwords(setting('sebutan_kabupaten_singkat') . ' ' . $desa['nama_kabupaten']) }}
                                            {{ ucwords('Prov. ' . $desa['nama_propinsi']) }}
                                        </font>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </li>
                </ul>
            </div>
            <div class="navbar-right hidden-xs" style="margin-right: 15px; margin-top: 15px;">
                @foreach ($sosmed as $data)
                    @if (!empty($data['link']))
                        <a href="{{ $data['link'] }}" rel="noopener noreferrer" style="padding:2px;">
                            <i class="fa fa-{{ strtolower($data['nama']) }}-square fa-2x"></i>
                            @if (strtolower($data['nama']) == 'whatsapp' || strtolower($data['nama']) == 'instagram' || strtolower($data['nama']) == 'telegram')
                                <i class="fa fa-{{ strtolower($data['nama']) }} fa-2x"></i>
                            @endif
                        </a>
                    @endif
                @endforeach
                <a href="{{ ci_route('feed') }}" rel="noopener noreferrer" target="_blank">
                    <i class="fa fa-rss fa-2x"></i>
                </a>
            </div>
            <div class="visible-xs" style="margin-bottom: 5px;">
                <form method="get" action="{{ ci_route('first') }}" class="form-inline">
                    <table align="center">
                        <tr>
                            <td><input type="text" name="cari" maxlength="50" class="form-control" value="{{ old('cari') }}" placeholder="Cari Artikel"></td>
                            <td><button type="submit" class="btn btn-primary">Cari</button></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="catgimg2_container" style="margin-bottom:3px;">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
        </ol>
        <div class="carousel-inner">
            <div class="item active">
                <img class="tlClogo" src="{{ theme_asset('images/bg_header.jpg') }}">
            </div>
            <div class="item">
                <img class="tlClogo" src="{{ theme_asset('images/bg_header.jpg') }}">
            </div>
        </div>

        <a class="carousel-control left" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="carousel-control right" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
    $('.tlClogo').bind('contextmenu', function(e) {
        return false;
    });
</script>
