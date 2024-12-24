<div id="isi_popup_dusun">
    @foreach ($dusun_gis as $key_dusun => $dusun)
        <div id="isi_popup_dusun_{{ $key_dusun }}" style="visibility: hidden;">
            <div id="content">
                @php
                    $link = underscore($dusun['dusun']);
                    $data_title = "{$wilayah} {$dusun['dusun']}";
                @endphp
                <h5 id="firstHeading" class="firstHeading">Wilayah {{ $data_title }}</h5>
                <p><a
                        href="#collapseStatPenduduk"
                        class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block btn-modal"
                        title="Statistik Penduduk"
                        data-toggle="collapse"
                        data-target="#collapseStatPenduduk"
                        aria-expanded="false"
                        aria-controls="collapseStatPenduduk"
                    ><i class="fa fa-bar-chart"></i>Statistik Penduduk</a></p>
                <div class="collapse box-body no-padding" id="collapseStatPenduduk">
                    <div id="bodyContent">
                        <div class="card card-body">
                            @foreach ($list_ref as $key => $value)
                                <li {{ jecho($lap, $key, 'class="active"') }}><a href="{{ ci_route("statistik.chart_gis_dusun.{$key}.{$link}") }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk {{ $data_title }}">{{ $value }}</a></li>
                            @endforeach
                        </div>
                    </div>
                </div>

                <p><a
                        href="#collapseStatBantuan"
                        class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block btn-modal"
                        title="Statistik Bantuan"
                        data-toggle="collapse"
                        data-target="#collapseStatBantuan"
                        aria-expanded="false"
                        aria-controls="collapseStatBantuan"
                    ><i class="fa fa-heart"></i>Statistik Bantuan</a></p>
                <div class="collapse box-body no-padding" id="collapseStatBantuan">
                    <div class="card card-body">
                        @foreach ($list_bantuan as $key => $value)
                            <li {{ jecho($lap, $key, 'class="active"') }}><a href="{{ ci_route("statistik.chart_gis_dusun.{$key}.{$link}") }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Bantuan {{ $data_title }}">{{ $value }}</a></li>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
