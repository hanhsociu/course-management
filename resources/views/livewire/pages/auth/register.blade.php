<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'student';

        event(new Registered($user = User::create($validated)));
        Auth::login($user);
        $this->redirect(route('catalog', absolute: false), navigate: true);
    }
}; ?>

<div class="p-4">
    <h4 class="text-center mb-4">Đăng ký tài khoản</h4>

    <form wire:submit="register">
        <div class="form-group">
            <label for="name">Họ tên</label>
            <input wire:model="name" id="name" type="text" class="form-control form-control-sm" required autofocus>
            @error('name')<label class="error d-block">{{ $message }}</label>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input wire:model="email" id="email" type="email" class="form-control form-control-sm" required>
            @error('email')<label class="error d-block">{{ $message }}</label>@enderror
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input wire:model="password" id="password" type="password" class="form-control form-control-sm" required>
            @error('password')<label class="error d-block">{{ $message }}</label>@enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Xác nhận mật khẩu</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" class="form-control form-control-sm" required>
            @error('password_confirmation')<label class="error d-block">{{ $message }}</label>@enderror
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-lg btn-block login-page-button" wire:loading.attr="disabled">Đăng ký</button>
        </div>

        <p class="text-center small mt-3 mb-0">
            Đã có tài khoản? <a href="{{ route('login') }}" wire:navigate>Đăng nhập</a>
        </p>
    </form>
</div>
