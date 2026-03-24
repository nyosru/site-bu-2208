<?php

namespace App\Application\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class VkOAuthService
{
    private const OAUTH_AUTHORIZE_URL = 'https://oauth.vk.com/authorize';
    private const OAUTH_ACCESS_TOKEN_URL = 'https://oauth.vk.com/access_token';
    private const API_USERS_GET_URL = 'https://api.vk.com/method/users.get';

    private readonly string $clientId;
    private readonly string $clientSecret;
    private readonly string $redirectUri;
    private readonly string $apiVersion;

    public function __construct()
    {
        $this->clientId = (string) Config::get('services.vk.client_id', '');
        $this->clientSecret = (string) Config::get('services.vk.client_secret', '');
        $this->redirectUri = (string) Config::get('services.vk.redirect_uri', '');
        $this->apiVersion = (string) Config::get('services.vk.api_version', '5.199');

        if ($this->clientId === '' || $this->clientSecret === '' || $this->redirectUri === '') {
            throw new InvalidArgumentException('VK OAuth credentials are not configured.');
        }
    }

    public function buildAuthorizationUrl(string $state): string
    {
        return self::OAUTH_AUTHORIZE_URL.'?'.http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'state' => $state,
            'v' => $this->apiVersion,
        ]);
    }

    public function authenticateByCode(string $code): User
    {
        $oauthResponse = Http::get(self::OAUTH_ACCESS_TOKEN_URL, [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'code' => $code,
        ]);

        if ($oauthResponse->failed()) {
            throw new RuntimeException('VK OAuth request failed with status '.$oauthResponse->status().'.');
        }

        $oauthData = $oauthResponse->json();
        if (isset($oauthData['error'])) {
            $description = (string) ($oauthData['error_description'] ?? $oauthData['error']);
            throw new RuntimeException('VK OAuth error: '.$description);
        }

        $vkUserId = (int) ($oauthData['user_id'] ?? 0);
        $accessToken = (string) ($oauthData['access_token'] ?? '');

        if ($vkUserId <= 0 || $accessToken === '') {
            throw new RuntimeException('VK OAuth returned an unexpected payload.');
        }

        $profileResponse = Http::get(self::API_USERS_GET_URL, [
            'user_ids' => $vkUserId,
            'fields' => 'photo_200',
            'access_token' => $accessToken,
            'v' => $this->apiVersion,
        ]);

        if ($profileResponse->failed()) {
            throw new RuntimeException('VK users.get request failed with status '.$profileResponse->status().'.');
        }

        $profilePayload = $profileResponse->json();
        if (isset($profilePayload['error'])) {
            $errorCode = $profilePayload['error']['error_code'] ?? 'unknown';
            $errorMessage = $profilePayload['error']['error_msg'] ?? 'Unknown VK API error.';
            throw new RuntimeException('VK API error '.$errorCode.': '.$errorMessage);
        }

        $profile = $profilePayload['response'][0] ?? null;
        if (! is_array($profile)) {
            throw new RuntimeException('VK users.get returned an empty profile.');
        }

        $name = trim((string) (($profile['first_name'] ?? '').' '.($profile['last_name'] ?? '')));
        if ($name === '') {
            $name = 'VK User '.$vkUserId;
        }

        $email = trim((string) ($oauthData['email'] ?? ''));
        if ($email === '') {
            $email = $this->generateVkEmail($vkUserId);
        }

        $userByVk = User::query()->where('vk_id', $vkUserId)->first();
        if ($userByVk !== null) {
            if ($userByVk->name === '') {
                $userByVk->name = $name;
                $userByVk->save();
            }

            return $userByVk;
        }

        $userByEmail = User::query()->where('email', $email)->first();
        if ($userByEmail !== null) {
            if ($userByEmail->vk_id !== null && (int) $userByEmail->vk_id !== $vkUserId) {
                throw new RuntimeException('Email is already linked to another VK account.');
            }

            $userByEmail->vk_id = $vkUserId;
            $userByEmail->name = $userByEmail->name !== '' ? $userByEmail->name : $name;
            $userByEmail->save();

            return $userByEmail;
        }

        return User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(Str::random(40)),
            'vk_id' => $vkUserId,
            'email_verified_at' => now(),
        ]);
    }

    private function generateVkEmail(int $vkUserId): string
    {
        return $vkUserId.'@vk.com';
    }
}
