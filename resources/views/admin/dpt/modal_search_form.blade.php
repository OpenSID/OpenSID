<div class="modal fade" id="modal-search-form" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Pencarian Spesifik</h4>
            </div>
            <form action="">
                <div class="modal-body search-advance">

                    <div class="row">
                        <div class="col-sm-12">
                            <label for="nama">Umur</label>
                        </div>
                        @if ($input_umur)
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <input
                                        class="form-control input-sm bilangan"
                                        maxlength="3"
                                        type="text"
                                        placeholder="Dari"
                                        id="umur_min"
                                        name="umur_min"
                                        value="{{ $umur_min }}"
                                    />
                                </div>
                            </div>

                            <div class="col-sm-5">
                                <div class="form-group">
                                    <input
                                        id="umur_max"
                                        class="form-control input-sm bilangan"
                                        maxlength="3"
                                        type="text"
                                        placeholder="Sampai"
                                        name="umur_max"
                                        value="{{ $umur_max }}"
                                    />
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <select class="form-control input-sm select2" id="umur" name="umur">
                                        <option value="tahun">Tahun</option>
                                        <option value="bulan">Bulan</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if ($list_pekerjaan)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="pekerjaan">Pekerjaan</label>
                                    <select class="form-control input-sm select2" id="pekerjaan_id" name="pekerjaan_id">
                                        <option value=""> -- </option>
                                        @foreach ($list_pekerjaan as $data)
                                            <option value="{{ $data['id'] }}">{{ $data['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if ($list_status_kawin)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="status_kawin">Status Perkawinan</label>
                                    <select class="form-control input-sm select2" id="status_kawin" name="status_kawin">
                                        <option value=""> -- </option>
                                        @foreach ($list_status_kawin as $data)
                                            <option value="{{ $data['id'] }}">{{ $data['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if ($list_agama)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="agama">Agama</label>
                                    <select class="form-control input-sm select2" id="agama_id" name="agama_id">
                                        <option value=""> -- </option>
                                        @foreach ($list_agama as $data)
                                            <option value="{{ $data['id'] }}">{{ $data['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if ($list_pendidikan)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="pendidikan_sedang_id">Pendidikan Sedang Ditempuh</label>
                                    <select class="form-control input-sm select2" id="pendidikan_sedang_id" name="pendidikan_sedang_id">
                                        <option value=""> -- </option>
                                        @foreach ($list_pendidikan as $data)
                                            <option value="{{ $data['id'] }}">{{ $data['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if ($list_pendidikan_kk)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="pendidikan_kk_id">Pendidikan Dalam KK</label>
                                    <select class="form-control input-sm select2" id="pendidikan_kk_id" name="pendidikan_kk_id">
                                        <option value=""> -- </option>
                                        @foreach ($list_pendidikan_kk as $data)
                                            <option value="{{ $data['id'] }}">{{ $data['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if ($list_status_penduduk)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="status_penduduk">Status Penduduk</label>
                                    <select class="form-control input-sm select2" id="status" name="status">
                                        <option value=""> -- </option>
                                        @foreach ($list_status_penduduk as $data)
                                            <option value="{{ $data['id'] }}">{{ $data['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if ($list_tag_id_card)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="tag_id_card">Kepemilikan Tag ID Card</label>
                                    <select class="form-control input-sm select2" id="tag_id_card" name="tag_id_card">
                                        <option value=""> -- </option>
                                        @foreach ($list_tag_id_card as $key => $value)
                                            <option value="{{ $key }}">{{ strtoupper($value) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    {!! batal() !!}
                    <button type="button" id="btnSearchAdvance" class="btn btn-social btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
                </div>
        </div>
        </form>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('bootstrap/js/jquery.inputmask.js') }}"></script>
    <script>
        $(function() {
            $("input.bilangan").inputmask({
                mask: 9999,
                autoUnmask: true
            });
        })
        $('#umur_min').on('input', function(e) {
            var min = $(this).val();
            var max = $('#umur_max').val();

            if (min) {
                $('#umur_max').prop('class', 'required')
            } else {
                $('#umur_max').removeClass('required')
            }
            $(this).prop('max', max)
        });

        $('#umur_max').on('input', function(e) {
            var max = $(this).val();
            var min = $('#umur_min').val();

            if (max) {
                $('#umur_min').prop('class', 'required')
            } else {
                $('#umur_min').removeClass('required')
            }
            $(this).prop('min', min)
        });
    </script>
@endpush
