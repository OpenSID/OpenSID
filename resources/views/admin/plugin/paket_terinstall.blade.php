<div class="tab-pane active">
    @php $belumVerif = json_decode($paket_belum_verif ?? '[]', true) ?: []; @endphp
    @if (!empty($belumVerif))
        <div class="callout callout-warning">
            <h4><i class="fa fa-exclamation-triangle"></i> Paket belum terverifikasi</h4>
            <p style="margin-bottom:0">
                Terpasang tetapi hak pakainya belum dapat diverifikasi (belum terdaftar di bursa paket lokal,
                atau tanpa token/Layanan aktif): <strong>{{ implode(', ', $belumVerif) }}</strong>.
                Fitur dinonaktifkan hingga terverifikasi.
            </p>
        </div>
    @endif
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
            let paketTersediaSumber = {!! $paket_tersedia_sumber ?? '[]' !!}
            let paketCachedData = JSON.parse(localStorage.getItem('paketCachedData') || '{}')
            const defaultThumbnail = '{{ $default_thumbnail }}'

            // Tandai tiap kartu: hak-pakai terverifikasi bila sumber aktif menjaminnya
            // (dikembalikan Layanan = langganan aktif, ATAU terdaftar di bursa paket lokal).
            function tandaiTerverifikasi(cards, metaByName) {
                metaByName = metaByName || {}
                cards.forEach(function(c) {
                    c.terverifikasi = (metaByName[c.name] !== undefined) || paketTersediaSumber.indexOf(c.name) !== -1
                })
                return cards
            }

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
                    // Terpasang tapi hak-pakai belum diverifikasi sumber aktif → beri peringatan.
                    if (packageData.terverifikasi === false) {
                        displayDescription =
                            '<span class="label label-warning"><i class="fa fa-exclamation-triangle"></i> Belum terverifikasi</span>' +
                            '<p style="margin-top:5px"><small class="text-muted">Terpasang, tetapi hak pakainya belum dapat diverifikasi ' +
                            '(tanpa token/Layanan aktif, atau belum terdaftar di bursa paket lokal). Fitur dinonaktifkan hingga terverifikasi.</small></p>'
                    }
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
                            let isDefault = paketBawaan.includes(packageName)
                            cachedPackages.push({
                                name: packageName,
                                version: '-',
                                description: isDefault ? 'Paket default untuk OpenSID' : 'Paket tambahan untuk OpenSID',
                                thumbnail: defaultThumbnail,
                                price: 'Gratis',
                                totalInstall: '-'
                            })
                        }
                    }
                    displayInstalledPackages(tandaiTerverifikasi(cachedPackages, {}), paketBawaan)
                    return
                }

                $.ajax({
                    url: urlModule,
                    data: {
                        per_page: 10000,
                        list_module: paketTerpasangNames
                    },
                    method: 'GET',
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
                        displayInstalledPackages(tandaiTerverifikasi(cachedPackages, {}), paketBawaan)
                    },
                    success: function(response) {
                        const data = response.data || []
                        // Cache data untuk penggunaan offline/token expired
                        let metaByName = {}
                        for (let i in data) {
                            metaByName[data[i].name] = data[i]
                            paketCachedData[data[i].name] = data[i]
                        }
                        localStorage.setItem('paketCachedData', JSON.stringify(paketCachedData))

                        // Digerakkan daftar TERPASANG (bukan respons Layanan): tiap add-on
                        // yang terpasang tetap tampil walau Layanan tak mengembalikannya;
                        // diperkaya metadata Layanan bila tersedia.
                        let installedCards = []
                        for (let i in paketTerpasangNames) {
                            let packageName = paketTerpasangNames[i]
                            installedCards.push(metaByName[packageName] || paketCachedData[packageName] || {
                                name: packageName,
                                version: '-',
                                description: paketBawaan.includes(packageName) ? 'Paket default untuk OpenSID' : 'Paket tambahan untuk OpenSID',
                                thumbnail: defaultThumbnail,
                                price: 'Gratis',
                                totalInstall: '-'
                            })
                        }
                        displayInstalledPackages(tandaiTerverifikasi(installedCards, metaByName), paketBawaan)
                    }
                })
            }

            if (paketTerpasangNames && Object.keys(paketTerpasangNames).length > 0) {
                loadModule()
            }
        })
    </script>
@endpush
