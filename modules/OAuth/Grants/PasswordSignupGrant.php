<?php

namespace Modules\OAuth\Grants;

use Psr\Http\Message\ServerRequestInterface;
use Modules\OAuth\Dto\OAuthPasswordSignupDto;
use Infrastructure\Validation\Rules\PasswordRule;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

final class PasswordSignupGrant extends AbstractGrant
{
    public function getIdentifier(): string
    {
        return 'password_signup';
    }

    protected function shouldValidateOtp(): bool
    {
        return false;
    }

    protected function getServerRequestValidationRules(ServerRequestInterface $request): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:255',
                'email',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'max:255',
                new PasswordRule(),
            ],
            'first_name' => [
                'required',
                'string',
                'max:255',
            ],
            'last_name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    protected function getServerRequestValidationMessages(ServerRequestInterface $request): array
    {
        return [
            'username.unique' => trans('validation.unique', ['attribute' => 'email']),
        ];
    }

    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client): UserEntityInterface
    {
        $data = $this->validateRequest($request);

        return $this->service->passwordSignup(new OAuthPasswordSignupDto(
            $data['username'],
            $data['password'],
            $data['first_name'],
            $data['last_name'],
        ));
    }
}
