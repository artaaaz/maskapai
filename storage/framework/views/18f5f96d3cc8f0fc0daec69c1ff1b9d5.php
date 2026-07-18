<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?> - Manager</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #7c3aed;
            --primary-light: #8b5cf6;
            --primary-dark: #5b21b6;
            --sidebar-width: 260px;
            --header-height: 64px;
            --radius: 12px;
            --radius-sm: 8px;
            --radius-lg: 16px;
            --shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07), 0 2px 4px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.08);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        body { background: #f1f5f9; min-height: 100vh; display: flex; }

        .sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-width); height: 100vh;
            background: linear-gradient(180deg, #5b21b6 0%, #7c3aed 100%);
            z-index: 50; display: flex; flex-direction: column; transition: transform 0.3s ease; overflow: hidden;
        }
        .sidebar-header { padding: 20px 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.08); flex-shrink: 0; }
        .sidebar-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .sidebar-brand-icon {
            width: 40px; height: 40px; background: white; border-radius: var(--radius);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .sidebar-brand-icon svg { width: 22px; height: 22px; color: var(--primary); }
        .sidebar-brand-text h2 { font-size: 16px; font-weight: 700; color: white; line-height: 1.2; }
        .sidebar-brand-text p { font-size: 10px; color: rgba(255,255,255,0.6); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 12px 12px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }
        .nav-section { margin-bottom: 8px; }
        .nav-section-title { padding: 8px 12px 6px; font-size: 10px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.8px; }
        .nav-item {
            display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: var(--radius-sm);
            color: rgba(255,255,255,0.7); font-size: 13px; font-weight: 500; text-decoration: none;
            transition: var(--transition); margin-bottom: 2px; position: relative;
        }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: white; }
        .nav-item.active { background: rgba(255,255,255,0.12); color: white; font-weight: 600; }
        .nav-item.active::before {
            content: ''; position: absolute; left: -12px; top: 50%; transform: translateY(-50%);
            width: 3px; height: 24px; background: #c084fc; border-radius: 0 3px 3px 0;
        }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .sidebar-footer { padding: 12px; border-top: 1px solid rgba(255,255,255,0.08); flex-shrink: 0; }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px; padding: 10px 12px;
            border-radius: var(--radius-sm); background: rgba(255,255,255,0.05); margin-bottom: 8px;
        }
        .sidebar-user-avatar {
            width: 36px; height: 36px; border-radius: var(--radius-sm);
            background: linear-gradient(135deg, #a78bfa, #7c3aed); display: flex; align-items: center;
            justify-content: center; color: white; font-weight: 700; font-size: 14px; flex-shrink: 0;
        }
        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-info p { font-size: 12px; font-weight: 600; color: white; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .sidebar-user-info span { font-size: 10px; color: rgba(255,255,255,0.5); }
        .sidebar-logout {
            display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--radius-sm);
            color: rgba(255,255,255,0.5); font-size: 13px; font-weight: 500; text-decoration: none;
            transition: var(--transition); background: none; border: none; width: 100%; cursor: pointer;
        }
        .sidebar-logout:hover { background: rgba(239, 68, 68, 0.15); color: #fca5a5; }
        .sidebar-logout svg { width: 18px; height: 18px; flex-shrink: 0; }

        .main-wrapper { margin-left: var(--sidebar-width); flex: 1; min-height: 100vh; display: flex; flex-direction: column; }
        .top-header {
            background: rgba(255,255,255,0.85); backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding: 0 32px; height: var(--header-height);
            display: flex; align-items: center; justify-content: space-between; position: sticky;
            top: 0; z-index: 40; flex-shrink: 0;
        }
        .header-left { display: flex; align-items: center; gap: 16px; }
        .header-title h1 { font-size: 18px; font-weight: 700; color: #0f172a; line-height: 1.2; }
        .header-title p { font-size: 12px; color: #64748b; font-weight: 500; }
        .header-right { display: flex; align-items: center; gap: 16px; }
        .header-user { display: flex; align-items: center; gap: 12px; }
        .header-user-info { text-align: right; }
        .header-user-info p { font-size: 13px; font-weight: 600; color: #0f172a; line-height: 1.2; }
        .header-user-info span { font-size: 11px; color: #64748b; }
        .header-avatar {
            width: 38px; height: 38px; border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            display: flex; align-items: center; justify-content: center; color: white;
            font-weight: 700; font-size: 14px; flex-shrink: 0; box-shadow: 0 2px 8px rgba(124, 58, 237, 0.25);
        }
        .main-content { flex: 1; padding: 28px 32px; }

        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #94a3b8; margin-bottom: 4px; }
        .breadcrumb a { color: #64748b; text-decoration: none; font-weight: 500; transition: color 0.15s; }
        .breadcrumb a:hover { color: var(--primary-light); }
        .breadcrumb span { color: #94a3b8; }
        .breadcrumb .separator { color: #cbd5e1; }

        .card { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow); border: 1px solid #f1f5f9; overflow: hidden; }
        .card-header { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .card-header h2 { font-size: 16px; font-weight: 700; color: #0f172a; }
        .card-body { padding: 24px; }

        .table-container { overflow-x: auto; }
        .table-container table { width: 100%; border-collapse: collapse; }
        .table-container thead { background: #f8fafc; }
        .table-container th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .table-container td { padding: 14px 16px; font-size: 13px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .table-container tbody tr { transition: background 0.15s; }
        .table-container tbody tr:hover { background: #f8fafc; }
        .table-container tbody tr:nth-child(even) { background: #fafbfc; }
        .table-container tbody tr:nth-child(even):hover { background: #f1f5f9; }

        .badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; line-height: 1.2; }
        .badge-blue { background: #eff6ff; color: #1d4ed8; }
        .badge-green { background: #f0fdf4; color: #15803d; }
        .badge-red { background: #fef2f2; color: #dc2626; }
        .badge-yellow { background: #fefce8; color: #a16207; }
        .badge-purple { background: #faf5ff; color: #7c3aed; }
        .badge-gray { background: #f1f5f9; color: #475569; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px;
            border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; text-decoration: none;
            border: none; cursor: pointer; transition: var(--transition); line-height: 1.2;
        }
        .btn-primary { background: var(--primary-light); color: white; }
        .btn-primary:hover { background: var(--primary); box-shadow: 0 2px 8px rgba(124, 58, 237, 0.3); }
        .btn-success { background: #059669; color: white; }
        .btn-success:hover { background: #047857; box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3); }
        .btn-warning { background: #d97706; color: white; }
        .btn-warning:hover { background: #b45309; box-shadow: 0 2px 8px rgba(217, 119, 6, 0.3); }
        .btn-danger { background: #dc2626; color: white; }
        .btn-danger:hover { background: #b91c1c; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3); }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-outline { background: transparent; border: 1.5px solid #e2e8f0; color: #475569; }
        .btn-outline:hover { background: #f8fafc; border-color: #94a3b8; }

        .form-input {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: var(--radius-sm);
            font-size: 13px; color: #0f172a; background: #fafbfc; transition: var(--transition); outline: none;
        }
        .form-input:focus { border-color: var(--primary-light); background: white; box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1); }
        .form-input::placeholder { color: #94a3b8; }
        .form-select {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: var(--radius-sm);
            font-size: 13px; color: #0f172a; background: #fafbfc; transition: var(--transition); outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px;
        }
        .form-select:focus { border-color: var(--primary-light); background-color: white; box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1); }
        .form-label { display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px; }
        .form-group { margin-bottom: 16px; }

        .stat-card {
            background: white; border-radius: var(--radius-lg); padding: 20px; box-shadow: var(--shadow);
            border: 1px solid #f1f5f9; transition: var(--transition);
        }
        .stat-card:hover { box-shadow: var(--shadow-md); }
        .stat-card .stat-icon { width: 44px; height: 44px; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
        .stat-card .stat-value { font-size: 24px; font-weight: 800; color: #0f172a; line-height: 1.2; }
        .stat-card .stat-label { font-size: 12px; color: #64748b; font-weight: 500; margin-top: 2px; }

        .grid-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 24px; }

        .mobile-menu-btn { display: none; width: 38px; height: 38px; border-radius: var(--radius-sm); border: 1.5px solid #e2e8f0; background: white; align-items: center; justify-content: center; cursor: pointer; transition: var(--transition); }
        .mobile-menu-btn:hover { background: #f8fafc; }
        .sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 45; }
        .sidebar-backdrop.active { display: block; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 280px; }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .top-header { padding: 0 16px; }
            .main-content { padding: 16px; }
            .header-user-info { display: none; }
            .grid-stats { grid-template-columns: 1fr; }
            .mobile-menu-btn { display: flex !important; }
            .sidebar-backdrop.active { display: block; }
        }
        .chart-container { position: relative; width: 100%; height: 300px; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?php echo e(route('manager.dashboard')); ?>" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </div>
                <div class="sidebar-brand-text">
                    <h2>drg.Maskapai</h2>
                    <p>Manager Portal</p>
                </div>
            </a>
        </div>
        <div class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Analytics</div>
                <a href="<?php echo e(route('manager.dashboard')); ?>"
                   class="nav-item <?php echo e(request()->routeIs('manager.dashboard') ? 'active' : ''); ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="<?php echo e(route('manager.reports')); ?>"
                   class="nav-item <?php echo e(request()->routeIs('manager.reports') ? 'active' : ''); ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Laporan
                </a>
            </div>
        </div>
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></div>
                <div class="sidebar-user-info">
                    <p><?php echo e(Auth::user()->name); ?></p>
                    <span><?php echo e(Auth::user()->email); ?></span>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="sidebar-logout">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="top-header">
            <div class="header-left">
                <button class="mobile-menu-btn" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="header-title">
                    <h1><?php echo e($header ?? 'Dashboard'); ?></h1>
                    <p><?php echo e(now()->format('l, d F Y')); ?></p>
                </div>
            </div>
            <div class="header-right">
                <div class="header-user">
                    <div class="header-user-info">
                        <p><?php echo e(Auth::user()->name); ?></p>
                        <span><?php echo e(Auth::user()->email); ?></span>
                    </div>
                    <div class="header-avatar"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></div>
                </div>
            </div>
        </header>
        <main class="main-content">
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-error"><?php echo e(session('error')); ?></div>
            <?php endif; ?>
            <?php echo e($slot); ?>

        </main>
    </div>
    <script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarBackdrop').classList.toggle('active');
    }
    </script>
</body>
</html><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/components/manager-layout.blade.php ENDPATH**/ ?>