@extends('layouts.customer')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Riwayat Transaksi Peminjaman</div>
    <div class="page-subtitle">Daftar transaksi peminjaman yang telah selesai atau ditolak</div>
  </div>
</div>

<div class="card" style="margin-bottom:16px">
  <div class="card-body" style="padding:16px">
    <form method="GET" action="{{ route('customer.borrowings.history') }}" class="filter-row">
      <div class="search-input-wrap">
        <span class="search-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
        <input type="text" name="search" class="form-input" placeholder="Cari kode peminjaman atau barang..." value="{{ request('search') }}" style="padding-left:36px;max-width:280px"/>
      </div>
      <select name="status" class="form-input form-select" style="max-width:150px">
        <option value="">Semua Status</option>
        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
      </select>
      <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
    </form>
  </div>
</div>

<div class="card">
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>Kode</th>
          <th>Barang</th>
          <th>Tgl Pinjam</th>
          <th>Tgl Kembali</th>
          <th>Total Biaya</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($borrowings as $b)
          <tr>
            <td style="font-weight:600;color:var(--navy)">{{ $b->borrow_code }}</td>
            <td style="font-weight:500">{{ $b->item->name ?? '-' }} ({{ $b->quantity }} unit)</td>
            <td>{{ $b->borrow_date->format('d/m/Y') }}</td>
            <td>{{ $b->return_date->format('d/m/Y') }}</td>
            <td style="font-weight:600">
              @if($b->total_price > 0)
                Rp {{ number_format($b->total_price, 0, ',', '.') }}
              @else
                Gratis
              @endif
            </td>
            <td>
              @if($b->status == 'Selesai')
                <span class="badge badge-success">Selesai</span>
              @elseif($b->status == 'Ditolak')
                <span class="badge badge-danger">Ditolak</span>
              @else
                <span class="badge badge-gray">{{ $b->status }}</span>
              @endif
            </td>
            <td>
              <a href="{{ route('customer.borrowings.show', $b) }}" class="btn btn-ghost btn-sm" style="color:var(--blue)">Detail</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align:center;color:var(--gray-500);padding:24px">Belum ada riwayat transaksi peminjaman.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="padding: 16px;">
    {{ $borrowings->withQueryString()->links() }}
  </div>
</div>
@endsection
