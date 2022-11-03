<?php

namespace Modules\OAuth\Grants;

use Modules\OAuth\Dto\OAuthGoogleSignupDto;
use Psr\Http\Message\ServerRequestInterface;
use Modules\OAuth\Exceptions\OAuthServerException;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Infrastructure\Validation\Rules\GoogleAccessTokenRule;

final class GoogleSignupGrant extends AbstractGrant
{
    public function getIdentifier(): string
    {
        return 'google_signup';
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
                new GoogleAccessTokenRule(),
            ],
        ];
    }

    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client): UserEntityInterface
    {
        $data = $this->validateRequest($request);

        $user = $this->service->googleSignup(new OAuthGoogleSignupDto(
            $data['token'],
        ));

        if ($user instanceof UserEntityInterface === false) {
            throw OAuthServerException::invalidSignupCredentials();
        }

        return $user;
    }
}
