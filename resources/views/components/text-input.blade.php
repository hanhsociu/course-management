@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-xl border-slate-200 bg-white/90 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20']) }}>
