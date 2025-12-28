{{-- SIAKAD Logo - Clean Academic Design --}}
@php
    $pt = \App\Models\PerguruanTinggi::getInstance();
    $hasLogo = $pt->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($pt->logo_path);
    $logoUrl = $hasLogo ? \Illuminate\Support\Facades\Storage::url($pt->logo_path) : null;
@endphp

@if($hasLogo)
    <img src="{{ $logoUrl }}" alt="Logo" {{ $attributes->merge(['class' => 'object-contain']) }}>
@else
    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
        {{-- Graduation Cap Base --}}
        <path d="M20 6L2 14L20 22L38 14L20 6Z" fill="currentColor" fill-opacity="0.9"/>
        
        {{-- Cap Top --}}
        <path d="M8 16V26C8 26 14 32 20 32C26 32 32 26 32 26V16L20 24L8 16Z" fill="currentColor" fill-opacity="0.7"/>
        
        {{-- Tassel Line --}}
        <path d="M32 14V28" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        
        {{-- Tassel End --}}
        <circle cx="32" cy="30" r="2" fill="currentColor"/>
    </svg>
@endif
