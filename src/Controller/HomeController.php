<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @return Response
     */
    public function homepage()
    {
        $bonjour = 'Bonjour';
        return $this->render('Frontend/home.html.twig', [
            'bonjour' => $bonjour]);
    }

    /**
     * @Route("/user/new", name="app_new_user")
     * @return Response
     */
    public function  new(EntityManagerInterface $entityManager)
    {
        $user = new User();
        $user->setFirstName('Alexis')
            ->setLastName('Flacher')
            ->setSeller(false)
            ->setIsAdmin(true)
            ->setUpVote(0)
            ->setDownVote(0);

        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Un nouvel user en BDD');
    }
}