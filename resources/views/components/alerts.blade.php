{{-- Reusable Form Components for E-Rapot Admin --}}

{{-- Alert success/error --}}
@if(session('success'))
<div class="alert alert-success" style="background:#ecfdf5;border:1px solid #a7f3d0;color:#047857;border-radius:12px;padding:0.85rem 1rem;font-size:0.875rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.75rem;font-weight:500;box-shadow:var(--shadow)">
    <i data-lucide="check-circle" style="width:18px;height:18px"></i>
    {{ session('success') }}
</div>
@endif

@if(isset($errors) && $errors->any())
<div class="alert alert-error" style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;border-radius:12px;padding:0.85rem 1rem;font-size:0.875rem;margin-bottom:1.5rem;display:flex;flex-direction:column;gap:0.5rem;font-weight:500;box-shadow:var(--shadow)">
    <div style="display:flex;align-items:center;gap:0.75rem">
        <i data-lucide="alert-circle" style="width:18px;height:18px"></i>
        <span>Terjadi beberapa kesalahan:</span>
    </div>
    <ul style="list-style:none;padding:0;margin:0;margin-left:2rem;font-size:0.8rem;opacity:0.9">
        @foreach($errors->all() as $error)
            <li>• {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
