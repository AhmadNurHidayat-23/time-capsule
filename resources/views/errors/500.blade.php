@extends('layouts.app')
@section('title','Terjadi kendala. — Time Capsule')
@section('content')
<section class="hero">
<p class="eyebrow">TIME CAPSULE / 500</p>
<h1>Terjadi kendala.</h1>
<p class="subtitle">Pesan tidak dapat diproses saat ini. Silakan coba kembali nanti.</p>
<a href="{{ route('home') }}">← Kembali ke halaman utama</a>
</section>
@endsection
