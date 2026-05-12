@extends('layouts.dashboard')
@section('title', 'Tambah Kelas')
@section('page-title', '➕ Tambah Kelas Baru')
@section('page-subtitle', 'Buat kelas untuk tahun ajaran tertentu')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')
<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Form Data Kelas</span></div>
    <div class="panel-body">
        <form method="POST" action="{{ route('admin.kelas.store') }}">@csrf @include('admin.kelas._form')</form>
    </div>
</div>
@endsection
