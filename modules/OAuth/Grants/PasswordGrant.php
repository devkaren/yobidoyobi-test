<?php

namespace Modules\OAuth\Grants;

use League\OAuth2\Server\RequestEvent;
use Modules\OAuth\Dto\OAuthPasswordDto;
use Psr\Http\Message\ServerRequestInterface;
use Modules\OAuth\Exceptions\OAuthServerException;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

final class PasswordGrant extends AbstractGrant
{
    public function getIdentifier(): string
    {
        return 'password';
    }

    protected function shouldValidateOtp(): bool
    {
        return true;
    }

    protected function getServerRequestValidationRules(ServerRequestInterface $request): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client): UserEntityInterface
    {
        $data = $this->validateRequest($request);

        $user = $this->service->password(new OAuthPasswordDto(
            $data['username'],
            $data['password'],
        ));

        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }
}
