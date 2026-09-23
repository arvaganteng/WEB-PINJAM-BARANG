@extends('layouts.customer')

@section('title', 'Detail: ' . $item->name)

@section('content')
<div style="margin-bottom: 16px;">
  <a href="{{ route('customer.catalog.index') }}" class="btn btn-ghost btn-sm" style="padding: 6px 0; color: #475569; font-weight: 600;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Kembali ke Katalog
  </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px; margin-bottom: 32px;">
  <!-- Foto Produk & Rating Summary -->
  <div>
    <div style="background: #f8fafc; border-radius: 16px; overflow: hidden; margin-bottom: 16px; border: 1px solid #e2e8f0; height: 380px;">
      @if($item->image)
        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
      @else
        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); display: flex; align-items: center; justify-content: center; color: white;">
          <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
        </div>
      @endif
    </div>

    <!-- Rating Summary Box -->
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 12px;">
        <div style="font-size: 32px; font-weight: 800; color: #0f172a; line-height: 1;">
          {{ $item->averageRating() > 0 ? $item->averageRating() : '5.0' }}
        </div>
        <div>
          <div style="color: #f59e0b; font-size: 16px; letter-spacing: 2px;">
            @php $stars = round($item->averageRating() > 0 ? $item->averageRating() : 5); @endphp
            @for($i = 1; $i <= 5; $i++)
              {{ $i <= $stars ? '★' : '☆' }}
            @endfor
          </div>
          <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
            Berdasarkan {{ $item->reviewsCount() }} ulasan peminjam
          </div>
        </div>
      </div>
      <span class="badge badge-success" style="font-size: 12px;">Kondisi: {{ $item->condition }}</span>
    </div>
  </div>

  <!-- Detail Informasi & Tombol Aksi -->
  <div>
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
      <span class="badge badge-navy">{{ $item->category->name }}</span>
      <span style="color: #64748b; font-size: 12.5px; font-weight: 600;">Kode: {{ $item->code }}</span>
    </div>

    <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 12px;">
      {{ $item->name }}
    </h1>

    <div style="font-size: 24px; font-weight: 800; color: #1e40af; margin-bottom: 16px;">
      @if($item->price_per_day > 0)
        Rp {{ number_format($item->price_per_day, 0, ',', '.') }}<span style="font-size: 13.5px; font-weight: 500; color: #64748b;">/hari</span>
      @else
        <span style="color: #16a34a;">Gratis (Inventaris Kantor)</span>
      @endif
    </div>

    <!-- Spesifikasi Grid -->
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; margin-bottom: 20px;">
      <div style="display: flex; justify-content: space-between; font-size: 13.5px; padding: 6px 0; border-bottom: 1px solid #f1f5f9;">
        <span style="color: #64748b;">Status Ketersediaan</span>
        <span class="badge {{ $item->status == 'Tersedia' ? 'badge-success' : 'badge-gray' }}">{{ $item->status }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 13.5px; padding: 6px 0; border-bottom: 1px solid #f1f5f9;">
        <span style="color: #64748b;">Total Stok Unit</span>
        <span style="font-weight: 700; color: #0f172a;">{{ $item->stock }} unit</span>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 13.5px; padding: 6px 0; border-bottom: 1px solid #f1f5f9;">
        <span style="color: #64748b;">Lokasi Penyimpanan</span>
        <span style="font-weight: 700; color: #0284c7;">{{ $item->storage_location ?? 'Gudang Utama Lt. 1' }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 13.5px; padding: 6px 0;">
        <span style="color: #64748b;">Kategori</span>
        <span style="font-weight: 600; color: #0f172a;">{{ $item->category->name }}</span>
      </div>
    </div>

    <!-- Deskripsi -->
    <div style="margin-bottom: 24px;">
      <h3 style="font-size: 13.5px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Deskripsi &amp; Spesifikasi</h3>
      <p style="font-size: 13.5px; color: #475569; line-height: 1.6;">
        {{ $item->description ?? 'Tidak ada catatan deskripsi tambahan untuk barang ini.' }}
      </p>
    </div>

    @if($item->status == 'Tersedia' && $item->stock > 0)
      <a href="{{ route('customer.borrowings.create', $item) }}" class="btn btn-primary btn-full btn-lg" style="height: 48px; font-size: 15px; font-weight: 700; border-radius: 10px; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        Ajukan Peminjaman Barang Ini
      </a>
    @else
      <button class="btn btn-secondary btn-full btn-lg" disabled style="height: 48px; border-radius: 10px;">
        Barang Sedang Tidak Tersedia
      </button>
    @endif
  </div>
</div>

<!-- BOTTOM SECTIONS: JADWAL KETERSEDIAAN & ULASAN PENGGUNA -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px;">
  
  <!-- JADWAL KETERSEDIAAN / AVAILABILITY SCHEDULE -->
  <div class="card">
    <div class="card-header" style="background: #f8fafc; display: flex; align-items: center; justify-content: space-between;">
      <div class="card-title" style="font-size: 15px; color: #0f172a;">Jadwal Peminjaman Aktif</div>
      <span style="font-size: 12px; color: #64748b;">(Sedang Berjalan / Terbooking)</span>
    </div>
    <div class="card-body" style="padding: 16px;">
      @if(isset($schedules) && $schedules->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 10px;">
          @foreach($schedules as $sch)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; border-left: 3px solid #2563eb;">
              <div>
                <div style="font-size: 13px; font-weight: 700; color: #0f172a;">
                  {{ $sch->borrow_date->format('d M Y') }} – {{ $sch->return_date->format('d M Y') }}
                </div>
                <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">
                  Durasi: {{ $sch->duration_days }} hari • {{ $sch->quantity }} unit
                </div>
              </div>
              <span class="badge badge-info" style="font-size: 11px;">{{ $sch->status }}</span>
            </div>
          @endforeach
        </div>
      @else
        <div style="text-align: center; padding: 32px 16px; color: #64748b;">
          <div style="font-size: 14px; font-weight: 700; color: #16a34a; margin-bottom: 4px;">Barang Sepenuhnya Tersedia!</div>
          <p style="font-size: 12.5px; margin: 0;">Belum ada jadwal peminjaman mendatang untuk barang ini. Anda dapat memilih tanggal sewa kapan saja.</p>
        </div>
      @endif
    </div>
  </div>

  <!-- ULASAN & TESTIMONI PENGGUNA -->
  <div class="card">
    <div class="card-header" style="background: #f8fafc; display: flex; align-items: center; justify-content: space-between;">
      <div class="card-title" style="font-size: 15px; color: #0f172a;">Ulasan Peminjam ({{ $item->reviewsCount() }})</div>
    </div>
    <div class="card-body" style="padding: 16px;">
      @if($item->reviews && $item->reviews->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 12px; max-height: 280px; overflow-y: auto;">
          @foreach($item->reviews as $rev)
            <div style="padding: 12px 14px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <div style="width: 26px; height: 26px; border-radius: 50%; background: #2563eb; color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700;">
                    {{ substr($rev->user->name ?? 'U', 0, 1) }}
                  </div>
                  <span style="font-size: 13px; font-weight: 700; color: #0f172a;">{{ $rev->user->name ?? 'Pengguna' }}</span>
                </div>
                <div style="color: #f59e0b; font-size: 13px;">
                  @for($i = 1; $i <= 5; $i++)
                    {{ $i <= $rev->rating ? '★' : '☆' }}
                  @endfor
                </div>
              </div>
              @if($rev->comment)
                <p style="font-size: 12.5px; color: #475569; margin: 4px 0 0; line-height: 1.5;">
                  "{{ $rev->comment }}"
                </p>
              @endif
              <div style="font-size: 10.5px; color: #94a3b8; margin-top: 4px;">
                {{ $rev->created_at->translatedFormat('d F Y') }}
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div style="text-align: center; padding: 32px 16px; color: #64748b;">
          <div style="font-size: 13.5px; font-weight: 600; color: #0f172a; margin-bottom: 4px;">Belum Ada Ulasan</div>
          <p style="font-size: 12.5px; margin: 0;">Jadilah yang pertama meminjam dan memberikan ulasan untuk barang ini!</p>
        </div>
      @endif
    </div>
  </div>

</div>
@endsection
