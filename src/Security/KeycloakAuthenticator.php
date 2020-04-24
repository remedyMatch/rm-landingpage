<?php

namespace App\Security;

use App\Exception\KeycloakAuthException;
use App\Exception\KeycloakException;
use App\Service\KeycloakManager;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

final class KeycloakAuthenticator extends AbstractFormLoginAuthenticator implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public const LOGIN_ROUTE = 'admin_security_login';

    private $urlGenerator;
    private $csrfTokenManager;
    private $keycloakManager;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        KeycloakManager $keycloakManager
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->keycloakManager = $keycloakManager;
    }

    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request): array
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if (!$user instanceof SecurityUser) {
            throw new \LogicException('User must be of type SecurityUser');
        }

        try {
            $roles = $this->keycloakManager->getRolesForUsernameAndPassword(
                $credentials['username'],
                $credentials['password']
            );
        } catch (KeycloakAuthException $exception) {
            throw new CustomUserMessageAuthenticationException('Username or password wrong');
        } catch (KeycloakException $exception) {
            $this->logger->error('Login failed due to Keycloak Issues', [
                'exception' => $exception,
            ]);

            throw new CustomUserMessageAuthenticationException('Something went wrong, please excuse any inconvenience caused');
        }

        $user->setRoles($roles);

        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->urlGenerator->generate('admin_registration_list'));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
