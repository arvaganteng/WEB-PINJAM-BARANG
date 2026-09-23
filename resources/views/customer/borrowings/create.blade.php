@extends('layouts.customer')

@section('title', 'Form Pengajuan Peminjaman')

@section('content')
<div style="margin-bottom:16px">
  <a href="{{ route('customer.catalog.show', $item) }}" class="btn btn-ghost btn-sm" style="padding:6px 0">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <polyline points="15 18 9 12 15 6" />
    </svg>
    Kembali ke Detail Barang
  </a>
</div>

<div class="page-title" style="margin-bottom:20px">Formulir Pengajuan Peminjaman</div>

<div style="display:grid;grid-template-columns:1fr 360px;gap:24px">
  <div class="card">
    <div class="card-header">
      <div class="card-title">Data Pengajuan</div>
    </div>
    <form action="{{ route('customer.borrowings.store') }}" method="POST" enctype="multipart/form-data" class="card-body" style="display:flex;flex-direction:column;gap:16px">
      @csrf
      <input type="hidden" name="item_id" value="{{ $item->id }}" />

      <div class="form-group">
        <label class="form-label">Nama Peminjam</label>
        <input type="text" class="form-input" value="{{ Auth::user()->name }}" readonly style="background:var(--gray-50)" />
      </div>

      <div class="form-group">
        <label class="form-label">Barang yang Dipinjam</label>
        <input type="text" class="form-input" value="{{ $item->name }} ({{ $item->code }})" readonly style="background:var(--gray-50)" />
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Jumlah Unit <span class="required">*</span></label>
          <input type="number" name="quantity" class="form-input" value="1" min="1" max="{{ $item->stock }}" id="qty-input" oninput="updateEstimasi()" required />
        </div>
        <div class="form-group">
          <label class="form-label">Stok Tersedia Saat Ini</label>
          <input type="text" class="form-input" value="{{ $item->stock }} unit" readonly style="background:var(--gray-50)" />
        </div>
      </div>

      <!-- Live Stock Exceed Soft Alert Banner with Close Button -->
      <div id="stock-exceed-alert" style="display: none; margin-top: -6px; margin-bottom: 8px; padding: 10px 14px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; color: #991b1b; font-size: 12.5px; font-weight: 500; align-items: center; justify-content: space-between; gap: 10px; animation: softPageFadeInUp 0.3s ease;">
        <div style="display: flex; align-items: center; gap: 10px;">
          <div>
            <strong>Jumlah Melebihi Stok!</strong> Stok <strong>{{ $item->name }}</strong> yang tersedia saat ini hanya <strong>{{ $item->stock }} unit</strong>.
          </div>
        </div>
        <button type="button" onclick="closeStockAlert()" title="Tutup Notifikasi" style="background: none; border: none; color: #991b1b; font-size: 16px; font-weight: 700; cursor: pointer; padding: 2px 8px; border-radius: 6px; opacity: 0.7; transition: all 0.2s;" onmouseover="this.style.opacity='1'; this.style.background='rgba(153, 27, 27, 0.1)';" onmouseout="this.style.opacity='0.7'; this.style.background='none';">✕</button>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tanggal Mulai Pinjam <span class="required">*</span></label>
          <input type="date" name="borrow_date" class="form-input" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" id="tgl-pinjam" onchange="syncReturnDateLimits(); updateEstimasi();" oninput="updateEstimasi()" required />
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Pengembalian <span class="required">*</span></label>
          <input type="date" name="return_date" class="form-input" value="{{ date('Y-m-d', strtotime('+3 days')) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" max="{{ date('Y-m-d', strtotime('+10 days')) }}" id="tgl-kembali" oninput="updateEstimasi()" required />
          <span style="font-size:11px;color:var(--gray-500);margin-top:4px;display:block;">
            <em>Maksimal durasi peminjaman adalah 10 hari.</em>
          </span>
        </div>
      </div>

      <!-- Lokasi Penggunaan Barang (Live GPS Otomatis) -->
      <div class="form-group" id="location-section">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
          <label class="form-label" style="margin-bottom:0;">Lokasi Penggunaan Barang <span class="required">*</span></label>
          <span id="gps-status-badge" style="display:inline-flex;align-items:center;gap:5px;font-size:11.5px;font-weight:700;color:#0369a1;background:#e0f2fe;padding:3px 10px;border-radius:20px;">
            <span class="live-pulse" style="width:7px;height:7px;border-radius:50%;background:#0284c7;display:inline-block;"></span>
            <span>Mendeteksi GPS...</span>
          </span>
        </div>


        <!-- Live GPS Detection Card -->
        <!-- Live GPS Detection Card -->
        <div id="gps-card" style="border:1px solid #bae6fd;background:linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);border-radius:12px;padding:14px 16px;margin-bottom:10px;transition:all 0.3s ease;">
          <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div style="flex:1;min-width:240px;">
              <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;">
                <span id="gps-headline" style="font-size:13px;font-weight:700;color:#0369a1;">Mencari Titik Lokasi Real-Time...</span>
              </div>
              <div id="gps-desc" style="font-size:12px;color:#075985;line-height:1.5;">
                Browser sedang mendeteksi live koordinat lokasi perangkat Anda secara otomatis agar Anda tidak perlu mengetik.
              </div>

              <!-- Pill Info Koordinat & Alamat Terdeteksi -->
              <div id="gps-result-box" style="display:none;margin-top:10px;padding:10px 12px;background:#ffffff;border:1px solid #7dd3fc;border-radius:8px;">
                <div style="font-size:12px;font-weight:700;color:#0f172a;" id="gps-address-text">-</div>
                <div style="display:flex;align-items:center;gap:10px;margin-top:6px;font-size:11.5px;color:#64748b;flex-wrap:wrap;">
                  <span id="gps-coords-text">Lat: -, Long: -</span>
                  <a id="gps-gmaps-link" href="#" target="_blank" style="color:#0284c7;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                      <polyline points="15 3 21 3 21 9" />
                      <line x1="10" y1="14" x2="21" y2="3" />
                    </svg>
                    Lihat di Google Maps
                  </a>
                </div>
              </div>
            </div>

            <!-- Tombol Aksi GPS -->
            <div style="display:flex;flex-direction:column;gap:6px;align-items:flex-end;">
              <button type="button" onclick="detectLiveLocation(true)" class="btn btn-sm" style="background:#ffffff;border:1px solid #7dd3fc;color:#0369a1;font-size:11.5px;display:inline-flex;align-items:center;gap:5px;box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="23 4 23 10 17 10" />
                  <polyline points="1 20 1 14 7 14" />
                  <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
                </svg>
                Perbarui GPS
              </button>
              <button type="button" onclick="toggleManualLocation()" id="toggle-manual-btn" class="btn btn-ghost btn-sm" style="font-size:11.5px;color:#0369a1;padding:4px 6px;">
                Edit Alamat
              </button>
            </div>
          </div>
        </div>

        <!-- Input Tersembunyi Koordinat -->
        <input type="hidden" name="latitude" id="lat-input" value="{{ old('latitude') }}" />
        <input type="hidden" name="longitude" id="lng-input" value="{{ old('longitude') }}" />

        <!-- Interactive Leaflet OpenStreetMap Container for Precise Pin Positioning -->
        <div id="map-wrapper" style="margin-top:10px;margin-bottom:10px;border-radius:12px;overflow:hidden;border:1px solid #bae6fd;position:relative;box-shadow:0 2px 8px rgba(2, 132, 199, 0.08);">
          <div id="location-map" style="height:220px;width:100%;z-index:1;background:#f0f9ff;"></div>
          <div style="position:absolute;bottom:8px;left:10px;z-index:1000;background:rgba(255,255,255,0.92);backdrop-filter:blur(4px);padding:4px 10px;border-radius:8px;font-size:11.5px;color:#0369a1;font-weight:600;box-shadow:0 2px 8px rgba(0,0,0,0.12);border:1px solid #e0f2fe;">
            Geser pin merah / klik peta untuk atur titik presisi
          </div>
        </div>

        <!-- Input Text Alamat (Otomatis Terisi dari GPS & Sync Peta) -->
        <div id="manual-location-wrapper" style="margin-top:6px;">
          <div style="display:flex;gap:8px;align-items:center;">
            <input type="text" name="location" id="location-input" class="form-input @error('location') is-invalid @enderror" placeholder="Ketik nama jalan / gedung / alamat lokasi..." value="{{ old('location') }}" onkeydown="if(event.key==='Enter'){event.preventDefault();searchTypedLocation();}" required style="flex:1;"/>
            <button type="button" onclick="searchTypedLocation()" class="btn btn-sm" style="background:#0284c7;color:#fff;white-space:nowrap;display:inline-flex;align-items:center;gap:4px;padding:8px 14px;border-radius:8px;font-weight:600;cursor:pointer;">
              Cari di Peta
            </button>
          </div>
          <span style="font-size:11px;color:var(--gray-500);margin-top:4px;display:block;">
            <em>Ketik alamat lalu klik "Cari di Peta", atau geser pin merah pada peta secara manual.</em>
          </span>
        </div>

        @error('location')
        <span style="font-size:12px;color:var(--red);margin-top:4px;display:block;">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Keperluan Peminjaman <span class="required">*</span></label>
        <input type="text" name="purpose" class="form-input" placeholder="Contoh: Rapat kerja klien di kantor cabang" value="{{ old('purpose') }}" required />
      </div>

      <!-- Upload Foto KTP / KK Jaminan -->
      <div class="form-group">
        @if(!empty(Auth::user()->id_card_image))
        <label class="form-label">Dokumen Jaminan (Foto KTP / KK)</label>
        <div style="padding:12px 14px;background:#F0FDF4;border:1px solid #86EFAC;border-radius:10px;margin-bottom:8px;">
          <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
            <div style="display:flex;align-items:center;gap:8px;">
              <div>
                <div style="font-size:13px;font-weight:700;color:#166534;">Foto KTP/KK sudah tersimpan dari peminjaman sebelumnya</div>
                <div style="font-size:11.5px;color:#15803D;">Unggah dokumen baru hanya jika ingin mengganti foto identitas Anda.</div>
              </div>
            </div>
            <button type="button" onclick="previewKtpSoft('{{ asset('storage/' . Auth::user()->id_card_image) }}', 'Foto KTP/KK Tersimpan')" class="btn btn-ghost btn-sm" style="background:#fff;border:1px solid #86EFAC;color:#166534;font-size:12px;cursor:pointer;">
              Lihat KTP Tersimpan
            </button>
          </div>
        </div>
        <input type="file" name="id_card_image" class="form-input @error('id_card_image') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/webp" id="ktp-file-input" onchange="previewKtp(event)" />
        <span style="font-size:11px;color:var(--gray-500);margin-top:4px;display:block;">Opsional: Format JPG, PNG, WEBP. Maks 3 MB.</span>
        @else
        <label class="form-label">Upload Foto KTP / KK Asli (Dokumen Jaminan) <span class="required">*</span></label>
        <div style="padding:10px 14px;background:#EFF6FF;border:1px solid #BFDBFE;border-radius:8px;margin-bottom:8px;font-size:12px;color:#1E40AF;">
          <strong>Wajib untuk Peminjam Baru:</strong> Lampirkan foto KTP / KK asli sebagai jaminan identitas peminjaman aset inventaris.
        </div>
        <input type="file" name="id_card_image" class="form-input @error('id_card_image') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/webp" id="ktp-file-input" onchange="previewKtp(event)" required />
        <span style="font-size:11px;color:var(--gray-500);margin-top:4px;display:block;">Pastikan foto KTP/KK terlihat jelas &amp; tidak buram (Format JPG, PNG, WEBP. Maks 3 MB).</span>
        @endif

        @error('id_card_image')
        <span style="font-size:12px;color:var(--red);margin-top:4px;display:block;">{{ $message }}</span>
        @enderror

        <!-- Live Preview Container -->
        <div id="ktp-preview-container" style="display:none;margin-top:10px;padding:8px;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;width:fit-content;">
          <div style="font-size:11px;font-weight:700;color:var(--gray-600);margin-bottom:6px;">Pratinjau Foto KTP/KK yang Dipilih:</div>
          <img id="ktp-preview-img" src="" alt="Pratinjau KTP" style="max-width:280px;max-height:160px;border-radius:6px;border:1px solid #CBD5E1;object-fit:contain;display:block;" />
        </div>
      </div>

      <!-- OPSI METODE PEMBAYARAN BIAYA SEWA -->
      @if($item->price_per_day > 0)
      <div class="form-group" style="border-top:1px solid var(--gray-200); padding-top:16px;">
        <label class="form-label" style="font-size:14px; font-weight:700; color:var(--navy);">
          Metode Pembayaran Biaya Sewa
        </label>
        <div style="font-size:12px; color:var(--gray-600); margin-bottom:12px;">
          Pilih metode pembayaran untuk biaya sewa barang. Anda dapat mengunggah bukti transfer sekarang atau bayar saat barang disetujui.
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:10px; margin-bottom:14px;">
          <!-- Transfer Bank -->
          <div class="pay-card active" onclick="selectPayCard(this, 'Transfer Bank')" style="border:2px solid #2563eb; background:#eff6ff; border-radius:10px; padding:12px 10px; text-align:center; cursor:pointer; user-select:none;">
            <input type="radio" name="payment_method" value="Transfer Bank" checked style="display:none;" />
            <div style="font-size:12.5px; font-weight:700; color:#1e293b; pointer-events:none;">Transfer Bank</div>
            <div style="font-size:10.5px; color:#64748b; pointer-events:none;">BCA, Mandiri</div>
          </div>

          <!-- QRIS -->
          <div class="pay-card" onclick="selectPayCard(this, 'QRIS')" style="border:2px solid #e2e8f0; background:#fff; border-radius:10px; padding:12px 10px; text-align:center; cursor:pointer; user-select:none;">
            <input type="radio" name="payment_method" value="QRIS" style="display:none;" />
            <div style="font-size:12.5px; font-weight:700; color:#1e293b; pointer-events:none;">QRIS Instan</div>
            <div style="font-size:10.5px; color:#64748b; pointer-events:none;">GoPay, OVO, Dana</div>
          </div>

          <!-- Tunai -->
          <div class="pay-card" onclick="selectPayCard(this, 'Tunai')" style="border:2px solid #e2e8f0; background:#fff; border-radius:10px; padding:12px 10px; text-align:center; cursor:pointer; user-select:none;">
            <input type="radio" name="payment_method" value="Tunai" style="display:none;" />
            <div style="font-size:12.5px; font-weight:700; color:#1e293b; pointer-events:none;">Tunai / Cash (COD)</div>
            <div style="font-size:10.5px; color:#64748b; pointer-events:none;">Bayar di Gudang</div>
          </div>
        </div>

        <!-- Bank Account / QRIS Detail Box -->
        <div id="bank-info-box" style="padding:14px 16px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:12px; margin-bottom:12px;">
          <div id="transfer-details">
            <div style="font-size:12px; font-weight:700; color:#0f172a; margin-bottom:8px;">Rekening Resmi PT Nusantara Digital Express:</div>
            <div style="display:flex; flex-direction:column; gap:8px;">
              <div style="display:flex; justify-content:space-between; align-items:center; background:#fff; padding:8px 12px; border-radius:8px; border:1px solid #e2e8f0;">
                <div style="display:flex; align-items:center; gap:10px;">
                  <img src="{{ asset('images/payments/bca.svg') }}" alt="BCA" height="24" />
                  <div>
                    <span style="font-size:13px; font-weight:700; color:#0f172a;">8830-1928-11</span>
                    <div style="font-size:11px; color:#64748b;">a.n PT Nusantara Digital Express</div>
                  </div>
                </div>
                <button type="button" onclick="navigator.clipboard.writeText('8830192811'); alert('No. Rekening BCA berhasil disalin!');" class="btn btn-ghost btn-sm" style="font-size:11px; padding:4px 8px; color:#2563eb;">Salin</button>
              </div>

              <div style="display:flex; justify-content:space-between; align-items:center; background:#fff; padding:8px 12px; border-radius:8px; border:1px solid #e2e8f0;">
                <div style="display:flex; align-items:center; gap:10px;">
                  <img src="{{ asset('images/payments/mandiri.svg') }}" alt="Mandiri" height="24" />
                  <div>
                    <span style="font-size:13px; font-weight:700; color:#0f172a;">137-00-99281-22</span>
                    <div style="font-size:11px; color:#64748b;">a.n PT Nusantara Digital Express</div>
                  </div>
                </div>
                <button type="button" onclick="navigator.clipboard.writeText('137009928122'); alert('No. Rekening Mandiri berhasil disalin!');" class="btn btn-ghost btn-sm" style="font-size:11px; padding:4px 8px; color:#2563eb;">Salin</button>
              </div>
            </div>
          </div>

          <div id="qris-details" style="display:none; text-align:center;">
            <div style="font-size:12.5px; font-weight:700; color:#0f172a; margin-bottom:8px;">Scan QRIS PT Nusantara Digital Express:</div>
            <div style="display:inline-block; padding:8px; background:#fff; border-radius:12px; border:1px solid #cbd5e1; margin-bottom:8px; box-shadow:0 4px 12px rgba(0,0,0,0.08);">
              <img src="{{ asset('images/payments/qris-code.jpg') }}" alt="QRIS PT Nusantara Digital Express" style="max-width:240px; width:100%; height:auto; border-radius:8px; display:block;" />
            </div>
            <div style="display:flex; align-items:center; justify-content:center; gap:6px; flex-wrap:wrap; margin-top:4px;">
              <img src="{{ asset('images/payments/qris.svg') }}" alt="QRIS" height="18" />
              <img src="{{ asset('images/payments/dana.png') }}" alt="DANA" height="18" style="object-fit:contain;" />
              <img src="{{ asset('images/payments/gopay.png') }}" alt="GoPay" height="18" style="object-fit:contain;" />
              <img src="{{ asset('images/payments/ovo.jpg') }}" alt="OVO" height="18" style="object-fit:contain;" />
            </div>
          </div>

          <div id="tunai-details" style="display:none;">
            <div style="font-size:12.5px; color:#0f172a; line-height:1.5;">
              <strong>Pembayaran Tunai di Lokasi:</strong><br />
              Silakan serahkan uang tunai kepada kasir/petugas gudang saat pengambilan barang fisik.
            </div>
          </div>
        </div>

        <!-- Upload Bukti Transfer -->
        <div id="proof-upload-box">
          <label class="form-label">Upload Bukti Pembayaran / Struk (Opsional saat Pengajuan)</label>
          <input type="file" name="payment_proof" class="form-input" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewPayProof(event)" />
          <span style="font-size:11px; color:var(--gray-500); margin-top:4px; display:block;">Format JPG, PNG, WEBP. Maks 3 MB. (Bisa diunggah nanti setelah disetujui).</span>

          <div id="pay-proof-preview-container" style="display:none; margin-top:10px; padding:8px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; width:fit-content;">
            <div style="font-size:11px; font-weight:700; color:var(--gray-600); margin-bottom:6px;">Pratinjau Bukti Pembayaran:</div>
            <img id="pay-proof-preview-img" src="" alt="Pratinjau Bukti" style="max-width:240px; max-height:140px; border-radius:6px; border:1px solid #cbd5e1; object-fit:contain; display:block;" />
          </div>
        </div>
      </div>
      @else
      <input type="hidden" name="payment_method" value="Gratis" />
      @endif

      <div class="form-group">
        <label class="form-label">Catatan Tambahan (Opsional)</label>
        <textarea name="notes" class="form-input form-textarea" placeholder="Kelengkapan khusus yang dibutuhkan...">{{ old('notes') }}</textarea>
      </div>

      <div class="info-alert blue">
        Pengajuan peminjaman, dokumen KTP, dan pembayaran akan diverifikasi oleh tim inventaris sebelum disetujui.
      </div>

      <button type="submit" class="btn btn-primary btn-full btn-lg">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="22" y1="2" x2="11" y2="13" />
          <polygon points="22 2 15 22 11 13 2 9 22 2" />
        </svg>
        Kirim Pengajuan Peminjaman
      </button>
    </form>
  </div>

  <div class="card" style="height:fit-content">
    <div class="card-header">
      <div class="card-title">Ringkasan Estimasi Biaya</div>
    </div>
    <div class="card-body">
      <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--gray-50);border-radius:var(--radius);margin-bottom:12px">
        <div>
          <div style="font-size:13.5px;font-weight:700">{{ $item->name }}</div>
          <div style="font-size:12px;color:var(--gray-500)">
            @if($item->price_per_day > 0)
            Rp {{ number_format($item->price_per_day, 0, ',', '.') }}/hari
            @else
            Gratis
            @endif
          </div>
        </div>
      </div>

      <!-- Lokasi Asal Penyimpanan Barang -->
      <div style="padding:10px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:16px;font-size:12px;">
        <div style="color:#64748b;font-weight:600;margin-bottom:2px;">Lokasi Asal Barang (Gudang/Ruangan):</div>
        <div style="font-weight:700;color:#0284c7;display:flex;align-items:center;gap:6px;">
          <span>{{ $item->storage_location ?? 'Gudang Utama Lt. 1' }}</span>
        </div>
      </div>

      <div id="estimasi-detail" style="display:flex;flex-direction:column;gap:8px;border-top:1px solid var(--gray-200);padding-top:12px">
        <div style="display:flex;justify-content:space-between;font-size:13px"><span style="color:var(--gray-500)">Jumlah</span><span id="est-qty">1 unit</span></div>
        <div style="display:flex;justify-content:space-between;font-size:13px"><span style="color:var(--gray-500)">Durasi</span><span id="est-days">3 hari</span></div>
        <div style="display:flex;justify-content:space-between;font-size:13px"><span style="color:var(--gray-500)">Harga/hari</span><span>Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</span></div>
        <div style="border-top:1px solid var(--gray-200);margin:4px 0"></div>
        <div style="display:flex;justify-content:space-between;font-size:15px;font-weight:800">
          <span>Total Estimasi</span>
          <span style="color:var(--navy)" id="est-total">Rp {{ number_format($item->price_per_day * 3, 0, ',', '.') }}</span>
        </div>
      </div>
      <div class="info-alert green" style="margin-top:12px">
        ✓ Pembayaran baru dilakukan setelah pengajuan Anda disetujui admin.
      </div>
    </div>
  </div>
</div>

@push('styles')
<!-- Leaflet OpenStreetMap CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
  @keyframes pulseGlow {
    0% {
      transform: scale(0.95);
      box-shadow: 0 0 0 0 rgba(2, 132, 199, 0.7);
    }

    70% {
      transform: scale(1);
      box-shadow: 0 0 0 6px rgba(2, 132, 199, 0);
    }

    100% {
      transform: scale(0.95);
      box-shadow: 0 0 0 0 rgba(2, 132, 199, 0);
    }
  }

  .live-pulse {
    animation: pulseGlow 1.8s infinite;
  }

  @keyframes pulseGreen {
    0% {
      transform: scale(0.95);
      box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7);
    }

    70% {
      transform: scale(1);
      box-shadow: 0 0 0 6px rgba(22, 163, 74, 0);
    }

    100% {
      transform: scale(0.95);
      box-shadow: 0 0 0 0 rgba(22, 163, 74, 0);
    }
  }

  .live-pulse-green {
    animation: pulseGreen 1.8s infinite;
  }
</style>
@endpush

@push('scripts')
<!-- Leaflet OpenStreetMap JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  const pricePerDay = {{ $item->price_per_day ?? 0 }};
  const maxStock = {{ $item->stock ?? 0 }};
  let isStockAlertDismissed = false;

  let leafletMap = null;
  let leafletMarker = null;

  function initLocationMap(initialLat = -6.2088, initialLng = 106.8456) {
    if (leafletMap) return;
    const mapContainer = document.getElementById('location-map');
    if (!mapContainer || typeof L === 'undefined') return;

    leafletMap = L.map('location-map', {
      zoomControl: true
    }).setView([initialLat, initialLng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(leafletMap);

    const customIcon = L.icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41]
    });

    leafletMarker = L.marker([initialLat, initialLng], {
      draggable: true,
      icon: customIcon
    }).addTo(leafletMap);

    // Event saat pin diseret (dragged)
    leafletMarker.on('dragend', function() {
      const pos = leafletMarker.getLatLng();
      syncCoordinatesAndAddress(pos.lat, pos.lng, true);
    });

    // Event saat peta diklik
    leafletMap.on('click', function(e) {
      leafletMarker.setLatLng(e.latlng);
      syncCoordinatesAndAddress(e.latlng.lat, e.latlng.lng, true);
    });
  }

  function updateMapPosition(lat, lng, zoom = 15) {
    if (!leafletMap) {
      initLocationMap(lat, lng);
    } else {
      leafletMap.setView([lat, lng], zoom);
      if (leafletMarker) {
        leafletMarker.setLatLng([lat, lng]);
      }
    }
  }

  function syncCoordinatesAndAddress(lat, lng, fetchAddress = true) {
    const latInput = document.getElementById('lat-input');
    const lngInput = document.getElementById('lng-input');
    const coordsText = document.getElementById('gps-coords-text');
    const gmapsLink = document.getElementById('gps-gmaps-link');
    const resultBox = document.getElementById('gps-result-box');
    const addressText = document.getElementById('gps-address-text');
    const locationInput = document.getElementById('location-input');

    latInput.value = lat;
    lngInput.value = lng;
    coordsText.textContent = `Lat: ${parseFloat(lat).toFixed(5)}, Long: ${parseFloat(lng).toFixed(5)}`;
    gmapsLink.href = `https://www.google.com/maps?q=${lat},${lng}`;
    resultBox.style.display = 'block';

    if (fetchAddress) {
      addressText.textContent = 'Memuat nama jalan / lokasi presisi...';
      fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
        .then(res => res.json())
        .then(data => {
          let readable = '';
          if (data && data.address) {
            const a = data.address;
            const parts = [
              a.amenity || a.building || a.office || a.shop || '',
              a.road || '',
              a.suburb || a.neighbourhood || a.village || '',
              a.city || a.town || a.county || '',
              a.state || ''
            ].filter(Boolean);
            readable = parts.join(', ');
          }
          if (!readable && data && data.display_name) {
            readable = data.display_name.split(',').slice(0, 4).join(',').trim();
          }
          if (!readable) {
            readable = `Koordinat GPS (${parseFloat(lat).toFixed(5)}, ${parseFloat(lng).toFixed(5)})`;
          }

          addressText.textContent = readable;
          locationInput.value = readable;
        })
        .catch(() => {
          const fallback = `Koordinat GPS (${parseFloat(lat).toFixed(5)}, ${parseFloat(lng).toFixed(5)})`;
          addressText.textContent = fallback;
          locationInput.value = fallback;
        });
    }
  }

  function updateEstimasi() {
    try {
      const qtyInput = document.getElementById('qty-input');
      const stockAlert = document.getElementById('stock-exceed-alert');
      let qty = parseInt(qtyInput.value) || 1;

      // Validation check if requested quantity exceeds available stock
      if (qty > maxStock) {
        if (stockAlert && !isStockAlertDismissed) {
          stockAlert.style.display = 'flex';
        }
        qtyInput.style.borderColor = '#ef4444';
        qtyInput.style.background = '#fef2f2';

        // Trigger Soft SweetAlert popup notification only if user has not dismissed it
        if (typeof Swal !== 'undefined' && !isStockAlertDismissed && !qtyInput.dataset.swalShown) {
          qtyInput.dataset.swalShown = 'true';
          Swal.fire({
            icon: 'warning',
            title: 'Jumlah Melebihi Stok',
            html: `
              <div style="font-size: 13.5px; color: #475569; line-height: 1.6; margin-top: 6px;">
                Jumlah unit yang Anda minta (<strong>${qty} unit</strong>) melebihi stok yang tersedia saat ini (<strong>${maxStock} unit</strong>).<br>
                <span style="font-size: 12px; color: #64748b; display: block; margin-top: 6px;">Klik <strong>"Mengerti, Sesuaikan"</strong> untuk menyesuaikan otomatis ke <strong>${maxStock} unit</strong>, atau <strong>"Tutup"</strong> untuk mengabaikan.</span>
              </div>
            `,
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: 'Mengerti, Sesuaikan',
            cancelButtonText: 'Tutup',
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#94a3b8',
            customClass: {
              popup: 'swal-soft-popup'
            }
          }).then((result) => {
            if (result.isConfirmed) {
              qtyInput.value = maxStock;
              isStockAlertDismissed = false;
              if (stockAlert) stockAlert.style.display = 'none';
              updateEstimasi();
            } else {
              // User clicked 'Tutup' or close button
              closeStockAlert();
            }
            qtyInput.dataset.swalShown = '';
          });
        }
      } else {
        isStockAlertDismissed = false;
        if (stockAlert) {
          stockAlert.style.display = 'none';
        }
        qtyInput.style.borderColor = '';
        qtyInput.style.background = '';
        qtyInput.dataset.swalShown = '';
      }

      const tglPinjamInput = document.getElementById('tgl-pinjam');
      const tglKembaliInput = document.getElementById('tgl-kembali');

      const tglPinjam = new Date(tglPinjamInput.value);
      let tglKembali = new Date(tglKembaliInput.value);
      let diffTime = tglKembali - tglPinjam;
      let days = Math.max(1, Math.round(diffTime / (1000 * 60 * 60 * 24)));

      if (days > 10) {
        // Set to max 10 days
        const maxDate = new Date(tglPinjam);
        maxDate.setDate(maxDate.getDate() + 10);
        tglKembaliInput.value = maxDate.toISOString().split('T')[0];
        days = 10;
        alert('⚠️ Maksimal Durasi Peminjaman adalah 10 Hari. Tanggal pengembalian disesuaikan otomatis menjadi 10 hari.');
      }

      const total = qty * days * pricePerDay;

      document.getElementById('est-qty').textContent = qty + ' unit';
      document.getElementById('est-days').textContent = days + ' hari';
      document.getElementById('est-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    } catch (e) {}
  }

  function syncReturnDateLimits() {
    const pInput = document.getElementById('tgl-pinjam');
    const kInput = document.getElementById('tgl-kembali');
    if (!pInput || !kInput || !pInput.value) return;

    const pDate = new Date(pInput.value);
    const minDate = new Date(pDate);
    minDate.setDate(minDate.getDate() + 1);

    const maxDate = new Date(pDate);
    maxDate.setDate(maxDate.getDate() + 10);

    kInput.min = minDate.toISOString().split('T')[0];
    kInput.max = maxDate.toISOString().split('T')[0];

    const currentK = new Date(kInput.value);
    if (currentK > maxDate || currentK < minDate) {
      const defaultK = new Date(pDate);
      defaultK.setDate(defaultK.getDate() + 3);
      kInput.value = defaultK.toISOString().split('T')[0];
    }
  }

  function closeStockAlert() {
    isStockAlertDismissed = true;
    const alertBox = document.getElementById('stock-exceed-alert');
    if (alertBox) {
      alertBox.style.display = 'none';
    }
  }

  function previewKtp(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('ktp-preview-container');
    const previewImg = document.getElementById('ktp-preview-img');

    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        previewImg.src = e.target.result;
        previewContainer.style.display = 'block';
      };
      reader.readAsDataURL(file);
    } else {
      previewContainer.style.display = 'none';
      previewImg.src = '';
    }
  }

  function selectPayCard(cardEl, val) {
    const form = cardEl.closest('form');
    if (form) {
      const radios = form.querySelectorAll('input[name="payment_method"]');
      radios.forEach(r => r.checked = (r.value === val));
    }

    const cards = document.querySelectorAll('.pay-card');
    cards.forEach(c => {
      c.style.border = '2px solid #e2e8f0';
      c.style.background = '#ffffff';
    });
    cardEl.style.border = '2px solid #2563eb';
    cardEl.style.background = '#eff6ff';

    const tDetails = document.getElementById('transfer-details');
    const qDetails = document.getElementById('qris-details');
    const tuDetails = document.getElementById('tunai-details');

    if (tDetails) tDetails.style.display = (val === 'Transfer Bank') ? 'block' : 'none';
    if (qDetails) qDetails.style.display = (val === 'QRIS') ? 'block' : 'none';
    if (tuDetails) tuDetails.style.display = (val === 'Tunai') ? 'block' : 'none';
  }

  // --- Real-Time Live Geolocation Detection ---
  function detectLiveLocation(force = false) {
    const badge = document.getElementById('gps-status-badge');
    const card = document.getElementById('gps-card');
    const headline = document.getElementById('gps-headline');
    const desc = document.getElementById('gps-desc');
    const locationInput = document.getElementById('location-input');

    badge.innerHTML = '<span class="live-pulse" style="width:7px;height:7px;border-radius:50%;background:#0284c7;display:inline-block;"></span> <span>Mendeteksi GPS...</span>';
    badge.style.background = '#e0f2fe';
    badge.style.color = '#0369a1';
    card.style.background = 'linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%)';
    card.style.borderColor = '#bae6fd';
    headline.textContent = 'Mencari Titik Lokasi Real-Time...';
    headline.style.color = '#0369a1';
    desc.textContent = 'Browser sedang mendeteksi live koordinat lokasi perangkat Anda...';

    if (!navigator.geolocation) {
      showGpsError('Perangkat atau browser Anda tidak mendukung fitur Geolocation.');
      initLocationMap(-6.2088, 106.8456);
      return;
    }

    navigator.geolocation.getCurrentPosition(
      function(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;

        // Update badge & card tampilan sukses
        badge.innerHTML = '<span class="live-pulse-green" style="width:7px;height:7px;border-radius:50%;background:#16a34a;display:inline-block;"></span> <span>GPS Aktif</span>';
        badge.style.background = '#dcfce7';
        badge.style.color = '#15803d';

        card.style.background = 'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)';
        card.style.borderColor = '#86efac';
        headline.textContent = 'Live Lokasi Terdeteksi & Tersinkronisasi';
        headline.style.color = '#166534';
        desc.textContent = 'Titik koordinat terhubung ke peta. Geser pin merah pada peta jika ingin menyesuaikan titik presisi.';

        // Perbarui lokasi peta & koordinat
        updateMapPosition(lat, lng, 16);
        syncCoordinatesAndAddress(lat, lng, force || !locationInput.value);
      },
      function(error) {
        let msg = 'Gagal mengakses GPS perangkat.';
        if (error.code === error.PERMISSION_DENIED) {
          msg = 'Izin lokasi browser ditolak. Silakan geser pin pada peta atau ketik alamat manual.';
        } else if (error.code === error.POSITION_UNAVAILABLE) {
          msg = 'Sinyal lokasi tidak tersedia saat ini.';
        } else if (error.code === error.TIMEOUT) {
          msg = 'Waktu pencarian GPS habis. Silakan coba lagi.';
        }
        showGpsError(msg);
        initLocationMap(-6.2088, 106.8456);
      }, {
        enableHighAccuracy: true,
        timeout: 8000,
        maximumAge: 0
      }
    );
  }

  function showGpsError(message) {
    const badge = document.getElementById('gps-status-badge');
    const card = document.getElementById('gps-card');
    const headline = document.getElementById('gps-headline');
    const desc = document.getElementById('gps-desc');

    badge.innerHTML = '<span>GPS Nonaktif</span>';
    badge.style.background = '#fef3c7';
    badge.style.color = '#92400e';

    card.style.background = '#fffbeb';
    card.style.borderColor = '#fde68a';
    headline.textContent = 'Izin Lokasi Belum Aktif';
    headline.style.color = '#92400e';
    desc.textContent = message + ' Anda dapat menggeser pin pada peta atau mengetik alamat di bawah.';
  }

  function toggleManualLocation() {
    const input = document.getElementById('location-input');
    if (input) {
      input.focus();
    }
  }

  function geocodeAddressManual() {
    const locationInput = document.getElementById('location-input');
    const query = locationInput ? locationInput.value.trim() : '';

    if (!query) {
      alert('Silakan ketik nama jalan, kelurahan, atau kota terlebih dahulu.');
      return;
    }

    const addressText = document.getElementById('gps-address-text');
    const resultBox = document.getElementById('gps-result-box');
    if (addressText) addressText.textContent = 'Mencari lokasi pada peta...';
    if (resultBox) resultBox.style.display = 'block';

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Indonesia')}&limit=1`)
      .then(res => res.json())
      .then(data => {
        if (data && data.length > 0) {
          const lat = parseFloat(data[0].lat);
          const lon = parseFloat(data[0].lon);
          updateMapPosition(lat, lon, 16);
          document.getElementById('lat-input').value = lat;
          document.getElementById('lng-input').value = lon;
          document.getElementById('gps-coords-text').textContent = `Lat: ${lat.toFixed(5)}, Long: ${lon.toFixed(5)}`;
          document.getElementById('gps-gmaps-link').href = `https://www.google.com/maps?q=${lat},${lon}`;
          if (addressText) addressText.textContent = (data[0].display_name.split(',').slice(0, 4).join(',').trim());
        } else {
          alert('Lokasi tidak dapat ditemukan otomatis. Silakan geser pin merah pada peta secara manual untuk menentukan titik koordinat presisi.');
        }
      })
      .catch(() => {
        alert('Gagal mencari lokasi. Anda tetap bisa menggeser pin merah pada peta secara manual.');
      });
  }

  // Auto-search location on typing with debouncing
  let geocodeTimeout = null;
  document.addEventListener('DOMContentLoaded', function() {
    updateEstimasi();
    detectLiveLocation();
    loadProvinsi();

    const locInput = document.getElementById('location-input');
    if (locInput) {
      locInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        if (query.length < 3) return;

        clearTimeout(geocodeTimeout);
        geocodeTimeout = setTimeout(() => {
          fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
            .then(res => res.json())
            .then(data => {
              if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                updateMapPosition(lat, lon, 16);
                document.getElementById('lat-input').value = lat;
                document.getElementById('lng-input').value = lon;
                document.getElementById('gps-coords-text').textContent = `Lat: ${lat.toFixed(5)}, Long: ${lon.toFixed(5)}`;
                document.getElementById('gps-gmaps-link').href = `https://www.google.com/maps?q=${lat},${lon}`;
                document.getElementById('gps-result-box').style.display = 'block';
                document.getElementById('gps-address-text').textContent = (data[0].display_name.split(',').slice(0, 4).join(',').trim());
              }
            }).catch(() => {});
        }, 700);
      });
    }
  });

  // ===== WILAYAH INDONESIA (API emsifa) =====
  const WILAYAH_BASE = 'https://www.emsifa.com/api-wilayah-indonesia/api';
  let _wilayahCache = {};

  async function wilayahFetch(url) {
    if (_wilayahCache[url]) return _wilayahCache[url];
    const res = await fetch(url);
    const data = await res.json();
    _wilayahCache[url] = data;
    return data;
  }

  function setSelectLoading(sel, text) {
    sel.innerHTML = `<option>${text}</option>`;
    sel.disabled = true;
  }

  async function loadProvinsi() {
    const sel = document.getElementById('sel-provinsi');
    setSelectLoading(sel, 'Memuat provinsi...');
    try {
      const data = await wilayahFetch(`${WILAYAH_BASE}/provinces.json`);
      sel.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
      data.forEach(p => { sel.innerHTML += `<option value="${p.id}">${p.name}</option>`; });
      sel.disabled = false;
    } catch(e) {
      sel.innerHTML = '<option value="">Gagal memuat provinsi</option>';
      sel.disabled = false;
    }
  }

  async function loadKota() {
    const provId = document.getElementById('sel-provinsi').value;
    const selKota = document.getElementById('sel-kota');
    const selKec  = document.getElementById('sel-kecamatan');
    selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    selKec.disabled = true;
    document.getElementById('wilayah-result').style.display = 'none';
    if (!provId) { selKota.innerHTML = '<option value="">-- Pilih Kota --</option>'; selKota.disabled = true; return; }
    setSelectLoading(selKota, 'Memuat kota...');
    try {
      const data = await wilayahFetch(`${WILAYAH_BASE}/regencies/${provId}.json`);
      selKota.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
      data.forEach(k => { selKota.innerHTML += `<option value="${k.id}">${k.name}</option>`; });
      selKota.disabled = false;
    } catch(e) {
      selKota.innerHTML = '<option value="">Gagal memuat kota</option>';
      selKota.disabled = false;
    }
  }

  async function loadKecamatan() {
    const kotaId = document.getElementById('sel-kota').value;
    const selKec  = document.getElementById('sel-kecamatan');
    document.getElementById('wilayah-result').style.display = 'none';
    if (!kotaId) { selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>'; selKec.disabled = true; return; }
    setSelectLoading(selKec, 'Memuat kecamatan...');
    try {
      const data = await wilayahFetch(`${WILAYAH_BASE}/districts/${kotaId}.json`);
      selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
      data.forEach(k => { selKec.innerHTML += `<option value="${k.id}">${k.name}</option>`; });
      selKec.disabled = false;
    } catch(e) {
      selKec.innerHTML = '<option value="">Gagal memuat kecamatan</option>';
      selKec.disabled = false;
    }
  }

  function syncWilayahToLocation() {
    const provSel  = document.getElementById('sel-provinsi');
    const kotaSel  = document.getElementById('sel-kota');
    const kecSel   = document.getElementById('sel-kecamatan');
    const locInput = document.getElementById('location-input');

    const provName = provSel.options[provSel.selectedIndex]?.text || '';
    const kotaName = kotaSel.options[kotaSel.selectedIndex]?.text || '';
    const kecName  = kecSel.options[kecSel.selectedIndex]?.text  || '';
    if (!kecName || kecName.startsWith('--')) return;

    const wilayahStr = `${kecName}, ${kotaName}, ${provName}`;
    document.getElementById('wilayah-result-text').textContent = wilayahStr;
    document.getElementById('wilayah-result').style.display = 'block';

    const previousWilayah = locInput.dataset.lastWilayah || '';
    const currentVal = locInput.value.trim();
    if (!currentVal || currentVal === previousWilayah) {
      locInput.value = wilayahStr;
    } else {
      let stripped = currentVal.replace(previousWilayah, '').replace(/,\s*$/, '').trim();
      locInput.value = stripped ? `${stripped}, ${wilayahStr}` : wilayahStr;
    }
    locInput.dataset.lastWilayah = wilayahStr;

    // Pan map to selected region
    clearTimeout(geocodeTimeout);
    geocodeTimeout = setTimeout(() => {
      fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(wilayahStr + ', Indonesia')}&limit=1`)
        .then(r => r.json())
        .then(d => {
          if (d && d.length > 0) {
            const lat = parseFloat(d[0].lat), lon = parseFloat(d[0].lon);
            if (typeof updateMapPosition === 'function') updateMapPosition(lat, lon, 12);
            document.getElementById('lat-input').value = lat;
            document.getElementById('lng-input').value = lon;
          }
        }).catch(() => {});
    }, 300);
  }
</script>
@endpush
@endsection