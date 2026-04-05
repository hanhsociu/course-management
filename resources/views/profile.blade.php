<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">Tài khoản</p>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                {{ __('Profile') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-6 shadow-soft ring-1 ring-slate-100 sm:p-8">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-6 shadow-soft ring-1 ring-slate-100 sm:p-8">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-6 shadow-soft ring-1 ring-slate-100 sm:p-8">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
