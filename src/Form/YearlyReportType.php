<?php

namespace App\Form;

use App\Entity\YearlyReport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class YearlyReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = [];
        for($y = date('Y'); $y >= 1970; $y--){
            $years[(string)$y] = (string)$y;
        }
        $builder
            ->add('year', ChoiceType::class, [
                'choices'=>$years,
                'row_attr'=>['class'=>'form_row']
            ])
            ->add('description', TextareaType::class, [
                'row_attr'=>['class'=>'form_row']
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => YearlyReport::class,
        ]);
    }
}
