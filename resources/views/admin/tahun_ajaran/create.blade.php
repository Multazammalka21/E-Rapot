@extends('layouts.dashboard')
@section('title', 'Tambah Tahun Ajaran')
@section('page-title', '➕ Tambah Tahun Ajaran')
@section('page-subtitle', 'Buat tahun ajaran baru')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')
<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Form Tahun Ajaran</span></div>
    <div class="panel-body">
        <form method="POST" action="{{ route('admin.tahun-ajaran.store') }}">
            @csrf
            @include('admin.tahun_ajaran._form')
        </form>
    </div>
</div>
@endsection
