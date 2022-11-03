<?php

namespace Modules\OAuth\Grants;

use DateInterval;
use Ramsey\Uuid\Uuid;
use Modules\OAuth\Utils\RequestMeta;
use League\OAuth2\Server\RequestEvent;
use Modules\OAuth\Dto\OAuthVerifyOtpDto;
use Modules\OAuth\Services\OAuthService;
use Illuminate\Support\Facades\Validator;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Validation\ValidationException;
use Modules\OAuth\Exceptions\InvalidOtpException;
use Modules\OAuth\Exceptions\OAuthServerException;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Modules\OAuth\Exceptions\InvalidOtpRecoveryCodeException;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Grant\AbstractGrant as LeagueAbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

abstract class AbstractGrant extends LeagueAbstractGrant
{
    public function __construct(
        UserRepositoryInterface $userRepository,
        RefreshTokenRepositoryInterface $refreshTokenRepository,
        protected readonly OAuthService $service,
    ) {
        $this->setUserRepository($userRepository);
        $this->setRefreshTokenRepository($refreshTokenRepository);
        $this->refreshTokenTTL = new DateInterval('P1M');
    }

    abstract protected function getServerRequestValidationRules(ServerRequestInterface $request): array;

    protected function getServerRequestValidationMessages(ServerRequestInterface $request): array
    {
        return [
            //
        ];
    }

    abstract protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client): UserEntityInterface;

    abstract protected function shouldValidateOtp(): bool;

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     * @see \League\OAuth2\Server\Grant\PasswordGrant
     */
    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        DateInterval $accessTokenTTL
    ) {
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request, $client);

        if ($this->shouldValidateOtp()) {
            $this->validateOtp($request, $user);
        }

        $finalizedScopes = $this->scopeRepository->finalizeScopes(
            $scopes,
            $this->getIdentifier(),
            $client,
            $user->getIdentifier()
        );

        $accessToken = $this->issueAccessToken(
            $accessTokenTTL,
            $client,
            $user->getIdentifier(),
            $finalizedScopes,
        );

        $this->getEmitter()->emit(new RequestEvent(RequestEvent::ACCESS_TOKEN_ISSUED, $request));

        $responseType->setAccessToken($accessToken);

        $refreshToken = $this->issueRefreshToken($accessToken);

        if ($refreshToken !== null) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::REFRESH_TOKEN_ISSUED, $request));
            $responseType->setRefreshToken($refreshToken);
        }

        return $responseType;
    }

    protected function validateClient(ServerRequestInterface $request)
    {
        $clientId = $this->getRequestParameter('client_id', $request);

        if (!is_string($clientId) || !Uuid::isValid($clientId)) {
            throw OAuthServerException::invalidClient($request);
        }

        return parent::validateClient($request);
    }

    protected function validateRequest(ServerRequestInterface $request): array
    {
        try {
            $rules = collect($this->getServerRequestValidationRules($request));

            if ($this->shouldValidateOtp()) {
                $rules->merge($this->getServerRequestOTPValidationRules());
            }

            $data = $rules
                ->keys()
                ->mapWithKeys(fn ($key) => [$key => $this->getRequestParameter($key, $request)])
                ->toArray();

            $validator = Validator::make(
                $data,
                $rules->all(),
                $this->getServerRequestValidationMessages($request),
            );

            return $validator->validate();
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $parameter = array_key_first($errors);
            $hint = reset($errors)[0];

            throw OAuthServerException::invalidRequest($parameter, $hint);
        }
    }

    private function getServerRequestOTPValidationRules(): array
    {
        return [
            'otp' => [
                'nullable',
                'string',
                'size:6',
            ],
            'otp_recovery_code' => [
                'nullable',
                'string',
                'max:255',
            ],
            'trusted' => [
                'nullable',
                'bool',
            ],
        ];
    }

    private function validateOtp(ServerRequestInterface $request, UserEntityInterface $user): void
    {
        try {
            $this->service->verifyOtp(new OAuthVerifyOtpDto(
                $user->getIdentifier(),
                $this->getRequestParameter('otp', $request),
                $this->getRequestParameter('otp_recovery_code', $request),
                RequestMeta::getRemoteAddr($request),
                RequestMeta::getUserAgent($request),
                (bool) $this->getRequestParameter('trusted', $request),
            ));
        } catch (InvalidOtpException) {
            throw OAuthServerException::invalidOtp();
        } catch (InvalidOtpRecoveryCodeException) {
            throw OAuthServerException::invalidOtpRecoveryCode();
        }
    }
}
