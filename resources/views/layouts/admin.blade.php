<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Workspace' }} | Mauli Infra Plotted Developments</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --cms-navy: #111827;
            --cms-navy-dark: #0b0f19;
            --cms-navy-card: #1f2937;
            --cms-orange: #ea580c;
            --cms-orange-hover: #c2410c;
            --cms-orange-light: #fff7ed;
            --cms-orange-border: #fed7aa;
            --cms-bg: #f4f6f8;
            --cms-card: #ffffff;
            --cms-border: #e5e7eb;
            --cms-text-main: #1f2937;
            --cms-text-muted: #64748b;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--cms-bg);
            color: var(--cms-text-main);
            min-height: 100vh;
            font-size: 0.88rem;
        }

        /* Sidebar Styling (Desktop) */
        .admin-sidebar-desktop {
            width: var(--sidebar-width);
            background-color: var(--cms-navy);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1020;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Offcanvas Sidebar (Mobile) */
        .offcanvas-admin-sidebar {
            width: 270px !important;
            background-color: var(--cms-navy);
        }

        /* Sidebar Navigation Links */
        .sidebar-menu-wrapper .nav-link {
            color: #9ca3af;
            font-size: 0.84rem;
            font-weight: 400;
            padding: 8px 14px;
            border-radius: 6px;
            margin: 2px 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.15s ease-in-out;
        }

        .sidebar-menu-wrapper .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.06);
        }

        .sidebar-menu-wrapper .nav-link.active {
            color: #ffffff;
            background-color: var(--cms-orange);
            font-weight: 500;
        }

        .sidebar-menu-wrapper .nav-link.active i {
            color: #ffffff;
        }

        .sidebar-submenu {
            padding-left: 28px;
        }

        .sidebar-submenu .nav-link {
            font-size: 0.8rem;
            padding: 6px 12px;
            color: #94a3b8;
        }

        .sidebar-submenu .nav-link:hover {
            color: #ffffff;
        }

        .sidebar-submenu .nav-link.active {
            color: var(--cms-orange);
            background-color: transparent;
            font-weight: 600;
        }

        /* Main Workspace Wrapper */
        .admin-main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar-desktop {
                display: none;
            }
            .admin-main-wrapper {
                margin-left: 0;
            }
        }

        /* Commercial CMS Cards & Panels */
        .card-cms, .card-panel {
            background: #ffffff;
            border: 1px solid var(--cms-border, #e2e8f0);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-panel-header, .card-header-panel {
            padding: 16px 20px;
            background-color: #f8fafc;
            border-bottom: 1px solid var(--cms-border, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .card-panel-header h1,
        .card-panel-header h2,
        .card-panel-header h3,
        .card-panel-header h4,
        .card-panel-header h5,
        .card-panel-header h6 {
            margin-bottom: 0;
            font-weight: 700;
            font-size: 0.95rem;
            color: #1e293b;
            display: flex;
            align-items: center;
        }

        .card-panel-header small,
        .card-panel-header .text-muted {
            font-size: 0.8rem;
            color: #64748b;
            display: block;
            margin-top: 3px;
        }

        .card-panel > .card-body {
            padding: 20px;
        }

        .text-brand {
            color: var(--cms-orange, #fe5c0d) !important;
        }

        .metric-card {
            background: #ffffff;
            border: 1px solid var(--cms-border);
            border-radius: 8px;
            padding: 18px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .metric-card:hover {
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        }

        /* Commercial CMS Action Buttons */
        .btn-orange {
            background-color: var(--cms-orange);
            border-color: var(--cms-orange);
            color: #ffffff;
            font-weight: 500;
            font-size: 0.84rem;
            padding: 7px 16px;
            border-radius: 6px;
            transition: all 0.15s;
        }

        .btn-orange:hover {
            background-color: var(--cms-orange-hover);
            border-color: var(--cms-orange-hover);
            color: #ffffff;
        }

        .btn-action-edit {
            width: 32px;
            height: 32px;
            background-color: #fff1f2;
            color: #e11d48;
            border: none;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-action-edit:hover {
            background-color: #ffe4e6;
            color: #be123c;
            transform: scale(1.05);
        }

        .btn-action-delete {
            width: 32px;
            height: 32px;
            background-color: #fef2f2;
            color: #ef4444;
            border: none;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
            transition: all 0.15s;
        }

        .btn-action-delete:hover {
            background-color: #fee2e2;
            color: #b91c1c;
            transform: scale(1.05);
        }

        /* Clean CMS Table Format */
        .table-cms {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table-cms th {
            font-size: 0.76rem;
            font-weight: 600;
            color: var(--cms-text-muted);
            background-color: #ffffff;
            border-bottom: 1px solid var(--cms-border);
            padding: 14px 16px;
        }

        .table-cms td {
            font-size: 0.86rem;
            color: var(--cms-text-main);
            border-bottom: 1px solid #f1f5f9;
            padding: 14px 16px;
            vertical-align: middle;
        }

        .table-cms tr:hover td {
            background-color: #fafbfd;
        }

        .table-cms tr:last-child td {
            border-bottom: none;
        }

        /* Custom Scrollbar for Sidebar */
        .admin-sidebar-desktop::-webkit-scrollbar,
        .offcanvas-admin-sidebar .offcanvas-body::-webkit-scrollbar {
            width: 4px;
        }
        .admin-sidebar-desktop::-webkit-scrollbar-thumb,
        .offcanvas-admin-sidebar .offcanvas-body::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.15);
            border-radius: 2px;
        }

        /* Global Toast Feedback */
        #cmsToast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 420px;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--cms-border);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-20px) scale(0.95);
            transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s;
            pointer-events: none;
        }

        #cmsToast.show-toast {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Desktop Sidebar -->
    <aside class="admin-sidebar-desktop">
        @include('admin.partials.sidebar')
    </aside>

    <!-- Mobile Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start offcanvas-admin-sidebar p-0 text-white" tabindex="-1" id="adminSidebarOffcanvas" aria-labelledby="adminSidebarOffcanvasLabel">
        <div class="offcanvas-body p-0 d-flex flex-column h-100">
            @include('admin.partials.sidebar')
        </div>
    </div>

    <!-- Main Workspace Container -->
    <div class="admin-main-wrapper">
        @include('admin.partials.topbar')

        <main class="flex-grow-1 p-3 p-md-4 p-xl-5">
            <div class="container-fluid px-0">
                @include('admin.partials.alerts')
                @yield('content')
            </div>
        </main>

        <footer class="bg-white border-top py-3 px-4 text-center text-muted small mt-auto">
            <span>&copy; {{ date('Y') }} Mauli Infra. Real-Estate Plotted Development System. All rights reserved.</span>
        </footer>
    </div>

    <!-- Global Media Picker Modal -->
    @include('admin.partials.media-modal')

    <!-- Global Delete Confirmation Modal -->
    @include('admin.partials.delete-modal')

    <!-- Toast Notification Container -->
    <div id="cmsToast" class="p-3">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div id="cmsToastIcon" class="rounded-circle p-2 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                    <i class="bi bi-check2 fs-5"></i>
                </div>
                <div>
                    <div id="cmsToastTitle" class="fw-bold text-dark small">Success</div>
                    <div id="cmsToastMessage" class="text-muted small">Action completed successfully</div>
                </div>
            </div>
            <button type="button" class="btn-close ms-2 small flex-shrink-0" onclick="hideCmsToast()" aria-label="Close"></button>
        </div>
    </div>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let cmsToastTimeout = null;

        window.hideCmsToast = function() {
            const toast = document.getElementById('cmsToast');
            if (!toast) return;
            toast.classList.remove('show-toast');
            if (cmsToastTimeout) {
                clearTimeout(cmsToastTimeout);
                cmsToastTimeout = null;
            }
        };

        window.showCmsToast = function(message, isSuccess = true, title = null) {
            const toast = document.getElementById('cmsToast');
            if (!toast) return;
            const toastTitle = document.getElementById('cmsToastTitle');
            const toastMsg = document.getElementById('cmsToastMessage');
            const toastIcon = document.getElementById('cmsToastIcon');

            if (toastTitle) toastTitle.textContent = title || (isSuccess ? 'Success' : 'Notice');
            if (toastMsg) toastMsg.textContent = message;

            if (toastIcon) {
                if (isSuccess) {
                    toastIcon.className = 'rounded-circle p-2 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0';
                    toastIcon.innerHTML = '<i class="bi bi-check2 fs-5"></i>';
                } else {
                    toastIcon.className = 'rounded-circle p-2 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0';
                    toastIcon.innerHTML = '<i class="bi bi-exclamation-triangle fs-5"></i>';
                }
            }

            if (cmsToastTimeout) {
                clearTimeout(cmsToastTimeout);
            }

            toast.classList.add('show-toast');

            cmsToastTimeout = setTimeout(() => {
                hideCmsToast();
            }, 3500);
        };

        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                window.showCmsToast("{{ addslashes(session('success')) }}", true, "Success");
            });
        @endif
        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                window.showCmsToast("{{ addslashes(session('error')) }}", false, "Notice");
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
