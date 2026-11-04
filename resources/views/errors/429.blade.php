@extends('layouts.app')
@section('title','Terlalu banyak permintaan. — Time Capsule')
@section('content')
<section class="hero">
<p class="eyebrow">TIME CAPSULE / 429</p>
<h1>Terlalu banyak permintaan.</h1>
<p class="subtitle">Tunggu sebentar sebelum mencoba kembali.</p>
<a href="{{ route('home') }}">← Kembali ke halaman utama</a>
</section>
@endsection
