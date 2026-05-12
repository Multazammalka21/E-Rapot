@extends('layouts.dashboard')
@section('title', 'Tambah Siswa')
@section('page-title', '➕ Tambah Siswa Baru')
@section('page-subtitle', 'Isi data siswa dan opsional buat akun login')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')
<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Form Data Siswa</span></div>
    <div class="panel-body">
        <form method="POST" action="{{ route('admin.siswa.store') }}">@csrf @include('admin.siswa._form')</form>
    </div>
</div>
@endsection
