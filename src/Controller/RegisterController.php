<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

// controller qui gère l'inscription
class RegisterController extends AbstractController
{
    #[Route('/register', name: 'auth.register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            // on récup les données du formulaire
            $email = $request->request->get('user_email');
            $password = $request->request->get('user_password');
            $passwordConfirmation = $request->request->get('user_password_confirmation');

            // la on vérifie si tous les champs sont rempli
            if (empty($email) || empty($password) || empty($passwordConfirmation)) {
                $this->addFlash('error', ' Tous les champs doivent être remplis.');
                return $this->redirectToRoute('auth.register');
            }

            if (strlen($password) < 8) {
                $this->addFlash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
                return $this->redirectToRoute('auth.register');
            }

            // la on vérifie si les mots de passe sont les mêmes lors de l'inscription
            if ($password !== $passwordConfirmation) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('auth.register');
            }

            // la on vérifie si l'email est déjà utilisé avant
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existingUser) {
                $this->addFlash('error', 'Cet email est déjà utilisé.');
                return $this->redirectToRoute('auth.register');
            }

            // la on initialise la création d'un nouvel utilisateur
            $user = new User();
            $user->setEmail($email);

            // hachage du mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            // sauvegarde dans la BDD
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription réussie ! Connectez-vous.');
            return $this->redirectToRoute('auth.login');
        }

        return $this->render('auth/register.html.twig');
    }
}
