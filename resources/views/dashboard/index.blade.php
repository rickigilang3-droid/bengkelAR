@extends('layouts.app')

@section('title', 'Dashboard - Bengkel AR')

@section('content')
<div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-2xl rounded-3xl bg-white shadow-xl ring-1 ring-slate-200 p-10 text-center">
        <h1 class="text-3xl font-semibold text-slate-900">Dashboard</h1>
        <p class="mt-4 text-slate-600">Halaman dashboard belum tersedia secara langsung. Silakan masuk kembali atau gunakan menu utama untuk mengakses halaman Anda.</p>
        <a href="{{ route('login') }}" class="mt-6 inline-flex rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">Kembali ke Login</a>
    </div>
</div>
@endsection
