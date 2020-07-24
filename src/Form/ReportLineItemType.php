<?php

namespace App\Form;

use App\Entity\ReportLineItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportLineItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adulterant', null, [
                'row_attr'=>['class'=>'form_row'],
                'multiple'=>false
            ])
            ->add('value', NumberType::class,[
                'row_attr'=>['class'=>'form_row'],
                'scale'=>2,
                'html5'=>true,
                'attr'=>['min'=>'0', 'max'=>'100', 'step'=>'0.01', 'width'=>'5'],
                'help'=>'Entered number will be displayed as a percentage (i.e. 12.5 will become 12.50%)'
            ])
            ->add('Save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReportLineItem::class,
        ]);
    }
}
