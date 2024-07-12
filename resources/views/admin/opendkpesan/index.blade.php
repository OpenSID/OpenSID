@extends('admin.layouts.index')

@section('title')
    <h1>Pesan</h1>
@endsection

@section('breadcrumb')
    <li class="active">Pesan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-4 col-lg-3">
            @if (can('u'))
                <a href="{{ site_url('opendk_pesan/form') }}" class="btn btn-primary btn-block margin-bottom">Buat Pesan</a>
            @endif
            <div class="box box-solid">
                <div class="box-body no-padding">
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            <li {{ jecho($selected_nav, 'pesan', 'class="active"') }}><a href="{{ ci_route('opendk_pesan.clear') }}">Pesan</a></li>
                            <li {{ jecho($selected_nav, 'arsip', 'class="active"') }}><a href="{{ ci_route('opendk_pesan.clear.arsip') }}">Arsip</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            @if (can('h') && $selected_nav != 'arsip')
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Label</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <div class="box-footer no-padding">
                            {!! form_open(site_url('opendk_pesan/filter/status'), 'id="label" name="label"', ['status' => '1']) !!}
                            <ul class="nav nav-stacked" id="label">
                                <li {{ jecho($status, '1', 'class="active"') }} data-status='1'><a href="javascript:;">Sudah Dibaca</a></li>
                                <li {{ jecho($status, '0', 'class="active"') }} data-status='0'><a href="javascript:;">Belum Dibaca</a></li>
                            </ul>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-8 col-lg-9">

            <div class="box box-info">
                <div class="box-header with-border">
                    <div class="col-sm-4 col-md-2  pull-right">
                        {!! form_open('', 'id="mainform" name="mainform"') !!}
                        <div class="input-group input-group-sm">
                            <input
                                name="cari"
                                id="cari"
                                class="form-control ui-autocomplete-input"
                                placeholder="Cari..."
                                type="text"
                                value="{{ $cari }}"
                                onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '{{ site_url('opendk_pesan/search') }}');$('#'+'mainform').submit();}"
                                autocomplete="off"
                            >
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default" onclick="$('#mainform').attr('action', '{{ site_url('opendk_pesan/search/') }}');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        </form>
                    </div>

                    <a href="{{ ci_route('opendk_pesan.clear') }}" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
                    </form>
                </div>
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tabeldata">
                                <thead>
                                    <tr>
                                        <th class="padat">No</th>
                                        <th class="padat">Aksi</th>
                                        <th>Judul</th>
                                        <th>Tipe</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pesan as $key => $data)
                                        <tr @if ($data->sudah_dibaca == 0) class="info" @endif>

                                            <td>{{ $key + 1 }}</td>
                                            <td class="aksi">
                                                @if (can('u'))
                                                    <a href="{{ site_url('opendk_pesan/show/' . $data->id) }}" title="Tampilkan Pesan" class="btn bg-blue btn-flat btn-sm"><i class="fa fa-eye"></i></a>
                                                @endif
                                            </td>
                                            <td> {{ $data->judul }} - {{ strip_tags($data->detailpesan[0]->text) ?? '' }} </td>
                                            <td> {{ $data->jenis == 'Pesan Masuk' ? 'Pesan Keluar' : 'Pesan Masuk' }} </td>
                                            <td>
                                                @if ($data->sudah_dibaca == 0)
                                                    <span class="label label-warning">Belum dibaca</span>
                                                @else
                                                    <span class="label label-success">Sudah dibaca</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">Tidak ada Pesan untuk ditampilkan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $pesan->links('vendor.pagination.simple-bootstrap') }}
                        </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @if (can('h'))
        <div class="modal fade" id="confirm-arsip" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-red"></i> Konfirmasi</h4>
                    </div>
                    <div class="modal-body btn-info">
                        Apakah Anda yakin ingin Arsipkan Pesan ini ?
                    </div>
                    <div class="modal-footer">
                        {!! form_open(site_url('opendk_pesan/arsipkan'), 'name="arsip"') !!}
                        <input type="hidden" name="array_id">
                        <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                        <a class="btn-ok">
                            <button type="button" class="btn btn-social btn-danger btn-sm" id="arsip-action"><i class="fa fa-trash-o"></i> Arsipkan</button>
                        </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@include('admin.layouts.components.asset_datatables')
@push('scripts')
    <script>
        $(function() {
            $('#label a').click(function(e) {
                e.preventDefault();
                var data = $(this).parent().data('status');
                $('input[name="status"]').val(data);
                $('form#label').submit();
            });
        });
    </script>
@endpush
