<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route ("/admin", name="admin")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function admin(UserRepository $userRepository):Response
    {
        $users = $userRepository->findAll();
        return $this->render('Frontend/admin.html.twig', ['users' => $users]);
    }

    /**
     * @Route ("/user/{id}/{slug}/delete", name="user.delete", requirements={"slug": "[a-z0-9\-]*"})
     * @param User $user
     * @param string $slug
     * @return Response
     */
    public function removeUser(User $user, string $slug):Response
    {
        if($user->getSlug() !== $slug) {
            return $this->redirectToRoute('user.remove', [
                'id' => $user->getId(),
                'slug' => $user->getSlug(),
            ], 301);
        }

        if ($user->getSlug() == $slug) {
            $this->em->remove($user);
            $this->em->flush();
        }

        return $this->redirectToRoute('home');
    }
}