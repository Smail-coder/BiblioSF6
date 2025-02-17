<?php

// namespace App\Form;

// use App\Entity\Abonne;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;

// class AbonneType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $builder
//             ->add('pseudo')
//             ->add('roles')
//             ->add('password')
//             ->add('nom')
//             ->add('prenom')
//             ->add('email')
//         ;
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'data_class' => Abonne::class,
//         ]);
//     }
// }


namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo'
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Rôles',
                'attr' => [
                    'class' => 'form-check'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}