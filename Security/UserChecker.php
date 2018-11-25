<?php

namespace Zestfule\UserBundle\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserChecker of user
 * @package Zestfule\UserBundle\Security
 * @license MIT
 * @author Erling Thorkildsen <erling.thorkildsen@interestfule.com>
 * @homepage https://gitlab.com/zestfule/user
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * @param UserInterface $user
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof  UserInterface)
        {
            return;
        }
        if ($user->isLocked())
        {
            throw new CustomUserMessageAuthenticationException('This account is locked.');
        }
        if (!$user->isEnabled())
        {
            throw new CustomUserMessageAuthenticationException('This account has not been activate.');
        }
    }

    /**
     * @param UserInterface $user
     */
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof UserInterface)
        {
            return;
        }
    }
}