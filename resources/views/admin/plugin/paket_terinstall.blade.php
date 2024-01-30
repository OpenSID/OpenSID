<div class="tab-pane active">
    <div class="row" id="list-paket">
        <form id="form-paket" action="{{ ci_route('plugin.hapus') }}" method="post">
            <input type="hidden" name="name" value="">
            @if(! $paket_terpasang)
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
                              
                $.get(urlModule, {
                    per_page: 10000,
                    list_module: paketTerpasang
                }, function(response) {
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
                    
                    $('#form-paket').append(cardView.join(''))
                    $('#form-paket button:button').click(function(e) {
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
                                $(e.currentTarget).closest('form').find('input[name=name]').val($(e.currentTarget).val())
                                $(e.currentTarget).closest('form').submit()
                            }
                        })
                })

                }, 'json')
            }
            
            if (paketTerpasang) {
                loadModule()
            }
        })
    </script>
@endpush
