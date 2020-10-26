<?php

namespace App\Form;

use App\Entity\YearlyReport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class YearlyReportUSType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = [];
        for($y = date('Y'); $y >= 1970; $y--){
            $years[(string)$y] = (string)$y;
        }
        $states = [
            "None" => '',
            "AL - Alabama" => "AL",
            "AK - Alaska" => "AK",
            "AZ - Arizona" => "AZ",
            "AR - Arkansas" => "AR",
            "CA - California" => "CA",
            "CO - Colorado" => "CO",
            "CT - Connecticut" => "CT",
            "DE - Delaware" => "DE",
            "FL - Florida" => "FL",
            "GA - Georgia" => "GA",
            "HI - Hawaii" => "HI",
            "ID - Idaho" => "ID",
            "IL - Illinois" => "IL",
            "IN - Indiana" => "IN",
            "IA - Iowa" => "IA",
            "KS - Kansas" => "KS",
            "KY - Kentucky" => "KY",
            "LA - Louisiana" => "LA",
            "ME - Maine" => "ME",
            "MD - Maryland" => "MD",
            "MA - Massachusetts" => "MA",
            "MI - Michigan" => "MI",
            "MN - Minnesota" => "MN",
            "MS - Mississippi" => "MS",
            "MO - Missouri" => "MO",
            "MT - Montana" => "MT",
            "NE - Nebraska" => "NE",
            "NV - Nevada" => "NV",
            "NH - New Hampshire" => "NH",
            "NJ - New Jersey" => "NJ",
            "NM - New Mexico" => "NM",
            "NY - New York" => "NY",
            "NC - North Carolina" => "NC",
            "ND - North Dakota" => "ND",
            "OH - Ohio" => "OH",
            "OK - Oklahoma" => "OK",
            "OR - Oregon" => "OR",
            "PA - Pennsylvania" => "PA",
            "RI - Rhode Island" => "RI",
            "SC - South Carolina" => "SC",
            "SD - South Dakota" => "SD",
            "TN - Tennessee" => "TN",
            "TX - Texas" => "TX",
            "UT - Utah" => "UT",
            "VT - Vermont" => "VT",
            "VA - Virginia" => "VA",
            "WA - Washington" => "WA",
            "WV - West Virginia" => "WV",
            "WI - Wisconsin" => "WI",
            "WY - Wyoming" => "WY"
        ];
        $builder
            ->add('year', ChoiceType::class, [
                'choices'=>$years,
                'row_attr'=>['class'=>'form_row']
            ])
            ->add('usState', ChoiceType::class, [
                'choices'=>$states,
                'row_attr'=>['class'=>'form_row'],
                'label'=>'US State',
                'required'=>false
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

