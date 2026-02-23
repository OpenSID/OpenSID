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
                        href="#collapseStatPenduduk_{{ $key_dusun }}"
                        class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block btn-modal"
                        title="Statistik Penduduk"
                        
                        data-target="#collapseStatPenduduk_{{ $key_dusun }}"
                        aria-expanded="false"
                        aria-controls="collapseStatPenduduk_{{ $key_dusun }}"
                    ><i class="fa fa-bar-chart"></i>Statistik Penduduk</a></p>
                <div class="box-body no-padding" id="collapseStatPenduduk_{{ $key_dusun }}" style="display: none;">
                    <div id="bodyContent">
                        <div class="card card-body">
                            <ol class="list-unstyled">
                                @foreach ($list_ref as $key => $value)
                                    <li class="@active($lap == $key)"><a href="{{ ci_route("statistik_web.chart_gis_dusun.{$key}.{$link}") }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk {{ $data_title }}">{{ $value }}</a></li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>

                <p><a
                        href="#collapseStatBantuan_{{ $key_dusun }}"
                        class="btn btn-social bg-navy btn-sm btn-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block btn-modal"
                        title="Statistik Bantuan"
                        
                        data-target="#collapseStatBantuan_{{ $key_dusun }}"
                        aria-expanded="false"
                        aria-controls="collapseStatBantuan_{{ $key_dusun }}"
                    ><i class="fa fa-heart"></i>Statistik Bantuan</a></p>
                <div class="box-body no-padding" id="collapseStatBantuan_{{ $key_dusun }}" style="display: none;">
                    <div class="card card-body">
                        <ol class="list-unstyled">
                            @foreach ($list_bantuan as $key => $value)
                                <li class="@active($lap == $key)"><a href="{{ ci_route("statistik_web.chart_gis_dusun.{$key}.{$link}") }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Bantuan {{ $data_title }}">{{ $value }}</a></li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
