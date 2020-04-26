<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\Mention;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MentionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('url', TextType::class)
            ->add('urlGerman', TextType::class)
            ->add('priority', NumberType::class, [
                'html5' => true,
            ])
            ->add('isGerman', CheckboxType::class, [
                'required' => false,
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
            'data_class' => Mention::class,
        ]);
    }
}