<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>TaskFlow — Smart Task Manager</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:       #5b4cff;
            --primary-dark:  #4338ca;
            --primary-light: #ede9ff;
            --accent:        #ff6b6b;
            --success:       #22c55e;
            --warning:       #f59e0b;
            --danger:        #ef4444;
            --info:          #3b82f6;
            --bg:            #f5f4ff;
            --surface:       #ffffff;
            --surface-2:     #f8f7ff;
            --border:        #e5e3ff;
            --text:          #1e1b4b;
            --text-muted:    #6b7280;
            --shadow-sm:     0 1px 3px rgba(91,76,255,.08);
            --shadow-md:     0 4px 20px rgba(91,76,255,.12);
            --shadow-lg:     0 10px 40px rgba(91,76,255,.16);
            --radius:        14px;
            --radius-sm:     8px;
            --font-display:  'Syne', sans-serif;
            --font-body:     'DM Sans', sans-serif;
        }

        * { box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            margin: 0;
        }

        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: var(--font-display);
        }

        /* ── Sidebar ── */
        #sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform .3s ease;
        }

        .sidebar-logo {
            padding: 24px 24px 16px;
            font-family: var(--font-display);
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.5px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo .logo-icon {
            width: 36px; height: 36px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }

        .nav-section-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            padding: 8px 12px 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all .18s;
            border: none; background: none; width: 100%; text-align: left;
            text-decoration: none;
        }

        .sidebar-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .sidebar-link.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-link .badge-count {
            margin-left: auto;
            background: var(--primary);
            color: white;
            font-size: 0.7rem;
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 700;
        }

        .sidebar-link .badge-count.danger { background: var(--danger); }

        .sidebar-user {
            padding: 16px;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: var(--radius-sm);
            background: var(--surface-2);
        }

        .user-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--primary);
        }

        .user-info { flex: 1; min-width: 0; }
        .user-name { font-weight: 700; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-email { font-size: 0.72rem; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* ── Main Content ── */
        #main-content {
            margin-left: 260px;
            min-height: 100vh;
            padding: 0;
        }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-title {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.3rem;
            margin-right: auto;
        }

        .content-area {
            padding: 28px;
        }

        /* ── Stat Cards ── */
        .stat-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 22px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 14px;
        }

        .stat-value {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label { font-size: 0.82rem; color: var(--text-muted); font-weight: 500; }

        /* ── Task Cards ── */
        .task-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 18px 20px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: all .2s;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .task-card::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 4px;
            border-radius: 4px 0 0 4px;
        }

        .task-card.priority-urgent::before { background: var(--danger); }
        .task-card.priority-high::before   { background: var(--warning); }
        .task-card.priority-medium::before { background: var(--info); }
        .task-card.priority-low::before    { background: var(--success); }

        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
        }

        .task-card.status-done {
            opacity: 0.65;
        }

        .task-card.status-done .task-title {
            text-decoration: line-through;
        }

        .task-card.overdue {
            border-color: rgba(239,68,68,.3);
            background: rgba(239,68,68,.02);
        }

        .task-title {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .task-description {
            font-size: 0.82rem;
            color: var(--text-muted);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .task-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .task-tag {
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 20px;
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        .badge-priority-urgent { background: rgba(239,68,68,.12); color: var(--danger); font-weight: 700; }
        .badge-priority-high   { background: rgba(245,158,11,.12); color: var(--warning); font-weight: 700; }
        .badge-priority-medium { background: rgba(59,130,246,.12); color: var(--info); font-weight: 700; }
        .badge-priority-low    { background: rgba(34,197,94,.12); color: var(--success); font-weight: 700; }

        .badge-status-todo        { background: #f3f4f6; color: #6b7280; }
        .badge-status-in_progress { background: rgba(91,76,255,.12); color: var(--primary); }
        .badge-status-done        { background: rgba(34,197,94,.12); color: var(--success); }

        /* ── Buttons ── */
        .btn-primary-custom {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            padding: 10px 20px;
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 0.9rem;
            transition: all .2s;
            cursor: pointer;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(91,76,255,.3);
            color: white;
        }

        .btn-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-muted);
            cursor: pointer;
            transition: all .18s;
            font-size: 0.9rem;
        }

        .btn-icon:hover { background: var(--primary-light); color: var(--primary); border-color: var(--primary); }
        .btn-icon.danger:hover { background: rgba(239,68,68,.1); color: var(--danger); border-color: var(--danger); }

        /* ── Forms ── */
        .form-control, .form-select {
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: var(--font-body);
            font-size: 0.9rem;
            padding: 10px 14px;
            color: var(--text);
            background: var(--surface);
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(91,76,255,.15);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.82rem;
            color: var(--text);
            margin-bottom: 5px;
        }

        /* ── Modals ── */
        .modal-content {
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 20px 24px;
        }

        .modal-title {
            font-family: var(--font-display);
            font-weight: 800;
        }

        .modal-body { padding: 24px; }
        .modal-footer { border-top: 1px solid var(--border); padding: 16px 24px; }

        /* ── Auth Pages ── */
        #auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f4ff 0%, #ede9ff 100%);
        }

        .auth-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
        }

        .auth-logo {
            font-family: var(--font-display);
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            text-align: center;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 32px;
        }

        /* ── Alerts ── */
        .alert-custom {
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            font-size: 0.88rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-custom.success { background: rgba(34,197,94,.12); color: #166534; }
        .alert-custom.error   { background: rgba(239,68,68,.12); color: #991b1b; }
        .alert-custom.info    { background: rgba(91,76,255,.1); color: var(--primary-dark); }

        /* ── Search ── */
        .search-wrap { position: relative; }
        .search-wrap .bi-search {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        .search-wrap input { padding-left: 36px; }

        /* ── Filters bar ── */
        .filters-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .filter-chip {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface);
            color: var(--text-muted);
            cursor: pointer;
            transition: all .18s;
        }

        .filter-chip:hover, .filter-chip.active {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-icon {
            font-size: 3.5rem;
            margin-bottom: 16px;
            opacity: .5;
        }

        .empty-title {
            font-family: var(--font-display);
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        /* ── Loading ── */
        .spinner-wrap {
            display: flex; align-items: center; justify-content: center;
            padding: 60px;
        }

        .spinner-custom {
            width: 40px; height: 40px;
            border: 3px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Tooltips & misc ── */
        .due-date-text { font-size: 0.78rem; color: var(--text-muted); }
        .due-date-text.overdue { color: var(--danger); font-weight: 600; }
        .due-date-text.today   { color: var(--warning); font-weight: 600; }

        .progress-bar-custom {
            height: 6px;
            border-radius: 6px;
            background: var(--primary);
        }

        .progress-track {
            height: 6px;
            border-radius: 6px;
            background: var(--border);
            overflow: hidden;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #main-content { margin-left: 0; }
            .content-area { padding: 16px; }
        }

        /* ── Animations ── */
        .fade-in {
            animation: fadeIn .3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .task-card { animation: fadeIn .25s ease; }

        /* ── Checkbox ── */
        .task-check {
            width: 20px; height: 20px;
            border: 2px solid var(--border);
            border-radius: 6px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all .15s;
            flex-shrink: 0;
        }

        .task-check:hover { border-color: var(--primary); }
        .task-check.checked { background: var(--success); border-color: var(--success); color: white; font-size: 0.75rem; }

        /* Overdue pulse */
        @keyframes overduePulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: .6; }
        }
        .overdue-badge { animation: overduePulse 2s ease-in-out infinite; }
    </style>
</head>
<body>

<!-- AUTH VIEW -->
<div id="auth-view">
    <div id="auth-container">
        <div class="auth-card fade-in">
            <!-- Login Form -->
            <div id="login-form">
                <div class="auth-logo">⚡ TaskFlow</div>
                <p class="auth-subtitle">Sign in to manage your tasks</p>

                <div id="auth-alert" class="d-none"></div>

                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" id="login-email" class="form-control" placeholder="you@example.com" value="demo@taskmanager.com">
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" id="login-password" class="form-control" placeholder="Enter your password" value="password">
                        <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <button class="btn-primary-custom w-100 mb-3" id="btn-login">
                    <span id="login-text">Sign In</span>
                    <span id="login-spinner" class="d-none"><span class="spinner-border spinner-border-sm"></span></span>
                </button>
                <p class="text-center" style="font-size:.85rem; color:var(--text-muted);">
                    Don't have an account?
                    <a href="#" id="show-register" style="color:var(--primary);font-weight:600;">Create one</a>
                </p>
            </div>

            <!-- Register Form -->
            <div id="register-form" class="d-none">
                <div class="auth-logo">⚡ TaskFlow</div>
                <p class="auth-subtitle">Create your account</p>

                <div id="reg-alert" class="d-none"></div>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" id="reg-name" class="form-control" placeholder="Alex Johnson">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" id="reg-email" class="form-control" placeholder="you@example.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" id="reg-password" class="form-control" placeholder="Min 8 characters">
                </div>
                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" id="reg-password-confirm" class="form-control" placeholder="Repeat password">
                </div>
                <button class="btn-primary-custom w-100 mb-3" id="btn-register">
                    <span id="reg-text">Create Account</span>
                    <span id="reg-spinner" class="d-none"><span class="spinner-border spinner-border-sm"></span></span>
                </button>
                <p class="text-center" style="font-size:.85rem; color:var(--text-muted);">
                    Already have an account?
                    <a href="#" id="show-login" style="color:var(--primary);font-weight:600;">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- APP VIEW (hidden until logged in) -->
<div id="app-view" class="d-none">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon"><i class="bi bi-lightning-charge-fill"></i></div>
            TaskFlow
        </div>

        <div class="sidebar-nav">
            <div class="nav-section-label">Overview</div>
            <button class="sidebar-link active" data-view="dashboard">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </button>
            <button class="sidebar-link" data-view="all">
                <i class="bi bi-list-task"></i> All Tasks
                <span class="badge-count" id="nav-total">0</span>
            </button>

            <div class="nav-section-label mt-3">Status</div>
            <button class="sidebar-link" data-view="todo">
                <i class="bi bi-circle"></i> To Do
                <span class="badge-count" id="nav-todo">0</span>
            </button>
            <button class="sidebar-link" data-view="in_progress">
                <i class="bi bi-play-circle"></i> In Progress
                <span class="badge-count" id="nav-in-progress">0</span>
            </button>
            <button class="sidebar-link" data-view="done">
                <i class="bi bi-check-circle"></i> Completed
                <span class="badge-count" id="nav-done">0</span>
            </button>

            <div class="nav-section-label mt-3">Priority</div>
            <button class="sidebar-link" data-view="overdue">
                <i class="bi bi-exclamation-triangle-fill" style="color:var(--danger)"></i> Overdue
                <span class="badge-count danger" id="nav-overdue">0</span>
            </button>
            <button class="sidebar-link" data-view="due_today">
                <i class="bi bi-calendar-check" style="color:var(--warning)"></i> Due Today
                <span class="badge-count" id="nav-due-today">0</span>
            </button>
        </div>

        <div class="sidebar-user">
            <div class="user-card">
                <img class="user-avatar" id="user-avatar" src="" alt="avatar">
                <div class="user-info">
                    <div class="user-name" id="user-name">Loading...</div>
                    <div class="user-email" id="user-email">...</div>
                </div>
                <button class="btn-icon danger" id="btn-logout" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div id="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <button class="btn-icon d-md-none" id="sidebar-toggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="topbar-title" id="page-title">Dashboard</div>

            <!-- Search -->
            <div class="search-wrap" style="width:260px;">
                <i class="bi bi-search"></i>
                <input type="text" id="search-input" class="form-control" placeholder="Search tasks...">
            </div>

            <!-- Add Task Button -->
            <button class="btn-primary-custom d-flex align-items-center gap-2" id="btn-add-task">
                <i class="bi bi-plus-lg"></i> New Task
            </button>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Dashboard View -->
            <div id="view-dashboard">
                <!-- Stats Row -->
                <div class="row g-3 mb-4" id="stats-row">
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background:rgba(91,76,255,.1);color:var(--primary)">
                                <i class="bi bi-list-task"></i>
                            </div>
                            <div class="stat-value" id="stat-total">—</div>
                            <div class="stat-label">Total Tasks</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background:rgba(245,158,11,.1);color:var(--warning)">
                                <i class="bi bi-play-circle-fill"></i>
                            </div>
                            <div class="stat-value" id="stat-in-progress">—</div>
                            <div class="stat-label">In Progress</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background:rgba(34,197,94,.1);color:var(--success)">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="stat-value" id="stat-done">—</div>
                            <div class="stat-label">Completed</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background:rgba(239,68,68,.1);color:var(--danger)">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <div class="stat-value" id="stat-overdue">—</div>
                            <div class="stat-label">Overdue</div>
                        </div>
                    </div>
                </div>

                <!-- Completion Rate + Recent Tasks -->
                <div class="row g-3 mb-4">
                    <div class="col-lg-4">
                        <div class="stat-card h-100">
                            <h6 class="font-display fw-bold mb-3">Completion Rate</h6>
                            <div class="d-flex align-items-end gap-3 mb-3">
                                <div class="stat-value" id="stat-rate">0%</div>
                                <div style="font-size:.82rem;color:var(--text-muted);padding-bottom:4px">of all tasks done</div>
                            </div>
                            <div class="progress-track">
                                <div class="progress-bar-custom" id="progress-bar" style="width:0%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="stat-card h-100">
                            <h6 class="font-display fw-bold mb-3">Weekly Completions</h6>
                            <div id="weekly-chart" style="display:flex;align-items:flex-end;gap:8px;height:80px;"></div>
                            <div id="weekly-labels" style="display:flex;gap:8px;margin-top:6px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Tasks -->
                <h6 class="font-display fw-bold mb-3">Recent Tasks</h6>
                <div id="dashboard-tasks" class="row g-3"></div>
            </div>

            <!-- Tasks List View -->
            <div id="view-tasks" class="d-none">
                <div class="filters-bar">
                    <div class="search-wrap flex-grow-1" style="max-width:280px">
                        <i class="bi bi-search"></i>
                        <input type="text" id="list-search" class="form-control" placeholder="Filter tasks...">
                    </div>
                    <select class="form-select" id="filter-priority" style="width:auto">
                        <option value="">All Priorities</option>
                        <option value="urgent">🔴 Urgent</option>
                        <option value="high">🟠 High</option>
                        <option value="medium">🟡 Medium</option>
                        <option value="low">🟢 Low</option>
                    </select>
                    <select class="form-select" id="sort-by" style="width:auto">
                        <option value="created_at">Newest first</option>
                        <option value="due_date">Due date</option>
                        <option value="priority">Priority</option>
                        <option value="title">Title A–Z</option>
                    </select>
                    <button class="btn-icon" id="btn-refresh" title="Refresh">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>

                <!-- Bulk Actions -->
                <div id="bulk-actions" class="d-none mb-3 p-3 rounded-2 d-flex align-items-center gap-2" style="background:var(--primary-light);border:1px solid var(--border)">
                    <span id="bulk-count" style="font-size:.85rem;font-weight:600;color:var(--primary)">0 selected</span>
                    <div class="ms-auto d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" id="bulk-mark-done">Mark Done</button>
                        <button class="btn btn-sm btn-outline-danger" id="bulk-delete">Delete</button>
                        <button class="btn btn-sm btn-link" id="bulk-cancel">Cancel</button>
                    </div>
                </div>

                <div id="tasks-container" class="row g-3"></div>

                <!-- Pagination -->
                <div id="pagination-wrap" class="d-flex justify-content-center gap-2 mt-4"></div>
            </div>
        </div>
    </div>
</div>

<!-- Task Modal -->
<div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalTitle">New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="task-form-alert" class="d-none mb-3"></div>
                <input type="hidden" id="task-id">

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Title *</label>
                        <input type="text" id="task-title" class="form-control" placeholder="What needs to be done?">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea id="task-description" class="form-control" rows="3" placeholder="Add details, steps, or notes..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select id="task-status" class="form-select">
                            <option value="todo">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Priority</label>
                        <select id="task-priority" class="form-select">
                            <option value="low">🟢 Low</option>
                            <option value="medium" selected>🟡 Medium</option>
                            <option value="high">🟠 High</option>
                            <option value="urgent">🔴 Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Due Date</label>
                        <input type="datetime-local" id="task-due-date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            Email Reminder
                            <small class="text-muted fw-normal">(via Mailtrap)</small>
                        </label>
                        <input type="datetime-local" id="task-reminder" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tags</label>
                        <input type="text" id="task-tags" class="form-control" placeholder="e.g. frontend, bug, design (comma separated)">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-primary-custom" id="btn-save-task">
                    <span id="save-task-text">Save Task</span>
                    <span id="save-task-spinner" class="d-none"><span class="spinner-border spinner-border-sm"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Task Detail Modal -->
<div class="modal fade" id="taskDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="detail-title">Task Details</h5>
                    <div id="detail-badges" class="d-flex gap-2 mt-1"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="detail-description" class="text-muted mb-4"></p>
                <div class="row g-3 mb-3">
                    <div class="col-sm-4">
                        <div class="fw-bold" style="font-size:.8rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.8px">Due Date</div>
                        <div id="detail-due-date" style="font-size:.9rem;margin-top:4px">—</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="fw-bold" style="font-size:.8rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.8px">Reminder</div>
                        <div id="detail-reminder" style="font-size:.9rem;margin-top:4px">—</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="fw-bold" style="font-size:.8rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.8px">Created</div>
                        <div id="detail-created" style="font-size:.9rem;margin-top:4px">—</div>
                    </div>
                </div>
                <div id="detail-tags" class="d-flex gap-2 flex-wrap"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" id="detail-btn-edit"><i class="bi bi-pencil me-1"></i>Edit</button>
                <button class="btn btn-success" id="detail-btn-complete"><i class="bi bi-check-lg me-1"></i>Mark Complete</button>
                <button class="btn btn-outline-danger" id="detail-btn-delete"><i class="bi bi-trash me-1"></i>Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ═══════════════════════════════════════════════════
//  TaskFlow — Main Application Script
// ═══════════════════════════════════════════════════

const API = {
    base: '/api',
    token: null,

    headers() {
        const h = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
        if (this.token) h['Authorization'] = `Bearer ${this.token}`;
        return h;
    },

    async request(method, path, body = null) {
        const opts = { method, headers: this.headers() };
        if (body) opts.body = JSON.stringify(body);
        const res = await fetch(this.base + path, opts);
        const data = await res.json();
        if (!res.ok) throw { status: res.status, data };
        return data;
    },

    get:    (p)    => API.request('GET',    p),
    post:   (p, b) => API.request('POST',   p, b),
    put:    (p, b) => API.request('PUT',    p, b),
    delete: (p)    => API.request('DELETE', p),
};

// ── State ──
const state = {
    user: null,
    tasks: [],
    stats: {},
    currentView: 'dashboard',
    filters: { status: null, priority: '', overdue: false, due_today: false, search: '', page: 1, sort_by: 'created_at' },
    selectedTasks: new Set(),
    currentTaskDetail: null,
};

// ── Bootstrap Modals ──
const taskModal       = new bootstrap.Modal('#taskModal');
const taskDetailModal = new bootstrap.Modal('#taskDetailModal');

// ═══════════════════════════════════════════════════
//  Auth
// ═══════════════════════════════════════════════════
function showAlert(el, type, msg) {
    el.className = `alert-custom ${type} mb-3`;
    el.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${msg}`;
    el.classList.remove('d-none');
}

async function login() {
    const email    = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-password').value;
    const alert    = document.getElementById('auth-alert');
    const spinner  = document.getElementById('login-spinner');
    const text     = document.getElementById('login-text');

    if (!email || !password) { showAlert(alert, 'error', 'Please fill in all fields.'); return; }

    spinner.classList.remove('d-none');
    text.classList.add('d-none');

    try {
        const data = await API.post('/auth/login', { email, password });
        API.token = data.data.token;
        localStorage.setItem('taskflow_token', data.data.token);
        state.user = data.data.user;
        showApp();
    } catch (err) {
        showAlert(alert, 'error', err.data?.message || 'Login failed. Try demo@taskmanager.com / password');
    } finally {
        spinner.classList.add('d-none');
        text.classList.remove('d-none');
    }
}

async function register() {
    const name     = document.getElementById('reg-name').value.trim();
    const email    = document.getElementById('reg-email').value.trim();
    const password = document.getElementById('reg-password').value;
    const confirm  = document.getElementById('reg-password-confirm').value;
    const alert    = document.getElementById('reg-alert');
    const spinner  = document.getElementById('reg-spinner');
    const text     = document.getElementById('reg-text');

    if (!name || !email || !password) { showAlert(alert, 'error', 'Please fill in all fields.'); return; }
    if (password !== confirm) { showAlert(alert, 'error', 'Passwords do not match.'); return; }

    spinner.classList.remove('d-none');
    text.classList.add('d-none');

    try {
        const data = await API.post('/auth/register', { name, email, password, password_confirmation: confirm });
        API.token = data.data.token;
        localStorage.setItem('taskflow_token', data.data.token);
        state.user = data.data.user;
        showApp();
    } catch (err) {
        const errors = err.data?.errors;
        const msg = errors ? Object.values(errors).flat().join(' ') : err.data?.message || 'Registration failed.';
        showAlert(alert, 'error', msg);
    } finally {
        spinner.classList.add('d-none');
        text.classList.remove('d-none');
    }
}

async function logout() {
    try { await API.post('/auth/logout'); } catch {}
    API.token = null;
    localStorage.removeItem('taskflow_token');
    state.user = null;
    document.getElementById('app-view').classList.add('d-none');
    document.getElementById('auth-view').classList.remove('d-none');
    document.getElementById('login-form').classList.remove('d-none');
    document.getElementById('register-form').classList.add('d-none');
}

// ═══════════════════════════════════════════════════
//  App Init
// ═══════════════════════════════════════════════════
function showApp() {
    document.getElementById('auth-view').classList.add('d-none');
    document.getElementById('app-view').classList.remove('d-none');

    document.getElementById('user-name').textContent  = state.user.name;
    document.getElementById('user-email').textContent = state.user.email;
    document.getElementById('user-avatar').src        = state.user.avatar_url;

    navigateTo('dashboard');
    loadStats();
}

function navigateTo(view) {
    state.currentView = view;
    state.filters.status    = null;
    state.filters.overdue   = false;
    state.filters.due_today = false;
    state.filters.page      = 1;

    document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
    document.querySelector(`.sidebar-link[data-view="${view}"]`)?.classList.add('active');

    const titles = {
        dashboard: 'Dashboard', all: 'All Tasks', todo: 'To Do',
        in_progress: 'In Progress', done: 'Completed', overdue: 'Overdue', due_today: 'Due Today',
    };
    document.getElementById('page-title').textContent = titles[view] || view;

    if (view === 'dashboard') {
        document.getElementById('view-dashboard').classList.remove('d-none');
        document.getElementById('view-tasks').classList.add('d-none');
        loadDashboard();
    } else {
        document.getElementById('view-dashboard').classList.add('d-none');
        document.getElementById('view-tasks').classList.remove('d-none');

        if (view !== 'all') {
            if (view === 'overdue')   state.filters.overdue   = true;
            else if (view === 'due_today') state.filters.due_today = true;
            else state.filters.status = view;
        }
        loadTasks();
    }
}

// ═══════════════════════════════════════════════════
//  Stats
// ═══════════════════════════════════════════════════
async function loadStats() {
    try {
        const data = await API.get('/tasks/stats');
        state.stats = data.data;
        updateNavBadges(data.data);
    } catch {}
}

function updateNavBadges(s) {
    document.getElementById('nav-total').textContent      = s.total || 0;
    document.getElementById('nav-todo').textContent       = s.todo || 0;
    document.getElementById('nav-in-progress').textContent= s.in_progress || 0;
    document.getElementById('nav-done').textContent       = s.done || 0;
    document.getElementById('nav-overdue').textContent    = s.overdue || 0;
    document.getElementById('nav-due-today').textContent  = s.due_today || 0;
}

async function loadDashboard() {
    try {
        const data = await API.get('/tasks/stats');
        const s    = data.data;
        state.stats = s;
        updateNavBadges(s);

        document.getElementById('stat-total').textContent       = s.total;
        document.getElementById('stat-in-progress').textContent = s.in_progress;
        document.getElementById('stat-done').textContent        = s.done;
        document.getElementById('stat-overdue').textContent     = s.overdue;

        const rate = s.completion_rate || 0;
        document.getElementById('stat-rate').textContent        = rate + '%';
        document.getElementById('progress-bar').style.width     = rate + '%';

        renderWeeklyChart(s.weekly_completions || []);

        // Load recent tasks
        const tasksData = await API.get('/tasks?per_page=6&sort_by=created_at&sort_dir=desc');
        renderTaskCards(tasksData.data.tasks, 'dashboard-tasks');
    } catch (err) {
        console.error('Dashboard load error:', err);
    }
}

function renderWeeklyChart(data) {
    const chart  = document.getElementById('weekly-chart');
    const labels = document.getElementById('weekly-labels');
    if (!data.length) { chart.innerHTML = '<div style="color:var(--text-muted);font-size:.8rem">No data</div>'; return; }

    const max = Math.max(...data.map(d => d.count), 1);
    chart.innerHTML = data.map(d => {
        const h = Math.round((d.count / max) * 70) + 10;
        return `<div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px">
            <div style="font-size:.7rem;color:var(--text-muted)">${d.count}</div>
            <div style="width:100%;height:${h}px;background:var(--primary);border-radius:4px 4px 0 0;opacity:${d.count ? 1 : 0.2}"></div>
        </div>`;
    }).join('');

    labels.innerHTML = data.map(d =>
        `<div style="flex:1;text-align:center;font-size:.7rem;color:var(--text-muted)">${d.date}</div>`
    ).join('');
}

// ═══════════════════════════════════════════════════
//  Tasks
// ═══════════════════════════════════════════════════
async function loadTasks() {
    const container = document.getElementById('tasks-container');
    container.innerHTML = '<div class="col-12"><div class="spinner-wrap"><div class="spinner-custom"></div></div></div>';

    const params = new URLSearchParams({
        per_page: 12,
        page:     state.filters.page,
        sort_by:  state.filters.sort_by,
        sort_dir: 'desc',
    });

    if (state.filters.status)    params.set('status', state.filters.status);
    if (state.filters.priority)  params.set('priority', state.filters.priority);
    if (state.filters.search)    params.set('search', state.filters.search);
    if (state.filters.overdue)   params.set('overdue', '1');
    if (state.filters.due_today) params.set('due_today', '1');

    try {
        const data = await API.get('/tasks?' + params.toString());
        state.tasks = data.data.tasks;
        updateNavBadges(data.data.stats);

        renderTaskCards(data.data.tasks, 'tasks-container');
        renderPagination(data.data.pagination);
    } catch (err) {
        container.innerHTML = '<div class="col-12"><div class="alert-custom error">Failed to load tasks. Please try again.</div></div>';
    }
}

function renderTaskCards(tasks, containerId) {
    const container = document.getElementById(containerId);

    if (!tasks.length) {
        container.innerHTML = `
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <div class="empty-title">No tasks found</div>
                    <p>Create your first task to get started!</p>
                    <button class="btn-primary-custom" onclick="openCreateModal()">
                        <i class="bi bi-plus-lg me-1"></i> New Task
                    </button>
                </div>
            </div>`;
        return;
    }

    container.innerHTML = tasks.map(task => renderTaskCard(task)).join('');
}

function renderTaskCard(task) {
    const isOverdue  = task.is_overdue;
    const dueText    = task.due_date ? formatDueDate(task.due_date) : '';
    const tags       = (task.tags || []).slice(0, 3).map(t => `<span class="task-tag">${escHtml(t)}</span>`).join('');
    const isSelected = state.selectedTasks.has(task.id);

    return `
    <div class="col-md-6 col-xl-4">
        <div class="task-card priority-${task.priority} status-${task.status} ${isOverdue ? 'overdue' : ''}"
             data-task-id="${task.id}"
             onclick="showTaskDetail(${task.id})">
            <div class="d-flex align-items-start gap-2 mb-2">
                <div class="task-check ${task.status === 'done' ? 'checked' : ''}"
                     onclick="event.stopPropagation(); toggleTaskDone(${task.id})"
                     title="Mark complete">
                    ${task.status === 'done' ? '<i class="bi bi-check"></i>' : ''}
                </div>
                <div class="flex-grow-1">
                    <div class="task-title">${escHtml(task.title)}</div>
                </div>
                <div class="task-check" onclick="event.stopPropagation(); toggleSelect(${task.id})"
                     title="Select" style="border-style:dashed;${isSelected ? 'background:var(--primary);border-color:var(--primary);color:white' : ''}">
                    ${isSelected ? '<i class="bi bi-check"></i>' : ''}
                </div>
            </div>
            ${task.description ? `<div class="task-description">${escHtml(task.description)}</div>` : ''}
            <div class="task-meta">
                <span class="badge rounded-pill badge-priority-${task.priority}" style="font-size:.72rem">
                    ${task.priority.toUpperCase()}
                </span>
                <span class="badge rounded-pill badge-status-${task.status}" style="font-size:.72rem">
                    ${formatStatus(task.status)}
                </span>
                ${isOverdue ? '<span class="badge rounded-pill bg-danger text-white overdue-badge" style="font-size:.72rem">OVERDUE</span>' : ''}
                ${dueText ? `<span class="due-date-text ms-auto ${isOverdue ? 'overdue' : ''} ${isDueToday(task.due_date) ? 'today' : ''}">
                    <i class="bi bi-calendar2"></i> ${dueText}
                </span>` : ''}
            </div>
            ${tags ? `<div class="d-flex gap-1 flex-wrap mt-2">${tags}</div>` : ''}
            ${task.reminder_at && !task.reminder_sent ? `
                <div class="mt-2" style="font-size:.74rem;color:var(--text-muted)">
                    <i class="bi bi-bell"></i> Reminder set
                </div>` : ''}
        </div>
    </div>`;
}

function renderPagination(pagination) {
    const wrap = document.getElementById('pagination-wrap');
    if (!pagination || pagination.last_page <= 1) { wrap.innerHTML = ''; return; }

    let html = '';
    for (let i = 1; i <= pagination.last_page; i++) {
        html += `<button onclick="changePage(${i})"
            class="btn ${i === pagination.current_page ? 'btn-primary' : 'btn-outline-secondary'} btn-sm">
            ${i}
        </button>`;
    }
    wrap.innerHTML = html;
}

function changePage(page) {
    state.filters.page = page;
    loadTasks();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ═══════════════════════════════════════════════════
//  Task CRUD
// ═══════════════════════════════════════════════════
function openCreateModal() {
    document.getElementById('task-id').value          = '';
    document.getElementById('task-title').value       = '';
    document.getElementById('task-description').value = '';
    document.getElementById('task-status').value      = 'todo';
    document.getElementById('task-priority').value    = 'medium';
    document.getElementById('task-due-date').value    = '';
    document.getElementById('task-reminder').value    = '';
    document.getElementById('task-tags').value        = '';
    document.getElementById('task-form-alert').classList.add('d-none');
    document.getElementById('taskModalTitle').textContent = 'New Task';
    taskModal.show();
}

function openEditModal(task) {
    document.getElementById('task-id').value          = task.id;
    document.getElementById('task-title').value       = task.title;
    document.getElementById('task-description').value = task.description || '';
    document.getElementById('task-status').value      = task.status;
    document.getElementById('task-priority').value    = task.priority;
    document.getElementById('task-due-date').value    = task.due_date ? toLocalDateTime(task.due_date) : '';
    document.getElementById('task-reminder').value    = task.reminder_at ? toLocalDateTime(task.reminder_at) : '';
    document.getElementById('task-tags').value        = (task.tags || []).join(', ');
    document.getElementById('task-form-alert').classList.add('d-none');
    document.getElementById('taskModalTitle').textContent = 'Edit Task';
    taskDetailModal.hide();
    setTimeout(() => taskModal.show(), 200);
}

async function saveTask() {
    const id      = document.getElementById('task-id').value;
    const title   = document.getElementById('task-title').value.trim();
    const alert   = document.getElementById('task-form-alert');
    const spinner = document.getElementById('save-task-spinner');
    const text    = document.getElementById('save-task-text');

    if (!title) { showAlert(alert, 'error', 'Title is required.'); return; }

    const tags = document.getElementById('task-tags').value
        .split(',').map(t => t.trim()).filter(Boolean);

    const payload = {
        title,
        description: document.getElementById('task-description').value.trim() || null,
        status:      document.getElementById('task-status').value,
        priority:    document.getElementById('task-priority').value,
        due_date:    document.getElementById('task-due-date').value || null,
        reminder_at: document.getElementById('task-reminder').value || null,
        tags,
    };

    spinner.classList.remove('d-none');
    text.classList.add('d-none');

    try {
        if (id) {
            await API.put(`/tasks/${id}`, payload);
        } else {
            await API.post('/tasks', payload);
        }
        taskModal.hide();
        showToast(id ? 'Task updated!' : 'Task created!', 'success');
        refreshCurrentView();
    } catch (err) {
        const errors = err.data?.errors;
        const msg = errors ? Object.values(errors).flat().join(' ') : err.data?.message || 'Failed to save task.';
        showAlert(alert, 'error', msg);
    } finally {
        spinner.classList.add('d-none');
        text.classList.remove('d-none');
    }
}

async function deleteTask(id) {
    if (!confirm('Delete this task? This cannot be undone.')) return;
    try {
        await API.delete(`/tasks/${id}`);
        taskDetailModal.hide();
        showToast('Task deleted.', 'success');
        refreshCurrentView();
    } catch { showToast('Failed to delete task.', 'error'); }
}

async function toggleTaskDone(id) {
    const task = state.tasks.find(t => t.id === id);
    const newStatus = (task?.status === 'done') ? 'todo' : 'done';
    try {
        await API.put(`/tasks/${id}`, { status: newStatus });
        refreshCurrentView();
    } catch {}
}

function showTaskDetail(id) {
    const task = state.tasks.find(t => t.id === id);
    if (!task) return;
    state.currentTaskDetail = task;

    document.getElementById('detail-title').textContent = task.title;
    document.getElementById('detail-description').textContent = task.description || 'No description provided.';
    document.getElementById('detail-due-date').textContent  = task.due_date ? formatDateTime(task.due_date) : '—';
    document.getElementById('detail-reminder').textContent  = task.reminder_at ? formatDateTime(task.reminder_at) : '—';
    document.getElementById('detail-created').textContent   = formatDateTime(task.created_at);

    document.getElementById('detail-badges').innerHTML = `
        <span class="badge rounded-pill badge-priority-${task.priority}">${task.priority.toUpperCase()}</span>
        <span class="badge rounded-pill badge-status-${task.status}">${formatStatus(task.status)}</span>
        ${task.is_overdue ? '<span class="badge rounded-pill bg-danger">OVERDUE</span>' : ''}
    `;

    const tags = (task.tags || []).map(t => `<span class="task-tag">${escHtml(t)}</span>`).join('');
    document.getElementById('detail-tags').innerHTML = tags;

    const completeBtn = document.getElementById('detail-btn-complete');
    completeBtn.style.display = task.status === 'done' ? 'none' : '';

    taskDetailModal.show();
}

// ═══════════════════════════════════════════════════
//  Bulk Actions
// ═══════════════════════════════════════════════════
function toggleSelect(id) {
    if (state.selectedTasks.has(id)) state.selectedTasks.delete(id);
    else state.selectedTasks.add(id);

    const hasSel = state.selectedTasks.size > 0;
    document.getElementById('bulk-actions').classList.toggle('d-none', !hasSel);
    document.getElementById('bulk-count').textContent = `${state.selectedTasks.size} selected`;

    // Re-render just the card to reflect selection
    loadTasks();
}

async function bulkUpdate(status) {
    if (!state.selectedTasks.size) return;
    try {
        await API.post('/tasks/bulk/update', { task_ids: [...state.selectedTasks], status });
        state.selectedTasks.clear();
        document.getElementById('bulk-actions').classList.add('d-none');
        showToast(`${state.selectedTasks.size} tasks updated.`, 'success');
        refreshCurrentView();
    } catch {}
}

async function bulkDelete() {
    if (!state.selectedTasks.size) return;
    if (!confirm(`Delete ${state.selectedTasks.size} task(s)?`)) return;
    try {
        await API.post('/tasks/bulk/delete', { task_ids: [...state.selectedTasks] });
        state.selectedTasks.clear();
        document.getElementById('bulk-actions').classList.add('d-none');
        showToast('Tasks deleted.', 'success');
        refreshCurrentView();
    } catch {}
}

// ═══════════════════════════════════════════════════
//  Helpers
// ═══════════════════════════════════════════════════
function refreshCurrentView() {
    if (state.currentView === 'dashboard') loadDashboard();
    else { loadTasks(); loadStats(); }
}

function formatStatus(s) {
    return { todo: 'To Do', in_progress: 'In Progress', done: 'Done' }[s] || s;
}

function formatDueDate(dt) {
    const d = new Date(dt);
    const now = new Date();
    const diff = Math.ceil((d - now) / 86400000);
    if (diff < 0)  return `${Math.abs(diff)}d overdue`;
    if (diff === 0) return 'Today';
    if (diff === 1) return 'Tomorrow';
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
}

function formatDateTime(dt) {
    return new Date(dt).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function isDueToday(dt) {
    if (!dt) return false;
    const d = new Date(dt), now = new Date();
    return d.toDateString() === now.toDateString();
}

function toLocalDateTime(isoStr) {
    const d = new Date(isoStr);
    return new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
}

function escHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function showToast(msg, type = 'info') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position:fixed;bottom:24px;right:24px;z-index:9999;
        background:${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--danger)' : 'var(--primary)'};
        color:white;padding:12px 20px;border-radius:10px;font-size:.88rem;font-weight:600;
        box-shadow:0 4px 20px rgba(0,0,0,.2);animation:fadeIn .3s ease;
    `;
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// ═══════════════════════════════════════════════════
//  Event Listeners
// ═══════════════════════════════════════════════════
document.getElementById('btn-login').addEventListener('click', login);
document.getElementById('btn-register').addEventListener('click', register);
document.getElementById('btn-logout').addEventListener('click', logout);
document.getElementById('btn-add-task').addEventListener('click', openCreateModal);
document.getElementById('btn-save-task').addEventListener('click', saveTask);
document.getElementById('btn-refresh').addEventListener('click', () => loadTasks());

document.getElementById('show-register').addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('login-form').classList.add('d-none');
    document.getElementById('register-form').classList.remove('d-none');
});

document.getElementById('show-login').addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('register-form').classList.add('d-none');
    document.getElementById('login-form').classList.remove('d-none');
});

document.getElementById('toggle-password').addEventListener('click', () => {
    const inp = document.getElementById('login-password');
    inp.type  = inp.type === 'password' ? 'text' : 'password';
});

// Sidebar navigation
document.querySelectorAll('.sidebar-link[data-view]').forEach(btn => {
    btn.addEventListener('click', () => navigateTo(btn.dataset.view));
});

// Search
let searchTimer;
document.getElementById('search-input').addEventListener('input', e => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        state.filters.search = e.target.value;
        if (state.currentView !== 'dashboard') loadTasks();
    }, 400);
});

// Filter selects
document.getElementById('filter-priority')?.addEventListener('change', e => {
    state.filters.priority = e.target.value;
    loadTasks();
});

document.getElementById('sort-by')?.addEventListener('change', e => {
    state.filters.sort_by = e.target.value;
    loadTasks();
});

// Task detail buttons
document.getElementById('detail-btn-edit').addEventListener('click', () => {
    if (state.currentTaskDetail) openEditModal(state.currentTaskDetail);
});

document.getElementById('detail-btn-complete').addEventListener('click', async () => {
    if (state.currentTaskDetail) {
        await toggleTaskDone(state.currentTaskDetail.id);
        taskDetailModal.hide();
    }
});

document.getElementById('detail-btn-delete').addEventListener('click', () => {
    if (state.currentTaskDetail) deleteTask(state.currentTaskDetail.id);
});

// Bulk actions
document.getElementById('bulk-mark-done').addEventListener('click', () => bulkUpdate('done'));
document.getElementById('bulk-delete').addEventListener('click', bulkDelete);
document.getElementById('bulk-cancel').addEventListener('click', () => {
    state.selectedTasks.clear();
    document.getElementById('bulk-actions').classList.add('d-none');
    loadTasks();
});

// Mobile sidebar toggle
document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('open');
});

// Enter key on login
document.getElementById('login-password').addEventListener('keydown', e => {
    if (e.key === 'Enter') login();
});

// ═══════════════════════════════════════════════════
//  Auto-login from localStorage
// ═══════════════════════════════════════════════════
(async function init() {
    const saved = localStorage.getItem('taskflow_token');
    if (!saved) return;

    API.token = saved;
    try {
        const data = await API.get('/auth/profile');
        state.user = data.data;
        showApp();
    } catch {
        API.token = null;
        localStorage.removeItem('taskflow_token');
    }
})();
</script>
</body>
</html>
<?php /**PATH C:\Users\SAMSUNG\OneDrive\Desktop\taskflow\resources\views/app.blade.php ENDPATH**/ ?>