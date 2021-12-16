<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,[
                'label' => 'Titre'
            ])
            ->add('text', TextType::class,[
                'label' => 'Texte'
            ])
            ->add('imagefile',FileType::class,[
                'label' => 'Image Article',
                'required' => false,
            ])
            ->add('soustext', TextType::class,[
                'label' => 'Sous Titre'
            ])
            ->add('afficher')
            ->add('source', EntityType::class,[
                'class' => Utilisateur::class,
                'choice_label' => 'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
