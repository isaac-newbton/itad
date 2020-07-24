<?php

namespace App\Form;

use App\Entity\Adulterant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdulterantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'row_attr'=>['class'=>'form_row']
            ])
            ->add('synonyms', TextType::class, [
                'row_attr'=>['class'=>'form_row'],
                'required'=>false
            ])
            ->add('spanishName', TextType::class, [
                'row_attr'=>['class'=>'form_row'],
                'required'=>false
            ])
            ->add('drugClass', TextType::class, [
                'row_attr'=>['class'=>'form_row'],
                'required'=>false
            ])
            ->add('occurrenceUsage', TextareaType::class, [
                'row_attr'=>['class'=>'form_row'],
                'help'=>'Accepts HTML',
                'required'=>false
            ])
            ->add('physiologicalEffect', TextareaType::class, [
                'row_attr'=>['class'=>'form_row'],
                'help'=>'Accepts HTML',
                'required'=>false
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adulterant::class,
        ]);
    }
}
