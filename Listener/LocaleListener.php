<?php

namespace Zestfule\UserBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class LocaleListener of user
 * @package Zestfule\UserBundle\Listener
 * @license MIT
 * @author Erling Thorkildsen <erling.thorkildsen@interestfule.com>
 * @link https://gitlab.com/zestfule/user
 */
class LocaleListener implements EventSubscriberInterface
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($locale = $request->attributes->get('_locale'))
        {
            $request->getSession()->set('_locale', $locale);
        } else {
            $request->setLocale($request->getSession()->get('_locale', $request->getDefaultLocale()));
        }
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => [['onKernelRequest', 15]]];
    }
}