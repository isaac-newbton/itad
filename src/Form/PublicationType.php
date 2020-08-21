<?php

namespace App\Form;

use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;

class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'row_attr'=>['class'=>'form_row']
            ])
            ->add('author', TextType::class, [
                'row_attr'=>['class'=>'form_row']
            ])
            ->add('url', TextType::class, [
                'row_attr'=>['class'=>'form_row']
            ])
            ->add('description', TextareaType::class, [
                'row_attr'=>['class'=>'form_row'],
                'required'=>false
            ])
            ->add('file', FileType::class, [
                'row_attr'=>['class'=>'form_row'],
                'constraints'=>[
                    new File([
                        'maxSize'=>'8192k',
                        'mimeTypes'=>['application/pdf'],
                        'mimeTypesMessage'=>'{{ type }} is not allowed. This field requires a file of type: {{ types }}'
                    ])
                ],
                'required'=>false,
                'mapped'=>false
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
