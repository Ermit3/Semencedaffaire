<?php

namespace App\Form;

use App\Entity\CommandeClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PanierConfirmationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Nom Complet',
                'attr' => [
                    'placeholder' => 'Nom complet pour la livraison'
                ]
            ])
            ->add('adresse', TextareaType::class, [
                'label' => 'Adresse de Livraison',
                'attr' => [
                    'placeholder' => 'Adresse Complete de Livraison'
                ]
            ])
            ->add('codepostal', TextType::class, [
                'label' => 'Code Postal',
                'attr' => [
                    'placeholder' => 'Code Postal'
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Votre numéro de Téléphone'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Votre Email de contact'
                ]
            ])
            ->add('ville', TextType::class,[
                'label' => 'Ville de Livraison',
                'attr' => [
                    'placeholder' => 'Ville de Livraison'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommandeClient::class
                               ]);
    }
}