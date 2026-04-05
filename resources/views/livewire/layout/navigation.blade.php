<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/75 backdrop-blur-lg supports-[backdrop-filter]:bg-white/65">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate class="rounded-lg p-1 -m-1 transition hover:bg-slate-100/80">
                        <x-application-logo class="block h-9 w-auto fill-current text-indigo-600" />
                    </a>
                </div>

                <div class="hidden items-center gap-1 sm:ms-10 sm:flex">
                    @auth
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->isAdmin())
                    <x-nav-link :href="route('admin.courses')"
                        :active="request()->routeIs('admin.courses') || request()->routeIs('admin.courses.lessons')"
                        wire:navigate>
                        {{ __('Khóa học') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')" wire:navigate>
                        {{ __('Người dùng') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.enrollments')"
                        :active="request()->routeIs('admin.enrollments')" wire:navigate>
                        {{ __('Đăng ký') }}
                    </x-nav-link>
                    @else
                    <x-nav-link :href="route('my-courses')" :active="request()->routeIs('my-courses')" wire:navigate>
                        {{ __('Khóa học của tôi') }}
                    </x-nav-link>
                    @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200/90 bg-slate-50/80 px-3 py-1.5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
                            <div x-data="{{ json_encode(['name' => auth()->user()?->name ?? 'User']) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
                @else
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}"
                        class="rounded-full px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                        wire:navigate>Đăng nhập</a>

                    <a href="{{ route('register') }}"
                        class="inline-flex items-center rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-indigo-500/25 transition hover:bg-indigo-700 hover:shadow-md hover:shadow-indigo-500/30 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        wire:navigate>
                        Đăng ký
                    </a>
                </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-200/80 bg-white/95 backdrop-blur-md sm:hidden">
        <div class="space-y-0.5 px-2 py-3">
            @auth
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if(auth()->user()->isAdmin())
            <x-responsive-nav-link :href="route('admin.courses')"
                :active="request()->routeIs('admin.courses') || request()->routeIs('admin.courses.lessons')"
                wire:navigate>
                {{ __('Khóa học') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')"
                wire:navigate>
                {{ __('Người dùng') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.enrollments')"
                :active="request()->routeIs('admin.enrollments')" wire:navigate>
                {{ __('Đăng ký') }}
            </x-responsive-nav-link>
            @else
            <x-responsive-nav-link :href="route('my-courses')" :active="request()->routeIs('my-courses')"
                wire:navigate>
                {{ __('Khóa học của tôi') }}
            </x-responsive-nav-link>
            @endif
            @endauth
        </div>

        <div class="border-t border-slate-200/80 px-2 py-4">
            @auth
            <div class="px-3 pb-3">
                <div class="text-base font-semibold text-slate-900"
                    x-data="{{ json_encode(['name' => auth()->user()?->name ?? '']) }}" x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="text-sm text-slate-500">{{ auth()->user()?->email }}</div>
            </div>

            <div class="space-y-0.5">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
            @else
            <div class="flex flex-col gap-2 px-3">
                <a href="{{ route('login') }}" class="block rounded-xl px-3 py-2.5 text-base font-medium text-slate-700 hover:bg-slate-100" wire:navigate>Đăng nhập</a>
                <a href="{{ route('register') }}" class="block rounded-xl bg-indigo-600 px-3 py-2.5 text-center text-base font-semibold text-white shadow-sm" wire:navigate>Đăng ký</a>
            </div>
            @endauth
        </div>
    </div>
</nav>