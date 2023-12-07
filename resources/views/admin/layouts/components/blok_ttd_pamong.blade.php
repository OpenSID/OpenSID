    <table>
        <tr>
            <td colspan="{{ $total_col }}">&nbsp;</td>
        </tr>
        <tr class="text-center">
            <td colspan="{{ $spasi_kiri }}" style="width: 20%">&nbsp;</td>
            <td>MENGETAHUI</td>
            <td colspan="{{ $spasi_tengah }}" style="width: 30%">&nbsp;</td>
            <td colspan="2" class="nowrap">{{ strtoupper($desa['nama_desa'] . ', ' . tgl_indo(date('Y m d'))) }}</td>
            <td style="width: 20%">&nbsp;</td>
        </tr>
        <tr class="text-center">
            <td colspan="{{ $spasi_kiri }}">&nbsp;</td>
            <td class="nowrap">{{ strtoupper($pamong_ketahui['pamong_jabatan'] . ' ' . $desa['nama_desa']) }}</td>
            <td colspan="{{ $spasi_tengah }}">&nbsp;</td>
            <td colspan="2" class="nowrap">{{ strtoupper($pamong_ttd['pamong_jabatan'] . ' ' . $desa['nama_desa']) }}</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="{{ $total_col }}">&nbsp;</td>
        <tr>
            <td colspan="{{ $total_col }}">&nbsp;</td>
        <tr>
            <td colspan="{{ $total_col }}">&nbsp;</td>
        <tr>
            <td colspan="{{ $total_col }}">&nbsp;</td>
        <tr>
            <td colspan="{{ $total_col }}">&nbsp;</td>
        <tr>
            <td colspan="{{ $total_col }}">&nbsp;</td>
        <tr class="text-center">
            <td colspan="{{ $spasi_kiri }}">&nbsp;</td>
            <td class="nowrap"><u>{{ gelar($pamong_ketahui['gelar_depan'], $pamong_ketahui['pamong_nama'], $pamong_ketahui['gelar_belakang']) }}</u></td>
            <td colspan="{{ $spasi_tengah }}">&nbsp;</td>
            <td colspan="2" class="nowrap"><u>{{ gelar($pamong_ttd['gelar_depan'], $pamong_ttd['pamong_nama'], $pamong_ttd['gelar_belakang']) }}</u></td>
            <td>&nbsp;</td>
        </tr>
        <tr class="text-center">
            <td colspan="{{ $spasi_kiri }}">&nbsp;</td>
            <td>{{ $pamong_ketahui['pamong_nip'] ? 'NIP :' : ($pamong_ketahui['pamong_niap'] ? setting('sebutan_nip_desa') . ' :' : '') }} {{ $pamong_ketahui['pamong_nip'] ?? $pamong_ketahui['pamong_niap'] }}</td>
            <td colspan="{{ $spasi_tengah }}">&nbsp;</td>
            <td colspan="2">{{ $pamong_ttd['pamong_nip'] ? 'NIP :' : ($pamong_ttd['pamong_niap'] ? setting('sebutan_nip_desa') . ' :' : '') }} {{ $pamong_ttd['pamong_nip'] ?? $pamong_ttd['pamong_niap'] }}</td>
            <td>&nbsp;</td>
        </tr>
    </table>
