<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\AdSearch;
use App\Entity\User;
use App\Form\AdSearchType;
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
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * @var AdRepository
     */
    private $adRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(AdRepository $adRepository,EntityManagerInterface $em)
    {
        $this->adRepository = $adRepository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="home")
     * @param Request $request
     * @return Response
     */

    public function homepage(Request $request):Response
    {
        $search = new AdSearch();
        $search->setPage($request->get('page', 1));
        $form = $this->createForm(AdSearchType::class, $search);
        $form->handleRequest($request);
        $ads = $this->adRepository->findBySearchBar($search);

        $newAd = new Ad();
        $formAd = $this->createForm(AdType::class,$newAd);

        $formAd->handleRequest($request);

        if ($formAd->isSubmitted() && $formAd->isValid()) {
            $newAd->setUser($this->getUser());
            $this->em->persist($newAd);
            $this->em->flush();
            return $this->redirectToRoute('home');
        }

//      $ads = $this->adRepository->findAllOrderByNew();


        return $this->render('Frontend/home.html.twig', [
            'ads' => $ads,
            'searchForm' => $form->createView(),
            'formAd' => $formAd->createView()
        ]);
    }
}