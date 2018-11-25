<?php

namespace Zestfule\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GroupType of user
 * @package Zestfule\UserBundle\Form
 * @license MIT
 * @author Erling Thorkildsen <erling.thorkildsen@interestfule.com>
 * @homepage https://gitlab.com/zestfule/user
 */
class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'security_messages.group_name',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'security_messages.save_button'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('data_class');
    }
}