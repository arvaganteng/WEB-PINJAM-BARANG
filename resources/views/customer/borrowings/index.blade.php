@extends('layouts.customer')

@section('title', 'Peminjaman Saya')

@push('styles')
<style>
  .borrowing-card {
    background: white;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    padding: 20px;
    display: grid;
    grid-template-columns: 140px 1fr auto;
    gap: 20px;
    align-items: start;
    transition: all 0.3s ease;
    margin-bottom: 16px;
  }
  
  .borrowing-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
  }
  
  .borrowing-image {
    width: 140px;
    height: 140px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid #e2e8f0;
  }
  
  .borrowing-image-placeholder {
    width: 140px;
    height: 140px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    color: white;
  }
  
  .borrowing-info h3 {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
  }
  
  .borrowing-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 12px;
    font-size: 13px;
    color: #64748b;
  }
  
  .borrowing-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
  }
  
  .borrowing-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 180px;
  }
  
  .status-timeline {
    background: #f8fafc;
    padding: 16px;
    border-radius: 10px;
    margin-top: 12px;
    border-left: 3px solid var(--status-color);
  }
  
  .timeline-step {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13px;
    color: #64748b;
    margin-bottom: 8px;
  }
  
  .timeline-step.active {
    color: #0f172a;
    font-weight: 600;
  }
  
  .timeline-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    flex-shrink: 0;
  }
  
  .timeline-dot.active {
    background: var(--status-color);
    border-color: var(--status-color);
    color: white;
  }
  
  @media (max-width: 768px) {
    .borrowing-card {
      grid-template-columns: 1fr;
    }
    
    .borrowing-actions {
      min-width: auto;
    }
  }
</style>
@endpush

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 32px 24px;">
  
  <!-- Header -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
      <h1 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
        Peminjaman Saya
      </h1>
      <p style="font-size: 14px; color: #64748b;">
        Kelola dan pantau status peminjaman barang Anda
      </p>
    </div>
    
    <a href="{{ route('customer.catalog.index') }}" class="btn btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Pinjam Barang Baru
    </a>
  </div>

  <!-- Quota Badge Info -->
  <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
    <div style="display: flex; align-items: center; gap: 12px;">
      <div style="width: 38px; height: 38px; border-radius: 10px; background: {{ $borrowings->count() >= 2 ? '#fef2f2' : '#eff6ff' }}; color: {{ $borrowings->count() >= 2 ? '#dc2626' : '#2563eb' }}; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: bold;">
        {{ $borrowings->count() }}/2
      </div>
      <div>
        <div style="font-size: 13.5px; font-weight: 700; color: #0f172a;">
          Status Kuota Peminjaman Aktif: <span style="color: {{ $borrowings->count() >= 2 ? '#dc2626' : '#2563eb' }};">{{ $borrowings->count() }} dari 2 Barang Maksimal</span>
        </div>
        <div style="font-size: 12px; color: #64748b;">
          @if($borrowings->count() >= 2)
            Batas kuota tercapai. Kembalikan salah satu barang untuk bisa mengajukan peminjaman baru.
          @else
            Anda masih bisa meminjam {{ 2 - $borrowings->count() }} barang lagi.
          @endif
        </div>
      </div>
    </div>
    @if($borrowings->count() >= 2)
      <span class="badge badge-danger" style="font-size: 12px; padding: 6px 12px; border-radius: 8px;">
        Kuota Penuh (Maks 2 Barang)
      </span>
    @else
      <span class="badge badge-success" style="font-size: 12px; padding: 6px 12px; border-radius: 8px;">
        Sisa Kuota: {{ 2 - $borrowings->count() }} Barang
      </span>
    @endif
  </div>

  @if(session('success'))
    <div style="background: #f0fdf4; border: 1px solid #86efac; color: #166534; padding: 16px; border-radius: 10px; margin-bottom: 24px;">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 16px; border-radius: 10px; margin-bottom: 24px;">
      {{ session('error') }}
    </div>
  @endif

  <!-- Borrowings List -->
  @forelse($borrowings as $borrowing)
    <div class="borrowing-card" style="--status-color: {{ 
      $borrowing->status == 'Menunggu' ? '#d97706' : 
      ($borrowing->status == 'Disetujui' ? '#2563eb' : 
      ($borrowing->status == 'Dipinjam' ? '#7c3aed' : 
      ($borrowing->status == 'Menunggu Verifikasi' ? '#0d9488' : '#64748b'))) 
    }};">
      
      <!-- Image -->
      <div>
        @if($borrowing->item->image)
          <img src="{{ asset('storage/' . $borrowing->item->image) }}" alt="{{ $borrowing->item->name }}" class="borrowing-image">
        @else
          <div class="borrowing-image-placeholder" style="background: linear-gradient(135deg, #0f2d6b 0%, #1a3f8f 100%); flex-direction: column; gap: 6px;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            <span style="font-size: 10px; font-weight: 600; opacity: 0.85; text-transform: uppercase;">Foto Barang</span>
          </div>
        @endif
      </div>
      
      <!-- Info -->
      <div class="borrowing-info">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
          <div>
            <div style="font-family: monospace; font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 4px;">
              {{ $borrowing->borrow_code }}
            </div>
            <h3>{{ $borrowing->item->name }}</h3>
          </div>
          
          @if($borrowing->status == 'Menunggu')
            <span class="badge badge-warning">Menunggu</span>
          @elseif($borrowing->status == 'Disetujui')
            <span class="badge badge-info">Disetujui</span>
          @elseif($borrowing->status == 'Dipinjam')
            <span class="badge badge-purple">Dipinjam</span>
          @elseif($borrowing->status == 'Menunggu Verifikasi')
            <span class="badge" style="background: #f0fdfa; color: #0d9488;">Menunggu Verifikasi</span>
          @else
            <span class="badge badge-gray">{{ $borrowing->status }}</span>
          @endif
        </div>
        
        <div class="borrowing-meta">
          <div class="borrowing-meta-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span>{{ $borrowing->borrow_date->format('d M Y') }} - {{ $borrowing->return_date->format('d M Y') }}</span>
          </div>
          
          <div class="borrowing-meta-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>{{ $borrowing->duration_days }} hari</span>
          </div>
          
          <div class="borrowing-meta-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
            </svg>
            <span>{{ $borrowing->quantity }} unit</span>
          </div>
        </div>
        
        <!-- Timeline Status -->
        <div class="status-timeline">
          <div class="timeline-step {{ in_array($borrowing->status, ['Menunggu', 'Disetujui', 'Dipinjam', 'Menunggu Verifikasi', 'Selesai']) ? 'active' : '' }}">
            <div class="timeline-dot {{ in_array($borrowing->status, ['Menunggu', 'Disetujui', 'Dipinjam', 'Menunggu Verifikasi', 'Selesai']) ? 'active' : '' }}">1</div>
            <span>Pengajuan Dikirim</span>
          </div>
          <div class="timeline-step {{ in_array($borrowing->status, ['Disetujui', 'Dipinjam', 'Menunggu Verifikasi', 'Selesai']) ? 'active' : '' }}">
            <div class="timeline-dot {{ in_array($borrowing->status, ['Disetujui', 'Dipinjam', 'Menunggu Verifikasi', 'Selesai']) ? 'active' : '' }}">2</div>
            <span>Disetujui Admin</span>
          </div>
          <div class="timeline-step {{ in_array($borrowing->status, ['Dipinjam', 'Menunggu Verifikasi', 'Selesai']) ? 'active' : '' }}">
            <div class="timeline-dot {{ in_array($borrowing->status, ['Dipinjam', 'Menunggu Verifikasi', 'Selesai']) ? 'active' : '' }}">3</div>
            <span>Barang Dipinjam</span>
          </div>
          <div class="timeline-step {{ in_array($borrowing->status, ['Menunggu Verifikasi', 'Selesai']) ? 'active' : '' }}">
            <div class="timeline-dot {{ in_array($borrowing->status, ['Menunggu Verifikasi', 'Selesai']) ? 'active' : '' }}">4</div>
            <span>{{ $borrowing->status == 'Menunggu Verifikasi' ? 'Menunggu Verifikasi' : 'Dikembalikan' }}</span>
          </div>
        </div>
      </div>
      
      <!-- Actions -->
      <div class="borrowing-actions">
        <a href="{{ route('customer.borrowings.show', $borrowing) }}" class="btn btn-outline btn-sm">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
          </svg>
          Lihat Detail
        </a>
        
        @if($borrowing->status == 'Dipinjam' && !$borrowing->returnRecord)
          <button onclick="openReturnModal('{{ route('customer.borrowings.return-request', $borrowing) }}', '{{ $borrowing->borrow_code }}', '{{ addslashes($borrowing->item->name) }}')" class="btn btn-success btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/>
            </svg>
            Ajukan Pengembalian
          </button>
        @endif
        
        @if($borrowing->status == 'Menunggu Verifikasi')
          <div style="background: #f0fdfa; padding: 12px; border-radius: 8px; border: 1px solid #5eead4; font-size: 12px; color: #0d9488; text-align: center;">
            <div style="font-weight: 600; margin-bottom: 4px;">Sedang Diperiksa</div>
            <div>Admin sedang memeriksa kondisi barang</div>
          </div>
        @endif
      </div>
    </div>
  @empty
    <div style="background: white; border-radius: 14px; padding: 80px 24px; text-align: center;">
      <div style="font-size: 48px; margin-bottom: 16px; color: #cbd5e1; display: flex; justify-content: center;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
      </div>
      <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">
        Belum Ada Peminjaman Aktif
      </h3>
      <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;">
        Anda belum memiliki peminjaman aktif. Mulai pinjam barang sekarang!
      </p>
      <a href="{{ route('customer.catalog.index') }}" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        Jelajahi Katalog Barang
      </a>
    </div>
  @endforelse
  
  <!-- Link to History -->
  @if($borrowings->count() > 0)
    <div style="text-align: center; margin-top: 32px;">
      <a href="{{ route('customer.borrowings.history') }}" style="color: #2563eb; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/>
        </svg>
        Lihat Riwayat Peminjaman
      </a>
    </div>
  @endif
  
</div>

@push('scripts')
<script>
  function openReturnModal(actionUrl, code, itemName) {
    openModal(`
      <div class="modal-header">
        <div class="modal-title">Ajukan Pengembalian Barang</div>
        <button class="modal-close" onclick="closeModal()">&times;</button>
      </div>
      <form action="${actionUrl}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <div class="modal-body">
          <div style="background: #eff6ff; padding: 16px; border-radius: 10px; margin-bottom: 20px; border-left: 3px solid #2563eb;">
            <h4 style="font-size: 14px; font-weight: 700; color: #1e40af; margin-bottom: 8px;">
              Barang yang Akan Dikembalikan:
            </h4>
            <div style="font-size: 16px; font-weight: 600; color: #0f172a;">${itemName}</div>
            <div style="font-size: 13px; color: #64748b; margin-top: 4px;">Kode: ${code}</div>
          </div>
          
          <div class="form-group">
            <label class="form-label">Catatan Pengembalian (Opsional)</label>
            <textarea name="customer_notes" class="form-input form-textarea" rows="4" placeholder="Contoh: Barang dalam kondisi baik, tidak ada kerusakan..."></textarea>
            <small style="color: #64748b; font-size: 12px; display: block; margin-top: 6px;">
              Jelaskan kondisi barang saat dikembalikan untuk mempercepat proses verifikasi
            </small>
          </div>
          
          <div style="background: #fef3c7; padding: 12px; border-radius: 8px; font-size: 13px; color: #92400e; margin-top: 16px;">
            <strong>Penting:</strong> Admin akan memeriksa kondisi barang. Pastikan barang dalam kondisi baik untuk menghindari denda.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
          <button type="submit" class="btn btn-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            Konfirmasi Pengembalian
          </button>
        </div>
      </form>
    `);
  }
</script>
@endpush
@endsection
