<?php

namespace Zestfule\UserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class ZestfuleUserExtension of user
 * @package Zestfule\UserBundle\DependencyInjection
 * @license MIT
 * @author Erling Thorkildsen <erling.thorkildsen@interestfule.com>
 * @homepage https://gitlab.com/zestfule/user
 */
class ZestfuleUserExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('zestfule_user.user_class', $config['user_class']);
        $container->setParameter('zestfule_user.profile_class', $config['profile_class']);
        $container->setParameter('zestfule_user.group_class', $config['group_class']);
        $container->setParameter('zestfule_user.default_group', $config['default_group']);
        $container->setParameter('zestfule_user.login_redirect', $config['login_redirect']);
        $container->setParameter('zestfule_user.email_confirmation', $config['email_confirmation']);
        $container->setParameter('zestfule_user.email_welcome', $config['email_welcome']);
        $container->setParameter('zestfule_user.user_registration', $config['user_registration']);
        $container->setParameter('zestfule_user.template_path', $config['template_path']);
        $container->setParameter('zestfule_user.password_request_time', $config['password_request_time']);
        $container->setParameter('zestfule_user.mail_sender_address', $config['mail_sender_address']);
        $container->setParameter('zestfule_user.mail_sender_name', $config['mail_sender_name']);
        $container->setParameter('zestfule_user.active_language', $config['active_language']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }
}