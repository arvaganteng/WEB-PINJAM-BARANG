@extends('layouts.admin')

@section('title', 'Laporan')

@push('styles')
<style>
  .report-filter-bar {
    background: #ffffff;
    border-radius: 16px;
    padding: 18px 24px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    margin-bottom: 20px;
  }

  .filter-inline-form {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 16px;
  }

  .filter-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .filter-field label {
    font-size: 12px;
    font-weight: 600;
    color: #475569;
  }

  .filter-field input,
  .filter-field select {
    padding: 8px 14px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    font-size: 13px;
    background: #ffffff;
    color: #0f172a;
    outline: none;
    transition: all 0.2s ease;
  }

  .filter-field input:focus,
  .filter-field select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }

  .stats-grid-reports {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }

  .report-stat-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 16px 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    text-align: center;
  }

  .report-stat-value {
    font-size: 22px;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 4px;
  }

  .report-stat-label {
    font-size: 11.5px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.03em;
  }

  .report-table-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    overflow: hidden;
  }

  .report-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  .report-table th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    padding: 12px 16px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
  }

  .report-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    vertical-align: middle;
  }

  .report-table tr:hover td {
    background: #f8fafc;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">📊 Laporan Sistem Peminjaman</div>
    <div class="page-subtitle">Rekapitulasi data transaksi peminjaman, pendapatan sewa, dan denda aset perusahaan</div>
  </div>
  <div class="page-actions" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
    <!-- Unduh PDF -->
    <a href="{{ route('admin.reports.export', array_merge(request()->all(), ['format' => 'pdf', 'download' => '1'])) }}" 
       target="_blank" 
       class="btn btn-sm"
       style="background: #DC2626; color: #ffffff; border-color: #DC2626; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; border-radius: 10px; padding: 8px 14px;">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      Unduh PDF
    </a>

    <!-- Export Excel -->
    <a href="{{ route('admin.reports.export', array_merge(request()->all(), ['format' => 'excel'])) }}" 
       class="btn btn-sm"
       style="background: #16A34A; color: #ffffff; border-color: #16A34A; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; border-radius: 10px; padding: 8px 14px;">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="17"/><line x1="16" y1="13" x2="8" y2="17"/></svg>
      Export Excel (.xls)
    </a>

    <!-- Preview / Cetak PDF Langsung -->
    <a href="{{ route('admin.reports.export', array_merge(request()->all(), ['format' => 'pdf', 'download' => '0'])) }}" 
       target="_blank" 
       class="btn btn-secondary btn-sm"
       style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; border-radius: 10px; padding: 8px 14px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
      Pratinjau / Cetak
    </a>
  </div>
</div>

<!-- Horizontal Filter Bar -->
<div class="report-filter-bar">
  <form method="GET" action="{{ route('admin.reports.index') }}" class="filter-inline-form">
    <div class="filter-field">
      <label>Dari Tanggal</label>
      <input type="date" name="start_date" value="{{ $startDate }}" required style="width: 160px;"/>
    </div>
    
    <div class="filter-field">
      <label>Sampai Tanggal</label>
      <input type="date" name="end_date" value="{{ $endDate }}" required style="width: 160px;"/>
    </div>
    
    <div class="filter-field">
      <label>Status Transaksi</label>
      <select name="status" style="width: 160px;">
        <option value="Semua">Semua Status</option>
        <option value="Disetujui" {{ $status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
        <option value="Dipinjam" {{ $status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
        <option value="Selesai" {{ $status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
        <option value="Ditolak" {{ $status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
      </select>
    </div>
    
    <div style="display: flex; gap: 8px;">
      <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 18px; border-radius: 10px; font-weight: 600;">
        🔍 Filter Laporan
      </button>
      
      <a href="{{ route('admin.reports.index') }}" class="btn btn-outline btn-sm" style="padding: 8px 14px; border-radius: 10px; font-weight: 500;">
        Reset
      </a>
    </div>
  </form>
</div>

<!-- Summary Statistics Grid -->
<div class="stats-grid-reports">
  <div class="report-stat-card">
    <div class="report-stat-value" style="color: var(--navy);">{{ $stats['total'] }}</div>
    <div class="report-stat-label">Total Transaksi</div>
  </div>
  
  <div class="report-stat-card">
    <div class="report-stat-value" style="color: #16a34a;">{{ $stats['approved'] }}</div>
    <div class="report-stat-label">Disetujui / Selesai</div>
  </div>

  <div class="report-stat-card">
    <div class="report-stat-value" style="color: #2563eb;">Rp {{ number_format($stats['rental_revenue'], 0, ',', '.') }}</div>
    <div class="report-stat-label">Pendapatan Sewa</div>
  </div>
  
  <div class="report-stat-card">
    <div class="report-stat-value" style="color: #059669;">Rp {{ number_format($stats['fine_revenue'], 0, ',', '.') }}</div>
    <div class="report-stat-label">Denda Lunas</div>
  </div>

  <div class="report-stat-card">
    <div class="report-stat-value" style="color: #1e3a8a;">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
    <div class="report-stat-label">Total Penerimaan</div>
  </div>
</div>

<!-- Full-Width Table Card -->
<div class="report-table-card">
  <div class="card-header" style="padding: 16px 20px; background: #ffffff; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
    <div>
      <div class="card-title" style="font-size: 15px; font-weight: 700; color: #0f172a;">📋 Data Rekapitulasi Transaksi</div>
      <div class="card-subtitle" style="font-size: 12px; color: #64748b;">
        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} – {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }} ({{ $status ?? 'Semua Status' }})
      </div>
    </div>
    <span style="font-size: 12px; font-weight: 600; color: #64748b;">Total: {{ $borrowings->count() }} Data</span>
  </div>

  <table class="report-table">
    <thead>
      <tr>
        <th style="width: 140px;">Kode Pinjam</th>
        <th>Customer</th>
        <th>Barang</th>
        <th style="width: 110px;">Tgl Pinjam</th>
        <th style="width: 110px;">Biaya Sewa</th>
        <th style="width: 120px;">Denda</th>
        <th style="width: 130px;">Total Pembayaran</th>
        <th style="width: 100px; text-align: center;">Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($borrowings as $b)
        <tr>
          <td>
            <span style="font-family: 'Consolas', monospace; font-weight: 700; color: var(--navy); font-size: 13px;">
              {{ $b->borrow_code }}
            </span>
          </td>
          <td>
            <div style="font-weight: 600; color: #0f172a;">{{ $b->user->name ?? '-' }}</div>
            <div style="font-size: 11.5px; color: #64748b;">{{ $b->user->email ?? '' }}</div>
          </td>
          <td>
            <div style="font-weight: 600; color: #0f172a;">{{ $b->item->name ?? '-' }}</div>
            <div style="font-size: 11.5px; color: #64748b;">Jumlah: {{ $b->quantity }} unit ({{ $b->duration_days }} Hari)</div>
          </td>
          <td>{{ $b->borrow_date->format('d M Y') }}</td>
          <td style="font-weight: 600; color: #334155;">
            Rp {{ number_format($b->total_price, 0, ',', '.') }}
          </td>
          <td>
            @if($b->returnRecord && $b->returnRecord->fine_amount > 0)
              <div style="font-weight: 700; color: #dc2626;">
                Rp {{ number_format($b->returnRecord->fine_amount, 0, ',', '.') }}
              </div>
              <div style="font-size: 11px;">
                @if($b->returnRecord->fine_payment_status === 'Lunas')
                  <span style="color: #16a34a; font-weight: 600;">(LUNAS)</span>
                @elseif($b->returnRecord->fine_payment_status === 'Menunggu Verifikasi')
                  <span style="color: #d97706; font-weight: 600;">(Verifikasi)</span>
                @else
                  <span style="color: #dc2626; font-weight: 600;">(Belum Bayar)</span>
                @endif
              </div>
            @else
              <span style="color: #94a3b8; font-size: 12px;">Rp 0</span>
            @endif
          </td>
          <td>
            @php
              $finePaid = ($b->returnRecord && $b->returnRecord->fine_payment_status === 'Lunas') ? $b->returnRecord->fine_amount : 0;
              $grandTotal = $b->total_price + $finePaid;
            @endphp
            <span style="font-weight: 800; color: #0f172a; font-size: 13.5px;">
              Rp {{ number_format($grandTotal, 0, ',', '.') }}
            </span>
          </td>
          <td style="text-align: center;">
            @if($b->status == 'Selesai')
              <span class="badge badge-success">✓ Selesai</span>
            @elseif($b->status == 'Dipinjam')
              <span class="badge badge-info">🔄 Dipinjam</span>
            @elseif($b->status == 'Disetujui')
              <span class="badge badge-purple">✓ Disetujui</span>
            @elseif($b->status == 'Menunggu')
              <span class="badge badge-warning">⏳ Menunggu</span>
            @else
              <span class="badge badge-danger">✕ {{ $b->status }}</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8">
            <div style="text-align: center; padding: 60px 24px; color: var(--gray-400);">
              <div style="font-size: 48px; margin-bottom: 12px; opacity: 0.5;">📭</div>
              <div style="font-size: 14px; color: var(--gray-500);">Tidak ada data transaksi pada rentang tanggal ini</div>
            </div>
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
