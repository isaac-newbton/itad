<?php

namespace App\Form;

use App\Entity\ExcelDataFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ExcelDataFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'label'=>'File download',
                'mapped'=>false,
                'required'=>true,
                'constraints'=>[
                    new File([
                        'maxSize'=>'8192k',
                        'mimeTypes'=>['application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','text/csv'],
                        'mimeTypesMessage'=>'{{ type }} is not allowed. This field requires a file of type: {{ types }}'
                    ])
                ],
                'row_attr'=>['class'=>'form_row']
            ])
            ->add('niceName', TextType::class, [
                'label'=>'Display name/label',
                'row_attr'=>['class'=>'form_row'],
                'required'=>false
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExcelDataFile::class,
        ]);
    }
}
