<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use App\Form\AdSearchType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * @param AdRepository $adRepository
     * @Route("/", name="app_homepage")
     * @return Response
     */
    public function homepage(AdRepository $adRepository):Response
    {
        $ad = $adRepository->findAll();

        return $this->render('Frontend/home.html.twig', [
            'ad' => $ad]);
    }

    /**
     * @Route ("/search", name="app_search")
     * @return Response
     */
    public function search(): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdSearchType::class, $ad);
        return $this->render('Frontend/search.html.twig', [
            'form' => $form->createView()
        ]);
    }
}