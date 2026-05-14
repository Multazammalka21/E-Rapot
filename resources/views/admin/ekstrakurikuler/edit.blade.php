@extends('layouts.dashboard')
@section('title', 'Edit Ekstrakurikuler')
@section('page-title', '✏️ Edit Ekstrakurikuler')
@section('page-subtitle', 'Ubah data ekstrakurikuler')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')
<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Form Ekstrakurikuler</span></div>
    <div class="panel-body">
        <form method="POST" action="{{ route('admin.ekstrakurikuler.update', $ekstrakurikuler) }}">
            @csrf @method('PUT')
            @include('admin.ekstrakurikuler._form')
        </form>
    </div>
</div>
@endsection
