<div class="row" style="margin-bottom:3px; margin-top:5px;">
    <div class="col-lg-12 col-md-12">
        <div class="header_top">
            <div class="header_top_left" style="margin-bottom:0px;">
                <ul class="top_nav">
                    <li>
                        <table>
                            <tr>
                                <td class="hidden-xs"><img class="tlClogo" src="{{ gambar_desa($desa['logo']) }}" width="30" valign="top" alt="{{ $desa['nama_desa'] }}" /></td>
                                <td>
                                    <a href="{{ site_url() }}">
                                        <font size="4">{{ setting('website_title') . ' ' . ucwords(setting('sebutan_desa')) . ($desa['nama_desa'] ? ' ' . $desa['nama_desa'] : '') }}</font><br />
                                        <font size="2">
                                            {{ ucwords(setting('sebutan_kecamatan_singkat') . ' ' . $desa['nama_kecamatan']) }}
                                            {{ ucwords(setting('sebutan_kabupaten_singkat') . ' ' . $desa['nama_kabupaten']) }}
                                            {{ ucwords('Prov. ' . $desa['nama_propinsi']) }}
                                        </font>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </li>
                </ul>
            </div>
            <div class="navbar-right" style="margin-right: 0px; margin-top: 15px; margin-bottom: 3px;">
                <form method="get" action="{{ site_url() }}" class="form-inline">
                    <table align="center">
                        <tr>
                            <td><input type="text" name="cari" maxlength="50" class="form-control" value="{{ html_escape($cari) }}" placeholder="Cari Artikel"></td>
                            <td><button type="submit" class="btn btn-primary">Cari</button></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
