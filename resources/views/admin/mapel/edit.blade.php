@extends('layouts.dashboard')
@section('title', 'Edit Mata Pelajaran')
@section('page-title', '✏️ Edit Mata Pelajaran')
@section('page-subtitle', 'Perbarui data {{ $mapel->nama_mapel }}')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')
<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Edit Mata Pelajaran</span></div>
    <div class="panel-body">
        <form method="POST" action="{{ route('admin.mapel.update', $mapel) }}">
            @csrf @method('PUT')
            @include('admin.mapel._form')
        </form>
    </div>
</div>
@endsection
