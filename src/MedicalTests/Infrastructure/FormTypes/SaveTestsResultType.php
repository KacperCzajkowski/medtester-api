<?php

declare(strict_types=1);

namespace App\MedicalTests\Infrastructure\FormTypes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class SaveTestsResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('testId', TextType::class, [
                'constraints' => [
                    new Uuid(),
                ],
            ])
            ->add('userId', TextType::class, [
                'constraints' => [
                    new Uuid(),
                    new NotBlank(),
                ],
            ])
            ->add('status', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('results', CollectionType::class, [
                'entry_type' => SingleTestResultType::class,
                'allow_add' => true,
            ])
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
