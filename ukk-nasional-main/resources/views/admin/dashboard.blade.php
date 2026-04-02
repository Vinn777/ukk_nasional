@extends('layouts.app')
@section('title', 'Dasbor Admin - Pengaduan Prasarana Sekolah')

@section('content')

<div class="dashboard-header">
    <h1>Forum Pengawasan</h1>
    <p>Layanan Pemantauan Infrastruktur Sekolah</p>
</div>

{{-- ===================== STAT CARD ===================== --}}
<div class="grid grid-cols-3" style="margin-bottom: 2rem; gap: 1rem;">

    {{-- Pending --}}
    <div class="card glass" style="border-left: 4px solid var(--status-pending); padding:1rem;">
        <h3 class="text-muted" style="font-size: 0.75rem;">MENUNGGU VALIDASI</h3>
        <p style="font-size: 2.2rem; font-weight: 800;">{{ $pending }}</p>
    </div>

    {{-- Processing --}}
    <div class="card glass" style="border-left: 4px solid var(--status-processing); padding:1rem;">
        <h3 class="text-muted" style="font-size: 0.75rem;">SEDANG DIPROSES</h3>
        <p style="font-size: 2.2rem; font-weight: 800;">{{ $processing }}</p>
    </div>

    {{-- Resolved --}}
    <div class="card glass" style="border-left: 4px solid var(--status-resolved); padding:1rem;">
        <h3 class="text-muted" style="font-size: 0.75rem;">SELESAI DITANGANI</h3>
        <p style="font-size: 2.2rem; font-weight: 800;">{{ $resolved }}</p>
    </div>

</div>

{{-- ===================== TABLE CARD ===================== --}}
<div class="card glass" style="padding: 1.5rem;">

    {{-- Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <h2 style="margin:0;">Daftar Pengaduan Siswa</h2>

        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.report') }}" target="_blank" class="btn btn-outline" style="font-size: 0.8rem;">
                🖨️ Cetak Laporan
            </a>
        @endif
    </div>

    {{-- Table --}}
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th width="80px">ID</th>
                    <th width="150px">Data Pelapor</th>
                    <th width="150px">Lokasi</th>
                    <th>Laporan</th>
                    <th width="120px">Foto Bukti</th>
                    <th width="100px">Status</th>
                    <th width="220px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $c)
                <tr>
                    {{-- ID --}}
                    <td>
                        <span style="font-family: monospace; font-weight: 700; color: var(--primary);">
                            #{{ str_pad($c->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>

                    {{-- PELAPOR --}}
                    <td>
                        <div style="font-weight: 600;">{{ $c->user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">NIK: {{ $c->user->nik }}</div>
                    </td>

                    {{-- LOKASI --}}
                    <td>
                        <div style="display: flex; align-items: center; gap: 6px; font-size: 0.85rem;">
                            <i class="bi bi-geo-alt-fill" style="color: #E10600;"></i>
                            <span>{{ $c->location }}</span>
                        </div>
                    </td>

                    {{-- LAPORAN --}}
                    <td>
                        <div style="font-weight: 700; margin-bottom: 4px; color: var(--primary);">{{ $c->title }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4;">
                            {{ Str::limit($c->description, 70) }}
                        </div>
                    </td>

                    {{-- FOTO --}}
                    <td style="text-align: center;">
                        @if($c->photo)
                            <a href="{{ asset($c->photo) }}" target="_blank">
                                <img src="{{ asset($c->photo) }}" class="img-thumbnail-custom" alt="Bukti Foto">
                            </a>
                        @else
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-style: italic;">No Photo</div>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @php
                            $badgeClass = $c->status;
                            $badgeText = $c->status == 'pending' ? 'Pending' :
                                         ($c->status == 'processing' ? 'Proses' : 'Selesai');
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                    </td>

                    {{-- AKSI --}}
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            
                            {{-- Form ID assignment --}}
                            @php $formId = 'update-form-' . $c->id; @endphp

                            {{-- Status & Delete Actions --}}
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <form action="{{ route('complaints.updateStatus', $c->id) }}" method="POST" style="flex: 1;" id="{{ $formId }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-control" style="padding: 0.4rem; font-size: 0.8rem;" onchange="this.form.submit()">
                                        <option value="pending" {{ $c->status=='pending'?'selected':'' }}>Pending</option>
                                        <option value="processing" {{ $c->status=='processing'?'selected':'' }}>Proses</option>
                                        <option value="resolved" {{ $c->status=='resolved'?'selected':'' }}>Selesai</option>
                                    </select>
                                </form>

                                <form action="{{ route('complaints.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus pengaduan ini?')">
                                    @csrf
                                    <button type="submit" class="btn" style="background:#FFEBEB; color:#E10600; padding: 0.4rem 0.8rem;">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>

                            {{-- Responses Section --}}
                            <div style="border-top: 1px dashed var(--border); padding-top: 8px;">
                                @foreach($c->responses as $resp)
                                    <div style="background: #F8F9FA; padding: 6px 10px; border-radius: 8px; font-size: 0.75rem; margin-bottom: 4px; border-left: 3px solid var(--primary);">
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                            <span style="font-weight: 700;">{{ $resp->user->name }}</span>
                                            <form action="{{ route('responses.destroy', $resp->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" style="background:none; border:none; color:#E10600; cursor:pointer; font-size:0.7rem;">
                                                    <i class="bi bi-x-circle-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div style="font-style: italic;">"{{ $resp->response }}"</div>
                                    </div>
                                @endforeach

                                @if($c->responses->count() == 0)
                                    {{-- Unified Response & Status Update Button --}}
                                    <div class="table-action-form" style="display: flex; gap: 4px;">
                                        <input type="text" name="response" placeholder="Tanggapan..." 
                                               class="form-control" style="flex:1;" form="{{ $formId }}" required>
                                        <button type="submit" class="btn btn-primary" style="padding: 0.4rem 0.6rem;" form="{{ $formId }}">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 1rem;"></i>
                        Belum ada laporan pengaduan masuk
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

        </table>
    </div>
</div>
@endsection