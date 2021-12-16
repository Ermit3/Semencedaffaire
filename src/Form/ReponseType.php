<?php

namespace App\Form;

use App\Entity\Reponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('mailsource', EmailType::class,[
               'label' => 'Mail Source'
            ])
            ->add('maildestination', EmailType::class,[
                'label' => 'Mail Destination'
            ])
            ->add('text', TextareaType::class,[
                'label' => 'Reponse'
            ])
            ->add('contact')
            ->add('idrepondre')
            ->add('commentaire')
            ->add('source')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
