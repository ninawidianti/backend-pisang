<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Bahan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Laporan Stok Bahan</h1>
    <table>
        <thead>
            <tr>
                <th>Nama Bahan Baku</th>
                <th>Jumlah Stok Masuk</th>
                <th>Tanggal</th>
                <th>Tempat Pembelian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stokbahans as $stokbahan)
                <tr>
                    <td>{{ $stokbahan->name }}</td>
                    <td>{{ $stokbahan->stock_quantity }}</td>
                    <td>{{ $stokbahan->created_at }}</td>
                    <td>{{ $stokbahan->supplier }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>