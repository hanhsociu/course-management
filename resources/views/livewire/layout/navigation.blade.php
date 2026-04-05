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

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
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
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
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
                <div class="space-x-4">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 underline" wire:navigate>Đăng nhập</a>

                    <a href="{{ route('register') }}"
                        class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150"
                        wire:navigate>
                        Đăng ký thành viên
                    </a>
                </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
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

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
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

        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
            <div class="px-4">
                <div class="font-medium text-base text-gray-800"
                    x-data="{{ json_encode(['name' => auth()->user()?->name ?? '']) }}" x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()?->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
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
            <div class="px-4 py-2 space-y-2">
                <a href="{{ route('login') }}" class="block text-base font-medium text-gray-600" wire:navigate>Đăng
                    nhập</a>
                <a href="{{ route('register') }}" class="block text-base font-medium text-indigo-600" wire:navigate>Đăng
                    ký</a>
            </div>
            @endauth
        </div>
    </div>
</nav>