<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('catalog', absolute: false), navigate: true);
    }
}; ?>

<div class="p-4">
    <h4 class="text-center mb-4">Đăng nhập</h4>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form wire:submit="login">
        <div class="form-group">
            <label for="email">Email</label>
            <input wire:model="form.email" id="email" type="email" class="form-control form-control-sm"
                placeholder="Email" required autofocus autocomplete="username">
            @error('form.email')<label class="error d-block">{{ $message }}</label>@enderror
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input wire:model="form.password" id="password" type="password" class="form-control form-control-sm"
                placeholder="Mật khẩu" required autocomplete="current-password">
            @error('form.password')<label class="error d-block">{{ $message }}</label>@enderror
        </div>

        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input wire:model="form.remember" type="checkbox" class="custom-control-input" id="remember">
                <label class="custom-control-label" for="remember">Ghi nhớ đăng nhập</label>
            </div>
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="float-right forgot-text small" wire:navigate>Quên mật khẩu?</a>
            @endif
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-lg btn-block login-page-button" wire:loading.attr="disabled">
                <span wire:loading.remove>Đăng nhập</span>
                <span wire:loading>Đang xử lý...</span>
            </button>
        </div>
    </form>
</div>
