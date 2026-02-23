<div id="isi_popup_rw">
    @foreach ($rw_gis as $key_rw => $rw)
        <div id="isi_popup_rw_{{ $key_rw }}" style="visibility: hidden;">
            @php
                $link = underscore($rw['dusun']) . '/' . underscore($rw['rw']);
                $data_title = " RW {$rw['rw']} {$wilayah} {$rw['dusun']}";
            @endphp
            <div id="content">
                <h5 id="firstHeading" class="firstHeading">Wilayah RW {{ $rw['rw'] . ' ' . ucwords(setting('sebutan_dusun')) . ' ' . $rw['dusun'] }}</h5>
                <p><a
                        href="#collapseStatPenduduk_{{ $key_rw}}"
                        class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block btn-modal"
                        title="Statistik Penduduk"
                        
                        data-target="#collapseStatPenduduk_{{ $key_rw}}"
                        aria-expanded="false"
                        aria-controls="collapseStatPenduduk_{{ $key_rw}}"
                    ><i class="fa fa-bar-chart"></i>Statistik Penduduk</a></p>
                <div class="box-body no-padding" id="collapseStatPenduduk_{{ $key_rw}}" style="display: none;">
                    <div id="bodyContent">
                        <div class="card card-body">
                            <ol class="list-unstyled">
                                @foreach ($list_ref as $key => $value)
                                    <li class="@active($lap == $key)"><a href="{{ ci_route("statistik_web.chart_gis_rw.{$key}.{$link}") }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk {{ $data_title }}">{{ $value }}</a></li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>

                <p><a
                        href="#collapseStatBantuan_{{ $key_rw}}"
                        class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block btn-modal"
                        title="Statistik Bantuan"
                        
                        data-target="#collapseStatBantuan_{{ $key_rw}}"
                        aria-expanded="false"
                        aria-controls="collapseStatBantuan_{{ $key_rw}}"
                    ><i class="fa fa-heart"></i>Statistik Bantuan</a></p>
                <div class="box-body no-padding" id="collapseStatBantuan_{{ $key_rw}}" style="display: none;">
                    <div class="card card-body">
                        <ol class="list-unstyled">
                            @foreach ($list_bantuan as $key => $value)
                                <li class="@active($lap == $key)"><a href="{{ ci_route("statistik_web.chart_gis_rw.{$key}.{$link}") }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Bantuan RW {{ $data_title }}">{{ $value }}</a></li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
