<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan Prasarana Sekolah</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #1B263B;
            --primary-light: #415A77;
            --accent: #778DA9;
            --text-main: #1D1D1F;
            --text-muted: #6E6E73;
            --border: #D1D9E6;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            color: var(--text-main);
            margin: 0;
            padding: 3rem;
            background: #FFFFFF;
            line-height: 1.6;
        }

        .report-header {
            text-align: center;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid var(--primary);
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--primary);
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.02em;
        }

        .subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 0.2rem 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            font-size: 0.85rem;
        }

        th {
            background: var(--primary);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem;
            text-align: left;
            border: 1px solid var(--primary);
        }

        td {
            padding: 1rem;
            border: 1px solid var(--border);
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #F8F9FA;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge.pending { background: #E9ECEF; color: #495057; }
        .badge.processing { background: #E3F2FD; color: #1976D2; }
        .badge.resolved { background: #E8F5E9; color: #2E7D32; }

        .btn-print {
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }

        .btn-print:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            th { -webkit-print-color-adjust: exact; }
            .badge { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center;">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak Dokumen</button>
        <a href="{{ route('admin.dashboard') }}" class="btn-print" style="background: #666;">Kembali ke Dasbor</a>
    </div>

    <div class="report-header">
        <h1>Laporan Rekapitulasi Pengaduan Siswa</h1>
        <div class="subtitle">Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</div>
        <div class="subtitle">Dicetak Oleh: {{ Auth::user()->name }} ({{ Auth::user()->role }})</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal & Kategori</th>
                <th width="20%">Data Pelapor & Lokasi</th>
                <th width="30%">Isi Aduan & Tanggapan</th>
                <th width="15%">Foto Bukti</th>
                <th width="15%">Status & Riwayat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $index => $c)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($c->date)->translatedFormat('d M Y') }}
                    </td>
                    <td>
                        <b>{{ $c->user->name }}</b><br>
                        Lokasi: {{ $c->location }}
                    </td>
                    <td>
                        <b>Judul: {{ $c->title }}</b><br>
                        <p style="margin:5px 0;">{{ $c->description }}</p>
                        <hr>
                        @if($c->responses->count() > 0)
                            <b>Dibalas oleh: {{ $c->responses->last()->user->name }}</b><br>
                            <i>"{{ $c->responses->last()->response }}"</i>
                        @else
                            <i>Belum ada tanggapan</i>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($c->photo)
                            <img src="{{ asset($c->photo) }}" style="width: 100px; height: 80px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                        @else
                            <span style="color: #999; font-style: italic;">Tidak ada foto</span>
                        @endif
                    </td>
                    <td>
                        Status: <span class="badge {{ $c->status }}">{{ strtoupper($c->status) }}</span><br>
                        Terakhir diubah: {{ $c->updated_at->diffForHumans() }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right; font-size: 14px;">
        <p>Mengetahui,</p>
        <p style="margin-top: 80px;">___________________________</p>
        <p>Admin Sistem</p>
    </div>
</body>

</html>