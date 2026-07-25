<div class="tab-pane active">
    <div class="search">
        <div class="box box-info">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-4">
                        <select name="tipe" id="tipe" class="control-form select2">
                            <option value="">-Pilih tipe -</option>
                            <option value="gratis">Gratis</option>
                            <option value="premium">Premium</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="row" id="list-paket">
                {!! form_open(ci_route('plugin.pasang'), 'id="mainform" name="mainform"') !!}
                </form>
            </div>
            <ul class="pagination pagination-sm" id="pagination-container">

            </ul>
        </div>
    </div>
</div>

<!-- Modal Persetujuan Instalasi Paket Premium -->
<div class="modal fade" id="modalPersetujuanPaket" tabindex="-1" role="dialog" aria-labelledby="modalPersetujuanLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning" id="modalPersetujuanHeader">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalPersetujuanLabel"></h4>
            </div>
            <div class="modal-body" id="modalPersetujuanBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-social btn-default btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i>
                    Tutup
                </button>
                <button type="button" class="btn btn-social btn-success btn-sm" id="btnSetujuPasang">
                    <i class="fa fa-check"></i> Setuju & Lanjutkan Instalasi
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(function() {
            let pendingInstallValue = null;

            // Susun isi modal instalasi sesuai jenis paket:
            // - klien Layanan (Pelanggan): pengelola layanan non-aplikasi (hosting,
            //   pasang/pembaruan, verifikasi langganan & lisensi modul);
            // - modul berlisensi (mis. Anjungan): butuh "Lisensi <fitur>"
            //   (sekali bayar, berlaku selamanya);
            // - modul gratis lain: konfirmasi sederhana.
            function kontenModalPaket(nama, info) {
                const esc = (s) => $('<div>').text(s).html();
                if (info.isClient) {
                    return {
                        header: 'bg-info',
                        judul: '<i class="fa fa-plug"></i> &nbsp;Aktifkan Layanan Desa',
                        body: `<p>Modul <strong>${esc(nama)}</strong> adalah <strong>klien Layanan</strong> desa Anda. Memasangnya mengaktifkan pengelolaan layanan non-aplikasi:</p>
                            <ul>
                                <li>Langganan <strong>hosting</strong> desa</li>
                                <li>Layanan <strong>pemasangan &amp; pembaruan</strong> aplikasi</li>
                                <li>Verifikasi <strong>langganan/lisensi</strong> &amp; pengelolaan lisensi modul berbayar (mis. Anjungan)</li>
                            </ul>
                            <p class="text-muted">Modul ini gratis dan menjadi prasyarat sebelum memasang modul berlisensi.</p>`,
                    };
                }
                if (info.requiresEntitlement) {
                    const kunci = info.entitlement || nama;
                    const lisensi = 'Lisensi ' + kunci.charAt(0).toUpperCase() + kunci.slice(1);
                    return {
                        header: 'bg-info',
                        judul: '<i class="fa fa-key"></i> &nbsp;Modul Berlisensi',
                        body: `<div class="alert alert-info"><i class="fa fa-info-circle"></i> Modul ini memerlukan <strong>${esc(lisensi)}</strong> — sekali bayar, berlaku selamanya.</div>
                            <p>Modul <strong>${esc(nama)}</strong> memerlukan <strong>${esc(lisensi)}</strong>. Lisensi ini cukup dibeli satu kali dan berlaku permanen — tanpa perpanjangan maupun langganan berkala. Aktivasi lisensi dilakukan melalui modul <strong>Layanan (Pelanggan)</strong>.</p>
                            <ul>
                                <li><strong>Sekali bayar</strong> — lisensi berlaku selamanya, tanpa masa berlaku.</li>
                                <li>Lisensi tersendiri, dikelola lewat modul <strong>Layanan (Pelanggan)</strong>.</li>
                            </ul>`,
                    };
                }
                return {
                    header: 'bg-info',
                    judul: '<i class="fa fa-download"></i> &nbsp;Pasang Modul',
                    body: `<p>Pasang modul <strong>${esc(nama)}</strong>?</p>`,
                };
            }

            function compareVersions(version1, version2) {
                const splitVersion1 = version1.split('.');
                const splitVersion2 = version2.split('.');

                const maxLength = Math.max(splitVersion1.length, splitVersion2.length);

                for (let i = 0; i < maxLength; i++) {
                    const num1 = parseInt(splitVersion1[i]) || 0;
                    const num2 = parseInt(splitVersion2[i]) || 0;

                    if (num1 < num2) {
                        return -1;
                    } else if (num1 > num2) {
                        return 1;
                    }
                }

                return 0; // Versions are equal
            }

            function displayPagination(response) {
                // Populate the pagination container with links
                var paginationContainer = $('#pagination-container');
                paginationContainer.empty();
                const currentPage = response.meta.current_page
                const perPage = response.meta.per_page
                const totalPages = Math.ceil(response.meta.total / perPage)
                for (var i = 1; i <= totalPages; i++) {
                    // Create a link for each page
                    var pageLink = $('<li>', {
                        text: i,
                        html: `<a href="#">${i}</a>`,
                        click: function() {
                            // Fetch data for the clicked page
                            var page = $(this).text();
                            loadModule(page);
                        }
                    });

                    // Add an active class to the current page
                    if (i == currentPage) {
                        pageLink.addClass('active');
                    }

                    // Append the link to the container
                    paginationContainer.append(pageLink);
                }

                // Add "Previous" button
                if (currentPage > 1) {
                    var prevButton = $('<li>', {
                        text: i,
                        html: `<a href="#">Sebelumnya</a>`,
                        click: function() {
                            // Fetch data for the clicked page
                            var page = currentPage - 1;
                            loadModule(page);
                        }
                    });

                    prevButton.insertBefore(paginationContainer.find('li:first-child'));
                }

                // Add "Next" button
                if (currentPage < totalPages) {
                    var nextButton = $('<li>', {
                        text: i,
                        html: `<a href="#">Selanjutnya</a>`,
                        click: function() {
                            // Fetch data for the clicked page
                            var page = currentPage + 1;
                            loadModule(page);
                        }
                    });
                    paginationContainer.append(nextButton);
                }
            }

            function loadModule(page, tipe) {
                let paketTerpasang = {!! $paket_terpasang ?? '{}' !!}
                let klienTerpasang = {!! ($klien_terpasang ?? true) ? 'true' : 'false' !!}
                let cardView = [],
                    disabledPaket, buttonInstall, versionCheck, templateTmp
                let paketInfo = {}
                let urlModule = '{{ $url_marketplace }}'
                const templateCard = `@include('admin.plugin.item')`
                $('div#list-paket').find('form').empty()
                if (tipe === undefined) {
                    tipe = $('#tipe').val()
                }
                $.ajax({
                    url: urlModule,
                    data: {
                        page: page,
                        tipe: tipe
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
                            const installValue = `${data[i].name}___${data[i].url}___${data[i].version}`
                            paketInfo[data[i].name] = {
                                requiresEntitlement: !!data[i].requires_entitlement,
                                entitlement: data[i].entitlement || '',
                                isClient: !!data[i].is_client,
                            }
                            buttonInstall = `<button type="button" ${disabledPaket} name="pasang" value="${installValue}" class="btn btn-primary btn-pasang-paket">Pasang</button>`
                            if (paketTerpasang[data[i].name] !== undefined) {
                                versionCheck = compareVersions(data[i].version, paketTerpasang[data[i].name].version)
                                if (versionCheck > 0) {
                                    buttonInstall = `<button type="button" ${disabledPaket} name="pasang" value="${installValue}" class="btn btn-primary btn-pasang-paket">Tingkatkan Versi</button>`
                                } else {
                                    disabledPaket = 'disabled'
                                    buttonInstall = `<button type="button" ${disabledPaket} name="pasang" value="${installValue}" class="btn btn-primary">Pasang</button>`
                                }
                            }

                            // Prasyarat: modul berbayar terkunci sampai klien langganan
                            // (Layanan) terpasang. Klien sendiri tak berbayar → tak terkunci.
                            if (!klienTerpasang && data[i].requires_entitlement && paketTerpasang[data[i].name] === undefined) {
                                buttonInstall = `<button type="button" disabled class="btn btn-default btn-terkunci" title="Perlu Layanan aktif — pasang paket klien langganan lebih dulu"><i class="fa fa-lock"></i> Perlu Layanan</button>`
                            }

                            templateTmp = templateTmp.replace('__name__', data[i].name)
                            templateTmp = templateTmp.replace('__version__', data[i].version)
                            templateTmp = templateTmp.replace('__description__', data[i].description)
                            templateTmp = templateTmp.replace('__button__', buttonInstall)
                            templateTmp = templateTmp.replace('__thumbnail__', data[i].thumbnail)
                            templateTmp = templateTmp.replace('__price__', data[i].price)
                            templateTmp = templateTmp.replace('__totalInstall__', data[i].totalInstall)
                            cardView.push(templateTmp)
                        }
                        $('div#list-paket').find('form').append(cardView.join(''))
                        
                        // Event listener untuk tombol pasang paket
                        $('div#list-paket').find('.btn-pasang-paket').click(function(e) {
                            e.preventDefault();
                            const paketName = $(this).val().split('___')[0];
                            pendingInstallValue = $(this).val();

                            // Isi modal sesuai jenis paket (klien Layanan / berlisensi / gratis)
                            const isi = kontenModalPaket(paketName, paketInfo[paketName] || {});
                            $('#modalPersetujuanHeader').removeClass('bg-warning bg-info bg-primary').addClass(isi.header);
                            $('#modalPersetujuanLabel').html(isi.judul);
                            $('#modalPersetujuanBody').html(isi.body);
                            $('#modalPersetujuanPaket').modal('show');
                        });

                        displayPagination(response)
                    }
                })
            }

            // Handle tombol setuju di modal
            $('#btnSetujuPasang').click(function() {
                if (pendingInstallValue) {
                    $('#modalPersetujuanPaket').modal('hide');
                    
                    // Submit form dengan nilai paket
                    Swal.fire({
                        title: 'Sedang Memproses',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                    
                    // Create hidden input dan submit
                    const input = $('<input>').attr('type', 'hidden').attr('name', 'pasang').val(pendingInstallValue);
                    $('#mainform').append(input);
                    $('#mainform').submit();
                }
            });

            $('#tipe').on('change', function() {
                loadModule(1, $(this).val())
            })

            $('#tipe').trigger('change')
        })
    </script>
@endpush
