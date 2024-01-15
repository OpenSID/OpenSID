<div class="tab-pane active">
    <div class="row" id="list-paket">
        <form action="{{ ci_route('plugin.pasang') }}" method="post">
        </form>
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

            let paketTerpasang = {!! $paket_terpasang ?? '{}' !!}
            let cardView = [],
                disabledPaket, buttonInstall, versionCheck, templateTmp
            let urlModule = '{{ config_item('url_marketplace') }}'
            const templateCard = `@include('admin.plugin.item')`
            $.get(urlModule, {}, function(data) {
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

            }, 'json')
        })
    </script>
@endpush
