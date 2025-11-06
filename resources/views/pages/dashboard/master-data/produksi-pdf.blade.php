<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 20px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 2px 0 0 0;
            font-size: 14px;
        }

        .info {
            font-size: 13px;
            margin-bottom: 10px;
        }

        .info .month {
            text-align: left;
        }

        .info .date {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f3f3f3;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 13px;
        }

        .buttons {
            text-align: center;
            margin-top: 30px;
        }

        .buttons button {
            background-color: #f3f3f3;
            border: 1px solid #000;
            padding: 6px 16px;
            margin: 0 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        @media print {
            .buttons {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>PT. Agro Lestarindo</h2>
        <p><strong>Laporan Produksi</strong></p>
    </div>

    <div class="info">
        <div class="month">
            Bulan:
            {{ \Carbon\Carbon::parse(now())->translatedFormat('F Y') }}
        </div>
        <div class="date">
            Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>


    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Pupuk</th>
                <th>Jumlah Karung</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produksi as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_produksi)->format('d-m-Y') }}</td>
                    <td>{{ $item->barang->nama }}</td>
                    <td>{{ $item->jumlah_karung }}</td>
                    <td>{{ $item->note }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Tidak ada data produksi untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh:<br>
        <div>
            {{ Auth::user()->user_data->nama ?? Auth::user()->nama }} ({{ Auth::user()->role->role_name }})
        </div>
    </div>

</body>

</html>
