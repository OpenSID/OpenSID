<div class="box box-primary box-solid">
    <div class="box-header">
        <h3 class="box-title"><i class="fas fa-chart-line mr-1"></i>{{ $judul_widget }}</h3>
    </div>
    <div class="box-body">
        <table cellpadding="0" cellspacing="0" class="counter w-full divide-y">
            <tr class="py-4">
                <td> Hari ini</td>
                <td class="inline-flex w-full justify-end text-right">
                    {{ $statistik_pengunjung['hari_ini'] }}</td>
            </tr>
            <tr class="py-4">
                <td valign="middle">Kemarin </td>
                <td valign="middle" class="inline-flex w-full justify-end text-right">
                    {{ $statistik_pengunjung['kemarin'] ?? 0 }}</td>
            </tr>
            <tr class="py-4">
                <td valign="middle">Jumlah pengunjung</td>
                <td valign="middle" class="inline-flex w-full justify-end text-right">
                    {{ $statistik_pengunjung['total'] }}</td>
            </tr>
        </table>
    </div>
</div>
