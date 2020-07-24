<?php

namespace App\Form;

use App\Entity\YearlyReport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YearlyReportLaboratoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('participatingLaboratories', null, [
                'row_attr'=>['class'=>'form_row'],
                'expanded'=>true,
                'choice_attr'=>['class'=>'choice']
            ])
            ->add('Save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => YearlyReport::class,
        ]);
    }
}
