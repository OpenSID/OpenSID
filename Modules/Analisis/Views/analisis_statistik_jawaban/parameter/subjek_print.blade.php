<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Data Subjek Analisis</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="{{ asset('css/report.css') }}" rel="stylesheet" type="text/css">
    <!-- TODO: Pindahkan ke external css -->
    <style>
        td {
            mso-number-format: "\@";
        }

        td,
        th {
            font-size: 8pt;
        }
    </style>
</head>

<body>
    <div id="container">
        <!-- Print Body -->
        <div id="body">
            <div class="header" align="center">
                <label align="left">{{ get_identitas() }}</label>
                <h3> DATA SUBJEK ANALISIS </h3>
            </div>
            <table class="border">
                <thead>
                    <tr class="border thick">
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Dusun</th>
                        <th>RW</th>
                        <th>RT</th>
                        <th>Umur</th>
                        <th>J. Kelamin</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($main as $data)
                        <tr>
                            <td width="2">{{ $loop->iteration }}</td>
                            <td class="textx">{{ $data->nik }}</td>
                            <td>{{ strtoupper($data->nama) }}</td>
                            <td>{{ strtoupper($data->dusun) }}</td>
                            <td>{{ $data->rw }}</td>
                            <td>{{ $data->rt }}</td>
                            <td align="right">{{ $data->umur }}</td>
                            <td>{{ App\Enums\JenisKelaminEnum::valueOf($data->sex) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <label>Tanggal cetak : &nbsp; </label>{{ tgl_indo(date('Y m d')) }}
    </div>
</body>

</html>
