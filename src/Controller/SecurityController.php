<?php

namespace App\Controller;

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
        $bonjour = 'Connexion';
        return $this->render('Security/connexion.html.twig', ['bonjour' => $bonjour]);
    }

    /**
     * @Route("/inscription", name="app_inscription")
     * @return Response
     */
    public function inscription()
    {
        $bonjour = 'Inscription';
        return $this->render('Security/inscription.html.twig', ['bonjour' => $bonjour]);
    }
}