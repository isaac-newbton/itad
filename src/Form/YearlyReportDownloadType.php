<?php

namespace App\Form;

use App\Entity\FileDownload;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class YearlyReportDownloadType extends AbstractType
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
                        'maxSize'=>'8192k'
                    ])
                ],
                'row_attr'=>['class'=>'form_row']
            ])
            ->add('thumbnail', FileType::class, [
                'label'=>'Optional thumbnail',
                'mapped'=>false,
                'required'=>false,
                'constraints'=>[
                    new File([
                        'mimeTypes'=>[
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/svg',
                            'image/tiff'
                        ],
                        'mimeTypesMessage'=>'An image format is required.'
                    ])
                ],
                'row_attr'=>['class'=>'form_row']
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FileDownload::class,
        ]);
    }
}
