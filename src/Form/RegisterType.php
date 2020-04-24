<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street')
            ->add('email')
            ->add('firstname')
            ->add('lastname')
            ->add('housenumber')
            ->add('zipcode')
            ->add('city')
            ->add('phone')
            ->add('type')
            ->add('company')
            ->add('password');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ]);
    }
}
