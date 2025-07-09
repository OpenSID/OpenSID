<div class="tab-pane active">
    <div class="row" id="list-paket">
        {!! form_open(ci_route('plugin.hapus'), 'id="mainform" name="mainform"') !!}
        <input type="hidden" name="name" value="">
        @if (!$paket_terpasang)
            <div class="col-md-12">
                <div class="alert alert-warning">Belum ada paket yang terpasang</div>
            </div>
        @endif
        </form>
    </div>
</div>
@push('scripts')
    <script>
        $(function() {
            let paketTerpasang = {!! $paket_terpasang !!}

            function loadModule() {
                let cardView = [],
                    disabledPaket, buttonInstall, versionCheck, templateTmp
                let urlModule = '{{ $url_marketplace }}'
                const templateCard = `@include('admin.plugin.item')`

                $.ajax({
                    url: urlModule,
                    data: {
                        per_page: 10000,
                        list_module: paketTerpasang
                    },
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
                        const data = response.data
                        for (let i in data) {
                            templateTmp = templateCard
                            disabledPaket = ''
                            buttonInstall = `<button type="button" name="pasang" value="${data[i].name}" class="btn btn-danger">Hapus</button>`

                            templateTmp = templateTmp.replace('__name__', data[i].name)
                            templateTmp = templateTmp.replace('__description__', data[i].description)
                            templateTmp = templateTmp.replace('__button__', buttonInstall)
                            templateTmp = templateTmp.replace('__thumbnail__', data[i].thumbnail)
                            templateTmp = templateTmp.replace('__price__', data[i].price)
                            templateTmp = templateTmp.replace('__totalInstall__', data[i].totalInstall)
                            cardView.push(templateTmp)
                        }

                        $('#mainform').append(cardView.join(''))
                        $('#mainform button:button').click(function(e) {
                            e.preventDefault();

                            Swal.fire({
                                title: 'Apakah anda sudah melakukan backup database dan folder desa ?',
                                showDenyButton: true,
                                confirmButtonText: 'Sudah',
                                denyButtonText: `Belum`,
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    Swal.fire({
                                        title: 'Sedang Memproses',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        showConfirmButton: false,
                                        didOpen: () => {
                                            Swal.showLoading()
                                        }
                                    });
                                    // csrf tidak sama, coba update manual saja
                                    $(e.currentTarget).closest('form').find('input[name=sidcsrf]').val(getCsrfToken())
                                    $(e.currentTarget).closest('form').find('input[name=name]').val($(e.currentTarget).val())
                                    $(e.currentTarget).closest('form').submit()
                                }
                            })
                        })
                    }
                })
            }

            if (paketTerpasang) {
                loadModule()
            }
        })
    </script>
@endpush
