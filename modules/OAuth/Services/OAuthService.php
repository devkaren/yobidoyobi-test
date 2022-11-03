<?php

namespace Modules\OAuth\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Modules\OAuth\Dto\OAuthGoogleDto;
use Modules\OAuth\Dto\OAuthFacebookDto;
use Modules\OAuth\Dto\OAuthPasswordDto;
use Infrastructure\Eloquent\Models\User;
use Modules\OAuth\Dto\OAuthVerifyOtpDto;
use Modules\OAuth\Dto\OAuthGoogleSignupDto;
use Modules\OAuth\Dto\OAuthFacebookSignupDto;
use Modules\OAuth\Dto\OAuthPasswordSignupDto;
use Infrastructure\Google2FA\Google2FAService;
use Laravel\Passport\Bridge\User as UserEntity;
use Modules\OAuth\Exceptions\InvalidOtpException;
use Infrastructure\Eloquent\Models\UserTrustedDevice;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Infrastructure\Socialite\Google\GoogleUserProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Infrastructure\Socialite\Facebook\FacebookUserProvider;
use Modules\OAuth\Exceptions\InvalidOtpRecoveryCodeException;

final class OAuthService
{
    public function __construct(
        private readonly GoogleUserProvider $google,
        private readonly FacebookUserProvider $facebook,
        private readonly Google2FAService $tfa,
    ) {
        //
    }

    public function password(OAuthPasswordDto $request): ?UserEntityInterface
    {
        $query = User::query()->where('email', $request->username);

        /** @var null|User $user */
        $user = $query->first();

        if (!$user) {
            return null;
        }

        if (!Hash::check($request->password, $user->password)) {
            return null;
        }

        return new UserEntity($user->getAuthIdentifier());
    }

    public function verifyOtp(OAuthVerifyOtpDto $request): void
    {
        $user = User::find($request->userId);

        if (!$user->google_2fa_enabled) {
            return;
        }

        if (
            UserTrustedDevice::query()
                ->where('user_id', $user->id)
                ->where('ip', $request->ip)
                ->where('user_agent', $request->userAgent)
                ->where('valid_to', '>', now())
                ->exists()
        ) {
            return;
        }

        if (!is_null($request->otpRecoveryCode)) {
            if ($request->otpRecoveryCode === $user->google_2fa_recovery_code) {
                return;
            }

            throw new InvalidOtpRecoveryCodeException();
        }

        if (is_null($request->otp) || !$this->tfa->verifyKey($user->google_2fa_secret, $request->otp)) {
            throw new InvalidOtpException();
        }

        if ($request->trusted) {
            Event::dispatch('auth_trusted_device.create', [[
                'userId' => $user->id,
                'ip' => $request->ip,
                'userAgent' => $request->userAgent,
            ]]);
        }
    }

    public function passwordSignup(OAuthPasswordSignupDto $request): UserEntityInterface
    {
        $user = User::create([
            'email' => $request->username,
            'password' => Hash::make($request->password),
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'registered_at' => now(),
        ]);

        Event::dispatch('oauth.password_signed_up', [$user->id]);

        return new UserEntity($user->getAuthIdentifier());
    }

    public function google(OAuthGoogleDto $request): ?UserEntityInterface
    {
        try {
            $source = $this->google->request($request->token);

            $user = User::query()
                ->where('google_id', $source->id)
                ->firstOrFail();

            return new UserEntity($user->getAuthIdentifier());
        } catch (ModelNotFoundException) {
            return null;
        }
    }

    public function googleSignup(OAuthGoogleSignupDto $request): ?UserEntityInterface
    {
        $source = $this->google->request($request->token);

        if (User::query()->where('google_id', $source->id)->exists()) {
            return null;
        }

        $user = User::create([
            'google_id' => $source->id,
            'registered_at' => now(),
        ]);

        Event::dispatch('oauth.google_signed_up', [$user->id]);

        return new UserEntity($user->getAuthIdentifier());
    }

    public function facebook(OAuthFacebookDto $request): ?UserEntityInterface
    {
        try {
            $source = $this->facebook->request($request->token);

            $user = User::query()
                ->where('facebook_id', $source->id)
                ->firstOrFail();

            return new UserEntity($user->getAuthIdentifier());
        } catch (ModelNotFoundException) {
            return null;
        }
    }

    public function facebookSignup(OAuthFacebookSignupDto $request): ?UserEntityInterface
    {
        $source = $this->facebook->request($request->token);

        if (User::query()->where('facebook_id', $source->id)->exists()) {
            return null;
        }

        $user = User::create([
            'facebook_id' => $source->id,
            'registered_at' => now(),
        ]);

        Event::dispatch('oauth.facebook_signed_up', [$user->id]);

        return new UserEntity($user->getAuthIdentifier());
    }
}
