<?php

namespace App\Form;

use App\Entity\Adulterant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AdulterantThumbnailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'label'=>'Image file',
                'mapped'=>false,
                'required'=>true,
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
            'data_class' => Adulterant::class,
        ]);
    }
}
