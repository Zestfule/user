<?php

namespace Zestfule\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration of user
 * @package Zestfule\UserBundle\DependencyInjection
 * @license MIT
 * @author Erling Thorkildsen <erling.thorkildsen@interestfule.com>
 * @homepage https://gitlab.com/zestfule/user
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('zestfule_user');

        $root
            ->children()
                ->scalarNode('user_class')->defaultValue('')->end()
                ->scalarNode('profile_class')->defaultValue('')->end()
                ->scalarNode('group_class')->defaultValue('')->end()
                ->scalarNode('default_group')->defaultValue('')->end()
                ->scalarNode('login_redirect')->defaultValue('/')->end()
                ->booleanNode('email_confirmation')->defaultTrue()->end()
                ->booleanNode('email_welcome')->defaultTrue()->end()
                ->booleanNode('user_registration')->defaultTrue()->end()
                ->scalarNode('template_path')->defaultValue('@ZestfuleUser')->end()
                ->integerNode('password_request_time')->defaultValue(7200)->end()
                ->scalarNode('mail_sender_address')->defaultValue('admin@example.com')->end()
                ->scalarNode('mail_sender_name')->defaultValue('zestfuleUser')->end()
                ->arrayNode('active_language')->scalarPrototype()->end()->defaultValue(['en'])->end()
            ->end();

        return $treeBuilder;
    }
}