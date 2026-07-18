<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?> - Admin</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #1e40af;
            --primary-light: #3b82f6;
            --primary-dark: #1e3a8a;
            --sidebar-width: 260px;
            --sidebar-collapsed: 0px;
            --header-height: 64px;
            --radius: 12px;
            --radius-sm: 8px;
            --radius-lg: 16px;
            --shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07), 0 2px 4px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.08);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
            z-index: 50;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .sidebar-header {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .sidebar-brand-icon svg {
            width: 22px;
            height: 22px;
            color: var(--primary);
        }

        .sidebar-brand-text h2 {
            font-size: 16px;
            font-weight: 700;
            color: white;
            line-height: 1.2;
        }

        .sidebar-brand-text p {
            font-size: 10px;
            color: rgba(255,255,255,0.6);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 12px 12px;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }

        .nav-section {
            margin-bottom: 8px;
        }

        .nav-section-title {
            padding: 8px 12px 6px;
            font-size: 10px;
            font-weight: 700;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            color: rgba(255,255,255,0.7);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            margin-bottom: 2px;
            position: relative;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .nav-item.active {
            background: rgba(255,255,255,0.12);
            color: white;
            font-weight: 600;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 24px;
            background: #60a5fa;
            border-radius: 0 3px 3px 0;
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            background: rgba(255,255,255,0.05);
            margin-bottom: 8px;
        }

        .sidebar-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user-info p {
            font-size: 12px;
            font-weight: 600;
            color: white;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar-user-info span {
            font-size: 10px;
            color: rgba(255,255,255,0.5);
        }

        .sidebar-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            color: rgba(255,255,255,0.5);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            background: none;
            border: none;
            width: 100%;
            cursor: pointer;
        }

        .sidebar-logout:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
        }

        .sidebar-logout svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* ===== MAIN CONTENT ===== */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== HEADER ===== */
        .top-header {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 0 32px;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
            flex-shrink: 0;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-title h1 {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }

        .header-title p {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-user-info {
            text-align: right;
        }

        .header-user-info p {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.2;
        }

        .header-user-info span {
            font-size: 11px;
            color: #64748b;
        }

        .header-avatar {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
        }

        /* ===== MAIN ===== */
        .main-content {
            flex: 1;
            padding: 28px 32px;
        }

        /* ===== BREADCRUMB ===== */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .breadcrumb a {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s;
        }

        .breadcrumb a:hover {
            color: var(--primary-light);
        }

        .breadcrumb span {
            color: #94a3b8;
        }

        .breadcrumb .separator {
            color: #cbd5e1;
        }

        /* ===== CARD ===== */
        .card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-header h2 {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .card-body {
            padding: 24px;
        }

        /* ===== TABLE ===== */
        .table-container {
            overflow-x: auto;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container thead {
            background: #f8fafc;
        }

        .table-container th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .table-container td {
            padding: 14px 16px;
            font-size: 13px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .table-container tbody tr {
            transition: background 0.15s;
        }

        .table-container tbody tr:hover {
            background: #f8fafc;
        }

        .table-container tbody tr:nth-child(even) {
            background: #fafbfc;
        }

        .table-container tbody tr:nth-child(even):hover {
            background: #f1f5f9;
        }

        /* ===== BADGES ===== */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            line-height: 1.2;
        }

        .badge-blue {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .badge-green {
            background: #f0fdf4;
            color: #15803d;
        }

        .badge-red {
            background: #fef2f2;
            color: #dc2626;
        }

        .badge-yellow {
            background: #fefce8;
            color: #a16207;
        }

        .badge-purple {
            background: #faf5ff;
            color: #7c3aed;
        }

        .badge-gray {
            background: #f1f5f9;
            color: #475569;
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            line-height: 1.2;
        }

        .btn-primary {
            background: var(--primary-light);
            color: white;
        }
        .btn-primary:hover {
            background: var(--primary);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .btn-success {
            background: #059669;
            color: white;
        }
        .btn-success:hover {
            background: #047857;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
        }

        .btn-warning {
            background: #d97706;
            color: white;
        }
        .btn-warning:hover {
            background: #b45309;
            box-shadow: 0 2px 8px rgba(217, 119, 6, 0.3);
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }
        .btn-danger:hover {
            background: #b91c1c;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-outline {
            background: transparent;
            border: 1.5px solid #e2e8f0;
            color: #475569;
        }
        .btn-outline:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        /* ===== FORMS ===== */
        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: var(--radius-sm);
            font-size: 13px;
            color: #0f172a;
            background: #fafbfc;
            transition: var(--transition);
            outline: none;
        }
        .form-input:focus {
            border-color: var(--primary-light);
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: var(--radius-sm);
            font-size: 13px;
            color: #0f172a;
            background: #fafbfc;
            transition: var(--transition);
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }
        .form-select:focus {
            border-color: var(--primary-light);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        /* ===== ALERT ===== */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error, .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-warning {
            background: #fefce8;
            border: 1px solid #fef08a;
            color: #854d0e;
        }

        .alert-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
        }

        /* ===== PAGINATION ===== */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 16px 0;
        }

        .pagination a, .pagination span {
            padding: 8px 14px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }

        .pagination a {
            color: #475569;
            background: white;
            border: 1px solid #e2e8f0;
        }

        .pagination a:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        .pagination .active span {
            background: var(--primary-light);
            color: white;
            border-color: var(--primary-light);
        }

        .pagination .disabled span {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* ===== STAT CARD ===== */
        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow);
            border: 1px solid #f1f5f9;
            transition: var(--transition);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
        }

        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .stat-card .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        .stat-card .stat-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }

        /* ===== ACTION BUTTONS GROUP ===== */
        .action-group {
            display: flex;
            gap: 6px;
            flex-wrap: nowrap;
        }

        /* ===== SEARCH INPUT ===== */
        .search-wrapper {
            position: relative;
            max-width: 320px;
        }

        .search-wrapper svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #94a3b8;
        }

        .search-wrapper input {
            width: 100%;
            padding: 9px 12px 9px 36px;
            border: 1.5px solid #e2e8f0;
            border-radius: var(--radius-sm);
            font-size: 13px;
            color: #0f172a;
            background: #fafbfc;
            transition: var(--transition);
            outline: none;
        }

        .search-wrapper input:focus {
            border-color: var(--primary-light);
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
        }

        .empty-state .empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .empty-state .empty-icon svg {
            width: 32px;
            height: 32px;
            color: #94a3b8;
        }

        .empty-state h3 {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .empty-state p {
            font-size: 13px;
            color: #64748b;
        }

        /* ===== GRID ===== */
        .grid-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
            .top-header {
                padding: 0 16px;
            }
            .main-content {
                padding: 16px;
            }
            .header-user-info {
                display: none;
            }
            .grid-stats {
                grid-template-columns: 1fr;
            }
            .mobile-menu-btn {
                display: flex !important;
            }
        }

        .mobile-menu-btn {
            display: none;
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            border: 1.5px solid #e2e8f0;
            background: white;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .mobile-menu-btn:hover {
            background: #f8fafc;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 45;
        }

        .sidebar-backdrop.active {
            display: block;
        }

        @media (max-width: 768px) {
            .sidebar-backdrop.active {
                display: block;
            }
        }

        /* ===== TOGGLE SWITCH ===== */
        .toggle-switch {
            position: relative;
            width: 40px;
            height: 22px;
            display: inline-block;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            inset: 0;
            background: #cbd5e1;
            border-radius: 999px;
            cursor: pointer;
            transition: 0.3s;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            left: 2px;
            bottom: 2px;
            background: white;
            border-radius: 50%;
            transition: 0.3s;
        }

        .toggle-switch input:checked + .toggle-slider {
            background: var(--primary-light);
        }

        .toggle-switch input:checked + .toggle-slider::before {
            transform: translateX(18px);
        }

        /* ===== MODAL ===== */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(4px);
            z-index: 999;
            display: none;
        }

        .modal-backdrop.active {
            display: block;
        }

        .modal-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.95);
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            width: 90%;
            max-width: 520px;
            max-height: 85vh;
            overflow-y: auto;
            display: none;
            opacity: 0;
            transition: all 0.2s ease;
        }

        .modal-container.active {
            display: block;
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .modal-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .modal-header button {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            transition: var(--transition);
        }

        .modal-header button:hover {
            background: #f1f5f9;
            color: #475569;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }
    </style>
</head>
<body>
    
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    
    <aside class="sidebar" id="sidebar">
        
        <div class="sidebar-header">
            <a href="<?php echo e(route('dashboard')); ?>" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </div>
                <div class="sidebar-brand-text">
                    <h2>drgMaskapai</h2>
                    <p><?php echo e(Auth::user()->role); ?></p>
                </div>
            </a>
        </div>

        
        <div class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main Menu</div>

                <a href="<?php echo e(route('dashboard')); ?>"
                   class="nav-item <?php echo e(request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Dashboard
                </a>
            </div>

            <?php if(Auth::user()->role === 'admin'): ?>
            <div class="nav-section">
                <div class="nav-section-title">Master Data</div>

                <a href="<?php echo e(route('admin.airlines.index')); ?>"
                   class="nav-item <?php echo e(request()->routeIs('admin.airlines.*') ? 'active' : ''); ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Maskapai
                </a>

                <a href="<?php echo e(route('admin.airports.index')); ?>"
                   class="nav-item <?php echo e(request()->routeIs('admin.airports.*') ? 'active' : ''); ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Bandara
                </a>

                <a href="<?php echo e(route('admin.airplanes.index')); ?>"
                   class="nav-item <?php echo e(request()->routeIs('admin.airplanes.*') ? 'active' : ''); ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                    </svg>
                    Pesawat
                </a>

                <a href="<?php echo e(route('admin.flights.index')); ?>"
                   class="nav-item <?php echo e(request()->routeIs('admin.flights.*') ? 'active' : ''); ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Jadwal
                </a>

                <a href="<?php echo e(route('admin.promos.index')); ?>"
                   class="nav-item <?php echo e(request()->routeIs('admin.promos.*') ? 'active' : ''); ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                    Promo
                </a>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                </div>
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
                    <?php if(isset($breadcrumb)): ?>
                    <div class="breadcrumb">
                        <?php echo $breadcrumb; ?>

                    </div>
                    <?php endif; ?>
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
                    <div class="header-avatar">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                    </div>
                </div>
            </div>
        </header>

        
        <main class="main-content">
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
</html><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/components/admin-layout.blade.php ENDPATH**/ ?>