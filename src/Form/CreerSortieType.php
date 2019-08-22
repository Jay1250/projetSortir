<?php

namespace App\Form;

use App\Entity\Sorties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('datedebut', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('duree')
            ->add('datecloture', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('nbinscriptionsmax')
            ->add('descriptioninfos', TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])
            //->add('etatsortie')
            //->add('urlphoto')
            // ->add('etatsNoEtat')
            ->add('lieuxNoLieu')
          //  ->add('organisateur')
            ->add('sortiesNoSortie')
            //->add('participantsNoParticipant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}