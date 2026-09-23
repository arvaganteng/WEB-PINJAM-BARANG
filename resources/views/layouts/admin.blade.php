<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin Dashboard') – PT Nusantara Digital Express</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}" />
  <!-- SweetAlert2 for Soft Elegant Popups & Confirmations -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    html,
    body {
      height: 100%;
    }

    body {
      display: flex;
      flex-direction: column;
      background: var(--gray-100);
    }

    .admin-wrapper {
      display: flex;
      flex: 1;
      min-height: 100vh;
    }

    /* SweetAlert2 Soft & Elegant Custom Theme */
    .swal2-container {
      z-index: 10000 !important;
    }

    .swal2-popup.swal-soft-popup {
      border-radius: 20px !important;
      padding: 24px 28px !important;
      font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
      box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.15), 0 0 1px rgba(15, 23, 42, 0.1) !important;
      border: 1px solid #e2e8f0 !important;
      background: #ffffff !important;
    }

    .swal2-title.swal-soft-title {
      font-size: 18px !important;
      font-weight: 700 !important;
      color: #0f172a !important;
      padding-top: 8px !important;
    }

    .swal2-html-container.swal-soft-content {
      font-size: 13.5px !important;
      color: #475569 !important;
      line-height: 1.6 !important;
      margin-top: 8px !important;
    }

    .swal2-icon {
      margin-top: 10px !important;
      transform: scale(0.92);
    }

    .swal2-actions {
      gap: 10px !important;
      margin-top: 22px !important;
    }

    .swal2-styled.swal-soft-confirm-btn {
      border-radius: 12px !important;
      padding: 10px 22px !important;
      font-size: 13px !important;
      font-weight: 600 !important;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2) !important;
      transition: all 0.2s ease !important;
    }

    .swal2-styled.swal-soft-confirm-btn:hover {
      transform: translateY(-1px);
    }

    .swal2-styled.swal-soft-cancel-btn {
      border-radius: 12px !important;
      padding: 10px 22px !important;
      font-size: 13px !important;
      font-weight: 600 !important;
      background-color: #f1f5f9 !important;
      color: #64748b !important;
      box-shadow: none !important;
      transition: all 0.2s ease !important;
    }

    .swal2-styled.swal-soft-cancel-btn:hover {
      background-color: #e2e8f0 !important;
      color: #334155 !important;
    }

    .swal2-popup.swal-soft-toast {
      border-radius: 14px !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
      border: 1px solid #e2e8f0 !important;
      padding: 12px 16px !important;
      font-family: 'Inter', sans-serif !important;
      font-size: 13px !important;
      font-weight: 500 !important;
    }

    /* ================= MOBILE-LIKE PUSH TOAST NOTIFICATION ================= */
    #mobile-push-container {
      position: fixed;
      top: 18px;
      right: 20px;
      z-index: 999999;
      display: flex;
      flex-direction: column;
      gap: 12px;
      pointer-events: none;
      max-width: 390px;
      width: calc(100vw - 36px);
    }
    .mobile-push-toast {
      pointer-events: auto;
      background: rgba(255, 255, 255, 0.97);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(226, 232, 240, 0.95);
      box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.22), 0 4px 12px rgba(0, 0, 0, 0.06);
      border-radius: 18px;
      padding: 14px 16px;
      display: flex;
      align-items: flex-start;
      gap: 12px;
      cursor: pointer;
      transform: translateY(-50px) scale(0.92);
      opacity: 0;
      transition: all 0.45s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
    }
    .mobile-push-toast.show {
      transform: translateY(0) scale(1);
      opacity: 1;
    }
    .mobile-push-toast.hide {
      transform: translateY(-25px) scale(0.9);
      opacity: 0;
    }
    .mobile-push-toast:hover {
      transform: translateY(-2px) scale(1.02);
      box-shadow: 0 24px 48px -8px rgba(15, 23, 42, 0.28);
    }
    .mobile-push-icon {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: linear-gradient(135deg, #0f2d6b, #2563eb);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 20px;
      flex-shrink: 0;
      box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    }
    .mobile-push-progress {
      position: absolute;
      bottom: 0;
      left: 0;
      height: 3.5px;
      background: linear-gradient(90deg, #2563eb, #38bdf8);
      width: 100%;
      animation: pushProgressAnim 7s linear forwards;
    }
    @keyframes pushProgressAnim {
      from { width: 100%; }
      to { width: 0%; }
    }
  </style>
  @stack('styles')
</head>

<body>

  <!-- MOBILE-STYLE PUSH NOTIFICATION POPUP CONTAINER -->
  <div id="mobile-push-container"></div>

  <div class="admin-wrapper">

    <!-- ===================== SIDEBAR ===================== -->
    <aside class="admin-sidebar" id="adminSidebar">
      <div class="sidebar-brand" style="background:#ffffff; margin:10px 12px; border-radius:8px; padding:8px 12px; display:flex; align-items:center; justify-content:center;">
        <img src="{{ asset('images/logo.png') }}" alt="PT Nusantara Digital Express" style="height: 38px; width: auto; object-fit: contain; max-width: 100%;">
      </div>

      <nav class="sidebar-nav">
        <div class="sidebar-section-label">MENU UTAMA</div>
        <a class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7" />
            <rect x="14" y="3" width="7" height="7" />
            <rect x="14" y="14" width="7" height="7" />
            <rect x="3" y="14" width="7" height="7" />
          </svg>
          Dashboard
        </a>

        <div class="sidebar-section-label">PENGELOLAAN</div>
        <a class="sidebar-item {{ request()->routeIs('admin.items.*') ? 'active' : '' }}" href="{{ route('admin.items.index') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
          </svg>
          Data Barang
        </a>
        <a class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
          </svg>
          Data Kategori
        </a>
        <a class="sidebar-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
          </svg>
          Data Customer
        </a>

        <div class="sidebar-section-label">TRANSAKSI</div>
        <a class="sidebar-item {{ request()->routeIs('admin.borrowings.*') ? 'active' : '' }}" href="{{ route('admin.borrowings.index') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
            <polyline points="14 2 14 8 20 8" />
          </svg>
          Peminjaman
        </a>
        <a class="sidebar-item {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}" href="{{ route('admin.returns.index') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="1 4 1 10 7 10" />
            <path d="M3.51 15a9 9 0 1 0 .49-3.5" />
          </svg>
          Pengembalian
        </a>
        <a class="sidebar-item {{ request()->routeIs('admin.fines.*') ? 'active' : '' }}" href="{{ route('admin.fines.index') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="1" x2="12" y2="23"/>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
          </svg>
          Kelola Denda
        </a>

        <div class="sidebar-section-label">LAINNYA</div>
        <a class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="20" x2="18" y2="10" />
            <line x1="12" y1="20" x2="12" y2="4" />
            <line x1="6" y1="20" x2="6" y2="14" />
          </svg>
          Laporan
        </a>
        <a class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="3" />
            <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14" />
          </svg>
          Pengaturan
        </a>
        <a class="sidebar-item {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}" href="{{ route('admin.activity-logs.index') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
            <polyline points="14 2 14 8 20 8" />
            <line x1="16" y1="13" x2="8" y2="13" />
            <line x1="16" y1="17" x2="8" y2="17" />
            <polyline points="10 9 9 9 8 9" />
          </svg>
          Log Aktivitas
        </a>
      </nav>


      <div class="sidebar-footer">
        <div class="sidebar-user">
          <div class="user-avatar-sm admin-avatar">A</div>
          <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ Auth::user()->name ?? 'Admin NDE' }}</div>
            <div class="sidebar-user-role">Administrator</div>
          </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="display:inline">
          @csrf
          <button type="submit" class="sidebar-logout" title="Logout" style="border:none;background:none;cursor:pointer">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
              <polyline points="16 17 21 12 16 7" />
              <line x1="21" y1="12" x2="9" y2="12" />
            </svg>
          </button>
        </form>
      </div>
    </aside>

    <!-- ===================== MAIN AREA ===================== -->
    <main class="admin-main" id="adminMain">
      <div class="admin-topbar">
        <button class="topbar-toggle" onclick="toggleSidebar()" type="button">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6" />
            <line x1="3" y1="12" x2="21" y2="12" />
            <line x1="3" y1="18" x2="21" y2="18" />
          </svg>
        </button>
        <div class="topbar-search">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
          <input type="text" placeholder="Cari data di database..." />
        </div>
        <div class="topbar-right" style="display: flex; align-items: center; gap: 14px;">
          <!-- Admin Notification Bell -->
          <div style="position: relative;">
            <button type="button" id="admin-notif-btn" onclick="toggleAdminNotif()" style="position: relative; background: none; border: none; padding: 7px; cursor: pointer; color: #475569; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='none'">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
              </svg>
              <span id="admin-notif-badge" style="display: none; position: absolute; top: 0; right: 0; background: #ef4444; color: #fff; font-size: 10px; font-weight: 700; border-radius: 10px; padding: 1px 5px; min-width: 16px; text-align: center;">0</span>
            </button>

            <div id="admin-notif-menu" style="display: none; position: absolute; right: 0; top: 42px; width: 340px; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15), 0 8px 10px -6px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; z-index: 1050; overflow: hidden;">
              <div style="padding: 10px 14px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: #f8fafc;">
                <span style="font-weight: 700; font-size: 12.5px; color: #0f172a;">Notifikasi Sistem</span>
                <button type="button" onclick="markAllAdminNotifRead()" style="background: none; border: none; color: #2563eb; font-size: 11px; font-weight: 600; cursor: pointer;">Tandai semua dibaca</button>
              </div>
              <div id="admin-notif-list" style="max-height: 320px; overflow-y: auto; display: flex; flex-direction: column;">
                <div style="padding: 20px; text-align: center; color: #94a3b8; font-size: 12.5px;">Memuat notifikasi...</div>
              </div>
            </div>
          </div>

          <div class="topbar-user">
            <div class="user-avatar-sm admin-avatar">A</div>
            <div class="topbar-user-info">
              <div class="topbar-user-name">{{ Auth::user()->name ?? 'Admin NDE' }}</div>
              <div class="topbar-user-role">Administrator</div>
            </div>
          </div>
        </div>
      </div>

      <!-- MAIN CONTENT & FLASH MESSAGES -->
      <div class="admin-content" id="adminContent">
        @if(session('success'))
          <div class="info-alert green" style="margin-bottom: 16px;">
            {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="info-alert red" style="margin-bottom: 16px;">
            {{ session('error') }}
          </div>
        @endif

        @if($errors->any())
        <div class="info-alert red" style="margin-bottom: 20px;">
          <ul style="margin-left: 16px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        @yield('content')
      </div>
    </main>

  </div>

  <!-- MODAL OVERLAY -->
  <div id="modal-overlay" class="modal-overlay" onclick="closeModal()">
    <div class="modal-box" onclick="event.stopPropagation()" id="modal-box"></div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('adminSidebar').classList.toggle('collapsed');
    }

    function openModal(html) {
      const overlay = document.getElementById('modal-overlay');
      const box = document.getElementById('modal-box');
      if (!overlay || !box) return;
      box.innerHTML = html;
      overlay.classList.add('open');
    }

    function closeModal() {
      const overlay = document.getElementById('modal-overlay');
      if (overlay) overlay.classList.remove('open');
    }

    let adminNotifOpen = false;

    function toggleAdminNotif() {
      const menu = document.getElementById('admin-notif-menu');
      if (!menu) return;
      adminNotifOpen = !adminNotifOpen;
      menu.style.display = adminNotifOpen ? 'block' : 'none';
      if (adminNotifOpen) {
        loadAdminNotifications();
      }
    }

    document.addEventListener('click', function(e) {
      const btn = document.getElementById('admin-notif-btn');
      const menu = document.getElementById('admin-notif-menu');
      if (btn && menu && !btn.contains(e.target) && !menu.contains(e.target)) {
        menu.style.display = 'none';
        adminNotifOpen = false;
      }
    });

    /* ================= REAL-TIME MOBILE-LIKE PUSH NOTIFICATION SYSTEM ================= */
    let lastSeenAdminNotifId = null;
    let isInitialAdminNotifLoad = true;

    // Web Audio Synthesizer for pleasant Ding-Dong mobile chime
    function playMobileChime() {
      try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        
        // Note 1 (D5 -> A5)
        const osc1 = ctx.createOscillator();
        const gain1 = ctx.createGain();
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(587.33, ctx.currentTime);
        osc1.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.08);
        gain1.gain.setValueAtTime(0.25, ctx.currentTime);
        gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);
        osc1.connect(gain1);
        gain1.connect(ctx.destination);
        osc1.start();
        osc1.stop(ctx.currentTime + 0.35);

        // Note 2 (High chime note)
        setTimeout(() => {
          try {
            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(1174.66, ctx.currentTime);
            gain2.gain.setValueAtTime(0.25, ctx.currentTime);
            gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.45);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start();
            osc2.stop(ctx.currentTime + 0.45);
          } catch(e) {}
        }, 110);

        // Mobile vibration
        if (navigator.vibrate) {
          navigator.vibrate([100, 60, 120]);
        }
      } catch(e) {}
    }

    // Request browser notification permission gently on first interaction
    document.addEventListener('click', function requestNotifOnce() {
      if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
      }
      document.removeEventListener('click', requestNotifOnce);
    }, { once: true });

    function showMobilePushBanner(n) {
      const container = document.getElementById('mobile-push-container');
      if (!container) return;

      const toast = document.createElement('div');
      toast.className = 'mobile-push-toast';
      
      const icon = n.type === 'success' ? '✓' : (n.type === 'warning' ? '!' : (n.type === 'danger' ? '!' : 'i'));

      toast.innerHTML = `
        <div class="mobile-push-icon">${icon}</div>
        <div style="flex:1; min-width:0;">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:3px;">
            <span style="font-size:11px; font-weight:800; color:#2563eb; text-transform:uppercase; letter-spacing:0.04em; display:flex; align-items:center; gap:4px;">
              <span style="width:6px; height:6px; border-radius:50%; background:#22c55e; display:inline-block;"></span> NDE NOTIFIKASI
            </span>
            <button type="button" onclick="event.stopPropagation(); this.closest('.mobile-push-toast').remove();" style="background:none; border:none; color:#94a3b8; font-size:18px; line-height:1; cursor:pointer; padding:0 2px;">&times;</button>
          </div>
          <div style="font-size:13.5px; font-weight:800; color:#0f172a; margin-bottom:3px; line-height:1.3;">${n.title}</div>
          <div style="font-size:12.5px; color:#475569; line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">${n.message}</div>
          <div style="margin-top:7px; display:flex; align-items:center; gap:6px; font-size:12px; color:#2563eb; font-weight:700;">
            <span>Ketuk untuk buka &amp; proses sekarang</span>
          </div>
        </div>
        <div class="mobile-push-progress"></div>
      `;

      toast.onclick = function() {
        readAdminNotif(n.id, n.link || '{{ route("admin.borrowings.index") }}');
      };

      container.prepend(toast);

      setTimeout(() => toast.classList.add('show'), 30);

      // Auto dismiss after 7 seconds
      setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 450);
      }, 7000);
    }

    function loadAdminNotifications() {
      fetch('{{ route("notifications.get") }}')
        .then(res => res.json())
        .then(data => {
          const badge = document.getElementById('admin-notif-badge');
          const list = document.getElementById('admin-notif-list');

          if (badge) {
            if (data.unread_count > 0) {
              badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
              badge.style.display = 'block';
            } else {
              badge.style.display = 'none';
            }
          }

          // Check if there are new unread notifications to trigger mobile push banner
          if (data.notifications && data.notifications.length > 0) {
            const latest = data.notifications[0];
            
            if (isInitialAdminNotifLoad) {
              lastSeenAdminNotifId = latest.id;
              isInitialAdminNotifLoad = false;
            } else if (latest.id > lastSeenAdminNotifId && !latest.is_read) {
              lastSeenAdminNotifId = latest.id;
              
              // Trigger mobile-style push banner & chime sound
              showMobilePushBanner(latest);
              playMobileChime();

              // Trigger OS/Browser Native Notification if granted
              if ('Notification' in window && Notification.permission === 'granted') {
                new Notification(latest.title || 'PT NDE Inventaris', {
                  body: latest.message,
                  icon: '{{ asset("images/logo.png") }}'
                });
              }
            }
          } else {
            isInitialAdminNotifLoad = false;
          }

          if (!list) return;

          if (!data.notifications || data.notifications.length === 0) {
            list.innerHTML = '<div style="padding:24px; text-align:center; color:#94a3b8; font-size:12.5px;">Belum ada notifikasi</div>';
            return;
          }

          let html = '';
          data.notifications.forEach(n => {
            const bg = n.is_read ? '#fff' : '#f0fdf4';
            const icon = n.type === 'success' ? '•' : (n.type === 'warning' ? '•' : (n.type === 'danger' ? '•' : '•'));
            const time = new Date(n.created_at).toLocaleDateString('id-ID', {
              day: 'numeric',
              month: 'short',
              hour: '2-digit',
              minute: '2-digit'
            });

            html += `
            <div onclick="readAdminNotif(${n.id}, '${n.link || ''}')" style="padding:10px 14px; border-bottom:1px solid #f1f5f9; background:${bg}; cursor:pointer; transition:background 0.15s; display:flex; gap:10px; align-items:flex-start;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='${bg}'">
              <span style="font-size:15px;">${icon}</span>
              <div style="flex:1;">
                <div style="font-size:12px; font-weight:${n.is_read ? '600' : '700'}; color:#0f172a; margin-bottom:2px;">${n.title}</div>
                <div style="font-size:11.5px; color:#475569; line-height:1.4;">${n.message}</div>
                <div style="font-size:10px; color:#94a3b8; margin-top:3px;">${time}</div>
              </div>
            </div>
          `;
          });
          list.innerHTML = html;
        })
        .catch(err => console.error('Error fetching notifications:', err));
    }

    function readAdminNotif(id, link) {
      fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json'
        }
      }).then(() => {
        if (link && link !== '') {
          window.location.href = link;
        } else {
          loadAdminNotifications();
        }
      });
    }

    function markAllAdminNotifRead() {
      fetch('{{ route("notifications.read-all") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json'
        }
      }).then(() => {
        loadAdminNotifications();
      });
    }

    document.addEventListener('DOMContentLoaded', loadAdminNotifications);

    // Live Real-Time Polling every 4 seconds for instant phone-like notifications
    setInterval(loadAdminNotifications, 4000);

    /**
     * Soft & Elegant Confirmation Dialog (Replaces native browser confirm '127.0.0.1 says')
     */
    function confirmSoft(event, message, title = 'Konfirmasi Tindakan', type = 'warning', confirmBtnText = 'Ya, Lanjutkan', confirmBtnColor = '#2563eb') {
      if (event) {
        event.preventDefault();
        event.stopPropagation();
      }
      const target = event ? (event.target || event.srcElement) : null;
      const form = target ? (target.tagName === 'FORM' ? target : target.closest('form')) : null;

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: title,
          html: message,
          icon: type,
          showCancelButton: true,
          confirmButtonText: confirmBtnText,
          cancelButtonText: 'Batal',
          confirmButtonColor: confirmBtnColor,
          cancelButtonColor: '#94a3b8',
          reverseButtons: true,
          customClass: {
            popup: 'swal-soft-popup',
            title: 'swal-soft-title',
            htmlContainer: 'swal-soft-content',
            confirmButton: 'swal-soft-confirm-btn',
            cancelButton: 'swal-soft-cancel-btn'
          }
        }).then((result) => {
          if (result.isConfirmed && form) {
            form.removeAttribute('onsubmit');
            form.submit();
          }
        });
        return false;
      } else {
        if (confirm(message)) {
          if (form) {
            form.removeAttribute('onsubmit');
            form.submit();
          }
          return true;
        }
        return false;
      }
    }

    // Flash Notifications Toast Trigger
    @if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: "{{ session('success') }}",
          showConfirmButton: false,
          timer: 3500,
          timerProgressBar: true,
          customClass: {
            popup: 'swal-soft-toast'
          }
        });
      }
    });
    @endif

    @if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          title: "{{ session('error') }}",
          showConfirmButton: false,
          timer: 4000,
          timerProgressBar: true,
          customClass: {
            popup: 'swal-soft-toast'
          }
        });
      }
    });
    @endif

    /**
     * Soft & Elegant In-App Lightbox Image Viewer
     */
    function previewImageSoft(url, title = 'Pratinjau Dokumen / Foto') {
      if (!url || url.endsWith('/storage/') || url.includes('/null') || url.includes('/undefined')) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'info',
            title: 'Foto Tidak Tersedia',
            text: 'File dokumen/foto belum diunggah atau tidak tersedia.',
            confirmButtonColor: '#2563eb',
            customClass: {
              popup: 'swal-soft-popup'
            }
          });
        } else {
          alert('Foto tidak tersedia.');
        }
        return;
      }

      const imgTest = new Image();
      imgTest.onload = function() {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: title,
            html: `
            <div style="margin-top:10px; padding:12px; background:#f8fafc; border-radius:14px; border:1px solid #e2e8f0;">
              <img src="${url}" alt="${title}" style="max-width:100%; max-height:450px; border-radius:10px; object-fit:contain; display:block; margin:0 auto; box-shadow:0 4px 16px rgba(0,0,0,0.08);"/>
            </div>
          `,
            showConfirmButton: true,
            confirmButtonText: 'Tutup Pratinjau',
            confirmButtonColor: '#2563eb',
            width: '580px',
            customClass: {
              popup: 'swal-soft-popup',
              title: 'swal-soft-title'
            }
          });
        } else {
          window.open(url, '_blank');
        }
      };

      imgTest.onerror = function() {
        const filename = url.split('/').pop() || 'Dokumen';
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'warning',
            title: 'File Tidak Ditemukan',
            html: `
            <div style="font-size:13.5px; color:#475569; line-height:1.6; margin-top:6px;">
              Berkas foto <strong>${filename}</strong> tidak ditemukan di folder penyimpan server.<br>
              <span style="font-size:12px; color:#94a3b8; display:block; margin-top:6px;">(File mungkin belum diunggah secara sempurna atau telah dihapus)</span>
            </div>
          `,
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#2563eb',
            customClass: {
              popup: 'swal-soft-popup'
            }
          });
        } else {
          alert('File tidak ditemukan di server.');
        }
      };

      imgTest.src = url;
    }

    function previewKtpSoft(url, title = 'Dokumen Identitas (KTP / KK)') {
      previewImageSoft(url, title);
    }
  </script>
  @stack('scripts')
</body>

</html>