<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AdvertisementCreator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    private const SESSION_KEY = 'pending_advertisement';

    public function __construct(
        private readonly AdvertisementCreator $creator,
    ) {
    }

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $pendingAdvertisement = $request->session()->pull(self::SESSION_KEY);
        if (is_array($pendingAdvertisement)) {
            $this->creator->create($user->id, $pendingAdvertisement);

            return redirect()
                ->route('catalog.show', ['id' => $pendingAdvertisement['catalog_id']])
                ->with('status', 'Регистрация завершена, объявление добавлено.');
        }

        return redirect()->route('home')->with('status', 'Регистрация завершена.');
    }
}
