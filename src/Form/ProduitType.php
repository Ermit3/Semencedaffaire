<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,[
                'label' => 'Titre'
            ])
            ->add('text', TextareaType::class,[
                'label' => 'Texte'
            ])
            ->add('soustext', TextType::class,[
                'label' => 'Sous Titre',
                'required' => false,
            ])
            ->add('imagefile',FileType::class,[
                'label' => 'Image Produit',
                'required' => false,
            ])
            ->add('imagefiledos',FileType::class,[
                'label' => 'Image Produit Dos',
                'required' => false,
            ])
            ->add('afficher')
            ->add('prix')
            ->add('source', EntityType::class,[
                'class' => Utilisateur::class,
                'choice_label' => 'nom'
            ])
            ->add('categorie', EntityType::class,[
                'class' => Categorie::class,
                'choice_label' => 'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
