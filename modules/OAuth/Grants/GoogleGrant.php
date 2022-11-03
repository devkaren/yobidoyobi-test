<?php

namespace Modules\OAuth\Grants;

use Modules\OAuth\Dto\OAuthGoogleDto;
use League\OAuth2\Server\RequestEvent;
use Psr\Http\Message\ServerRequestInterface;
use Modules\OAuth\Exceptions\OAuthServerException;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Infrastructure\Validation\Rules\GoogleAccessTokenRule;

final class GoogleGrant extends AbstractGrant
{
    public function getIdentifier(): string
    {
        return 'google';
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
                new GoogleAccessTokenRule(),
            ],
        ];
    }

    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client): UserEntityInterface
    {
        $data = $this->validateRequest($request);

        $user = $this->service->google(new OAuthGoogleDto(
            $data['token'],
        ));

        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }
}
