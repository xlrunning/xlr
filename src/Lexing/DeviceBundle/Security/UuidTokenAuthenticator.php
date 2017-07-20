<?php

namespace Lexing\DeviceBundle\Security;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UuidTokenAuthenticator
 * @package Lexing\DeviceBundle\Security
 *
 * 终端设备令牌登入
 */
class UuidTokenAuthenticator extends AbstractGuardAuthenticator
{
    use ContainerAwareTrait;

    /**
     * Called on every request. Return whatever credentials you want,
     * or null to stop authentication.
     */
    public function getCredentials(Request $request)
    {
        // LX-AUTH-KEY: uuid
        // LX-AUTH-TOKEN: makeSign(['uuid'])
        if ((!$uuid = $request->headers->get('LX-AUTH-KEY')) || (!$token = $request->headers->get('LX-AUTH-TOKEN'))) {
            // no token? Return null and no other methods will be called
            return null;
        }

        return array(
            'uuid' => $uuid,
            'token' => $token,
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        unset($credentials['token']);
        $device = $em->getRepository('LexingDeviceBundle:Device')->findOneBy([
            'uuid' => $credentials['uuid']
        ]);
        if ($device && $device->getUser()) { // @todo && isdealeruser
            return $device->getUser();
        }

        return null;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $signHelper = $this->container->get('lexing_device.device_signhelper');
        if (empty($credentials['token']) || empty($credentials['uuid'])) {
            return false;
        }
        $receivedSign = $credentials['token'];
        unset($credentials['token']);
        $em = $this->container->get('doctrine.orm.entity_manager');
        $device = $em->getRepository('LexingDeviceBundle:Device')->findOneBy([
            'uuid' => $credentials['uuid']
        ]);
        if (!$device || !$device->getSecret()) {
            return false;
        }
        $ourSign = $signHelper->makeSign($credentials, $device->getSecret());
        return $signHelper->checkSign($receivedSign, $ourSign);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $translator = $this->container->get('translator');
        $data = array(
            'error' => [
                'code' => '1001', // @todo what code
                'message' => $translator->trans($exception->getMessageKey(), $exception->getMessageData())
            ]
        );

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $translator = $this->container->get('translator');
        $data = array(
            'error' => [
                'code' => '1001', // @todo what code
                'message' => $authException ? $translator->trans($authException->getMessageKey(), $authException->getMessageData()) : '认证失败'
            ]
        );

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}