{{-- Reusable Form Components for E-Rapot Admin --}}

{{-- Alert success/error --}}
@if(session('success'))
<div class="alert alert-success" style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);color:#6ee7b7;border-radius:10px;padding:0.75rem 1rem;font-size:0.85rem;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem">
    ✅ {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="alert alert-error" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;border-radius:10px;padding:0.75rem 1rem;font-size:0.85rem;margin-bottom:1rem">
    <ul style="list-style:none;padding:0;margin:0">
        @foreach($errors->all() as $error)
            <li>⚠️ {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
