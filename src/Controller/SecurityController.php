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
use Twig\Environment;

class SecurityController extends AbstractController
{

    /**
     * @Route("/connexion", name="app_connexion")
     * @return Response
     */
    public function connexion()
    {
        $title = 'Connexion';
        return $this->render('Security/connexion.html.twig', ['title' => $title]);
    }

    /**
     * @Route("/inscription", name="app_inscription")
     * @return Response
     */
    public function inscription()
    {
        $title = 'Inscription';
        return $this->render('Security/inscription.html.twig', ['title' => $title]);
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
            ->setSeller(true)
            ->setIsAdmin(false)
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