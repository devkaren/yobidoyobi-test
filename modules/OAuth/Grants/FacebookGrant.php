<?php

namespace Modules\OAuth\Grants;

use League\OAuth2\Server\RequestEvent;
use Modules\OAuth\Dto\OAuthFacebookDto;
use Psr\Http\Message\ServerRequestInterface;
use Modules\OAuth\Exceptions\OAuthServerException;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Infrastructure\Validation\Rules\FacebookAccessTokenRule;

final class FacebookGrant extends AbstractGrant
{
    public function getIdentifier(): string
    {
        return 'facebook';
    }

    protected function shouldValidateOtp(): bool
    {
        return false;
    }

    protected function getServerRequestValidationRules(ServerRequestInterface $request): array
    {
        return [
            'token' => [
                'required',
                'string',
                'max:255',
                new FacebookAccessTokenRule(),
            ],
        ];
    }

    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client): UserEntityInterface
    {
        $data = $this->validateRequest($request);

        $user = $this->service->facebook(new OAuthFacebookDto(
            $data['token'],
        ));

        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }
}
