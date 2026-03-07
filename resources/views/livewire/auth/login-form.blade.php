<div class="auth-card">
    <h2>Вход</h2>

    @if(session('status'))
        <p class="status-text">{{ session('status') }}</p>
    @endif

    <form wire:submit="login" class="auth-form">
        <label>
            <span>Email</span>
            <input type="email" wire:model="email" autocomplete="email" required>
            @error('email')
                <small class="error-text">{{ $message }}</small>
            @enderror
        </label>

        <label>
            <span>Пароль</span>
            <input type="password" wire:model="password" autocomplete="current-password" required>
            @error('password')
                <small class="error-text">{{ $message }}</small>
            @enderror
        </label>

        <label class="remember-label">
            <input type="checkbox" wire:model="remember">
            <span>Запомнить меня</span>
        </label>

        <button type="submit">Войти</button>
    </form>

    <p class="muted-text">Нет аккаунта? <a href="{{ route('register') }}">Регистрация</a></p>
</div>
