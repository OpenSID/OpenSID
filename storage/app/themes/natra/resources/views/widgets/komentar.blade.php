@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="single_bottom_rightbar">
    <h2><i class="fa fa-comments"></i>&ensp;{{ $judul_widget }}</h2>
    <div id="mostPopular2" class="tab-pane fade in active" role="tabpanel">
        <ul id="ul-menu">
            <div class="box-body">
                <div class="marquee-vertical-wrapper" style="height:280px; overflow:hidden;">
                    <div class="marquee-track-vertical">
                        @php $komens = $komen @endphp
                        @for ($i = 0; $i < 2; $i++)
                            <ul class="sidebar-latest">
                                @foreach ($komens as $data)
                                    <li>
                                        <table class="table table-bordered table-striped dataTable table-hover">
                                            <thead class="bg-gray disabled color-palette">
                                                <tr>
                                                    <th><i class="fa fa-comment"></i> {{ $data['pengguna']['nama'] }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <font color='green'><small>{{ tgl_indo2($data['tgl_upload']) }}</small></font><br />
                                                        {{ potong_teks($data['komentar'], 50) }}...<a href="{{ site_url('artikel/' . buat_slug($data)) }}">selengkapnya</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </li>
                                @endforeach
                            </ul>
                        @endfor
                    </div>
                </div>
            </div>
        </ul>
    </div>
</div>
