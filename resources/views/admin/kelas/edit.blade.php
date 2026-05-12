@extends('layouts.dashboard')
@section('title', 'Edit Kelas')
@section('page-title', '✏️ Edit Kelas {{ $kelas->nama_kelas }}')
@section('page-subtitle', 'Perbarui data kelas')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')
<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Edit Data Kelas</span></div>
    <div class="panel-body">
        <form method="POST" action="{{ route('admin.kelas.update', $kelas) }}">@csrf @method('PUT') @include('admin.kelas._form')</form>
    </div>
</div>
@endsection
