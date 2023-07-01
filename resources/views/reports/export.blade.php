<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan</title>

    <style>
        table {
            width: 95%;
            border-collapse: collapse;
            margin: 50px auto;
        }

        /* Zebra striping */
        tr:nth-of-type(odd) {
            background: #eee;
        }

        th {
            background: #3498db;
            color: white;
            font-weight: bold;
        }

        td,
        th {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
            font-family: 'Calibri';
        }
    </style>
</head>

<body>

    <div style="width: 95%; margin: 0 auto;">
        <div class="text-center justify-content-center" style="width: 50%; float: left;">
            <h3 class="text-center">Laporan Pendapatan</h3>
            <h4 class="text-center">
                Tanggal {{ indonesianDate($awal, false) }}
                s/d
                Tanggal {{ indonesianDate($akhir, false) }}
            </h4>
        </div>
    </div>

    <table style="position: relative; top: 50px;">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Penjualan</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    @foreach ($row as $col)
                        <td>{{ $col }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
