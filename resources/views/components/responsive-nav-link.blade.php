@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-xl px-3 py-2.5 text-start text-base font-semibold text-indigo-800 bg-indigo-50 ring-1 ring-indigo-100 transition'
            : 'block w-full rounded-xl px-3 py-2.5 text-start text-base font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
