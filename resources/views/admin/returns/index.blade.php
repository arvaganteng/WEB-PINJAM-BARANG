@extends('layouts.admin')

@section('title', 'Pengembalian Barang')

@push('styles')
<style>
  .return-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    border-bottom: 2px solid var(--gray-200);
    padding-bottom: 0;
  }
  
  .return-tab {
    padding: 12px 20px;
    background: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    color: var(--gray-600);
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    bottom: -2px;
  }
  
  .return-tab:hover {
    color: var(--navy);
    background: var(--gray-50);
  }
  
  .return-tab.active {
    color: var(--navy);
    border-bottom-color: var(--navy);
  }
  
  .return-tab-content {
    display: none;
  }
  
  .return-tab-content.active {
    display: block;
  }
  
  .condition-badge {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
  }
  
  .condition-baik {
    background: var(--green-pale);
    color: var(--green);
  }
  
  .condition-kurang {
    background: var(--yellow-pale);
    color: var(--yellow);
  }
  
  .condition-rusak {
    background: var(--red-pale);
    color: var(--red);
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Pengembalian Barang</div>
    <div class="page-subtitle">Verifikasi dan pemeriksaan kondisi barang yang dikembalikan customer</div>
  </div>
</div>

<!-- Tabs -->
<div class="return-tabs">
  <button class="return-tab active" onclick="switchTab('pending', this)">
    ⏳ Menunggu Verifikasi ({{ $pendingReturns->count() }})
  </button>
  <button class="return-tab" onclick="switchTab('verified', this)">
    ✅ Sudah Diverifikasi
  </button>
</div>


<!-- Tab Content: Pending -->
<div id="pending-tab" class="return-tab-content active">
  @if($pendingReturns->count() > 0)
    <div style="display: grid; gap: 20px;">
      @foreach($pendingReturns as $return)
        <div class="card">
          <div class="card-header" style="background: var(--yellow-pale); border-bottom-color: var(--yellow);">
            <div>
              <div class="card-title" style="color: var(--navy);">
                🔍 {{ $return->return_code }} - Perlu Verifikasi
              </div>
              <div class="card-subtitle">
                Diajukan: {{ $return->return_date->format('d F Y, H:i') }} WIB
              </div>
            </div>
            <span class="badge badge-warning">Menunggu Pemeriksaan</span>
          </div>
          
          <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
              <!-- Info Customer & Barang -->
              <div>
                <h4 style="font-size: 13px; font-weight: 700; color: var(--gray-500); margin-bottom: 12px; text-transform: uppercase;">Detail Peminjaman</h4>
                <table style="width: 100%; font-size: 14px;">
                  <tr>
                    <td style="padding: 6px 0; color: var(--gray-600); width: 140px;">Kode Pinjam</td>
                    <td style="padding: 6px 0; font-weight: 600; color: var(--navy);">{{ $return->borrowing->borrow_code }}</td>
                  </tr>
                  <tr>
                    <td style="padding: 6px 0; color: var(--gray-600);">Customer</td>
                    <td style="padding: 6px 0; font-weight: 600;">{{ $return->borrowing->user->name }}</td>
                  </tr>
                  <tr>
                    <td style="padding: 6px 0; color: var(--gray-600);">Barang</td>
                    <td style="padding: 6px 0; font-weight: 600;">{{ $return->borrowing->item->name }}</td>
                  </tr>
                  <tr>
                    <td style="padding: 6px 0; color: var(--gray-600);">Jumlah</td>
                    <td style="padding: 6px 0; font-weight: 600;">{{ $return->borrowing->quantity }} unit</td>
                  </tr>
                  <tr>
                    <td style="padding: 6px 0; color: var(--gray-600);">Tgl Pinjam</td>
                    <td style="padding: 6px 0;">{{ $return->borrowing->borrow_date->format('d M Y') }}</td>
                  </tr>
                  <tr>
                    <td style="padding: 6px 0; color: var(--gray-600);">Jatuh Tempo</td>
                    <td style="padding: 6px 0;">{{ $return->borrowing->return_date->format('d M Y') }}</td>
                  </tr>
                </table>
              </div>
              
              <!-- Catatan Customer & Foto Bukti Fisik -->
              <div>
                <h4 style="font-size: 13px; font-weight: 700; color: var(--gray-500); margin-bottom: 12px; text-transform: uppercase;">Catatan &amp; Foto Bukti Customer</h4>
                <div style="background: var(--gray-50); padding: 14px; border-radius: 8px; border-left: 3px solid var(--blue); margin-bottom: 12px;">
                  <p style="font-size: 13.5px; color: var(--gray-700); line-height: 1.5; margin: 0;">
                    {{ $return->customer_notes ?? 'Tidak ada catatan dari customer' }}
                  </p>
                </div>

                @if($return->return_photo)
                  <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px; display: flex; align-items: center; gap: 12px;">
                    <img src="{{ asset('storage/' . $return->return_photo) }}" alt="Foto Pengembalian" style="width: 72px; height: 72px; border-radius: 6px; object-fit: cover; border: 1px solid #cbd5e1; cursor: pointer;" onclick="previewImageSoft('{{ asset('storage/' . $return->return_photo) }}', '📸 Foto Bukti Fisik Barang')" title="Klik untuk perbesar foto"/>
                    <div>
                      <div style="font-size: 12px; font-weight: 700; color: #0f172a;">📸 Foto Bukti Fisik Barang</div>
                      <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Diunggah oleh customer saat pengembalian</div>
                      <button type="button" onclick="previewImageSoft('{{ asset('storage/' . $return->return_photo) }}', '📸 Foto Bukti Fisik Barang')" style="font-size: 11.5px; color: #2563eb; font-weight: 600; background: none; border: none; padding: 0; cursor: pointer; display: inline-block; margin-top: 4px;">Lihat Foto Ukuran Penuh 🔍</button>
                    </div>
                  </div>
                @else
                  <div style="font-size: 12px; color: #94a3b8; font-style: italic;">
                    (Customer tidak melampirkan foto fisik saat pengembalian)
                  </div>
                @endif
              </div>
            </div>
            
            <!-- Form Verifikasi -->
            <div style="background: var(--blue-pale); padding: 20px; border-radius: 10px; border: 1px solid #bfdbfe;">
              <h4 style="font-size: 14px; font-weight: 700; color: var(--navy); margin-bottom: 16px;">
                📝 Form Verifikasi Pengembalian
              </h4>
              
              <form action="{{ route('admin.returns.store') }}" method="POST">
                @csrf
                <input type="hidden" name="return_id" value="{{ $return->id }}"/>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                  <div class="form-group">
                    <label class="form-label">Kondisi Barang <span class="required">*</span></label>
                    <select name="item_condition" class="form-input form-select" required>
                      <option value="">-- Pilih Kondisi --</option>
                      <option value="Baik">✅ Baik (Tidak ada kerusakan)</option>
                      <option value="Kurang Baik">⚠️ Kurang Baik (Ada sedikit kerusakan)</option>
                      <option value="Rusak Ringan">🔧 Rusak Ringan (Perlu perbaikan kecil)</option>
                      <option value="Rusak Berat">❌ Rusak Berat (Tidak bisa digunakan)</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label class="form-label">Denda (Opsional)</label>
                    <input type="number" name="fine_amount" class="form-input" value="0" min="0" placeholder="0"/>
                    <small style="color: var(--gray-500); font-size: 12px;">Kosongkan jika tidak ada denda</small>
                  </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 16px;">
                  <label class="form-label">Catatan Pemeriksaan Admin</label>
                  <textarea name="admin_notes" class="form-input form-textarea" rows="3" placeholder="Hasil pemeriksaan fisik barang, kondisi detail, dll..."></textarea>
                </div>
                
                <div style="display: flex; gap: 12px;">
                  <button type="submit" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Verifikasi & Selesaikan
                  </button>
                  <a href="{{ route('admin.returns.index') }}" class="btn btn-ghost">Lewati</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="card">
      <div class="card-body" style="text-align: center; padding: 60px 24px;">
        <div style="font-size: 64px; margin-bottom: 16px; opacity: 0.3;">✅</div>
        <h3 style="font-size: 18px; font-weight: 700; color: var(--gray-900); margin-bottom: 8px;">
          Tidak Ada Pengembalian Menunggu
        </h3>
        <p style="color: var(--gray-500); font-size: 14px;">
          Semua pengembalian sudah diverifikasi atau belum ada pengajuan pengembalian baru
        </p>
      </div>
    </div>
  @endif
</div>

<!-- Tab Content: Verified -->
<div id="verified-tab" class="return-tab-content">
  <div class="card">
    <div class="card-header">
      <div class="card-title">📋 Riwayat Pengembalian yang Sudah Diverifikasi</div>
    </div>
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Kode Return</th>
            <th>Kode Pinjam</th>
            <th>Customer</th>
            <th>Barang</th>
            <th>Tgl Kembali</th>
            <th>Kondisi</th>
            <th>Denda &amp; Pembayaran</th>
            <th>Diverifikasi</th>
            <th>Aksi Pelunasan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($verifiedReturns as $r)
            <tr>
              <td><span style="font-family: monospace; font-weight: 600; color: var(--navy);">{{ $r->return_code }}</span></td>
              <td><span style="font-family: monospace; color: var(--blue);">{{ $r->borrowing->borrow_code }}</span></td>
              <td>{{ $r->borrowing->user->name }}</td>
              <td>{{ $r->borrowing->item->name }}</td>
              <td>{{ $r->return_date->format('d M Y') }}</td>
              <td>
                @if($r->item_condition == 'Baik')
                  <span class="condition-badge condition-baik">✅ Baik</span>
                @elseif($r->item_condition == 'Kurang Baik')
                  <span class="condition-badge condition-kurang">⚠️ Kurang Baik</span>
                @else
                  <span class="condition-badge condition-rusak">❌ {{ $r->item_condition }}</span>
                @endif
              </td>
              <td>
                @if($r->fine_amount > 0)
                  <div style="font-weight: 700; color: var(--red);">
                    Rp {{ number_format($r->fine_amount, 0, ',', '.') }}
                  </div>
                  <div style="margin-top: 4px;">
                    <span class="badge" style="font-size: 11px; background: {{ $r->fine_payment_status == 'Lunas' ? '#dcfce7' : ($r->fine_payment_status == 'Menunggu Verifikasi' ? '#fef3c7' : '#fee2e2') }}; color: {{ $r->fine_payment_status == 'Lunas' ? '#15803d' : ($r->fine_payment_status == 'Menunggu Verifikasi' ? '#92400e' : '#b91c1c') }};">
                      {{ $r->fine_payment_status == 'Lunas' ? '✓ Lunas (' . ($r->fine_payment_method ?? 'Cash') . ')' : ($r->fine_payment_status == 'Menunggu Verifikasi' ? '⏳ ' . $r->fine_payment_method : '⚠️ Belum Bayar') }}
                    </span>
                  </div>
                  @if($r->fine_payment_proof)
                    <button type="button" onclick="previewImageSoft('{{ asset('storage/' . $r->fine_payment_proof) }}', '🖼️ Bukti Transfer Denda')" class="btn btn-ghost btn-sm" style="font-size: 11px; padding: 2px 6px; color: #2563eb; margin-top: 4px; border: 1px solid #bfdbfe;">
                      🔍 Lihat Struk
                    </button>
                  @endif
                @else
                  <span style="color: var(--gray-400);">-</span>
                @endif
              </td>
              <td style="font-size: 13px;">
                <div style="font-weight: 600;">{{ $r->verifiedBy->name ?? 'Admin' }}</div>
                <div style="color: var(--gray-500); font-size: 12px;">{{ $r->verified_at->format('d M Y, H:i') }}</div>
              </td>
              <td>
                @if($r->fine_amount > 0 && $r->fine_payment_status !== 'Lunas')
                  <form action="{{ route('admin.returns.confirm-fine', $r) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm" style="font-size: 11.5px; padding: 4px 10px; font-weight: 600;" onclick="return confirm('Konfirmasi bahwa pembayaran denda ini telah diterima dan LUNAS?')">
                      ✓ Pelunasan Denda
                    </button>
                  </form>
                @elseif($r->fine_amount > 0 && $r->fine_payment_status === 'Lunas')
                  <span style="font-size: 12px; color: #166534; font-weight: 700;">✓ Lunas</span>
                @else
                  <span style="font-size: 12px; color: #64748b;">-</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" style="text-align: center; padding: 40px; color: var(--gray-400);">
                Belum ada riwayat pengembalian yang diverifikasi
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    @if($verifiedReturns->hasPages())
      <div style="padding: 20px;">
        {{ $verifiedReturns->links() }}
      </div>
    @endif
  </div>
</div>

@push('scripts')
<script>
  function switchTab(tab, btn) {
    // Update tab buttons
    document.querySelectorAll('.return-tab').forEach(b => b.classList.remove('active'));
    if (btn) {
      btn.classList.add('active');
    }
    
    // Update tab content
    document.querySelectorAll('.return-tab-content').forEach(content => content.classList.remove('active'));
    const target = document.getElementById(tab + '-tab');
    if (target) {
      target.classList.add('active');
    }
  }
</script>
@endpush
@endsection
