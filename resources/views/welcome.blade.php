<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mauli Properties | Plotted Developments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --ink: #142b38; --teal: #176b87; --gold: #d59d45; }
        body { color: var(--ink); font-family: Arial, sans-serif; }
        .hero { min-height: 72vh; background: linear-gradient(115deg, rgba(20,43,56,.96), rgba(23,107,135,.72)), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1800&q=85') center/cover; }
        .brand { letter-spacing: .08em; font-weight: 800; }
        .btn-gold { background: var(--gold); border-color: var(--gold); color: #fff; }
        .btn-gold:hover { background: #b98231; border-color: #b98231; color: #fff; }
        .stat { border-left: 3px solid var(--gold); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark position-absolute w-100 z-3">
        <div class="container py-3">
            <a class="navbar-brand brand" href="{{ url('/') }}">MAULI PROPERTIES</a>
            <div class="d-flex gap-3 align-items-center">
                <a class="text-white text-decoration-none" href="{{ route('login') }}">Admin Login</a>
                <a class="btn btn-gold px-3" href="{{ route('register') }}">Get Started</a>
            </div>
        </div>
    </nav>
    <header class="hero text-white d-flex align-items-center">
        <div class="container py-5">
            <div class="col-lg-7">
                <p class="text-uppercase fw-semibold mb-3" style="color: #f3c66e; letter-spacing: .16em;">Thoughtfully planned. Built for tomorrow.</p>
                <h1 class="display-3 fw-bold">Find a better place to build your future.</h1>
                <p class="lead mt-4 mb-4">Discover well-connected plotted developments designed around open spaces, strong infrastructure, and long-term value.</p>
                <a class="btn btn-gold btn-lg px-4" href="{{ route('register') }}">Explore Opportunities</a>
            </div>
        </div>
    </header>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4"><div class="stat ps-3"><div class="display-6 fw-bold">25+</div><div class="text-muted">Planned developments</div></div></div>
                <div class="col-md-4"><div class="stat ps-3"><div class="display-6 fw-bold">10 yrs</div><div class="text-muted">Building trusted relationships</div></div></div>
                <div class="col-md-4"><div class="stat ps-3"><div class="display-6 fw-bold">100%</div><div class="text-muted">Focused on clear ownership</div></div></div>
            </div>
        </div>
    </section>
    <footer class="py-4 text-center text-muted">© {{ date('Y') }} Mauli Properties. All rights reserved.</footer>
</body>
</html>
