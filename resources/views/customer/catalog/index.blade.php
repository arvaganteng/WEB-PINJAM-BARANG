@extends('layouts.customer')

@section('title', 'Katalog Barang')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Katalog Barang Perusahaan</div>
    <div class="page-subtitle">Pilih dan ajukan peminjaman aset inventaris sesuai kebutuhan pekerjaan Anda</div>
  </div>
</div>

<div class="card" style="margin-bottom:20px">
  <div class="card-body" style="padding:16px">
    <form method="GET" action="{{ route('customer.catalog.index') }}" class="filter-row">
      <div class="search-input-wrap" style="flex:1">
        <span class="search-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
        <input type="text" name="search" class="form-input" placeholder="Cari nama barang atau deskripsi..." value="{{ request('search') }}" style="padding-left:36px"/>
      </div>
      <select name="category_id" class="form-input form-select" style="max-width:180px">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
      <select name="status" class="form-input form-select" style="max-width:140px">
        <option value="">Semua Status</option>
        <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
      </select>
      <button type="submit" class="btn btn-primary btn-sm">Cari</button>
    </form>
  </div>
</div>

<div class="items-grid">
  @forelse($items as $item)
    <div class="item-card" onclick="window.location.href='{{ route('customer.catalog.show', $item) }}'" style="cursor:pointer">
      <div class="item-card-img">
        @if($item->image)
          <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
        @else
          <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #0f2d6b 0%, #1a3f8f 100%); border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; gap: 8px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            <span style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px; opacity: 0.8; text-transform: uppercase;">Foto Barang</span>
          </div>
        @endif
      </div>
      <div class="item-card-body">
        <div class="item-card-category">{{ $item->category->name }}</div>
        <div class="item-card-name">{{ $item->name }}</div>
        <div class="item-card-meta">
          <div class="item-card-meta-row"><span>Kondisi</span><span>{{ $item->condition }}</span></div>
          <div class="item-card-meta-row"><span>Stok</span><span>{{ $item->stock }} unit</span></div>
          <div class="item-card-meta-row">
            <span>Status</span>
            @if($item->status == 'Tersedia')
              <span class="badge badge-success">Tersedia</span>
            @elseif($item->status == 'Dipinjam')
              <span class="badge badge-info">Dipinjam</span>
            @else
              <span class="badge badge-gray">{{ $item->status }}</span>
            @endif
          </div>
        </div>
        <div class="item-price">
          @if($item->price_per_day > 0)
            Rp {{ number_format($item->price_per_day, 0, ',', '.') }}<span class="item-price-sub">/hari</span>
          @else
            Gratis
          @endif
        </div>
        <a href="{{ route('customer.catalog.show', $item) }}" class="btn {{ $item->status == 'Tersedia' && $item->stock > 0 ? 'btn-primary' : 'btn-secondary' }} btn-full btn-sm" onclick="event.stopPropagation()">
          {{ $item->status == 'Tersedia' && $item->stock > 0 ? 'Lihat Detail & Pinjam' : 'Tidak Tersedia' }}
        </a>
      </div>
    </div>
  @empty
    <div style="grid-column: 1/-1; text-align:center; color:var(--gray-500); padding:40px">
      Tidak ada barang yang sesuai dengan pencarian Anda.
    </div>
  @endforelse
</div>

<div style="margin-top:24px">
  {{ $items->withQueryString()->links() }}
</div>
@endsection
