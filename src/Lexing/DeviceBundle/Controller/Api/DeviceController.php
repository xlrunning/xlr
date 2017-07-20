<?php

namespace Lexing\DeviceBundle\Controller\Api;

use Lexing\DeviceBundle\Entity\Device;
use Lexing\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * @Route("/api/device")
 */
class DeviceController extends Controller
{
    /**
     * 终端设备注册绑定
     *
     * @Route("/register", name="lx_api_device_register", defaults={"_format": "json"})
     */
    public function registerAction(Request $req)
    {
        $username = $req->get('username');
        $password = $req->get('password');
        $type = $req->get('type');
        $uuid = $req->get('uuid');
        if (empty($username) || empty($password) || empty($uuid) || empty($type)) {
            throw new AccessDeniedHttpException('username，password，uuid, type缺一不可');
        }

        $em = $this->container->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('LexingUserBundle:User')
            ->findOneBy([
                'username' => $username
            ]);

        if (!$user) {
            throw new UsernameNotFoundException();
        }
        $passwordValid = $this->get('security.password_encoder')->isPasswordValid($user, $password);
        if (!$passwordValid) {
            throw new AuthenticationException();
        }

        if (!$dealer = $user->getAdminDealer()) {
            throw new \Exception('无关联车商');
        }
        $device = $em->getRepository('LexingDeviceBundle:Device')
            ->findOneBy([
                'uuid' => $uuid,
                'type' => $type
            ]);
        if (!$device) {
            $device = new Device();
            $device->setType($type)
                ->setUser($user)
                ->setUuid($uuid);

            $em->persist($device);
        } else {
            // 设备存在但是绑定的用户不是当前用户
            // @todo 记录设备绑定变化日志
            $device->setUser($user);
        }
        $expiredAt = new \DateTime();
        $expiredAt->modify('+7 days');
        $device->setSecret(md5(uniqid()))
            ->setExpiredAt($expiredAt)
            ->setRequestIp($req->getClientIp());
        $em->flush();

        return $this->json([
            'ok' => 1,
            'secret' => $device->getSecret(),
            // @todo merge with api/dealer/info
            'id' => $dealer->getId(),
            'name' => $dealer->getName(),
            'mart' => $dealer->getMart()->getName(),
        ]);
    }
}