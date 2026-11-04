@extends('layouts.app')
@section('title','Tautan tidak ditemukan. — Time Capsule')
@section('content')
<section class="hero">
<p class="eyebrow">TIME CAPSULE / 404</p>
<h1>Tautan tidak ditemukan.</h1>
<p class="subtitle">Periksa kembali tautan kapsul waktu yang Anda gunakan.</p>
<a href="{{ route('home') }}">← Kembali ke halaman utama</a>
</section>
@endsection
