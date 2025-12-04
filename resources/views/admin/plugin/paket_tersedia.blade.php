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
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalPersetujuanLabel">
                    <i class="fa fa-exclamation-triangle"></i> &nbsp;Perhatian: Paket Premium
                </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fa fa-info-circle"></i> <strong>Penting:</strong> Pastikan Anda siap melanjutkan langganan Premium untuk terus mendapatkan manfaat penuh dari modul ini.
                </div>
                <h5>Paket Premium: <strong id="paketNamaPendaftaran"></strong></h5>
                <p>Modul ini memerlukan <strong>Langganan Premium yang Aktif</strong>. Berikut yang perlu Anda ketahui:</p>
                <ul>
                    <li><strong>Dengan Premium Aktif:</strong> Akses penuh ke modul dengan update versi terbaru</li>
                    <li><strong>Premium Berakhir:</strong> Modul tidak akan menerima update versi terbaru</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i>
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
                let cardView = [],
                    disabledPaket, buttonInstall, versionCheck, templateTmp
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
                            
                            // Tampilkan modal persetujuan
                            $('#paketNamaPendaftaran').text(paketName);
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
