<?php

namespace Zestfule\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ResetPasswordRequestType of user
 * @package Zestfule\UserBundle\Form
 * @license MIT
 * @author Erling Thorkildsen <erling.thorkildsen@interestfule.com>
 * @homepage https://gitlab.com/zestfule/user
 */
class ResetPasswordRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'security.login_username'],
                'label_attr' => ['style' => 'display:none'],
                'label' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'security_messages.save_button',
            ]);
    }
}