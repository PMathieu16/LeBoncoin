<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/signup", name="app_signup")
     * @return Response
     */
    public function signup()
    {
        $title = 'Inscription';
        return $this->render('Security/signup.html.twig', ['title' => $title]);
    }

    /**
     * @Route("/user/new", name="app_new_user")
     * @return Response
     */
    public function new(EntityManagerInterface $entityManager)
    {
        $user = new User();
        $user->setFirstName('Emmanuel')
            ->setLastName('Macron')
            ->setUpVote(0)
            ->setDownVote(0)
            ->setEmail('emmanuel@gmail.com')
            ->setPassword('password');

        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Un nouvel user en BDD');
    }

    /**
     * @Route("/user/{id}", name="app_show_user")
     * @return Response
     */
    public function showOne($id, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(User::class);
        $monUser = $repository->findOneBy(['id' => $id]);

        if(!$monUser) {
            throw $this->createNotFoundException('404 : Utilisateur inconnu');
        }

        return $this->render('Frontend/user.html.twig', [
            'user' => $monUser]);
    }

    /**
     * @Route ("/user/{id}/vote", name="app_user_vote", methods="post")
     * @return Response
     */
    public function userVote(User $user, Request $request, EntityManagerInterface $entityManager):Response
    {
        $vote = $request->request->get('vote');
        if ($vote === 'up') {
            $user->setUpVote(($user->getUpVote() + 1));
        } elseif ($vote === 'down') {
            $user->setDownVote(($user->getDownVote() + 1));
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_show_user', [
           'id' => $user->getId()
        ]);
    }



}