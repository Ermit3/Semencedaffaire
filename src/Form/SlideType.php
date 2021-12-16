<?php

namespace App\Form;

use App\Entity\Slide;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('textSlide', TextareaType::class, [
                'label' => 'Texte'
            ])
            ->add('soustext', TextType::class, [
                'label' => 'Sous Texte',
                'required' => false,
            ])
            ->add('imagefile',FileType::class,[
                'label' => 'Slide',
                'required' => false,
            ])
            ->add('afficher')
            ->add('source',EntityType::class,[
                'class' => Utilisateur::class,
                'choice_label' => 'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Slide::class,
        ]);
    }
}
