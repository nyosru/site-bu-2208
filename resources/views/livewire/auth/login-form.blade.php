<div class="auth-card">
    @php
        $hasVkLoginConfig = filled(config('services.vk.client_id'))
            && filled(config('services.vk.client_secret'))
            && filled(config('services.vk.redirect_uri'));
    @endphp

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

    @if($hasVkLoginConfig)
        <a href="{{ route('auth.vk.redirect') }}" class="vk-login-btn">Войти через ВК</a>
    @elseif(config('app.debug'))
        <p class="error-text">добавте пааметры для входа через вк</p>
    @endif

    <p class="muted-text">Нет аккаунта? <a href="{{ route('register') }}">Регистрация</a></p>
</div>
