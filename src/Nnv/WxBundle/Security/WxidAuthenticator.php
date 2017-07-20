<?php

namespace Nnv\WxBundle\Security;

use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class WxidAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     *
     * @var WxidUserProvider
     */
    protected $userProvider;

    public function __construct(WxidUserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function createToken(Request $request, $providerKey)
    {
        if (!$request->query->has('openid')) {
            throw new BadCredentialsException('No weixin openid found');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $request->query->get('openid'),
            $providerKey
        );
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $openid = $token->getCredentials();
        $username = $this->userProvider->getUsernameForWxid($openid);
        
        if (!$username) {
            throw new AuthenticationException(
                sprintf('Weixin openid "%s" does not exist.', $openid)
            );
        }
        
        $user = $this->userProvider->loadUserByUsername($username);
        return new PreAuthenticatedToken(
            $user,
            $openid,
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }
}