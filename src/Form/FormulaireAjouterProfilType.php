<?php

namespace App\Form;

use App\Entity\Participants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FormulaireAjouterProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo')
            ->add('nom')
            ->add('prenom')
            ->add('telephone')
            ->add('mail')
//            ->add('motDePasse') #, \Symfony\Component\Form\Extension\Core\Type\PasswordType::class
//            ->add('administrateur')
           // ->add('actif')
            ->add('sitesNoSite')
          //  ->add('sortiesNoSortie')
          ->add('photoProfil', FileType::class, [
              'label' => 'TEst',
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
            'data_class' => Participants::class,
        ]);
    }

    public function __toString()
    {
        return (string) $this->get;
    }
}
