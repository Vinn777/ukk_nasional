@extends('layouts.app')
@section('title', 'Dasbor Siswa - Pengaduan Prasarana Sekolah')

@section('content')
    <div class="dashboard-header">
        <h1>Pengaduan Prasarana Sekolah</h1>
        <p>Sampaikan Keluhan Anda Demi Kemajuan Sekolah</p>
    </div>

    <div class="grid grid-cols-3">
        <!-- Form Pengaduan -->
        <div class="card" style="grid-column: span 1; height: fit-content;">
            <h2 style="font-size: 1.25rem; margin-bottom: 0.5rem;"><i class="bi bi-pencil-square"></i> Buat Pengaduan Siswa</h2>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 2rem;">Gunakan bahasa yang santun dan deskriptif.</p>

            <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="title">Judul Laporan</label>
                    <input type="text" id="title" name="title" class="form-control" required placeholder="Contoh: Kerusakan Meja Kelas">
                </div>

                <div class="form-group">
                    <label class="form-label" for="location">Lokasi Kejadian</label>
                    <input type="text" id="location" name="location" class="form-control" required placeholder="Contoh: Lab Komputer 1">
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Detail Laporan</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required placeholder="Ceritakan detail keluhan Anda..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="photo">Lampiran Visual</label>
                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Kirim Pengaduan Siswa</button>
            </form>
        </div>

        <!-- Riwayat Pengaduan -->
        <div style="grid-column: span 2;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.5rem;">Riwayat Pengaduan Siswa</h2>
            </div>

            @if($complaints->isEmpty())
                <div class="card" style="text-align: center; padding: 4rem; opacity: 0.7;">
                    <i class="bi bi-chat-square-dots" style="font-size: 3rem; color: var(--accent);"></i>
                    <p style="margin-top: 1rem;">Anda belum memiliki riwayat pelaporan.</p>
                </div>
            @else
                <div class="grid grid-cols-1">
                    @foreach($complaints as $c)
                        <div class="card" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <h3 style="font-size: 1.2rem; margin-bottom: 0.25rem;">{{ $c->title }}</h3>
                                    <div style="font-size: 0.8rem; color: var(--text-muted); display: flex; gap: 1rem;">
                                        <span><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($c->date)->format('d M Y') }}</span>
                                        <span><i class="bi bi-geo-alt"></i> {{ $c->location }}</span>
                                    </div>
                                </div>
                                <span class="badge {{ $c->status }}">
                                    {{ $c->status == 'pending' ? 'Terkirim' : ($c->status == 'processing' ? 'Diproses' : 'Selesai') }}
                                </span>
                            </div>

                            <p style="font-size: 0.95rem; color: var(--text-main);">{{ $c->description }}</p>

                            @if($c->photo)
                                <img src="{{ asset($c->photo) }}" style="width: 100%; max-height: 250px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                            @endif

                            @if($c->responses->count() > 0)
                                <div style="background: var(--background); padding: 1.25rem; border-radius: 8px; border-left: 4px solid var(--primary);">
                                    <strong style="display: block; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: var(--primary);">Tanggapan Terakhir:</strong>
                                    <p style="font-size: 0.9rem; font-style: italic;">"{{ $c->responses->last()->response }}"</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection