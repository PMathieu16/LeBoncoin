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
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * @var AdRepository
     */
    private $adRepository;
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(AdRepository $adRepository, TagRepository $tagRepository, EntityManagerInterface $em)
    {
        $this->adRepository = $adRepository;
        $this->tagRepository = $tagRepository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="home")
     * @param Request $request
     * @return Response
     */

    public function homepage(Request $request):Response
    {
        $form = $this->createForm(TagType::class);

        $newAd = new Ad();
        $formAd = $this->createForm(AdType::class,$newAd);

        $formAd->handleRequest($request);

        if ($formAd->isSubmitted() && $formAd->isValid()) {
            $newAd->setUser($this->getUser());
            $this->em->persist($newAd);
            $this->em->flush();
            return $this->redirectToRoute('home');
        }

        $ads = $this->adRepository->findAllOrderByNew();
        $tag = $this->tagRepository->findAll();

        return $this->render('Frontend/home.html.twig', [
            'ads' => $ads,
            'tag' => $tag,
            'tag_form' => $form->createView(),
            'formAd' => $formAd->createView()
        ]);
    }
}