<?php

namespace App\Form;

use App\Entity\Acl;
use App\Entity\Grade;
use App\Entity\Groupe;
use App\Entity\Sponsors;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('prenom', TextType::class,[
                'label' => 'Prenom'
            ])
            ->add('login',TextType::class, [
                'label' => 'Login'
            ])
            ->add('password', PasswordType::class,[
                'label' => 'Password'
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('roles', ChoiceType::class,[
                'choices'  => $this->getRoleUtil(),
                'multiple' => true,
                'required' => false
            ])
            ->add('montant', MoneyType::class, [
                'label' => 'Cotisation'
            ])
            ->add('statut', TextType::class, [
                'label' => 'Statut'
            ])
            ->add('source', TextType::class, [
                'label' => 'Source'
            ])
            ->add('parent', EntityType::class, [
                'class' => Utilisateur::class,
                'label' => 'Sponsors',
                'choice_label' => 'nom'
            ])
            ->add('afficher')
            ->add('acl',EntityType::class,[
                'class' => Acl::class,
                'choice_label' => function (Acl $acl){
                    return $acl->getNom();
                },
                'mapped'   => false,
                'required' => false,
                'label'    => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => function (Groupe $groupe){
                return $groupe->getNom();
                }
            ])
            ->add('grade', EntityType::class, [
                'class' => Grade::class,
                'choice_label' => function (Grade $groupe){
                    return $groupe->getNom();
                }
            ])
            ->add('imagefile',FileType::class,[
                'label' => 'Image Utilisateur',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }

    /**
     * @return array
     */
    private function getRoleUtil(): array
    {
        return $tableauRole = [
            'ROLE_USER' => 'ROLE_USER',
            'ROLE_ADMIN' => 'ROLE_ADMIN',
            'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN'
        ];

    }
}
