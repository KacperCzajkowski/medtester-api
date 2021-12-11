<?php

declare(strict_types=1);

namespace App\MedicalTests\Infrastructure\FormTypes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class IndicatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('result', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('unit', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('referenceRange', ReferenceRangeType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'POST',
            'csrf_protection' => false,
            'allow_extra_fields' => false,
        ]);
    }
}
