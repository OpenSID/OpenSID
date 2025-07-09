@push('styles')
    <style type="text/css">
        #agenda .tab-content {
            margin-top: 0px;
        }
    </style>
@endpush

<div class="single_bottom_rightbar">
    <h2><i class="fa fa-calendar"></i>&ensp;{{ $judul_widget }}</h2>
    <div id="agenda" class="box-body">
        <ul class="nav nav-tabs">
            @if (count($hari_ini ?? []) > 0)
                <li class="active"><a data-toggle="tab" href="#hari-ini">Hari ini</a></li>
            @endif
            @if (count($yad ?? []) > 0)
                <li class="@if (count($hari_ini ?? []) == 0) active @endif"><a data-toggle="tab" href="#yad">Mendatang</a></li>
            @endif
            @if (count($lama ?? []) > 0)
                <li class="@if (count(array_merge($hari_ini, $yad) ?? []) == 0) active @endif"><a data-toggle="tab" href="#lama">Lama</a></li>
            @endif
        </ul>
        <div class="tab-content">
            @php $merge = array_merge($hari_ini, $yad, $lama); @endphp
            @if (count($merge ?? []) > 0)
                <div id="hari-ini" class="tab-pane fade in active">
                    <ul class="sidebar-latest">
                        @foreach ($hari_ini as $agenda)
                            <li>
                                <table id="table-agenda" width="100%">
                                    <tr>
                                        <td colspan="3"><a href="{{ site_url('artikel/' . buat_slug($agenda)) }}">{{ $agenda['judul'] }}</a></td>
                                    </tr>
                                    <tr>
                                        <th id="label-meta-agenda" width="30%">Waktu</th>
                                        <td width="5%">:</td>
                                        <td id="isi-meta-agenda" width="65%">{{ tgl_indo2($agenda['tgl_agenda']) }}</td>
                                    </tr>
                                    <tr>
                                        <th id="label-meta-agenda">Lokasi</th>
                                        <td>:</td>
                                        <td id="isi-meta-agenda">{{ $agenda['lokasi_kegiatan'] }}</td>
                                    </tr>
                                    <tr>
                                        <th id="label-meta-agenda">Koordinator</th>
                                        <td>:</td>
                                        <td id="isi-meta-agenda">{{ $agenda['koordinator_kegiatan'] }}</td>
                                    </tr>
                                </table>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div id="yad" class="tab-pane fade @if (count($hari_ini ?? []) == 0) in active @endif">
                    <ul class="sidebar-latest">
                        @if (count($yad ?? []) > 0)
                            @foreach ($yad as $agenda)
                                <li>
                                    <table id="table-agenda" width="100%">
                                        <tr>
                                            <td colspan="3"><a href="{{ site_url('artikel/' . buat_slug($agenda)) }}">{{ $agenda['judul'] }}</a></td>
                                        </tr>
                                        <tr>
                                            <th id="label-meta-agenda" width="30%">Waktu</th>
                                            <td width="5%">:</td>
                                            <td id="isi-meta-agenda" width="65%">{{ tgl_indo2($agenda['tgl_agenda']) }}</td>
                                        </tr>
                                        <tr>
                                            <th id="label-meta-agenda">Lokasi</th>
                                            <td>:</td>
                                            <td id="isi-meta-agenda">{{ $agenda['lokasi_kegiatan'] }}</td>
                                        </tr>
                                        <tr>
                                            <th id="label-meta-agenda">Koordinator</th>
                                            <td>:</td>
                                            <td id="isi-meta-agenda">{{ $agenda['koordinator_kegiatan'] }}</td>
                                        </tr>
                                    </table>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div id="lama" class="tab-pane fade @if (count($merge ?? []) == 0) in active @endif">
                    akasih
                    <marquee
                        onmouseover="this.stop()"
                        onmouseout="this.start()"
                        scrollamount="2"
                        direction="up"
                        width="100%"
                        height="100"
                        align="center"
                        behavior="alternate"
                    >
                        <ul class="sidebar-latest">
                            @foreach ($lama as $agenda)
                                <li>
                                    <table id="table-agenda" width="100%">
                                        <tr>
                                            <td colspan="3"><a href="{{ site_url('artikel/' . buat_slug($agenda)) }}">{{ $agenda['judul'] }}</a></td>
                                        </tr>
                                        <tr>
                                            <th id="label-meta-agenda" width="30%">Waktu</th>
                                            <td width="5%">:</td>
                                            <td id="isi-meta-agenda" width="65%">{{ tgl_indo2($agenda['tgl_agenda']) }}</td>
                                        </tr>
                                        <tr>
                                            <th id="label-meta-agenda">Lokasi</th>
                                            <td>:</td>
                                            <td id="isi-meta-agenda">{{ $agenda['lokasi_kegiatan'] }}</td>
                                        </tr>
                                        <tr>
                                            <th id="label-meta-agenda">Koordinator</th>
                                            <td>:</td>
                                            <td id="isi-meta-agenda">{{ $agenda['koordinator_kegiatan'] }}</td>
                                        </tr>
                                    </table>
                                </li>
                            @endforeach
                        </ul>
                    </marquee>
                </div>
            @else
                <p>Belum ada agenda</p>
            @endif
        </div>
    </div>
</div>
