<?php

namespace Zestfule\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Class ChangePasswordType of user
 * @package Zestfule\UserBundle\Form
 * @license MIT
 * @author Erling Thorkildsen <erling.thorkildsen@interestfule.com>
 * @homepage https://gitlab.com/zestfule/user
 */
class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password_current', PasswordType::class, [
                'label' => 'security_messages.password_current',
                'mapped' => false,
                'constraints' => new UserPassword(),
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['placeholder' => 'security_messages.password'],
                    'label_attr' => ['style' => 'display:none'],
                ],
                'second_options' => [
                    'attr' => ['placeholder' => 'security_messages.password_confirmation'],
                    'label_attr' => ['style' => 'display:none'],
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 2048,
                    ]),
                ],
                'invalid_message' => 'security_validators.password_dont_match'
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'security_messages.save_button',
            ]);

        if ($options['disable_password_current'])
        {
            $builder->remove('password_current');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('data_class')
            ->setRequired('disable_password_current');
    }
}