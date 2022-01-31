<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Question;
use App\Form\AdType;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{


    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/ad/{id}/{slug}", name="ad.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Ad $ad
     * @param string $slug
     * @param Request $request
     * @return Response
     */
    public function infoAd(Ad $ad, string $slug, Request $request):Response
    {
        if($ad->getSlug() !== $slug) {
            return $this->redirectToRoute('ad.show', [
                'id' => $ad->getId(),
                'slug' => $ad->getSlug(),
            ], 301);
        }

        $question = new Question(); // Nouvelle Entity

        $form = $this->createForm(QuestionType::class, $question); // On l'add dans le form pour qu'il se complete automatique en fonction de ce que l'on met
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setUser($this->getUser())->setAd($ad); // On ajoute ce qui n'est pas précisé dans le form :)
            $this->em->persist($question);
            $this->em->flush();
        }

        return $this->render('Frontend/ad.html.twig', [
            'ad' => $ad,
            'question_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ad/{id}/{slug}/delete", name="ad.delete", requirements={"slug": "[a-z0-9\-]*"})
     * @param Ad $ad
     * @param string $slug
     * @return Response
     */

    public function removeAd(Ad $ad, string $slug): Response {
        if($ad->getSlug() !== $slug) {
            return $this->redirectToRoute('ad.remove', [
                'id' => $ad->getId(),
                'slug' => $ad->getSlug(),
            ], 301);
        }

        if($ad->getUser() === $this->getUser()){
            $this->em->remove($ad);
            $this->em->flush();
        }

        return $this->redirectToRoute('home');

    }

    /**
     * @Route("/ad/{id}/{slug}/edit", name="ad.edit", requirements={"slug": "[a-z0-9\-]*"})
     * @param Ad $ad
     * @param string $slug
     * @param Request $request
     * @return Response
     */

    public function editAd(Ad $ad, string $slug, Request $request){
        if($ad->getSlug() !== $slug) {
            return $this->redirectToRoute('ad.show', [
                'id' => $ad->getId(),
                'slug' => $ad->getSlug(),
            ], 301);
        }

        if($ad->getUser() !== $this->getUser()){
            $this->redirectToRoute("ad.edit", [
                'id' => $ad->getId(),
                'slug' => $ad->getSlug(),
            ], 301);
        }

        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($ad);
            $this->em->flush();

            return $this->redirectToRoute('ad.show', [
                'id' => $ad->getId(),
                'slug' => $ad->getSlug(),
            ]);
        }

        return $this->render('Frontend/adEdit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}