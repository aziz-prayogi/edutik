@extends('templates.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 fw-bold text-danger">Admin Dashboard</h1>
    <p class="text-muted">Selamat datang, {{ Auth::user()->full_name ?? Auth::user()->username }}. Ini adalah ringkasan aktivitas moderasi Edutic.</p>
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session::get('succeess') }} <b>selamat datang, {{ Auth::user()->name }}</b>
            </div>

        @endif
        
    </div>

@endsection
