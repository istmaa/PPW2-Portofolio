@props([
    'title',
    'value',
    'subtitle' => null,
    'color' => 'blue',
    'href' => null,
])

@php
    $allowedColors = ['blue', 'green', 'amber', 'slate', 'indigo', 'rose'];
    $colorClass = in_array($color, $allowedColors, true) ? 'stat-card-' . $color : 'stat-card-blue';
@endphp

@if ($href)
    <a href="{{ $href }}" class="stat-card {{ $colorClass }} stat-card-link">
        <span class="stat-card-title">{{ $title }}</span>
        <span class="stat-card-value">{{ $value }}</span>
        @if ($subtitle)
            <span class="stat-card-subtitle">{{ $subtitle }}</span>
        @endif
    </a>
@else
    <div class="stat-card {{ $colorClass }}">
        <span class="stat-card-title">{{ $title }}</span>
        <span class="stat-card-value">{{ $value }}</span>
        @if ($subtitle)
            <span class="stat-card-subtitle">{{ $subtitle }}</span>
        @endif
    </div>
@endif
