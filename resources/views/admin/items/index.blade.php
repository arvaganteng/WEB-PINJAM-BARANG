@extends('layouts.admin')

@section('title', 'Data Barang')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Data Barang Inventaris</div>
    <div class="page-subtitle">Kelola seluruh inventaris barang perusahaan di database</div>
  </div>
  <div class="page-actions">
    <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Tambah Barang
    </button>
  </div>
</div>

<div class="card" style="margin-bottom:16px">
  <div class="card-body" style="padding:16px">
    <form method="GET" action="{{ route('admin.items.index') }}" class="filter-row">
      <div class="search-input-wrap">
        <span class="search-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
        <input type="text" name="search" class="form-input" placeholder="Cari kode atau nama barang..." value="{{ request('search') }}" style="padding-left:36px;max-width:280px"/>
      </div>
      <select name="category_id" class="form-input form-select" style="max-width:160px">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
      <select name="status" class="form-input form-select" style="max-width:150px">
        <option value="">Semua Status</option>
        <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
        <option value="Tidak Tersedia" {{ request('status') == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
        <option value="Rusak" {{ request('status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
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
          <th style="width:45px;text-align:center;">No</th>
          <th>Foto</th>
          <th>Kode</th>
          <th>Nama Barang</th>
          <th>Kategori</th>
          <th>Kondisi</th>
          <th>Stok</th>
          <th>Harga/Hari</th>
          <th>Status</th>
          <th style="text-align:center;width:70px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
          <tr>
            <td style="text-align:center;font-weight:600;color:var(--gray-500)">
              {{ $items->firstItem() + $loop->index }}
            </td>
            <td class="td-photo">
              @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
              @else
                <div class="photo-placeholder">
                  @if($item->category && $item->category->name == 'Elektronik') 💻
                  @elseif($item->category && $item->category->name == 'AV Equipment') 📽️
                  @elseif($item->category && $item->category->name == 'Perkantoran') 🪑
                  @else 📦
                  @endif
                </div>
              @endif
            </td>
            <td style="font-weight:600;color:var(--navy)">{{ $item->code }}</td>
            <td style="font-weight:500">{{ $item->name }}</td>
            <td><span class="badge badge-navy">{{ $item->category->name ?? '-' }}</span></td>
            <td>
              @if($item->condition == 'Baik')
                <span class="badge badge-success">Baik</span>
              @elseif($item->condition == 'Kurang Baik')
                <span class="badge badge-warning">Kurang Baik</span>
              @else
                <span class="badge badge-danger">{{ $item->condition }}</span>
              @endif
            </td>
            <td style="font-weight:600">{{ $item->stock }}</td>
            <td>
              @if($item->price_per_day > 0)
                Rp {{ number_format($item->price_per_day, 0, ',', '.') }}
              @else
                <span style="color:var(--green);font-weight:600">Gratis</span>
              @endif
            </td>
            <td>
              @if($item->status == 'Tersedia')
                <span class="badge badge-success">Tersedia</span>
              @elseif($item->status == 'Dipinjam')
                <span class="badge badge-info">Dipinjam</span>
              @else
                <span class="badge badge-gray">{{ $item->status }}</span>
              @endif
            </td>
            <td style="text-align:center">
              <div class="action-dropdown">
                <button type="button" class="action-dropdown-toggle" onclick="toggleActionDropdown(this, event)" title="Menu Aksi">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1.5" fill="currentColor"/>
                    <circle cx="12" cy="5" r="1.5" fill="currentColor"/>
                    <circle cx="12" cy="19" r="1.5" fill="currentColor"/>
                  </svg>
                </button>
                <div class="action-dropdown-menu">
                  <!-- READ / DETAIL -->
                  <button type="button" class="action-dropdown-item" onclick="openDetailModal({{ json_encode($item) }})">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--blue)"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <span>Lihat Detail</span>
                  </button>

                  <!-- UPDATE / EDIT -->
                  <button type="button" class="action-dropdown-item" onclick="openEditModal({{ json_encode($item) }})">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--yellow)"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    <span>Edit Barang</span>
                  </button>

                  <div class="action-dropdown-divider"></div>

                  <!-- DELETE / HAPUS -->
                  <form action="{{ route('admin.items.destroy', $item) }}" method="POST" onsubmit="return confirmSoft(event, 'Apakah Anda yakin ingin menghapus barang \'{{ addslashes($item->name) }}\'?', 'Hapus Barang?', 'warning', 'Ya, Hapus', '#ef4444')" style="margin:0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-dropdown-item danger">
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                      <span>Hapus Barang</span>
                    </button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="10" style="text-align:center;color:var(--gray-500);padding:24px">Tidak ada data barang ditemukan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $items->withQueryString()->links() }}
</div>

@push('scripts')
<script>
  function openCreateModal() {
    const categoriesHtml = `@foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach`;
    openModal(`
      <div class="modal-header">
        <div class="modal-title">Tambah Barang Baru</div>
        <button class="modal-close" onclick="closeModal()">✕</button>
      </div>
      <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label">Nama Barang <span class="required">*</span></label>
            <input type="text" name="name" class="form-input" placeholder="Contoh: Laptop Dell XPS 15" required/>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Kategori <span class="required">*</span></label>
              <select name="category_id" class="form-input form-select" required>
                ${categoriesHtml}
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Kondisi <span class="required">*</span></label>
              <select name="condition" class="form-input form-select" required>
                <option value="Baik">Baik</option>
                <option value="Kurang Baik">Kurang Baik</option>
                <option value="Rusak Ringan">Rusak Ringan</option>
                <option value="Rusak Berat">Rusak Berat</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Stok <span class="required">*</span></label>
              <input type="number" name="stock" class="form-input" value="1" min="0" required/>
            </div>
            <div class="form-group">
              <label class="form-label">Harga Sewa/Hari (Rp)</label>
              <input type="number" name="price_per_day" class="form-input" value="0" min="0"/>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Status <span class="required">*</span></label>
            <select name="status" class="form-input form-select" required>
              <option value="Tersedia">Tersedia</option>
              <option value="Dipinjam">Dipinjam</option>
              <option value="Tidak Tersedia">Tidak Tersedia</option>
              <option value="Rusak">Rusak</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Foto Barang</label>
            <input type="file" name="image" class="form-input" accept="image/*"/>
          </div>
          <div class="form-group">
            <label class="form-label">Deskripsi Barang</label>
            <textarea name="description" class="form-input form-textarea" placeholder="Spesifikasi & detail barang..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Barang</button>
        </div>
      </form>
    `);
  }

  function openEditModal(item) {
    const categories = @json($categories);
    let options = '';
    categories.forEach(cat => {
      options += `<option value="${cat.id}" ${item.category_id == cat.id ? 'selected' : ''}>${cat.name}</option>`;
    });

    openModal(`
      <div class="modal-header">
        <div class="modal-title">Edit Barang: ${item.name}</div>
        <button class="modal-close" onclick="closeModal()">✕</button>
      </div>
      <form action="/admin/items/${item.id}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <input type="hidden" name="_method" value="PUT"/>
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label">Nama Barang <span class="required">*</span></label>
            <input type="text" name="name" class="form-input" value="${item.name}" required/>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Kategori <span class="required">*</span></label>
              <select name="category_id" class="form-input form-select" required>
                ${options}
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Kondisi <span class="required">*</span></label>
              <select name="condition" class="form-input form-select" required>
                <option value="Baik" ${item.condition == 'Baik' ? 'selected' : ''}>Baik</option>
                <option value="Kurang Baik" ${item.condition == 'Kurang Baik' ? 'selected' : ''}>Kurang Baik</option>
                <option value="Rusak Ringan" ${item.condition == 'Rusak Ringan' ? 'selected' : ''}>Rusak Ringan</option>
                <option value="Rusak Berat" ${item.condition == 'Rusak Berat' ? 'selected' : ''}>Rusak Berat</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Stok <span class="required">*</span></label>
              <input type="number" name="stock" class="form-input" value="${item.stock}" min="0" required/>
            </div>
            <div class="form-group">
              <label class="form-label">Harga Sewa/Hari (Rp)</label>
              <input type="number" name="price_per_day" class="form-input" value="${item.price_per_day}" min="0"/>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Status <span class="required">*</span></label>
            <select name="status" class="form-input form-select" required>
              <option value="Tersedia" ${item.status == 'Tersedia' ? 'selected' : ''}>Tersedia</option>
              <option value="Dipinjam" ${item.status == 'Dipinjam' ? 'selected' : ''}>Dipinjam</option>
              <option value="Tidak Tersedia" ${item.status == 'Tidak Tersedia' ? 'selected' : ''}>Tidak Tersedia</option>
              <option value="Rusak" ${item.status == 'Rusak' ? 'selected' : ''}>Rusak</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Foto Barang (Opsional)</label>
            <input type="file" name="image" class="form-input" accept="image/*"/>
            <small style="color:var(--gray-500);font-size:12px">Biarkan kosong jika tidak ingin mengubah foto</small>
          </div>
          <div class="form-group">
            <label class="form-label">Deskripsi Barang</label>
            <textarea name="description" class="form-input form-textarea">${item.description || ''}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    `);
  }

  function openDetailModal(item) {
    const categories = @json($categories);
    const category = categories.find(c => c.id == item.category_id);
    const categoryName = category ? category.name : '-';
    const priceFormatted = item.price_per_day > 0 
      ? 'Rp ' + Number(item.price_per_day).toLocaleString('id-ID') + ' / hari'
      : 'Gratis';
    
    const photoHtml = item.image 
      ? `<img src="/storage/${item.image}" alt="${item.name}" style="width:100%;max-height:220px;object-fit:cover;border-radius:12px;border:1px solid var(--gray-200);box-shadow:var(--shadow-sm);"/>`
      : `<div style="height:130px;background:var(--gray-100);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:42px;color:var(--gray-400)">📦</div>`;

    let conditionBadge = '<span class="badge badge-success">Baik</span>';
    if (item.condition === 'Kurang Baik') conditionBadge = '<span class="badge badge-warning">Kurang Baik</span>';
    else if (item.condition === 'Rusak Ringan' || item.condition === 'Rusak Berat') conditionBadge = '<span class="badge badge-danger">' + item.condition + '</span>';

    let statusBadge = '<span class="badge badge-success">Tersedia</span>';
    if (item.status === 'Dipinjam') statusBadge = '<span class="badge badge-info">Dipinjam</span>';
    else if (item.status === 'Rusak') statusBadge = '<span class="badge badge-danger">Rusak</span>';
    else if (item.status === 'Tidak Tersedia') statusBadge = '<span class="badge badge-gray">Tidak Tersedia</span>';

    openModal(`
      <div class="modal-header">
        <div class="modal-title">Detail Barang: ${item.name}</div>
        <button class="modal-close" onclick="closeModal()">✕</button>
      </div>
      <div class="modal-body" style="padding:20px">
        <div style="margin-bottom:16px;text-align:center">
          ${photoHtml}
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;background:var(--gray-50);padding:14px;border-radius:10px;border:1px solid var(--gray-200)">
          <div>
            <div style="font-size:11px;color:var(--gray-500);font-weight:700;text-transform:uppercase;letter-spacing:0.04em">Kode Barang</div>
            <div style="font-weight:700;color:var(--navy);font-size:14px;margin-top:2px">${item.code}</div>
          </div>
          <div>
            <div style="font-size:11px;color:var(--gray-500);font-weight:700;text-transform:uppercase;letter-spacing:0.04em">Kategori</div>
            <div style="font-weight:600;color:var(--gray-800);font-size:14px;margin-top:2px">${categoryName}</div>
          </div>
          <div>
            <div style="font-size:11px;color:var(--gray-500);font-weight:700;text-transform:uppercase;letter-spacing:0.04em">Kondisi</div>
            <div style="margin-top:2px">${conditionBadge}</div>
          </div>
          <div>
            <div style="font-size:11px;color:var(--gray-500);font-weight:700;text-transform:uppercase;letter-spacing:0.04em">Status</div>
            <div style="margin-top:2px">${statusBadge}</div>
          </div>
          <div>
            <div style="font-size:11px;color:var(--gray-500);font-weight:700;text-transform:uppercase;letter-spacing:0.04em">Stok Tersedia</div>
            <div style="font-weight:700;color:var(--gray-900);font-size:14px;margin-top:2px">${item.stock} unit</div>
          </div>
          <div>
            <div style="font-size:11px;color:var(--gray-500);font-weight:700;text-transform:uppercase;letter-spacing:0.04em">Harga Sewa</div>
            <div style="font-weight:700;color:var(--blue);font-size:14px;margin-top:2px">${priceFormatted}</div>
          </div>
        </div>
        <div style="margin-top:14px">
          <div style="font-size:12px;font-weight:700;color:var(--gray-700);margin-bottom:6px">Deskripsi / Spesifikasi</div>
          <div style="font-size:13px;color:var(--gray-600);line-height:1.6;background:#fff;padding:12px;border-radius:8px;border:1px solid var(--gray-200);white-space:pre-wrap">${item.description || 'Tidak ada catatan deskripsi untuk barang ini.'}</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal()">Tutup</button>
        <button type="button" class="btn btn-primary" onclick="closeModal(); setTimeout(() => openEditModal(${JSON.stringify(item).replace(/"/g, '&quot;')}), 150)">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit Barang Ini
        </button>
      </div>
    `);
  }

  function toggleActionDropdown(btn, event) {
    event.stopPropagation();
    const dropdown = btn.nextElementSibling;
    const isOpen = dropdown.classList.contains('show');
    
    // Tutup dropdown lain yang terbuka
    document.querySelectorAll('.action-dropdown-menu.show').forEach(el => {
      if (el !== dropdown) el.classList.remove('show');
    });
    document.querySelectorAll('.action-dropdown-toggle.active').forEach(el => {
      if (el !== btn) el.classList.remove('active');
    });

    if (!isOpen) {
      // Periksa apakah posisi di dekat bawah viewport
      const rect = btn.getBoundingClientRect();
      const spaceBelow = window.innerHeight - rect.bottom;
      if (spaceBelow < 180 && rect.top > 180) {
        dropdown.style.top = 'auto';
        dropdown.style.bottom = 'calc(100% + 5px)';
      } else {
        dropdown.style.top = 'calc(100% + 5px)';
        dropdown.style.bottom = 'auto';
      }
      dropdown.classList.add('show');
      btn.classList.add('active');
    } else {
      dropdown.classList.remove('show');
      btn.classList.remove('active');
    }
  }

  document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-dropdown')) {
      document.querySelectorAll('.action-dropdown-menu.show').forEach(el => el.classList.remove('show'));
      document.querySelectorAll('.action-dropdown-toggle.active').forEach(el => el.classList.remove('active'));
    }
  });
</script>
@endpush
@endsection
