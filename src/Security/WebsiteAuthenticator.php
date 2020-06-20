<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class WebsiteAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_signin';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $encoder;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $encoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->encoder = $encoder;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'username'      => $request->request->get('username'),
            'password'      => $request->request->get('password'),
            'csrf_token'    => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        if (isset($credentials['username'])) {
            $currentUser = htmlspecialchars($credentials['username']);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $currentUser]);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->encoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        $user       = $token->getUser();
        
        $user       = $user->getId();
        $curr_user  = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $user]);

        $curr_user->setLastLogin();
        $this->entityManager->persist($curr_user);
        $this->entityManager->flush();
        
        $user_role  = $token->getRoleNames();
        
        if (in_array("ROLE_ADMIN", $user_role)) {
            return new RedirectResponse($this->urlGenerator->generate('_admin'));
        } else {
            return new RedirectResponse($this->urlGenerator->generate('_blogs'));
        }
    }

    public function getPassword($credentials): ?string {

        $currentUser    = $credentials['username'];
        $givenPassword  = $credentials['password'];

        if (($givenPassword != null) &&
            ($currentUser != null)) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $currentUser]);
            if ($credentials['password'] === $this->encoder->encodePassword($user, $givenPassword)) {
                
                return new RedirectResponse($this->urlGenerator->generate('_blogs'));
            }
        }

        return new RedirectResponse($this->urlGenerator->generate('app_signin'));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}