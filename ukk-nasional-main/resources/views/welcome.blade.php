<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang - Pengaduan Prasarana Sekolah</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body {
            background-color: #F8F9FA;
            color: var(--primary);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-image: 
                radial-gradient(at 0% 0%, rgba(119, 141, 169, 0.05) 0, transparent 50%),
                radial-gradient(at 100% 100%, rgba(27, 38, 59, 0.05) 0, transparent 50%);
        }

        .hero {
            text-align: center;
            max-width: 900px;
            padding: 4rem;
            animation: fadeIn 0.8s ease-out;
        }

        .logo-large {
            height: 160px;
            margin-bottom: 2rem;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
            letter-spacing: -0.01em;
        }

        p {
            font-size: 1.25rem;
            color: var(--text-muted);
            margin-bottom: 3rem;
            line-height: 1.6;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-group {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
        }

        .btn-large {
            padding: 1rem 3rem;
            font-size: 1.1rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .btn-primary-classic {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 20px rgba(27, 38, 59, 0.15);
        }

        .btn-primary-classic:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-outline-classic {
            background: white;
            color: var(--primary);
            border: 1px solid var(--border);
        }

        .btn-outline-classic:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        footer {
            margin-top: 4rem;
            font-size: 0.9rem;
            color: var(--text-muted);
        }
    </style>
</head>

<body>
    <div class="hero">
        <img src="{{ asset('images/logo-student.png') }}" alt="Logo Student" class="logo-large">
        <h1>Pengaduan Prasarana Sekolah</h1>
        <p>Solusi pelaporan infrastruktur sekolah dengan pendekatan moderen dan profesional. Keamanan dan transparansi untuk kenyamanan belajar Anda.</p>

        <div class="cta-group">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-large btn-primary-classic">Masuk ke Dasbor</a>
            @else
                <a href="{{ route('login') }}" class="btn-large btn-primary-classic">Masuk</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-large btn-outline-classic">Daftar Akun</a>
                @endif
            @endauth
        </div>

        <footer>
            &copy; {{ date('Y') }} Pengaduan Prasarana Sekolah. Professional Excellence.
        </footer>
    </div>
</body>

</html>