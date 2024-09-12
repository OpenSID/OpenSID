<div id="isi_popup_rt">
    @foreach ($rt_gis as $key_rt => $rt)
        <div id="isi_popup_rt_{{ $key_rt }}" style="visibility: hidden;">
            @php
                $link = underscore($rt['dusun']) . '/' . underscore($rt['rw']) . '/' . underscore($rt['rt']);
                $data_title = " RT {$rt['rt']} RW {$rt['rw']} {$wilayah} {$rt['dusun']}";
            @endphp
            <div id="content">
                <h5 id="firstHeading" class="firstHeading">Wilayah RT {{ $rt['rt'] }} RW {{ $rt['rw'] . ' ' . ucwords(setting('sebutan_dusun')) . ' ' . $rt['dusun'] }}</h5>
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
                                <li {{ jecho($lap, $key, 'class="active"') }}><a href="{{ ci_route("statistik.chart_gis_rt.{$key}.{$link}") }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk {{ $data_title }}">{{ $value }}</a></li>
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
                            <li {{ jecho($lap, $key, 'class="active"') }}><a href="{{ ci_route("statistik.chart_gis_rt.{$key}.{$link}") }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Bantuan {{ $data_title }}">{{ $value }}</a></li>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
