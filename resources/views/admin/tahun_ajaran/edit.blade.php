@extends('layouts.dashboard')
@section('title', 'Edit Tahun Ajaran')
@section('page-title', '✏️ Edit Tahun Ajaran')
@section('page-subtitle', 'Ubah data tahun ajaran')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')
<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Form Tahun Ajaran</span></div>
    <div class="panel-body">
        <form method="POST" action="{{ route('admin.tahun-ajaran.update', $tahun_ajaran) }}">
            @csrf @method('PUT')
            @include('admin.tahun_ajaran._form')
        </form>
    </div>
</div>
@endsection
