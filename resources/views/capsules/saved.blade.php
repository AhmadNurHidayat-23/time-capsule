@extends('layouts.app')
@section('title','Pesan tersimpan — Time Capsule')
@section('content')
<section class="hero">
<p class="eyebrow">02 / PESAN TERSIMPAN</p>
<h1>Sampai bertemu nanti.</h1>
<p class="subtitle">Pesan Anda telah disimpan dengan aman.</p>
</section>
<section class="confirmation-grid">
<div>
<div class="confirmation-mark" aria-hidden="true">↗</div>
<h2>{{ $title }}</h2>
<p>Waktu pembukaan</p>
<p class="opening-date">{{ $opensLabel }}</p>
<div class="link-block">
<label for="share-link">Tautan unik pesan Anda</label>
<input id="share-link" value="{{ $url }}" readonly spellcheck="false">
<button class="button" type="button" id="copy-link">Salin tautan <span>↗</span>
</button>
<p id="copy-status" role="status" aria-live="polite">Simpan tautan ini. Tidak ada akun atau daftar pesan untuk menemukannya kembali.</p>
</div>
<a class="text-link" href="{{ $url }}">Lihat kapsul waktu →</a>
</div>
<aside class="side-note">
<p class="eyebrow">SATU TAUTAN. SATU PESAN.</p>
<h2>Tetap terkunci sampai waktunya.</h2>
<p>Isi pesan hanya akan ditampilkan setelah waktu yang ditentukan, berdasarkan jam server.</p>
<p>Jangan bagikan tautan jika pesan bersifat pribadi. Pemilik tautan dapat membacanya setelah kapsul terbuka.</p>
<a href="{{ route('home') }}">Tulis pesan lain →</a>
</aside>
</section>
@endsection
