<?php

namespace Zestfule\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Class ResetPasswordType of user
 * @package Zestfule\UserBundle\Form
 * @license MIT
 * @author Erling Thorkildsen <erling.thorkildsen@interestfule.com>
 * @homepage https://gitlab.com/zestfule/user
 */
class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['placeholder' => 'security.password'],
                    'label_attr' => ['style' => 'display:none'],
                ],
                'second_options' => [
                    'attr' => ['placeholder' => 'security.password_confirmation'],
                    'label_attr' => ['style' => 'display:none'],
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 2048,
                    ]),
                ],
                'invalid_message' => 'security.password_dont_match',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'security_messages.save_button',
            ]);
    }
}