<div class="tab-pane" id="sumber-penduduk">
    <div class="box-body">
        <div class="callout callout-info">
            <p>{{ $list_setting->where('key', 'form_penduduk_luar')->first()->keterangan }}</p>
        </div>
        <div class="panel">
            <div class="panel-header">
                <h4 class="panel-title">
                    <a  onclick="$(this).find('i').toggleClass('fa-plus fa-minus')" data-toggle="collapse" class="btn btn-sm btn-social btn-success" href="#form-penduduk-luar"><i class="fa fa-plus"></i> Form Penduduk Luar</a>
                </h4>
            </div>
            <div id="form-penduduk-luar" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="form-group"> 
                        <label class="control-label col-sm-2" for="">Label</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control judul" />
                        </div>
                    </div>
                    <div class="form-group"> 
                        <label class="control-label col-sm-2" for="">Pilihan input</label>
                        <div class="col-sm-10">
                            <div class="checkbox">
                                <label><input type="checkbox" data-id="nama" checked disabled> Nama Lengkap</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" data-id="no_ktp" checked disabled> NIK / No. KTP</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" data-id="tempat_lahir"> Tempat lahir</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" data-id="tanggal_lahir"> Tanggal lahir</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" data-id="alamat"> Alamat</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" data-id="agama"> Agama</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" data-id="pekerjaan"> Pekerjaan</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" data-id="warga_negara"> Warga Negara</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="button" class="btn btn-sm btn-success form-penduduk-btn">Tambahkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-body" id="list-form-penduduk">
        @foreach($penduduk_luar as $index => $penduduk)
        <div class="col-sm-4">
            <div class="panel panel-primary">
                <span class="btn btn-danger pull-right" onclick="$(this).closest('.col-sm-4').remove()">
                    <i class="fa fa-trash"></i>
                </span>
                <div class="panel-heading">
                    <h4 class="panel-title">
                        {{ $penduduk['title'] }}
                    </h4>
                </div>
                <div class="panel-body">
                    <ul>
                        @foreach(explode(',', $penduduk['input']) as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                    <input type="hidden" value="{{ $penduduk['title'] }}" name="penduduk_luar[{{$index}}][title]" />
                    <input type="hidden" value='{{ $penduduk['input'] }}' name="penduduk_luar[{{$index}}][input]" />
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@push('scripts')
<script type = "text/javascript" >
    $(function () {        
        $('.form-penduduk-btn').on('click', function() {
            let _panel = $(this).closest('.panel')
            let _panelBody = _panel.find('.panel-body')
            let _judul = _panelBody.find('input.judul')
            if ($.isEmptyObject(_judul.val())) {
                Swal.fire(
                        'Error!  ',
                        'Judul harus diisi',
                        'error'
                ).then(
                        () => _judul.focus() 
                )
                return
            }
            let _selected = ['<ul>']
            let _input = [], _index = new Date().getTime()
            _panelBody.find(':checked').each(function(){
                _selected.push(`<li>${$(this).closest('label').text()}</li>`)
                _input.push($(this).data('id'))
            })
            _selected.push('</ul>')
            _selected.push(`<input type="hidden" value="${_judul.val()}" name="penduduk_luar[${_index}][title]">`)
            _selected.push(`<input type="hidden" value="${_input}" name="penduduk_luar[${_index}][input]">`)
            let _template = `
            <div class="col-sm-4">
                <div class="panel panel-primary">
                    <span class="btn btn-danger pull-right" onclick="$(this).closest('.col-sm-4').remove()">
                        <i class="fa fa-trash"></i>
                    </span>
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            ${_judul.val()}                            
                        </h4>
                    </div>
                    <div class="panel-body">
                        ${_selected.join('')}
                    </div>
                </div>
            </div>`
            
            $(_template).appendTo('#list-form-penduduk')
        })
    }); 
</script>
@endpush