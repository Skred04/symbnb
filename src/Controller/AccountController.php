<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            "hasError" => $error !== null,
            "username" => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     * @Route("/logout", name="account_logout")
     * @return Void
     */
    public function logout(): Void{}

    /**
     * Permet d'afficher le formulaire d'inscription
     * @Route("/register", name="account_register")
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $hash = $hasher->hashPassword($user, $user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Votre compte a bien été crée, vous pouvez maintenant vous connecter.");

            return $this->redirectToRoute("account_login");
        }

        return $this->render("account/registration.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher et de traiter le formulaire de modification de profil
     * @Route ("/account/profile", name="account_profile")
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Les modifications du profil utilisateur ont bien été prisent en compte.");
        }

        return $this->render("account/profile.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher et de traiter le formulaire de mise a jour de mot de passe
     * @Route ("/account/update-password", name="account_password")
     * @return Response
     */
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher) : Response
    {
        $passwordUpdate = new PasswordUpdate();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        /** @var User $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()){
            //Verifier que le password actuel est le même que celui saisi
            if (!$hasher->isPasswordValid($user, $passwordUpdate->getOldPassword())){
                // Gérer l'erreur
                $form->get("oldPassword")->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $hasher->hashPassword($user, $newPassword);
                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash("success", "Votre mot de passe a bien été modifié");

                return $this->redirectToRoute("homepage");
            }
        }

        return $this->render("account/password.html.twig", [
           "form" => $form->createView()
        ]);
    }
}
