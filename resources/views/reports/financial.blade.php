<!DOCTYPE html>
<html>

<head>
    <title>Laporan Keuangan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Laporan Keuangan Bulan {{ $month }} Tahun {{ $year }}</h1>

    <h2>Pemasukan</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($income as $item)
                <tr>
                    <td>{{ $item['date'] }}</td>
                    <td>{{ number_format($item['amount'], 2) }}</td>
                    <td>{{ $item['description'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Pengeluaran</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $item)
                <tr>
                    <td>{{ $item['date'] }}</td>
                    <td>{{ number_format($item['amount'], 2) }}</td>
                    <td>{{ $item['description'] }}</td>
                </tr>
            @endforeach