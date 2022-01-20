<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class AdController extends AbstractController
{
    /**
     * @Route("/ad/{id}", name="app_show_ad")
     * @return Response
     */
    public function homepage($id, EntityManagerInterface $entityManager):Response
    {
        $repository = $entityManager->getRepository(Ad::class);
        $ad = $repository->findOneBy(['id' => $id]);

        if(!$ad) {
            throw $this->createNotFoundException('404 : Annonce inconnu');
        }

        return $this->render('Frontend/ad.html.twig', [
            'ad' => $ad]);
    }
}