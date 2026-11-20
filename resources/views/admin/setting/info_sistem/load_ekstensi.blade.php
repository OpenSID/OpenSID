{{-- Load Ekstensi Tab Content --}}
@if ($mysql['cek'])
    <div class="alert alert-success" role="alert">
        <p>Versi Database terpasang {{ $mysql['versi'] }} sudah memenuhi syarat.</p>
    </div>
@else
    <div class="alert alert-danger" role="alert">
        <p>Versi Database terpasang {{ $mysql['versi'] }} tidak memenuhi syarat.</p>
        <p>Update versi Database supaya minimal {{ $mysql['syarat']['mysql']['min'] }} dan maksimal {{ $mysql['syarat']['mysql']['max'] }}, atau MariaDB supaya minimal {{ $mysql['syarat']['mariadb']['min'] }}.</p>
    </div>
@endif

@if ($php['cek'])
    <div class="alert alert-success" role="alert">
        <p>Versi PHP terpasang {{ $php['versi'] }} sudah memenuhi syarat.</p>
    </div>
@else
    <div class="alert alert-danger" role="alert">
        <p>Versi PHP terpasang {{ $php['versi'] }} tidak memenuhi syarat.</p>
        <p>Update versi PHP supaya minimal {{ $php['syarat']['min'] }} dan maksimal {{ $php['syarat']['max'] }}.</p>
    </div>
@endif

@include('admin.setting.info_sistem.ioncube')
@if (!$ekstensi['lengkap'] || !$disable_functions['lengkap'])
    <div class="alert alert-danger" role="alert">
        <p>Ada beberapa ekstensi dan fungsi PHP wajib yang tidak tersedia di sistem anda.
            Karena itu, mungkin ada fungsi yang akan bermasalah.</p>
        <p>Aktifkan ekstensi dan fungsi PHP yang belum ada di sistem anda.</p>
    </div>
@else
    <p>
        Semua ekstensi PHP yang diperlukan sudah aktif di sistem anda.
    </p>
@endif

<div class="row">
    <div class="col-sm-6">
        <h4>EKSTENSI</h4>
        @foreach ($ekstensi['ekstensi'] as $key => $ext)
            <div class="form-group">
                <h5>
                    <i class="fa fa-{{ $ext['loaded'] ? 'check-circle-o' : 'times-circle-o' }} fa-lg"
                       style="color:{{ $ext['loaded'] ? 'green' : ($ext['dev_bypassed'] ? 'orange' : 'red') }}"></i>&nbsp;&nbsp;{{ $key }}
                    @if ($ext['dev_bypassed'])
                        <small style="color: orange"> &mdash; tidak diperlukan di lingkungan pengembangan</small>
                    @endif
                </h5>
            </div>
        @endforeach
    </div>
    @if ($disable_functions['functions'])
        <div class="col-sm-6">
            <h4>FUNGSI</h4>
            @foreach ($disable_functions['functions'] as $func => $val)
                <div class="form-group">
                    <h5><i class="fa fa-{{ $val ? 'check-circle-o' : 'times-circle-o' }} fa-lg" style="color:{{ $val ? 'green' : 'red' }}"></i>&nbsp;&nbsp;{{ $func }}</h5>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="row">
    <div class="col-sm-6">
        <h4>KEBUTUHAN SISTEM</h4>
        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Kebutuhan Sistem</th>
                                    <th>Nilai Saat Ini</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kebutuhan_sistem as $key => $val)
                                    <tr>
                                        <td class="text">{{ "{$key} ({$val['required']})" }}</td>
                                        <td class="text">{{ $val['current'] }}</td>
                                        <td>
                                            <i class="fa fa-{{ $val['result'] ? 'check-circle-o' : 'times-circle-o' }} fa-lg" style="color:{{ $val['result'] ? 'green' : 'red' }}"></i>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-warning" role="alert" style="margin-top: 15px;">
                        <p><strong>Catatan Penting:</strong> Selain konfigurasi PHP di atas, web server Anda (seperti Nginx, Apache, atau OpenLiteSpeed) juga memiliki batas ukuran request tersendiri:</p>
                        <ul>
                            <li><strong>Nginx:</strong> <code>client_max_body_size</code> (default 1M)</li>
                            <li><strong>Apache:</strong> <code>LimitRequestBody</code></li>
                            <li><strong>OpenLiteSpeed:</strong> <code>reqBodyMaxSize</code></li>
                        </ul>
                        <p>Pastikan administrator server Anda mengonfigurasi batas web server tersebut minimal <strong>256 MB</strong> (atau setara dengan batas PHP) agar proses backup/restore folder desa yang berukuran besar tidak mengalami error <em>413 Request Entity Too Large</em>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
