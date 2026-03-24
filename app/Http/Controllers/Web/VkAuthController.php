<?php

namespace App\Http\Controllers\Web;

use App\Application\Auth\Services\VkOAuthService;
use App\Http\Controllers\Controller;
use App\Support\AdvertisementCreator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class VkAuthController extends Controller
{
    private const STATE_KEY = 'vk_oauth_state';
    private const SESSION_KEY = 'pending_advertisement';

    public function __construct(
        private readonly VkOAuthService $vkOAuthService,
        private readonly AdvertisementCreator $creator,
    ) {
    }

    public function redirect(Request $request): RedirectResponse
    {
        try {
            $state = bin2hex(random_bytes(16));
            $request->session()->put(self::STATE_KEY, $state);

            return redirect()->away($this->vkOAuthService->buildAuthorizationUrl($state));
        } catch (Throwable $e) {
            Log::warning('VK redirect failed: '.$e->getMessage());

            return redirect()->route('login')->with('status', 'Не удалось начать авторизацию через VK.');
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        $storedState = (string) $request->session()->pull(self::STATE_KEY, '');
        $incomingState = (string) $request->query('state', '');
        $code = (string) $request->query('code', '');

        if ($storedState === '' || $incomingState === '' || ! hash_equals($storedState, $incomingState)) {
            return redirect()->route('login')->with('status', 'Ошибка авторизации через VK: некорректный state.');
        }

        if ($code === '') {
            return redirect()->route('login')->with('status', 'VK не вернул код авторизации.');
        }

        try {
            $user = $this->vkOAuthService->authenticateByCode($code);
        } catch (Throwable $e) {
            Log::warning('VK login failed: '.$e->getMessage());

            return redirect()->route('login')->with('status', 'Не удалось выполнить вход через VK.');
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        $pendingAdvertisement = $request->session()->pull(self::SESSION_KEY);
        if (is_array($pendingAdvertisement)) {
            $this->creator->create($user->id, $pendingAdvertisement);

            return redirect()
                ->route('catalog.show', ['id' => $pendingAdvertisement['catalog_id']])
                ->with('status', 'Вход через VK выполнен, объявление добавлено.');
        }

        return redirect()->route('home')->with('status', 'Вы вошли через VK.');
    }
}
