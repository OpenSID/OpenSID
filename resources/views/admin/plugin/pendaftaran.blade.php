@include('admin.layouts.components.asset_validasi')

<div class="tab-pane active">
    <div class="row" id="list-paket">
        <div class="col-md-12">
            <div class="box box-info">
                {!! form_open($form_action, 'class="form-horizontal" enctype="multipart/form-data" id="validasi"') !!}
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="module_name">Nama Modul</label>
                        <div class="col-sm-8">
                            <select class="form-control input-sm select2 required" id="module_name"
                                name="module_name" style="width: 100%;">
                                <option value=''>-- Pilih Modul --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="nukti">Bukti Pembayaran</label>
                        <div class="col-sm-6">
                            <div class="input-group input-group-sm col-sm-12">
                                <input type="text" class="form-control" id="file_path">
                                <input type="file" class="hidden required" id="file" name="bukti"
                                    accept=".gif,.jpg,.jpeg,.png,.pdf">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" id="file_browser"><i
                                            class="fa fa-search"></i> Browse</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="tanggal_pembayaran">Tanggal Pembayaran</label>
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control input-sm pull-right required" id="tanggal_pembayaran"
                                    name="tanggal_pembayaran" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="tanggal_nota">Tanggal Nota</label>
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control input-sm pull-right required" id="tanggal_nota"
                                    name="tanggal_nota" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea id="keterangan" name="keterangan" class="form-control input-sm required"
                                placeholder="Isi Keterangan" rows="3" style="resize:none;">{{ $pendaftaran->keterangan }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="nominal">Nominal</label>
                        <div class="col-sm-8">
                            <input id="nominal" name="nominal" class="form-control input-sm required" type="text" placeholder="nominal" disabled>

                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"
                        onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i
                            class="fa fa-check"></i> Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

</div>
@push('scripts')
    <script>
        $(function() {

            $('.date input').datetimepicker({
                format: 'DD-MM-YYYY',
                locale:'id'
            });

            $('#nominal').on('input', function () {
                let val = $(this).val().replace(/[^\d]/g, '');
                let formatted = val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(formatted);
            });

            loadModule()

            function loadModule() {
                let urlModule = '{{ $url_marketplace }}'
                $.ajax({
                    url: urlModule,
                    type: 'GET',
                    contentType: 'application/json',
                    headers: {
                        'Authorization': 'Bearer {{ $token_layanan }}',
                        'Accept': 'application/json'
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat Data',
                            text: response.responseJSON.message
                        })
                    },
                    success: function(response) {
                        const data = response.data || [];

                        console.log(data);

                        const $select = $('#module_name');
                        $select.empty().append(`<option value=''>-- Pilih Modul --</option>`);

                        data.forEach(item => {
                            const name = item.name;
                            const price = item.price;

                            let harga = Number(price.replace(/[^\d]/g, ''));

                            if (name) {
                                $select.append(`<option value="${name}" data-harga="${harga}">${name}</option>`);
                            }
                        });

                        $select.trigger('change');
                    }
                })
            }

            $('#module_name').on('change', function() {
                const selectedOption = $(this).find(':selected');
                const harga = selectedOption.data('harga');

                if (harga !== undefined && harga !== null) {
                    $('#nominal').val(harga);
                } else {
                    $('#nominal').val('');
                }
            });
        })
    </script>
@endpush
