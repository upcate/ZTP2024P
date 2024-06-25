<?php

/**
 * LoginFormAuthenticator.
 */

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class LoginFormAuthenticator.
 */
class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    /**
     * Log in route.
     */
    public const LOGIN_ROUTE = 'app_login';

    /**
     * Default route.
     */
    public const DEFAULT_ROUTE = 'main_index';

    /**
     * Constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator Url generator interface
     */
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }// end __construct()

    /**
     * Supports.
     *
     * @param Request $request HTTP Request
     *
     * @return bool Bool return
     */
    public function supports(Request $request): bool
    {
        return 'app_login' === $request->attributes->get('_route') && $request->isMethod('POST');
    }// end supports()

    /**
     * Authenticate.
     *
     * @param Request $request HTTP Request
     *
     * @return Passport Authentication passport
     */
    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username', '');

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }// end authenticate()

    /**
     * On authentication success.
     *
     * @param Request        $request      HTTP Request
     * @param TokenInterface $token        Token
     * @param string         $firewallName Firewall name
     *
     * @return Response|null HTTP Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate(self::DEFAULT_ROUTE));
    }// end onAuthenticationSuccess()

    /**
     * Get log in url.
     *
     * @param Request $request HTTP Request
     *
     * @return string Log in url
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }// end getLoginUrl()
}// end class
