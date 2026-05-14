@extends('layouts.dashboard')
@section('title', 'Tambah Ekstrakurikuler')
@section('page-title', '➕ Tambah Ekstrakurikuler')
@section('page-subtitle', 'Tambah kegiatan ekstrakurikuler baru')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')
<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Form Ekstrakurikuler</span></div>
    <div class="panel-body">
        <form method="POST" action="{{ route('admin.ekstrakurikuler.store') }}">
            @csrf
            @include('admin.ekstrakurikuler._form')
        </form>
    </div>
</div>
@endsection
