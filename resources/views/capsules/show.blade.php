@extends('layouts.app')
@section('title', $capsule['title'].' — Time Capsule')
@section('content')
<section class="hero capsule-heading">
<p class="eyebrow">{{ $capsule['unlocked'] ? '03 / WAKTUNYA TELAH TIBA' : '02 / MENUNGGU WAKTU' }}</p>
<h1>{{ $capsule['title'] }}</h1>
<p class="subtitle">{{ $capsule['unlocked'] ? 'Kapsul waktu telah terbuka.' : 'Pesan ini belum dapat dibuka.' }}</p>
</section>
@if($capsule['unlocked'])
<section class="reading-grid">
<aside class="message-meta">
<p class="eyebrow">CATATAN WAKTU</p>
<dl>
<dt>Ditulis pada</dt>
<dd>{{ $capsule['createdLabel'] }}</dd>
<dt>Dapat dibuka sejak</dt>
<dd>{{ $capsule['opensLabel'] }}</dd>
</dl>
<span class="open-label">● Kapsul terbuka</span>
</aside>
<article class="letter" aria-label="Isi pesan">
<p class="eyebrow">SEBUAH PESAN DARI MASA LALU</p>
<div class="message-body">{{ $capsule['body'] }}</div>
<div class="letter-end" aria-hidden="true">↗</div>
</article>
</section>
@else
<section class="locked-grid">
<div>
<div class="section-heading">
<p class="eyebrow">AKAN TERBUKA PADA</p>
<span>Asia/Jakarta</span>
</div>
<p class="opening-date">{{ $capsule['opensLabel'] }}</p>
<div id="countdown" data-server-now="{{ $capsule['serverNow'] }}" data-opens-at="{{ $capsule['opensAt'] }}" data-status-url="{{ $statusUrl }}" role="timer" aria-label="Waktu menuju pembukaan pesan">
<div>
<strong data-days>—</strong>
<span>Hari</span>
</div>
<div>
<strong data-hours>—</strong>
<span>Jam</span>
</div>
<div>
<strong data-minutes>—</strong>
<span>Menit</span>
</div>
<div>
<strong data-seconds>—</strong>
<span>Detik</span>
</div>
</div>
<p class="countdown-note" id="clock-status" role="status">Mengikuti waktu server. Pesan akan terbuka otomatis saat waktunya tiba.</p>
<noscript>
<p>JavaScript tidak aktif. Muat ulang halaman setelah waktu pembukaan untuk membaca pesan.</p>
</noscript>
</div>
<aside class="side-note">
<p class="eyebrow">BELUM WAKTUNYA.</p>
<h2>Pesan ini sedang menunggu Anda.</h2>
<p>Tak perlu terburu-buru. Isi pesan tetap tersimpan dan tidak dimuat ke halaman ini sebelum waktunya.</p>
<p>Anda dapat menutup halaman dan kembali melalui tautan yang sama nanti.</p>
<span class="waiting-symbol" aria-hidden="true">↗</span>
</aside>
</section>
@endif
<div class="back-row">
<a href="{{ route('home') }}">← Kembali ke halaman utama</a>
<span>Time Capsule / Setiap pesan punya waktunya.</span>
</div>
@endsection
