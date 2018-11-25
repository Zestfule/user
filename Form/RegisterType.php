<?php

namespace Zestfule\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Class RegisterType of user
 * @package Zestfule\UserBundle\Form
 * @license MIT
 * @author Erling Thorkildsen <erling.thorkildsen@interestfule.com>
 * @homepage https://gitlab.com/zestfule/user
 */
class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'security_messages.email'],
                'label_attr' => ['style' => 'display:none'],
                'label' => false,
            ])
            ->add('username', TextType::class, [
                'attr' => ['placeholder' => 'security_messages.username'],
                'label_attr' => ['style' => 'display:none'],
                'label' => false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['placeholder' => 'security_messages.password'],
                    'label_attr' => ['style' => 'display:none'],
                    'label' => false,
                ],
                'second_options' => [
                    'attr' => ['placeholder' => 'security_messages.password_confirmation'],
                    'label_attr' => ['style' => 'display:none'],
                    'label' => false,
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 2048,
                    ]),
                ],
                'invalid_message' => 'security_validators.password_dont_match',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'security_messages.save_button',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('data_class');
    }
}