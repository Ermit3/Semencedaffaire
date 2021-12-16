<?php

namespace App\Form;

use App\Entity\Presentation;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresentationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filename',TextType::class,[
                'label' => 'Image'
            ])
            ->add('titre',TextType::class,[
                'label' => 'Titre'
            ])
            ->add('soustitre',TextType::class,[
                'label' => 'Sous Titre'
            ])
            ->add('imagefile',FileType::class,[
                'label' => 'Upload Image',
                'required' => false,
            ])
            ->add('texte',TextareaType::class,[
                'label' => 'Texte'
            ])
            ->add('afficher')
            ->add('utilisateur',EntityType::class,[
                'class' => Utilisateur::class,
                'choice_label' => 'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Presentation::class,
        ]);
    }
}
