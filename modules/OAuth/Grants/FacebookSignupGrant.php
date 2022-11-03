<?php

namespace Modules\OAuth\Grants;

use Psr\Http\Message\ServerRequestInterface;
use Modules\OAuth\Dto\OAuthFacebookSignupDto;
use Modules\OAuth\Exceptions\OAuthServerException;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Infrastructure\Validation\Rules\FacebookAccessTokenRule;

final class FacebookSignupGrant extends AbstractGrant
{
    public function getIdentifier(): string
    {
        return 'facebook_signup';
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
                new FacebookAccessTokenRule(),
            ],
        ];
    }

    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client): UserEntityInterface
    {
        $data = $this->validateRequest($request);

        $user = $this->service->facebookSignup(new OAuthFacebookSignupDto(
            $data['token'],
        ));

        if ($user instanceof UserEntityInterface === false) {
            throw OAuthServerException::invalidSignupCredentials();
        }

        return $user;
    }
}
