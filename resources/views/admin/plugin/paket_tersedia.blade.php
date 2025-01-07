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

@push('scripts')
    <script>
        $(function() {
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
                            buttonInstall = `<button type="submit" ${disabledPaket} name="pasang" value="${data[i].name}___${data[i].url}" class="btn btn-primary">Pasang</button>`
                            if (paketTerpasang[data[i].name] !== undefined) {
                                versionCheck = compareVersions(data[i].version, paketTerpasang[data[i].name].version)
                                if (versionCheck > 0) {
                                    buttonInstall = `<button type="submit" ${disabledPaket} name="pasang" value="${data[i].name}___${data[i].url}___${data[i].version}" class="btn btn-primary">Tingkatkan Versi</button>`
                                } else {
                                    disabledPaket = 'disabled'
                                    buttonInstall = `<button type="button" ${disabledPaket} name="pasang" value="${data[i].name}___${data[i].url}" class="btn btn-primary">Pasang</button>`
                                }
                            }

                            templateTmp = templateTmp.replace('__name__', data[i].name)
                            templateTmp = templateTmp.replace('__description__', data[i].description)
                            templateTmp = templateTmp.replace('__button__', buttonInstall)
                            templateTmp = templateTmp.replace('__thumbnail__', data[i].thumbnail)
                            templateTmp = templateTmp.replace('__price__', data[i].price)
                            templateTmp = templateTmp.replace('__totalInstall__', data[i].totalInstall)
                            cardView.push(templateTmp)
                        }
                        $('div#list-paket').find('form').append(cardView.join(''))
                        $('div#list-paket').find('form').find('button:submit').click(function() {
                            Swal.fire({
                                title: 'Sedang Memproses',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });
                        })

                        displayPagination(response)
                    }
                })
            }

            $('#tipe').on('change', function() {
                loadModule(1, $(this).val())
            })

            $('#tipe').trigger('change')
        })
    </script>
@endpush
