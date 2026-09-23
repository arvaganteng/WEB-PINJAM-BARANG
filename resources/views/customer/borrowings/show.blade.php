@extends('layouts.customer')

@section('title', 'Status: ' . $borrowing->borrow_code)

@section('content')
<div style="margin-bottom: 16px;">
  <a href="{{ route('customer.borrowings.index') }}" class="btn btn-ghost btn-sm" style="padding: 6px 0; color: #475569; font-weight: 600;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Kembali ke Daftar Peminjaman
  </a>
</div>

<div class="page-header" style="margin-bottom: 20px;">
  <div>
    <div class="page-title">Pelacak Status Peminjaman</div>
    <div class="page-subtitle">Pantau progres pengajuan, ajukan perpanjangan waktu, atau konfirmasi pengembalian barang</div>
  </div>
</div>

<div class="card" style="margin-bottom: 24px;">
  <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
    <div>
      <div class="card-title" style="font-size: 16px;">{{ $borrowing->borrow_code }}</div>
      <div class="card-subtitle">{{ $borrowing->item->name }} • Diajukan {{ $borrowing->created_at->translatedFormat('d F Y') }}</div>
    </div>
    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
      @if($borrowing->status == 'Menunggu')
        <span class="badge badge-warning"><span class="badge-pulse-dot"></span> Menunggu Persetujuan</span>
      @elseif($borrowing->status == 'Disetujui')
        <span class="badge badge-info"><span class="badge-pulse-dot"></span> Disetujui Admin</span>
      @elseif($borrowing->status == 'Dipinjam')
        <span class="badge badge-purple"><span class="badge-pulse-dot"></span> Sedang Dipinjam</span>
      @elseif($borrowing->status == 'Menunggu Verifikasi')
        <span class="badge" style="background: #f0fdfa; color: #0d9488; border: 1px solid #99f6e4;"><span class="badge-pulse-dot"></span> Menunggu Verifikasi Retur</span>
      @elseif($borrowing->status == 'Selesai')
        <span class="badge badge-success">Peminjaman Selesai</span>
      @elseif($borrowing->status == 'Ditolak')
        <span class="badge badge-danger">Ditolak</span>
      @endif
      <a href="{{ route('customer.borrowings.invoice', $borrowing) }}" class="btn btn-ghost btn-sm" style="font-size: 12px; padding: 5px 12px; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; gap: 6px; color: #0b1f6b; font-weight: 600;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Invoice
      </a>
    </div>
  </div>

  <div class="card-body">
    <!-- 5-Step Progress Flow with Radar Pulsing Animations -->
    <div class="status-flow" style="margin-bottom: 24px;">
      @php
        $currentStep = 1;
        if ($borrowing->status == 'Disetujui') $currentStep = 2;
        if ($borrowing->status == 'Dipinjam') $currentStep = 3;
        if ($borrowing->status == 'Menunggu Verifikasi') $currentStep = 4;
        if ($borrowing->status == 'Selesai') $currentStep = 5;
      @endphp

      <div class="flow-step">
        <div class="flow-dot done" title="Permohonan diajukan">✓</div>
        <div class="flow-label">Diajukan</div>
      </div>
      <div class="flow-line {{ $currentStep >= 1 ? 'done' : '' }}"></div>

      <div class="flow-step">
        <div class="flow-dot {{ $currentStep == 1 ? 'pending' : ($currentStep > 1 ? 'done' : '') }}" title="Verifikasi Admin">
          {{ $currentStep > 1 ? '✓' : '1' }}
        </div>
        <div class="flow-label">Verifikasi</div>
      </div>
      <div class="flow-line {{ $currentStep >= 2 ? 'done' : '' }}"></div>

      <div class="flow-step">
        <div class="flow-dot {{ $currentStep == 2 ? 'active' : ($currentStep > 2 ? 'done' : '') }}" title="Persetujuan Peminjaman">
          {{ $currentStep > 2 ? '✓' : '2' }}
        </div>
        <div class="flow-label">Disetujui</div>
      </div>
      <div class="flow-line {{ $currentStep >= 3 ? 'done' : '' }}"></div>

      <div class="flow-step">
        <div class="flow-dot {{ $currentStep == 3 ? 'active' : ($currentStep > 3 ? 'done' : '') }}" title="Barang Sedang Dipinjam">
          {{ $currentStep > 3 ? '✓' : '3' }}
        </div>
        <div class="flow-label">Dipinjam</div>
      </div>
      <div class="flow-line {{ $currentStep >= 4 ? 'done' : '' }}"></div>

      <div class="flow-step">
        <div class="flow-dot {{ $currentStep == 4 ? 'verifying' : ($currentStep == 5 ? 'done' : '') }}" title="Verifikasi Retur Fisik">
          {{ $currentStep == 5 ? '✓' : '4' }}
        </div>
        <div class="flow-label">{{ $borrowing->status == 'Menunggu Verifikasi' ? 'Pemeriksaan Retur' : 'Selesai' }}</div>
      </div>
    </div>

    @if($borrowing->status == 'Menunggu')
      <div class="info-alert yellow">
        Pengajuan Anda sedang ditinjau oleh administrator inventaris PT NDE.
      </div>
    @elseif($borrowing->status == 'Disetujui')
      <div class="info-alert green">
        Pengajuan telah disetujui! Silakan temui administrator inventaris untuk serah terima barang fisik.
      </div>
    @elseif($borrowing->status == 'Ditolak')
      <div class="info-alert red">
        Pengajuan ditolak. Alasan: <strong>{{ $borrowing->rejection_reason ?? 'Barang tidak dapat dipinjam saat ini.' }}</strong>
      </div>
    @elseif($borrowing->status == 'Dipinjam')
      <div class="info-alert blue" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <div>
          Barang sedang Anda pinjam. Harap kembalikan sebelum <strong>{{ $borrowing->return_date->translatedFormat('d F Y') }}</strong>.
        </div>
        @if($borrowing->extension_status == 'Pending')
          <span class="badge badge-warning">Permohonan Perpanjangan Menunggu Approval</span>
        @elseif($borrowing->extension_status == 'Approved')
          <span class="badge badge-success">Perpanjangan Disetujui</span>
        @elseif($borrowing->extension_status == 'Rejected')
          <span class="badge badge-danger">Perpanjangan Ditolak</span>
        @endif
      </div>
    @elseif($borrowing->status == 'Menunggu Verifikasi')
      <div class="info-alert yellow">
        Pengajuan pengembalian barang beserta bukti fisik sedang diperiksa oleh administrator inventaris.
      </div>
    @elseif($borrowing->status == 'Selesai')
      <div class="info-alert green">
        Peminjaman telah selesai dan barang telah berhasil diverifikasi kembali ke inventaris.
      </div>
    @endif
  </div>
</div>

<!-- INFORMASI DETAIL -->
<div class="detail-grid" style="margin-bottom: 24px;">
  <div class="detail-section">
    <div class="detail-section-title">Informasi Barang</div>
    <div class="detail-row"><div class="detail-key">Nama Barang</div><div class="detail-val">{{ $borrowing->item->name }}</div></div>
    <div class="detail-row"><div class="detail-key">Kategori</div><div class="detail-val">{{ $borrowing->item->category->name ?? '-' }}</div></div>
    <div class="detail-row"><div class="detail-key">Jumlah Unit</div><div class="detail-val">{{ $borrowing->quantity }} unit</div></div>
    <div class="detail-row"><div class="detail-key">Kondisi Awal</div><div class="detail-val">{{ $borrowing->item->condition }}</div></div>
    <div class="detail-row">
      <div class="detail-key">Lokasi Asal Barang</div>
      <div class="detail-val" style="font-weight:700;color:#0369a1;">{{ $borrowing->item->storage_location ?? 'Gudang Utama Lt. 1' }}</div>
    </div>
  </div>

  <div class="detail-section">
    <div class="detail-section-title">Informasi Peminjaman</div>
    <div class="detail-row"><div class="detail-key">Tgl Mulai</div><div class="detail-val">{{ $borrowing->borrow_date->format('d/m/Y') }}</div></div>
    <div class="detail-row"><div class="detail-key">Tgl Kembali</div><div class="detail-val" style="font-weight: 700; color: #1e40af;">{{ $borrowing->return_date->format('d/m/Y') }}</div></div>
    <div class="detail-row"><div class="detail-key">Durasi</div><div class="detail-val">{{ $borrowing->duration_days }} hari</div></div>
    <div class="detail-row" style="align-items:flex-start;">
      <div class="detail-key">Lokasi Pemakaian</div>
      <div class="detail-val" style="font-weight:600;color:#0f172a;">
        {{ $borrowing->location ?? '-' }}
        @if($borrowing->latitude && $borrowing->longitude)
          <div style="margin-top:6px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <span style="font-size:11px;color:#64748b;background:#f1f5f9;padding:2px 8px;border-radius:4px;">
              Lat: {{ number_format($borrowing->latitude, 5) }}, Long: {{ number_format($borrowing->longitude, 5) }}
            </span>
            <a href="https://www.google.com/maps?q={{ $borrowing->latitude }},{{ $borrowing->longitude }}" target="_blank" class="btn btn-sm" style="background:#0284c7;color:#fff;font-size:11px;padding:3px 8px;display:inline-flex;align-items:center;gap:4px;text-decoration:none;">
              Buka di Google Maps
            </a>
          </div>
          <div style="margin-top:8px;border-radius:8px;overflow:hidden;border:1px solid #cbd5e1;max-width:400px;height:150px;">
            <iframe width="100%" height="150" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ $borrowing->latitude }},{{ $borrowing->longitude }}&z=15&output=embed"></iframe>
          </div>
        @elseif($borrowing->location)
          <div style="margin-top:6px;">
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($borrowing->location) }}" target="_blank" class="btn btn-sm" style="background:#0284c7;color:#fff;font-size:11px;padding:3px 8px;display:inline-flex;align-items:center;gap:4px;text-decoration:none;">
              Cari di Google Maps
            </a>
          </div>
          <div style="margin-top:8px;border-radius:8px;overflow:hidden;border:1px solid #cbd5e1;max-width:400px;height:150px;">
            <iframe width="100%" height="150" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode($borrowing->location) }}&z=15&output=embed"></iframe>
          </div>
        @endif
      </div>
    </div>
    <div class="detail-row"><div class="detail-key">Keperluan</div><div class="detail-val">{{ $borrowing->purpose }}</div></div>
    @if($borrowing->id_card_image)
      <div class="detail-row">
        <div class="detail-key">Dokumen Jaminan</div>
        <div class="detail-val">
          <button type="button" onclick="previewKtpSoft('{{ asset('storage/' . $borrowing->id_card_image) }}', 'Dokumen Jaminan (KTP / KK)')" class="btn btn-ghost btn-sm" style="font-size:12px;padding:3px 10px;border:1px solid #cbd5e1;color:#2563eb;cursor:pointer;">
            Lihat Foto KTP/KK
          </button>
        </div>
      </div>
    @endif
    <div class="detail-row"><div class="detail-key">Total Biaya</div><div class="detail-val" style="font-weight:700">Rp {{ number_format($borrowing->total_price, 0, ',', '.') }}</div></div>
  </div>
</div>

<!-- SECTION KARTU PEMBAYARAN BIAYA SEWA -->
@if($borrowing->total_price > 0)
  @php
    $psBorder = $borrowing->payment_status === 'Lunas' ? '#22c55e' : ($borrowing->payment_status === 'Menunggu Verifikasi' ? '#f59e0b' : ($borrowing->payment_status === 'Ditolak' ? '#ef4444' : '#3b82f6'));
    $psBg = $borrowing->payment_status === 'Lunas' ? '#f0fdf4' : ($borrowing->payment_status === 'Menunggu Verifikasi' ? '#fffbeb' : ($borrowing->payment_status === 'Ditolak' ? '#fef2f2' : '#eff6ff'));
    $psBdDiv = $borrowing->payment_status === 'Lunas' ? '#dcfce7' : ($borrowing->payment_status === 'Menunggu Verifikasi' ? '#fde68a' : ($borrowing->payment_status === 'Ditolak' ? '#fecaca' : '#bfdbfe'));
    $psColor = $borrowing->payment_status === 'Lunas' ? '#15803d' : ($borrowing->payment_status === 'Menunggu Verifikasi' ? '#92400e' : ($borrowing->payment_status === 'Ditolak' ? '#991b1b' : '#1e40af'));
  @endphp
  <div class="card" style="margin-bottom: 24px; border: 1.5px solid {{ $psBorder }};">
    <div class="card-header" style="background: {{ $psBg }}; border-bottom: 1px solid {{ $psBdDiv }}; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
      <div>
        <div class="card-title" style="color: {{ $psColor }}; font-size: 15.5px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
          <span>Tagihan Biaya Sewa Barang</span>
          @if($borrowing->payment_status === 'Lunas')
            <span class="badge" style="background:#dcfce7;color:#15803d;border:1px solid #86efac;">Lunas</span>
          @elseif($borrowing->payment_status === 'Menunggu Verifikasi')
            <span class="badge" style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;">Menunggu Verifikasi</span>
          @elseif($borrowing->payment_status === 'Ditolak')
            <span class="badge" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;">Bukti Ditolak</span>
          @else
            <span class="badge" style="background:#dbeafe;color:#1e40af;border:1px solid #bfdbfe;"><span class="badge-pulse-dot" style="background:#3b82f6;"></span> Belum Dibayar</span>
          @endif
        </div>
        <div class="card-subtitle" style="color:#64748b;">Sewa aset inventaris PT Nusantara Digital Express</div>
      </div>
      <div style="font-size:20px;font-weight:800;color:{{ $borrowing->payment_status === 'Lunas' ? '#15803d' : '#0b1f6b' }};">
        Rp {{ number_format($borrowing->total_price, 0, ',', '.') }}
      </div>
    </div>
    <div class="card-body" style="padding:20px;">

      @if($borrowing->payment_status === 'Lunas')
        <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:10px;padding:16px;color:#166534;font-size:13.5px;">
          <div style="font-weight:700;margin-bottom:4px;">Pembayaran Dikonfirmasi Lunas</div>
          <div>Biaya sewa <strong>Rp {{ number_format($borrowing->total_price, 0, ',', '.') }}</strong> via <strong>{{ $borrowing->payment_method }}</strong> telah diverifikasi lunas oleh admin.@if($borrowing->paid_at) Dikonfirmasi: <strong>{{ $borrowing->paid_at->translatedFormat('d F Y, H:i') }} WIB</strong>.@endif</div>
          <div style="margin-top:10px;">
            <a href="{{ route('customer.borrowings.invoice', $borrowing) }}" class="btn btn-ghost btn-sm" style="background:#fff;border:1px solid #86efac;color:#15803d;font-size:12px;display:inline-flex;align-items:center;gap:6px;">
              Lihat / Cetak Invoice
            </a>
          </div>
        </div>

      @elseif($borrowing->payment_status === 'Menunggu Verifikasi')
        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:16px;color:#92400e;font-size:13.5px;margin-bottom:14px;">
          <div style="font-weight:700;margin-bottom:4px;">Bukti Pembayaran Sedang Diverifikasi Admin</div>
          <div>Anda telah mengunggah bukti metode <strong>{{ $borrowing->payment_method }}</strong>. Admin akan segera mengkonfirmasi dan mengirimkan notifikasi.</div>
          @if($borrowing->payment_proof)
            <div style="margin-top:10px;">
              <button type="button" onclick="previewKtpSoft('{{ asset('storage/' . $borrowing->payment_proof) }}', 'Bukti Pembayaran Sewa')" class="btn btn-ghost btn-sm" style="background:#fff;border:1px solid #fde68a;color:#92400e;font-size:12px;cursor:pointer;">
                Lihat Bukti yang Dikirim
              </button>
            </div>
          @endif
        </div>
        <div style="text-align:right;">
          <a href="{{ route('customer.borrowings.invoice', $borrowing) }}" class="btn btn-ghost btn-sm" style="font-size:12px;border:1px solid #cbd5e1;color:#0b1f6b;display:inline-flex;align-items:center;gap:6px;">Lihat Invoice</a>
        </div>

      @elseif($borrowing->payment_status === 'Ditolak')
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:16px;color:#991b1b;font-size:13.5px;margin-bottom:16px;">
          <div style="font-weight:700;margin-bottom:4px;">Bukti Pembayaran Ditolak Admin — Perlu Upload Ulang</div>
          @if($borrowing->payment_notes)
            <div>Alasan penolakan: <strong>{{ $borrowing->payment_notes }}</strong></div>
          @else
            <div>Silakan unggah ulang bukti transfer yang valid.</div>
          @endif
        </div>
        <div style="margin-bottom:12px;font-size:13px;color:#0f172a;font-weight:600;">Unggah ulang bukti pembayaran:</div>
        <form action="{{ route('customer.borrowings.pay', $borrowing) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
            <div class="pay-card" onclick="selectPayCard(this, 'Transfer Bank')" style="border:2px solid #2563eb;background:#eff6ff;border-radius:12px;padding:14px;cursor:pointer;user-select:none;">
              <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;pointer-events:none;">
                <input type="radio" name="payment_method" value="Transfer Bank" checked style="width:17px;height:17px;cursor:pointer;pointer-events:none;"/>
                <span style="font-weight:700;font-size:13.5px;color:#1e40af;">Transfer Bank</span>
              </div>
              <div style="display:flex;align-items:center;gap:6px;margin-top:4px;pointer-events:none;">
                <img src="{{ asset('images/payments/bca.svg') }}" alt="BCA" height="18"/>
                <img src="{{ asset('images/payments/mandiri.svg') }}" alt="Mandiri" height="18"/>
              </div>
            </div>
            <div class="pay-card" onclick="selectPayCard(this, 'QRIS')" style="border:2px solid #e2e8f0;background:#f8fafc;border-radius:12px;padding:14px;cursor:pointer;user-select:none;">
              <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;pointer-events:none;">
                <input type="radio" name="payment_method" value="QRIS" style="width:17px;height:17px;cursor:pointer;pointer-events:none;"/>
                <span style="font-weight:700;font-size:13.5px;color:#334155;">QRIS &amp; E-Wallet</span>
              </div>
              <div style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;margin-top:4px;pointer-events:none;">
                <img src="{{ asset('images/payments/qris.svg') }}" alt="QRIS" height="18"/>
                <img src="{{ asset('images/payments/dana.png') }}" alt="DANA" height="18" style="object-fit:contain;"/>
                <img src="{{ asset('images/payments/gopay.png') }}" alt="GoPay" height="18" style="object-fit:contain;"/>
                <img src="{{ asset('images/payments/ovo.jpg') }}" alt="OVO" height="18" style="object-fit:contain;"/>
              </div>
            </div>
          </div>
          <div id="pay-qris-box" style="display:none;background:#fff;border:1px solid #cbd5e1;border-radius:12px;padding:16px;text-align:center;margin-bottom:14px;box-shadow:0 4px 12px rgba(0,0,0,0.06);">
            <div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:8px;">Scan QRIS Resmi ARVASHOP:</div>
            <img src="{{ asset('images/payments/qris-code.jpg') }}" alt="QRIS Resmi ARVASHOP" style="max-width:260px;width:100%;height:auto;border-radius:8px;border:1px solid #cbd5e1;display:inline-block;"/>
            <div style="font-size:11.5px;color:#64748b;margin-top:8px;">Bisa discan melalui DANA, GoPay, OVO, &amp; Aplikasi Mobile Banking (BCA/Mandiri/dll)</div>
          </div>
          <div class="form-group" style="margin-bottom:16px;">
            <label class="form-label" style="font-weight:600;">Bukti Pembayaran <span class="required">*</span></label>
            <input type="file" name="payment_proof" class="form-input" accept="image/jpeg,image/png,image/jpg,image/webp" required/>
            <small style="color:#64748b;font-size:11.5px;margin-top:4px;display:block;">Format JPG, PNG, WEBP. Maks 3 MB.</small>
          </div>
          <div class="form-group" style="margin-bottom:16px;">
            <label class="form-label" style="font-weight:600;">Catatan (Opsional)</label>
            <input type="text" name="payment_notes" class="form-input" placeholder="Contoh: Transfer BCA an Budi"/>
          </div>
          <div style="text-align:right;">
            <button type="submit" class="btn btn-primary btn-lg" style="padding:10px 24px;font-size:14px;font-weight:700;">
              Kirim Ulang Bukti Pembayaran
            </button>
          </div>
        </form>

      @else
        @if(!in_array($borrowing->status, ['Ditolak','Dibatalkan']))
          <div style="margin-bottom:14px;font-size:13.5px;color:#0f172a;font-weight:600;">Pilih metode dan unggah bukti pembayaran biaya sewa barang:</div>

          <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;margin-bottom:18px;">
            <div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:10px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
              <span>Rekening &amp; QRIS Resmi PT NDE:</span>
              <div style="display:flex;align-items:center;gap:5px;flex-wrap:wrap;">
                <img src="{{ asset('images/payments/bca.svg') }}" alt="BCA" height="20"/>
                <img src="{{ asset('images/payments/mandiri.svg') }}" alt="Mandiri" height="20"/>
                <img src="{{ asset('images/payments/qris.svg') }}" alt="QRIS" height="20"/>
                <img src="{{ asset('images/payments/dana.png') }}" alt="DANA" height="20" style="object-fit:contain;"/>
                <img src="{{ asset('images/payments/gopay.png') }}" alt="GoPay" height="20" style="object-fit:contain;"/>
                <img src="{{ asset('images/payments/ovo.jpg') }}" alt="OVO" height="20" style="object-fit:contain;"/>
              </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
              <div style="background:#fff;border:1px solid #bae6fd;border-radius:8px;padding:10px 12px;display:flex;align-items:center;gap:12px;">
                <img src="{{ asset('images/payments/bca.svg') }}" alt="BCA" height="30" style="border-radius:4px;"/>
                <div>
                  <div style="font-size:15px;font-weight:900;color:#0b1f6b;font-family:monospace;letter-spacing:0.5px;">8830-9921-4412</div>
                  <div style="font-size:11px;color:#334155;">a/n PT Nusantara Digital Express</div>
                </div>
              </div>
              <div style="background:#fff;border:1px solid #86efac;border-radius:8px;padding:10px 12px;display:flex;align-items:center;gap:12px;">
                <img src="{{ asset('images/payments/mandiri.svg') }}" alt="Mandiri" height="30" style="border-radius:4px;"/>
                <div>
                  <div style="font-size:15px;font-weight:900;color:#0b1f6b;font-family:monospace;letter-spacing:0.5px;">1420-0012-9981-2</div>
                  <div style="font-size:11px;color:#334155;">a/n PT Nusantara Digital Express</div>
                </div>
              </div>
            </div>
          </div>

          <form action="{{ route('customer.borrowings.pay', $borrowing) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:18px;">
              <div class="pay-card" onclick="selectPayCard(this, 'Transfer Bank')" style="border:2px solid #2563eb;background:#eff6ff;border-radius:12px;padding:14px;cursor:pointer;user-select:none;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;pointer-events:none;">
                  <input type="radio" name="payment_method" value="Transfer Bank" checked style="width:17px;height:17px;cursor:pointer;pointer-events:none;"/>
                  <span style="font-weight:700;font-size:13.5px;color:#1e40af;">Transfer Bank</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px;margin-top:4px;pointer-events:none;">
                  <img src="{{ asset('images/payments/bca.svg') }}" alt="BCA" height="18"/>
                  <img src="{{ asset('images/payments/mandiri.svg') }}" alt="Mandiri" height="18"/>
                </div>
              </div>

              <div class="pay-card" onclick="selectPayCard(this, 'QRIS')" style="border:2px solid #e2e8f0;background:#f8fafc;border-radius:12px;padding:14px;cursor:pointer;user-select:none;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;pointer-events:none;">
                  <input type="radio" name="payment_method" value="QRIS" style="width:17px;height:17px;cursor:pointer;pointer-events:none;"/>
                  <span style="font-weight:700;font-size:13.5px;color:#334155;">QRIS &amp; E-Wallet</span>
                </div>
                <div style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;margin-top:4px;pointer-events:none;">
                  <img src="{{ asset('images/payments/qris.svg') }}" alt="QRIS" height="18"/>
                  <img src="{{ asset('images/payments/dana.png') }}" alt="DANA" height="18" style="object-fit:contain;"/>
                  <img src="{{ asset('images/payments/gopay.png') }}" alt="GoPay" height="18" style="object-fit:contain;"/>
                  <img src="{{ asset('images/payments/ovo.jpg') }}" alt="OVO" height="18" style="object-fit:contain;"/>
                </div>
              </div>

              <div class="pay-card" onclick="selectPayCard(this, 'Tunai')" style="border:2px solid #e2e8f0;background:#f8fafc;border-radius:12px;padding:14px;cursor:pointer;user-select:none;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;pointer-events:none;">
                  <input type="radio" name="payment_method" value="Tunai" style="width:17px;height:17px;cursor:pointer;pointer-events:none;"/>
                  <span style="font-weight:700;font-size:13.5px;color:#334155;">Tunai / Cash (COD)</span>
                </div>
                <div style="font-size:11.5px;color:#64748b;margin-top:4px;pointer-events:none;">Bayar di kasir / gudang</div>
              </div>
            </div>

            <div id="pay-qris-box" style="display:none;background:#fff;border:1px solid #cbd5e1;border-radius:12px;padding:16px;text-align:center;margin-bottom:14px;box-shadow:0 4px 12px rgba(0,0,0,0.06);">
              <div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:8px;">Scan QRIS Resmi ARVASHOP:</div>
              <img src="{{ asset('images/payments/qris-code.jpg') }}" alt="QRIS Resmi ARVASHOP" style="max-width:260px;width:100%;height:auto;border-radius:8px;border:1px solid #cbd5e1;display:inline-block;"/>
              <div style="font-size:11.5px;color:#64748b;margin-top:8px;">Bisa discan melalui DANA, GoPay, OVO, &amp; Aplikasi Mobile Banking (BCA/Mandiri/dll)</div>
            </div>

            <div id="pay-proof-section">
              <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label" style="font-weight:600;">Bukti Pembayaran <span class="required">*</span></label>
                <input type="file" name="payment_proof" class="form-input" accept="image/jpeg,image/png,image/jpg,image/webp" id="pay-proof-input" required/>
                <small style="color:#64748b;font-size:11.5px;margin-top:4px;display:block;">Format JPG, PNG, WEBP. Maks 3 MB.</small>
              </div>
            </div>

            <div id="pay-tunai-box" style="display:none;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px;color:#92400e;font-size:12.5px;margin-bottom:14px;line-height:1.6;">
              <strong>Instruksi Tunai / COD:</strong> Siapkan uang tunai pas sebesar <strong>Rp {{ number_format($borrowing->total_price, 0, ',', '.') }}</strong> dan serahkan kepada kasir / petugas inventaris saat pengambilan fisik barang.
            </div>

            <div class="form-group" style="margin-bottom:16px;">
              <label class="form-label" style="font-weight:600;">Catatan (Opsional)</label>
              <input type="text" name="payment_notes" class="form-input" placeholder="Contoh: Transfer BCA an Budi Santoso / Bayar tunai di lokasi"/>
            </div>

            <div style="text-align:right;">
              <button type="submit" class="btn btn-primary btn-lg" style="padding:10px 24px;font-size:14px;font-weight:700;">
                Kirim Konfirmasi Pembayaran Sewa
              </button>
            </div>
          </form>

          <script>
            function selectPayCard(cardEl, val) {
              const form = cardEl.closest('form');
              if (!form) return;

              const radios = form.querySelectorAll('input[name="payment_method"]');
              radios.forEach(r => {
                r.checked = (r.value === val);
              });

              const cards = form.querySelectorAll('.pay-card');
              cards.forEach(c => {
                c.style.borderColor = '#e2e8f0';
                c.style.background = '#f8fafc';
                const span = c.querySelector('span');
                if (span) span.style.color = '#334155';
              });

              cardEl.style.borderColor = '#2563eb';
              cardEl.style.background = '#eff6ff';
              const mySpan = cardEl.querySelector('span');
              if (mySpan) mySpan.style.color = '#1e40af';

              const proofSec = form.querySelector('#pay-proof-section');
              const tunaiBox = form.querySelector('#pay-tunai-box');
              const qrisBox = form.querySelector('#pay-qris-box');
              const proofInput = form.querySelector('#pay-proof-input');

              if (val === 'Tunai') {
                if (proofSec) proofSec.style.display = 'none';
                if (tunaiBox) tunaiBox.style.display = 'block';
                if (qrisBox) qrisBox.style.display = 'none';
                if (proofInput) proofInput.required = false;
              } else if (val === 'QRIS') {
                if (proofSec) proofSec.style.display = 'block';
                if (tunaiBox) tunaiBox.style.display = 'none';
                if (qrisBox) qrisBox.style.display = 'block';
                if (proofInput) proofInput.required = true;
              } else {
                if (proofSec) proofSec.style.display = 'block';
                if (tunaiBox) tunaiBox.style.display = 'none';
                if (qrisBox) qrisBox.style.display = 'none';
                if (proofInput) proofInput.required = true;
              }
            }
          </script>
        @endif
      @endif
    </div>
  </div>
@endif

<!-- SECTION KARTU TAGIHAN PEMBAYARAN DENDA (TRANSFER & CASH) -->
@if($borrowing->returnRecord && $borrowing->returnRecord->fine_amount > 0)
  <div class="card" style="margin-bottom: 24px; border: 1.5px solid #ef4444; box-shadow: 0 4px 14px rgba(239, 68, 68, 0.08);">
    <div class="card-header" style="background: #fef2f2; border-bottom: 1px solid #fecaca; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
      <div>
        <div class="card-title" style="color: #991b1b; font-size: 15.5px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
          <span>Tagihan Denda Peminjaman</span>
          <span class="badge" style="background: {{ $borrowing->returnRecord->fine_payment_status == 'Lunas' ? '#dcfce7' : ($borrowing->returnRecord->fine_payment_status == 'Menunggu Verifikasi' ? '#fef3c7' : '#fee2e2') }}; color: {{ $borrowing->returnRecord->fine_payment_status == 'Lunas' ? '#15803d' : ($borrowing->returnRecord->fine_payment_status == 'Menunggu Verifikasi' ? '#92400e' : '#b91c1c') }};">
            {{ $borrowing->returnRecord->fine_payment_status == 'Lunas' ? 'Lunas' : ($borrowing->returnRecord->fine_payment_status == 'Menunggu Verifikasi' ? 'Menunggu Verifikasi Admin' : 'Belum Lunas') }}
          </span>
        </div>
        <div class="card-subtitle" style="color: #b91c1c;">Terdapat denda atas keterlambatan atau kondisi barang yang dikembalikan.</div>
      </div>
      <div style="font-size: 18px; font-weight: 800; color: #991b1b;">
        Rp {{ number_format($borrowing->returnRecord->fine_amount, 0, ',', '.') }}
      </div>
    </div>

    <div class="card-body" style="padding: 20px;">
      @if($borrowing->returnRecord->fine_payment_status == 'Lunas')
        <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 10px; padding: 16px; color: #166534; font-size: 13.5px;">
          <div style="font-weight: 700; margin-bottom: 4px;">Denda Telah Dikonfirmasi Lunas</div>
          <div>Pembayaran denda sebesar <strong>Rp {{ number_format($borrowing->returnRecord->fine_amount, 0, ',', '.') }}</strong> dengan metode <strong>{{ $borrowing->returnRecord->fine_payment_method }}</strong> telah diterima dan diverifikasi oleh admin. Terima kasih!</div>
        </div>
      @elseif($borrowing->returnRecord->fine_payment_status == 'Menunggu Verifikasi')
        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 16px; color: #92400e; font-size: 13.5px;">
          <div style="font-weight: 700; margin-bottom: 4px;">Menunggu Verifikasi Pembayaran Denda</div>
          <div>Anda telah memilih metode <strong>{{ $borrowing->returnRecord->fine_payment_method }}</strong>. Admin sedang memproses konfirmasi pelunasan denda Anda.</div>
          @if($borrowing->returnRecord->fine_payment_proof)
            <div style="margin-top: 10px;">
              <button type="button" onclick="previewKtpSoft('{{ asset('storage/' . $borrowing->returnRecord->fine_payment_proof) }}', 'Bukti Transfer Denda')" class="btn btn-ghost btn-sm" style="background: #ffffff; border: 1px solid #fde68a; color: #92400e; font-size: 12px; cursor: pointer;">
                Lihat Bukti Transfer Terkirim
              </button>
            </div>
          @endif
        </div>
      @else
        <!-- Form Pilih Metode Bayar (Transfer vs Cash) -->
        <form action="{{ route('customer.borrowings.pay-fine', $borrowing) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div style="margin-bottom: 16px; font-weight: 700; color: #0f172a; font-size: 14px;">Pilih Metode Pembayaran Denda:</div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <!-- Opsi 1: Transfer Bank -->
            <label id="opt-transfer" style="border: 2px solid #3b82f6; background: #eff6ff; border-radius: 12px; padding: 16px; cursor: pointer; transition: all 0.2s ease; display: block;" onclick="switchFineMethod('Transfer')">
              <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <input type="radio" name="fine_payment_method" value="Transfer" checked id="radio-transfer"/>
                <span style="font-weight: 700; font-size: 14px; color: #1e40af;">Transfer Bank / QRIS</span>
              </div>
              <div style="font-size: 12px; color: #3b82f6; line-height: 1.5;">
                Transfer ke Bank BCA / Mandiri resmi, lalu unggah struk foto bukti transfer.
              </div>
            </label>

            <!-- Opsi 2: Bayar Cash -->
            <label id="opt-cash" style="border: 2px solid #e2e8f0; background: #f8fafc; border-radius: 12px; padding: 16px; cursor: pointer; transition: all 0.2s ease; display: block;" onclick="switchFineMethod('Cash')">
              <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <input type="radio" name="fine_payment_method" value="Cash" id="radio-cash"/>
                <span style="font-weight: 700; font-size: 14px; color: #334155;">Bayar Tunai (Cash)</span>
              </div>
              <div style="font-size: 12px; color: #64748b; line-height: 1.5;">
                Bayar uang tunai langsung ke kasir/petugas gudang saat menyerahkan pengembalian barang.
              </div>
            </label>
          </div>

          <!-- Box Detail Transfer Bank -->
          <div id="transfer-details-box" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
            <div style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">Rekening Resmi Pembayaran Denda PT NDE:</div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
              <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px;">
                <div style="font-size: 11px; color: #64748b; font-weight: 600;">BANK BCA</div>
                <div style="font-size: 14px; font-weight: 800; color: #0b1f6b; font-family: monospace;">8830-9921-4412</div>
                <div style="font-size: 11px; color: #334155;">a/n PT Nusantara Digital Express</div>
              </div>
              <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px;">
                <div style="font-size: 11px; color: #64748b; font-weight: 600;">BANK MANDIRI</div>
                <div style="font-size: 14px; font-weight: 800; color: #0b1f6b; font-family: monospace;">1420-0012-9981-2</div>
                <div style="font-size: 11px; color: #334155;">a/n PT Nusantara Digital Express</div>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" style="font-weight: 600;">Upload Struk Foto Bukti Transfer <span class="required">*</span></label>
              <input type="file" name="fine_payment_proof" class="form-input" accept="image/jpeg,image/png,image/jpg,image/webp" id="proof-file-input" required/>
              <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Format JPG, PNG, WEBP. Maksimal 3 MB.</small>
            </div>
          </div>

          <!-- Box Detail Cash -->
          <div id="cash-details-box" style="display: none; background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 16px; margin-bottom: 20px; color: #92400e; font-size: 12.5px; line-height: 1.6;">
            <strong>Instruksi Pembayaran Tunai (Cash):</strong><br/>
            Silakan persiapkan uang pas sebesar <strong>Rp {{ number_format($borrowing->returnRecord->fine_amount, 0, ',', '.') }}</strong> dan serahkan kepada kasir atau petugas gudang inventaris saat pengembalian barang fisik dilakukan.
          </div>

          <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" style="font-weight: 600;">Catatan Tambahan Pembayaran (Opsional)</label>
            <input type="text" name="fine_payment_notes" class="form-input" placeholder="Contoh: Transfer via M-BCA an Budi / Bayar cash pas di kasir gudang lt 1"/>
          </div>

          <div style="text-align: right;">
            <button type="submit" class="btn btn-primary btn-lg" style="padding: 10px 24px; font-size: 14px; font-weight: 700;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
              Kirim Konfirmasi Pembayaran Denda
            </button>
          </div>
        </form>

        <script>
          function switchFineMethod(method) {
            const optTransfer = document.getElementById('opt-transfer');
            const optCash = document.getElementById('opt-cash');
            const radioTransfer = document.getElementById('radio-transfer');
            const radioCash = document.getElementById('radio-cash');
            const boxTransfer = document.getElementById('transfer-details-box');
            const boxCash = document.getElementById('cash-details-box');
            const proofInput = document.getElementById('proof-file-input');

            if (method === 'Transfer') {
              radioTransfer.checked = true;
              optTransfer.style.borderColor = '#3b82f6';
              optTransfer.style.background = '#eff6ff';
              optCash.style.borderColor = '#e2e8f0';
              optCash.style.background = '#f8fafc';
              boxTransfer.style.display = 'block';
              boxCash.style.display = 'none';
              if (proofInput) proofInput.required = true;
            } else {
              radioCash.checked = true;
              optCash.style.borderColor = '#3b82f6';
              optCash.style.background = '#eff6ff';
              optTransfer.style.borderColor = '#e2e8f0';
              optTransfer.style.background = '#f8fafc';
              boxCash.style.display = 'block';
              boxTransfer.style.display = 'none';
              if (proofInput) proofInput.required = false;
            }
          }
        </script>
      @endif
    </div>
  </div>
@endif

<!-- SECTION 1: PERPANJANGAN WAKTU (REQUEST EXTENSION) -->
@if($borrowing->status == 'Dipinjam')
  <div class="card" style="margin-bottom: 24px; border: 1.5px solid #3b82f6;">
    <div class="card-header" style="background: #eff6ff; border-bottom: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: space-between;">
      <div>
        <div class="card-title" style="color: #1e40af; font-size: 15.5px;">Pengajuan Perpanjangan Waktu Peminjaman</div>
        <div class="card-subtitle">Pekerjaan belum selesai? Ajukan perpanjangan tanggal sebelum masa pinjam habis.</div>
      </div>
      @if($borrowing->extension_status == 'Pending')
        <span class="badge badge-warning">Menunggu Respon Admin</span>
      @endif
    </div>
    <div class="card-body" style="padding: 20px;">
      @if($borrowing->extension_status == 'Pending')
        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 14px; font-size: 13px; color: #92400e;">
          Anda telah mengajukan perpanjangan hingga <strong>{{ $borrowing->extension_date->format('d F Y') }}</strong> dengan alasan: <em>"{{ $borrowing->extension_reason }}"</em>. Menunggu persetujuan Admin.
        </div>
      @elseif($borrowing->canRequestExtension())
        <form action="{{ route('customer.borrowings.extension-request', $borrowing) }}" method="POST">
          @csrf
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Tanggal Pengembalian Baru <span class="required">*</span></label>
              <input type="date" name="extension_date" class="form-input" min="{{ \Carbon\Carbon::parse($borrowing->return_date)->addDay()->toDateString() }}" max="{{ \Carbon\Carbon::parse($borrowing->return_date)->addDays(3)->toDateString() }}" required/>
              <small style="color: #64748b; font-size: 12px; margin-top: 4px; display: block;">Maksimal perpanjangan adalah 3 hari (s/d {{ \Carbon\Carbon::parse($borrowing->return_date)->addDays(3)->format('d/m/Y') }}).</small>
            </div>
            <div class="form-group">
              <label class="form-label">Alasan Perpanjangan <span class="required">*</span></label>
              <input type="text" name="extension_reason" class="form-input" placeholder="Contoh: Pekerjaan project presentasi diperpanjang 2 hari" required/>
            </div>
          </div>
          <div style="text-align: right;">
            <button type="submit" class="btn btn-primary">
              Ajukan Perpanjangan Waktu
            </button>
          </div>
        </form>
      @endif
    </div>
  </div>
@endif

<!-- SECTION 2: FORM PENGEMBALIAN BARANG + FOTO FISIK BUKTI -->
@if($borrowing->status == 'Dipinjam' && !$borrowing->returnRecord)
  <div class="card" style="margin-bottom: 24px; border: 1.5px solid #22c55e;">
    <div class="card-header" style="background: #f0fdf4; border-bottom: 1px solid #dcfce7;">
      <div>
        <div class="card-title" style="color: #15803d; font-size: 15.5px;">Ajukan Pengembalian Barang &amp; Bukti Foto</div>
        <div class="card-subtitle">Konfirmasi pengembalian aset ini dan lampirkan foto fisik kondisi barang</div>
      </div>
    </div>
    <div class="card-body" style="padding: 20px;">
      <form action="{{ route('customer.borrowings.return-request', $borrowing) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group" style="margin-bottom: 16px;">
          <label class="form-label" style="font-weight: 600;">Catatan Kondisi Barang (Opsional)</label>
          <textarea name="customer_notes" class="form-input form-textarea" rows="3" placeholder="Contoh: Barang dalam kondisi bersih, berfungsi 100%, lengkap dengan kabel/aksesoris..."></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
          <label class="form-label" style="font-weight: 600;">Unggah Foto Bukti Fisik Barang (Opsional)</label>
          <input type="file" name="return_photo" class="form-input" accept="image/*"/>
          <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">
            Lampirkan 1 foto kondisi barang saat ini untuk mempercepat verifikasi fisik admin
          </small>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
          <div style="font-size: 12.5px; color: #92400e; background: #fffbeb; padding: 10px 14px; border-radius: 8px; border: 1px solid #fef3c7;">
            <strong>Info:</strong> Setelah formulir dikirim, status berubah menjadi <em>Menunggu Verifikasi</em>.
          </div>
          <button type="submit" class="btn btn-success btn-lg" style="font-size: 14px; padding: 10px 24px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            Konfirmasi &amp; Ajukan Pengembalian
          </button>
        </div>
      </form>
    </div>
  </div>
@endif

<!-- SECTION 3: RATING & REVIEW BARANG (WHEN SELESAI) -->
@if($borrowing->status == 'Selesai')
  <div class="card" style="border: 1.5px solid #f59e0b;">
    <div class="card-header" style="background: #fffbeb; border-bottom: 1px solid #fde68a;">
      <div>
        <div class="card-title" style="color: #b45309; font-size: 15.5px;">Rating &amp; Ulasan Barang</div>
        <div class="card-subtitle">Bagikan pengalaman Anda saat menggunakan barang inventaris ini</div>
      </div>
    </div>
    <div class="card-body" style="padding: 20px;">
      @if($borrowing->review)
        <!-- Review Sudah Diberikan -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <div style="color: #f59e0b; font-size: 20px; letter-spacing: 2px;">
              @for($i = 1; $i <= 5; $i++)
                {{ $i <= $borrowing->review->rating ? '★' : '☆' }}
              @endfor
            </div>
            <span style="font-size: 12px; color: #64748b;">{{ $borrowing->review->created_at->format('d/m/Y H:i') }}</span>
          </div>
          @if($borrowing->review->comment)
            <p style="font-size: 13.5px; color: #334155; margin: 0; line-height: 1.6;">
              "{{ $borrowing->review->comment }}"
            </p>
          @endif
        </div>
      @else
        <!-- Form Input Review Baru -->
        <form action="{{ route('customer.borrowings.review', $borrowing) }}" method="POST">
          @csrf
          <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label" style="font-weight: 600;">Berikan Bintang Kepuasan (1 – 5)</label>
            <div style="display: flex; gap: 16px; align-items: center; margin-top: 6px;">
              @for($s = 5; $s >= 1; $s--)
                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 14px; font-weight: 600; color: #0f172a;">
                  <input type="radio" name="rating" value="{{ $s }}" {{ $s == 5 ? 'checked' : '' }}>
                  <span style="color: #f59e0b; font-size: 16px;">{{ str_repeat('★', $s) }}</span> ({{ $s }})
                </label>
              @endfor
            </div>
          </div>

          <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label" style="font-weight: 600;">Ulasan / Testimoni Kondisi Barang</label>
            <textarea name="comment" class="form-input form-textarea" rows="3" placeholder="Contoh: Barang sangat bagus, baterai awet, kabel lengkap, sangat membantu presentasi..."></textarea>
          </div>

          <div style="text-align: right;">
            <button type="submit" class="btn btn-primary" style="background: #d97706; border-color: #d97706;">
              Kirim Ulasan &amp; Rating
            </button>
          </div>
        </form>
      @endif
    </div>
  </div>
@endif

@endsection
