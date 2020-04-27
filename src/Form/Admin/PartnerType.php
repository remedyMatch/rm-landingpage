<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\Partner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('descriptionGerman', TextareaType::class)
            ->add('url', TextType::class)
            ->add('urlGerman', TextType::class)
            ->add('priority', NumberType::class, [
                'html5' => true,
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'download_uri' => true,
                'download_label' => true,
                'image_uri' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Partner::class,
        ]);
    }
}
