@extends('layouts.dashboard')
@section('title', 'Tambah Guru')
@section('page-title', '➕ Tambah Guru Baru')
@section('page-subtitle', 'Isi data guru dan buat akun login')

@section('sidebar-nav')
@include('admin.partials.sidebar')
@endsection

@section('content')
@include('components.alerts')
<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Form Data Guru</span></div>
    <div class="panel-body">
        <form method="POST" action="{{ route('admin.guru.store') }}">
            @csrf
            @include('admin.guru._form')
        </form>
    </div>
</div>
@endsection
