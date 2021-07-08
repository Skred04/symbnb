<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prenom", "Votre prénom"))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Votre nom de famille"))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre email"))
            ->add('picture', UrlType::class, $this->getConfiguration("Photo de profil", "Url de votre avatar"))
            ->add('hash', RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "Les mots de passes doivent correspondre",
                "first_options"  => ["label" => "Mot de passe", "attr" => ["placeholder" => "Choisissez un mot de passe sécurisé"]],
                "second_options" => ["label" => "Confirmer le mot de passe", "attr" => ["placeholder" => "Confirmer le mot de passe"]],
            ])
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Présentez vous en quelques mots"))
            ->add('description', TextareaType::class, $this->getConfiguration("Description détaillée", "Présentez vous en détail"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
