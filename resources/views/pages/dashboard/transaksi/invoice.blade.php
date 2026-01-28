<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $pesanan->order_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .btn-export {
            background: none;
            border: none;
            outline: none;
            cursor: pointer;
            color: white;
            background: rgb(243, 10, 10);
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h2>INVOICE / STRUK PEMBELIAN</h2>
            <p>No Pesanan: {{ $pesanan->order_no }}</p>
            <p>Tanggal: {{ $pesanan->tanggal_transaksi }}</p>
        </div>

        <p>
            <strong>Pelanggan:</strong>
            {{ $pesanan->user_data->nama ?? 'Guest' }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan->detailPesanan as $item)
                    <tr>
                        <td>{{ $item->barang->nama }}</td>
                        <td>{{ $item->qty_karung }}</td>
                        <td class="text-right">
                            Rp {{ number_format($item->barang->harga, 0, ',', '.') }}
                        </td>
                        <td class="text-right">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>

        <table>
            <tr>
                <th>Total</th>
                <td class="text-right">
                    Rp {{ number_format($pesanan->total_bayar + ($pesanan->pengiriman->ongkir ?? 0), 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <br>

        <div style="display: flex; justify-content:center; margin-top: 3rem;">
            <button onclick="window.print()" class="no-print btn-export">
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

</body>

</html>
