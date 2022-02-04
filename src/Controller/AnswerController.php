<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController  extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/ad/question/{id}/answer/create", name="answer.create")
     * @param Question $question
     * @param Request $request
     * @return Response
     */
    public function createAnswer(Question $question, Request $request):Response
    {
        if($question->getUser() !== $this->getUser() || $question->getAnswer()){
            return $this->redirectToRoute('ad.show', [
                'id' => $question->getAd()->getId(),
                'slug' => $question->getAd()->getSlug(),
            ]);
        }

        $answer = new Answer();

        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answer->setUser($this->getUser())->setQuestion($question);
            $this->em->persist($answer);
            $this->em->flush();

            return $this->redirectToRoute('ad.show', [
                'id' => $question->getAd()->getId(),
                'slug' => $question->getAd()->getSlug(),
            ]);
        }

        return $this->render('Frontend/answerCreate.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}