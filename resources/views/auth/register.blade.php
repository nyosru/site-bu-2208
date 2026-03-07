@extends('layouts.app')

@section('content')
    <section class="auth-card-wrap">
        <div class="auth-card">
            <h2>Регистрация</h2>

            @if(session('status'))
                <p class="status-text">{{ session('status') }}</p>
            @endif

            <form action="{{ route('register.store') }}" method="POST" class="auth-form">
                @csrf

                <label>
                    <span>Имя</span>
                    <input type="text" name="name" value="{{ old('name') }}" required maxlength="255">
                    @error('name')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </label>

                <label>
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email">
                    @error('email')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </label>

                <label>
                    <span>Пароль</span>
                    <input type="password" name="password" required minlength="8" autocomplete="new-password">
                    @error('password')
                        <small class="error-text">{{ $message }}</small>
                    @enderror
                </label>

                <label>
                    <span>Повторите пароль</span>
                    <input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">
                </label>

                <button type="submit">Зарегистрироваться</button>
            </form>
        </div>
    </section>
@endsection
