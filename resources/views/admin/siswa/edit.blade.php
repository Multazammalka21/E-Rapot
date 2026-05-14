@extends('layouts.dashboard')
@section('title', 'Edit Siswa')
@section('page-title', '✏️ Edit Data Siswa')
@section('page-subtitle', "Perbarui data {$siswa->nama_lengkap}")
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')
<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Edit Data Siswa</span></div>
    <div class="panel-body">
        <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}">@csrf @method('PUT') @include('admin.siswa._form')</form>
    </div>
</div>
@endsection
