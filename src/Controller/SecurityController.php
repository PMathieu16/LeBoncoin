<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            ->setDownVote(0);

        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Un nouvel user en BDD');
    }

    /**
     * @Route("/user/{user}", name="app_show_user")
     * @return Response
     */
    public function showOne($user, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(User::class);
        $monUser = $repository->findOneBy(['id' => $user]);

        return $this->render('Frontend/user.html.twig', [
            'user' => $monUser]);
    }
}