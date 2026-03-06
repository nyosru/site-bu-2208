<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginForm extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login(): mixed
    {
        $credentials = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ]);

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $credentials['remember'])) {
            $this->addError('email', 'Неверный email или пароль.');

            return null;
        }

        request()->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.login-form');
    }
}
