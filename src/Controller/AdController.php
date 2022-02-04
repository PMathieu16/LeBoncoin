<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Question;
use App\Form\AdType;
use App\Form\QuestionType;
use App\Repository\AdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AdController extends AbstractController
{


    private $em;

    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
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

        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setUser($this->getUser())->setAd($ad);
            $this->em->persist($question);
            $this->em->flush();

            return $this->redirectToRoute('ad.show', [
                'id' => $ad->getId(),
                'slug' => $ad->getSlug(),
            ]);
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
            return $this->redirectToRoute('ad.delete', [
                'id' => $ad->getId(),
                'slug' => $ad->getSlug(),
            ], 301);
        }

        if($ad->getUser() !== $this->getUser() && !$this->security->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute("ad.show", [
                'id' => $ad->getId(),
                'slug' => $ad->getSlug(),
            ]);
        }

        $this->em->remove($ad);
        $this->em->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/ad/{id}/{slug}/edit", name="ad.edit", requirements={"slug": "[a-z0-9\-]*"})
     * @param Ad $ad
     * @param string $slug
     * @param Request $request
     * @return Response
     */

    public function editAd(Ad $ad, string $slug, Request $request): Response
    {
        if($ad->getSlug() !== $slug) {
            return $this->redirectToRoute('ad.edit', [
                'id' => $ad->getId(),
                'slug' => $ad->getSlug(),
            ], 301);
        }

        if($ad->getUser() !== $this->getUser() && !$this->security->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute("ad.show", [
                'id' => $ad->getId(),
                'slug' => $ad->getSlug(),
            ]);
        }

        $originalImages = new ArrayCollection();

        foreach ($ad->getImages() as $image) {
            $originalImages->add($image);
        }

        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($originalImages as $image) {
                if (false === $ad->getImages()->contains($image)) {
                    $this->em->remove($image);
                }
            }

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