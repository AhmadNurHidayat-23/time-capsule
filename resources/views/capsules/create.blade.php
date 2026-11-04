@extends('layouts.app')
@section('content')
<section class="hero">
<p class="eyebrow">01 / SURAT UNTUK NANTI</p>
<h1>Pesan untuk Masa Depan.</h1>
<div class="hero-bottom">
<p>Tulis sekarang, buka pada waktunya.</p>
<span class="hero-index">SEKARANG → NANTI</span>
</div>
</section>
<section class="writing-grid">
<div class="form-column">
<div class="section-heading">
<span class="eyebrow">MULAI MENULIS</span>
<span>01 — 03</span>
</div>
@if($errors->any())<div class="notice" role="alert">
<strong>Periksa kembali pesan Anda.</strong>
<ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
<p>Demi privasi, isi pesan tidak disimpan pada formulir setelah validasi gagal.</p>
</div>@endif
<form action="{{ route('capsules.store') }}" method="POST" id="capsule-form">@csrf
<div class="field-heading">
<span>01</span>
<label for="title">Judul pesan</label>
<span>Maks. 120 karakter</span>
</div>
<input id="title" name="title" value="{{ old('title') }}" maxlength="120" required placeholder="Untuk diriku, suatu hari nanti" autocomplete="off">
<div class="field-heading">
<span>02</span>
<label for="body">Isi pesan</label>
<span id="character-count">0 / 20.000</span>
</div>
<textarea id="body" name="body" rows="8" maxlength="20000" required placeholder="Apa yang ingin kamu sampaikan kepada masa depan?" aria-describedby="privacy-note">
</textarea>
<p class="hint" id="privacy-note">Isi pesan dienkripsi dan tidak dikirim ke browser sebelum waktu pembukaan.</p>
<div class="field-heading">
<span>03</span>
<label for="opens_at">Tanggal dan waktu pembukaan</label>
</div>
<input id="opens_at" name="opens_at" type="datetime-local" value="{{ old('opens_at') }}" min="{{ $minimum }}" required aria-describedby="timezone-note">
<p class="hint" id="timezone-note">Gunakan waktu Asia/Jakarta (WIB / UTC+7). Pilih waktu di masa depan.</p>
<div class="submit-row">
<button class="button" type="submit">Simpan Pesan <span>↗</span>
</button>
<span>Setelah disimpan, pesan tidak dapat diubah.</span>
</div>
</form>
</div>
<aside class="editorial-side">
<div class="time-figure" aria-hidden="true">
<span class="figure-top">HARI INI</span>
<div class="hourglass">
<div>
</div>
<div>
</div>
</div>
<span class="figure-bottom">SUATU HARI NANTI</span>
</div>
<p class="eyebrow">BIARKAN WAKTU MENJAGA.</p>
<h2>Beberapa hal lebih berarti saat dibuka nanti.</h2>
<p>Sebuah harapan, pengingat kecil, atau cerita tentang hari ini. Simpan untuk dirimu di masa depan.</p>
<ol class="steps">
<li>
<span>01</span>Tulis sesuatu yang berarti.</li>
<li>
<span>02</span>Tentukan waktu pembukaan.</li>
<li>
<span>03</span>Simpan tautan uniknya.</li>
</ol>
<p class="side-footnote">Siapa pun yang memiliki tautan dapat membaca pesan setelah waktunya tiba. Simpan dan bagikan dengan bijak.</p>
</aside>
</section>
@endsection
