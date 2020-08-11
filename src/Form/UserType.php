<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'row_attr'=>['class'=>'form_row'],
                'required'=>true
            ])
            ->add('roles', ChoiceType::class, [
                'label'=>'Role',
                'choices'=>['User'=>'ROLE_USER', 'Admin'=>'ROLE_ADMIN'],
                'row_attr'=>['class'=>'form_row'],
                'mapped'=>false
            ])
            ->add('password', RepeatedType::class, [
                'type'=>PasswordType::class,
                'invalid_message'=>'Passwords must match',
                'options'=>[

                ],
                'required'=>true,
                'mapped'=>false,
                'first_options'=>[
                    'label'=>'Password',
                    'row_attr'=>['class'=>'form_row']
                ],
                'second_options'=>[
                    'label'=>'Repeat password',
                    'row_attr'=>['class'=>'form_row']
                ]
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
