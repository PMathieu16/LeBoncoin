<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Question;
use App\Entity\User;
use App\Form\QuestionType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class AdController extends AbstractController
{
    /**
     * @Route("/ad/{id}", name="app_show_ad")
     * @return Response
     */
    public function infoAd($id, EntityManagerInterface $entityManager, Request $request):Response
    {
        $repository = $entityManager->getRepository(Ad::class);
        $ad = $repository->findOneBy(['id' => $id]);
        if(!$ad) {
            throw $this->createNotFoundException('404 : Annonce inconnu');
        }

        $form = $this->createForm(QuestionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $question = new Question();
            $question->setText($data->getText())
                ->setAd($ad)
                ->setUser($this->getUser());
            $entityManager->persist($question);
            $entityManager->flush();
        }

        return $this->render('Frontend/ad.html.twig', [
            'ad' => $ad,
            'question_form' => $form->createView()]);
    }

    /**
     * @param Ad $ad
     * @param EntityManagerInterface $entityManager
     * @Route("/ad/new", name="app_edit_ad")
     * @return Response
     */
    public function editAd()
    {

    }

    /**
     * @param Ad $ad
     * @param EntityManagerInterface $entityManager
     * @Route("/ad/{ad}/delete", name="app_delete_ad")
     * @return Response
     */
    public function removeAd(Ad $ad, EntityManagerInterface $entityManager): Response {
        $entityManager->remove($ad);
        $entityManager->flush();
        return $this->redirectToRoute('app_homepage');
    }
}