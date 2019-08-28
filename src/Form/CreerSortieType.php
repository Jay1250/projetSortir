<?php

namespace App\Form;

use App\Entity\Sorties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CreerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('datedebut', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('duree')
            ->add('datecloture', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('nbinscriptionsmax')
            ->add('descriptioninfos', TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => false
            ])
            ->add('lieuxNoLieu')
            ->add('sortiesNoSortie')
            ->add('creer', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('creer_et_ouvrir', SubmitType::class, ['label' => 'Enregistrer et publier'])
            ->add('photoSortie', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10240k',
                        'mimeTypes' => [
                           'image/*',
                        ],
                        'mimeTypesMessage' => 'Le fichier est incorrect',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
