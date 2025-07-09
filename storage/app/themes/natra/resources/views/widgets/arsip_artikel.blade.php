@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="single_bottom_rightbar">
    <h2>
        <i class="fa fa-archive"></i> <a href="{{ site_url('arsip') }}">&ensp;{{ $judul_widget }}</a>
    </h2>
    <ul role="tablist" class="nav nav-tabs custom-tabs">
        <li class="active" role="presentation"><a data-toggle="tab" role="tab" aria-controls="home" href="#terkini">Terbaru</a></li>
        <li role="presentation"><a data-toggle="tab" role="tab" aria-controls="messages" href="#populer">Populer</a>
        </li>
        <li role="presentation"><a data-toggle="tab" role="tab" aria-controls="messages" href="#acak">Acak</a></li>
    </ul>
    <div class="tab-content">
        @foreach (['terkini' => 'arsip_terkini', 'populer' => 'arsip_populer', 'acak' => 'arsip_acak'] as $jenis => $jenis_arsip)
            <div id="{{ $jenis }}" class="tab-pane fade in @if ($jenis == 'terkini') active @endif" role="tabpanel">
                <table id="ul-menu">
                    @foreach ($$jenis_arsip as $arsip)
                        <tr>
                            <td colspan="2">
                                <span class="meta_date">{{ tgl_indo($arsip['tgl_upload']) }} | <i class="fa fa-eye"></i> {{ hit($arsip['hit']) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="justify">
                                <a href="{{ site_url('artikel/' . buat_slug($arsip)) }}">
                                    @if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip['gambar']))
                                        <img width="25%" style="float:left; margin:0 8px 4px 0;" class="yall_lazy img-fluid img-thumbnail" src="{{ asset('images/img-loader.gif') }}" data-src="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip['gambar']) }}" />
                                    @else
                                        <img width="25%" style="float:left; margin:0 8px 4px 0;" class="yall_lazy img-fluid img-thumbnail" src="{{ asset('images/img-loader.gif') }}" data-src="{{ asset(FOTO_TIDAK_TERSEDIA) }}" />
                                    @endif
                                    <small>
                                        <font color="green">{{ $arsip['judul'] }}</font>
                                    </small>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach
    </div>
</div>
