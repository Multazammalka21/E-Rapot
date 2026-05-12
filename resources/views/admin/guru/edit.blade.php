@extends('layouts.dashboard')
@section('title', 'Edit Guru')
@section('page-title', '✏️ Edit Data Guru')
@section('page-subtitle', 'Perbarui data {{ $guru->nama_lengkap }}')

@section('sidebar-nav')
@include('admin.partials.sidebar')
@endsection

@section('content')
@include('components.alerts')
<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Edit Data Guru</span></div>
    <div class="panel-body">
        <form method="POST" action="{{ route('admin.guru.update', $guru) }}">
            @csrf @method('PUT')
            @include('admin.guru._form')
        </form>
    </div>
</div>
@endsection
