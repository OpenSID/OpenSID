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
            let paketBawaan = {!! $paket_bawaan !!}
            let paketTerpasangNames = {!! $paket_terpasang !!}
            let paketCachedData = JSON.parse(localStorage.getItem('paketCachedData') || '{}')
            const defaultThumbnail = '{{ $default_thumbnail }}'

            function displayInstalledPackages(data, paketBawaan = []) {
                let cardView = []
                const templateCard = `@include('admin.plugin.item')`

                for (let i in data) {
                    let templateTmp = templateCard
                    let packageData = data[i]
                    let isPackageDefault = paketBawaan.includes(packageData.name)
                    let buttonInstall = isPackageDefault 
                        ? `<button type="button" name="pasang" value="${packageData.name}" class="btn btn-danger" disabled>Hapus</button>` 
                        : `<button type="button" name="pasang" value="${packageData.name}" class="btn btn-danger">Hapus</button>`

                    // Gunakan cached data jika tersedia, untuk fallback
                    let displayName = packageData.name || '-'
                    let displayVersion = packageData.version || '-'
                    let displayDescription = packageData.description || 'Paket tambahan untuk OpenSID'
                    let displayThumbnail = packageData.thumbnail || defaultThumbnail
                    let displayPrice = packageData.price || 'Gratis'
                    let displayTotalInstall = packageData.totalInstall || '-'

                    templateTmp = templateTmp.replace('__name__', displayName)
                    templateTmp = templateTmp.replace('__version__', displayVersion)
                    templateTmp = templateTmp.replace('__description__', displayDescription)
                    templateTmp = templateTmp.replace('__button__', buttonInstall)
                    templateTmp = templateTmp.replace('__thumbnail__', displayThumbnail)
                    templateTmp = templateTmp.replace('__price__', displayPrice)
                    templateTmp = templateTmp.replace('__totalInstall__', displayTotalInstall)
                    cardView.push(templateTmp)
                }

                $('#mainform').append(cardView.join(''))
                $('#mainform button:button:not(:disabled)').click(function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Apakah Anda sudah melakukan backup database dan folder desa ?',
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

            function loadModule() {
                let urlModule = '{{ $url_marketplace }}'
                let token = '{{ $token_layanan }}'

                // Jika token tidak ada, tampilkan dari cache atau data lokal
                if (!token) {
                    // Coba gunakan cached data terlebih dahulu
                    let cachedPackages = []
                    for (let i in paketTerpasangNames) {
                        let packageName = paketTerpasangNames[i]
                        if (paketCachedData[packageName]) {
                            cachedPackages.push(paketCachedData[packageName])
                        } else {
                            // Jika tidak ada cache, buat data minimal dari nama paket
                            cachedPackages.push({
                                name: packageName,
                                version: '-',
                                description: 'Paket tambahan untuk OpenSID',
                                thumbnail: defaultThumbnail,
                                price: 'Gratis',
                                totalInstall: '-'
                            })
                        }
                    }
                    displayInstalledPackages(cachedPackages, paketBawaan)
                    return
                }

                $.ajax({
                    url: urlModule,
                    data: {
                        per_page: 10000,
                        list_module: paketTerpasangNames
                    },
                    type: 'GET',
                    contentType: 'application/json',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    },
                    error: function(response) {
                        // Jika token expired atau gagal koneksi, tampilkan dari cache atau data lokal
                        let cachedPackages = []
                        for (let i in paketTerpasangNames) {
                            let packageName = paketTerpasangNames[i]
                            if (paketCachedData[packageName]) {
                                cachedPackages.push(paketCachedData[packageName])
                            } else {
                                cachedPackages.push({
                                    name: packageName,
                                    version: '-',
                                    description: 'Paket tambahan untuk OpenSID',
                                    thumbnail: defaultThumbnail,
                                    price: 'Gratis',
                                    totalInstall: '-'
                                })
                            }
                        }
                        displayInstalledPackages(cachedPackages, paketBawaan)
                    },
                    success: function(response) {
                        const data = response.data
                        // Cache data untuk penggunaan offline/token expired
                        for (let i in data) {
                            paketCachedData[data[i].name] = data[i]
                        }
                        localStorage.setItem('paketCachedData', JSON.stringify(paketCachedData))
                        displayInstalledPackages(data, paketBawaan)
                    }
                })
            }

            if (paketTerpasangNames && Object.keys(paketTerpasangNames).length > 0) {
                loadModule()
            }
        })
    </script>
@endpush
