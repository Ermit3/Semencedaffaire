<?php

namespace App\Form;

use App\Entity\ArrierePlan;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArrierePlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filename',TextType::class, [
                'label' => 'Image',
            ])
            ->add('nom',TextType::class, [
                'label' => 'Nom'
            ])
            ->add('afficher')
            ->add('imagefile',FileType::class,[
                'label' => 'Image Arriereplan',
                'required' => false,
            ])
            ->add('source',EntityType::class,[
                'class' => Utilisateur::class,
                'choice_label' => 'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArrierePlan::class,
        ]);
    }
}
