<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Login' }} | Mauli Infra Plotted Developments</title>
    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --navy-900: #0f1f2c;
            --navy-800: #142a3b;
            --teal-700: #115e7a;
            --teal-600: #176b87;
            --gold-600: #c9933b;
            --gold-500: #d59d45;
            --slate-100: #f4f7fa;
            --slate-200: #e2e8f0;
            --slate-600: #475569;
        }
        body {
            background: linear-gradient(135deg, #0f1f2c 0%, #173b52 100%);
            font-family: 'Poppins', sans-serif;
            color: #1e293b;
            min-height: 100vh;
        }
        .auth-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }
        .auth-card {
            width: min(100%, 450px);
            background: #ffffff;
            border: 0;
            border-radius: 16px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }
        .auth-header {
            background: #0f1f2c;
            padding: 28px 24px 22px;
            text-align: center;
            border-bottom: 2px solid var(--gold-500);
        }
        .brand-title {
            color: #ffffff;
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: .08em;
            margin-bottom: 2px;
        }
        .brand-subtitle {
            color: #94a3b8;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: .05em;
            text-transform: uppercase;
        }
        .btn-brand {
            background: var(--teal-600);
            border-color: var(--teal-600);
            color: #ffffff;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .btn-brand:hover {
            background: #0e4e64;
            border-color: #0e4e64;
            color: #ffffff;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px 14px;
            border: 1px solid var(--slate-200);
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: var(--teal-600);
            box-shadow: 0 0 0 3px rgba(23, 107, 135, 0.15);
        }
    </style>
</head>
<body>
    <main class="auth-shell">
        <section class="card auth-card">
            <div class="auth-header">
                <div class="brand-title">MAULI INFRA</div>
                <div class="brand-subtitle">Plotted Developments CMS</div>
            </div>
            <div class="card-body p-4 p-md-5">
                @if (session('status'))
                    <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 mb-4 rounded-3 small">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger py-2 px-3 mb-4 rounded-3 small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </section>
    </main>
</body>
</html>
