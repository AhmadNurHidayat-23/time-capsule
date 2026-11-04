@extends('layouts.app')
@section('title','Sesi telah berakhir. — Time Capsule')
@section('content')
<section class="hero">
<p class="eyebrow">TIME CAPSULE / 419</p>
<h1>Sesi telah berakhir.</h1>
<p class="subtitle">Kembali ke halaman utama dan kirim ulang pesan Anda.</p>
<a href="{{ route('home') }}">← Kembali ke halaman utama</a>
</section>
@endsection
