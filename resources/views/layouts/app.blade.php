<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="referrer" content="no-referrer">
<meta name="robots" content="noindex, nofollow">
<title>@yield('title','Time Capsule — Pesan untuk Masa Depan')</title>
<meta name="description" content="Tulis pesan hari ini, simpan dengan aman, dan buka saat waktunya tiba.">
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
<div class="shell">
<header>
<a class="brand" href="{{ route('home') }}">
<span class="brand-symbol" aria-hidden="true">↗</span> Time Capsule<span class="edition"> / 01</span>
</a>
<span class="header-note">DITULIS HARI INI. DIBUKA NANTI.</span>
<a class="header-link" href="{{ route('home') }}">Tulis pesan <span>+</span>
</a>
</header>
<main>@yield('content')</main>
<footer>
<span>Time Capsule / Arsip untuk esok</span>
<span>Setiap pesan punya waktunya.</span>
<span>Zona waktu: Asia/Jakarta</span>
</footer>
</div>
</body>
</html>
