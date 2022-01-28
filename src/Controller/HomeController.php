<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use App\Form\AdType;
use App\Form\TagType;
use App\Repository\AdRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * @param TagRepository $tagRepository
     * @param EntityManagerInterface $entityManager
     * @Route("/", name="app_homepage")
     * @return Response
     */
    public function homepage(TagRepository $tagRepository, EntityManagerInterface $entityManager):Response
    {
        $adRepository = $entityManager->getRepository(Ad::class);

        $ad = $adRepository->findAll();
        $tag = $tagRepository->findAll();

        $form = $this->createForm(TagType::class);

        $formAd = $this->createForm(AdType::class);

        if ($formAd->isSubmitted() && $formAd->isValid()) {
            $data = $formAd->getData();
            $annonce = new Ad();
            $annonce->setTitle($data->getTitle())
                ->setDescription($data->getDescription())
                ->setPrice($data->getPrice())
                ->setImage("https://www.yateo.com/blog/wp-content/uploads/2020/03/symfony.jpg")
                ->setUser($this->getUser());

            $entityManager->persist($annonce);
            $entityManager->flush();
        }

        return $this->render('Frontend/home.html.twig', [
            'ad' => $ad,
            'tag' => $tag,
            'tag_form' => $form->createView(),
            'formAd' => $formAd->createView()]);
    }
}