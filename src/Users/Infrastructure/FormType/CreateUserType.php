<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

class CreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->add('roles', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('pesel', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('gender', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('laboratoryId', TextType::class, [
                'constraints' => [
                    new Uuid(),
                ],
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
