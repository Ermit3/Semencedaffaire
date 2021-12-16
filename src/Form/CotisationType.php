<?php

namespace App\Form;

use App\Entity\Cotisations;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CotisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('utilisateur', EntityType::class,[
                'class' => Utilisateur::class
            ])
            ->add('montant', MoneyType::class,[
                'label' => 'Cotisation'
            ])
            ->add('afficher')
            ->add('source')

            ->add('facture', TextType::class,[
                'label' => 'Facture'
            ])
            ->add('idfiche', IntegerType::class,[
                'label' => 'ID'
            ])
            ->add('quartier', TextType::class,[
                'label' => 'Quartier'
            ])
            ->add('telephone', TextType::class,[
                'label' => 'Téléphone'
            ])
            ->add('nationalite', TextType::class,[
                'label' => 'Nationalite'
            ])
            ->add('nomsponsor', TextType::class,[
                'label' => 'Nom du Sponsor'
            ])
            ->add('prenomsponsor', TextType::class,[
                'label' => 'Prenom du Sponsor'
            ])
            ->add('telephonesponsor', TextType::class,[
                'label' => 'Téléphone du Sponsor'
            ])
            ->add('idsponsor', IntegerType::class,[
                'label' => 'ID du Sponsor'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cotisations::class,
        ]);
    }
}
