<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use App\Form\AdType;
use App\Form\TagType;
use App\Repository\AdRepository;
use App\Repository\TagRepository;
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
    public function homepage(AdRepository $adRepository, TagRepository $tagRepository):Response
    {
        $ad = $adRepository->findAll();
        $tag = $tagRepository->findAll();

        $form = $this->createForm(TagType::class);

        $formAd = $this->createForm(AdType::class);

        return $this->render('Frontend/home.html.twig', [
            'ad' => $ad,
            'tag' => $tag,
            'tag_form' => $form->createView(),
            'formAd' => $formAd->createView()]);
    }
}