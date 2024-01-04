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
                disabledPaket, buttonInstall, versionCheck
            let urlModule = '{{ config_item('url_marketplace') }}'
            $.get(urlModule, {}, function(data) {
                console.log(paketTerpasang)
                for (let i in data) {
                    console.log(paketTerpasang[data[i].name])
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
                    cardView.push(
                        `<div class="col-md-4 col-sm-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="panel-title">${data[i].name}</div>
                                </div>
                                <div class="panel-body" style="min-height:200px">${data[i].description}</div>
                                <div class="panel-footer">
                                    ${buttonInstall}
                                </div>
                            </div>
                        </div>`
                    )
                }
                $('div#list-paket').find('form').append(cardView.join(''))
            }, 'json')
        })
    </script>
@endpush
