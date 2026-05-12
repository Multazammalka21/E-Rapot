@extends('layouts.dashboard')
@section('title', 'Tambah Mata Pelajaran')
@section('page-title', '➕ Tambah Mata Pelajaran')
@section('page-subtitle', 'Tambah mapel Kurikulum Merdeka Fase D')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')
<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Form Mata Pelajaran</span></div>
    <div class="panel-body">
        <form method="POST" action="{{ route('admin.mapel.store') }}">
            @csrf
            @include('admin.mapel._form')
        </form>
    </div>
</div>
@endsection
